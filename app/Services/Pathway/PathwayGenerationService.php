<?php

namespace App\Services\Pathway;

use App\Exceptions\PathwayGenerationException;
use App\Models\Pathway;
use App\Models\PathwayGenerationLog;
use App\Models\PathwayPhase;
use App\Models\PathwayTask;
use App\Models\Profile;
use App\Models\Target;
use App\Models\TaskProgress;
use App\Models\User;
use App\Services\AI\AiClient;
use App\Services\Prompts\PathwayJsonSchema;
use App\Services\Prompts\PathwaySystemPrompt;
use App\Services\Prompts\PathwayUserPromptBuilder;
use App\Services\Validators\PathwayOutputValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Orchestrator service untuk Pathway generation.
 *
 * Phase 3A scope:
 * - Glue: Builder → AI → Validator → DB save
 * - DB transaction untuk consistency
 * - Hard delete pathway lama saat regenerate (no archive)
 * - Minimal logging ke pathway_generation_logs
 * - generation_count counter dari logs (Opsi 3)
 *
 * Phase 3B akan add:
 * - Retry mechanism
 * - Rate limiting
 * - Status-based archive (replace hard delete)
 * - Telemetry analytics
 */
class PathwayGenerationService
{
    public function __construct(
        private readonly PathwayUserPromptBuilder $promptBuilder,
        private readonly AiClient $aiClient,
        private readonly PathwayOutputValidator $validator,
    ) {
    }

    /**
     * Generate pathway dari profile + target untuk user.
     *
     * @throws PathwayGenerationException Jika AI fail / validation fail
     */
    public function generate(Profile $profile, Target $target, User $user): Pathway
    {
        $startTime = microtime(true);

        Log::info('Pathway generation started', [
            'user_id' => $user->id,
            'target_id' => $target->id,
            'profile_id' => $profile->id,
        ]);

        // ============================================
        // STEP 1: Build user prompt (Phase 2)
        // ============================================
        $userPrompt = $this->promptBuilder->build($profile, $target);
        $systemPrompt = PathwaySystemPrompt::get();
        $schema = PathwayJsonSchema::get();

        // ============================================
        // STEP 2: Call AI (Phase 1)
        // Bisa throw PathwayGenerationException
        // ============================================
        $output = $this->aiClient->generateStructured(
            $systemPrompt,
            $userPrompt,
            $schema,
        );

        // ============================================
        // STEP 3: Validate output (Phase 2)
        // ============================================
        $validationResult = $this->validator->validate($output);

        if (! $validationResult->passed) {
            // Log failure ke generation_logs sebelum throw
            $this->logFailure($user, $target, $startTime, 'validation_failed',
                "Layer: {$validationResult->failedLayer}; Errors: " . implode(', ', $validationResult->errors)
            );

            throw PathwayGenerationException::validationFailed(
                $validationResult->errors,
            );
        }

        // ============================================
        // STEP 4: Persist to database (transactional)
        // ============================================
        $pathway = DB::transaction(function () use ($output, $user, $target, $startTime) {
            // 4a. Hitung generation_count dari pathway_generation_logs
            // (Opsi 3: per-target counter via logs sebagai source of truth)
            $generationCount = PathwayGenerationLog::where('user_id', $user->id)
                ->where('target_id', $target->id)
                ->where('status', 'success')
                ->count() + 1;

            // 4b. Hard delete pathway lama user (Phase 3A approach)
            // CASCADE akan delete phases dan tasks otomatis (via FK)
            // task_progress juga akan ter-delete via CASCADE
            $deletedCount = Pathway::where('user_id', $user->id)->forceDelete();
            if ($deletedCount > 0) {
                Log::info("Hard deleted {$deletedCount} old pathway(s) for user {$user->id}");
            }

            // 4c. Insert pathway header
            $pathwayData = $output['pathway'];
            $pathway = Pathway::create([
                'user_id' => $user->id,
                'target_id' => $target->id,
                'title' => $pathwayData['title'],
                'summary' => $pathwayData['summary'],
                'estimated_total_duration' => $pathwayData['estimated_total_duration'],
                'status' => 'active',
                'generation_count' => $generationCount,
                'generated_at' => now(),
            ]);

            // 4d. Loop insert phases
            foreach ($pathwayData['phases'] as $phaseData) {
                $phase = PathwayPhase::create([
                    'pathway_id' => $pathway->id,
                    'phase_order' => $phaseData['phase_order'],
                    'title' => $phaseData['title'],
                    'description' => $phaseData['description'],
                    'estimated_duration' => $phaseData['estimated_duration'],
                ]);

                // 4e. Loop insert tasks
                foreach ($phaseData['tasks'] as $taskData) {
                    $task = PathwayTask::create([
                        'phase_id' => $phase->id,
                        'task_order' => $taskData['task_order'],
                        'title' => $taskData['title'],
                        'description' => $taskData['description'],
                        'category' => $taskData['category'],
                        'priority' => $taskData['priority'],
                        'estimated_duration' => $taskData['estimated_duration'],
                    ]);

                    // 4f. Auto-create task_progress (status='not_started')
                TaskProgress::create([
                    'task_id' => $task->id,
                    'user_id' => $user->id,
                    'status' => 'belum_dimulai',
                ]);
                }
            }

            // 4g. Insert success log
            $latencyMs = (int) ((microtime(true) - $startTime) * 1000);
            // 4g. Insert success log
            // Phase 3A: token extraction & cost calculation belum implemented.
            // Phase 3B akan extract dari Gemini API response metadata.
            $latencyMs = (int) ((microtime(true) - $startTime) * 1000);
            PathwayGenerationLog::create([
                'user_id' => $user->id,
                'target_id' => $target->id,
                'pathway_id' => $pathway->id,
                'model_used' => config('services.gemini.model'),
                'prompt_tokens' => 0,        // Phase 3B: extract from Gemini response
                'completion_tokens' => 0,    // Phase 3B
                'cost_idr' => 0,             // Phase 3B
                'latency_ms' => $latencyMs,
                'status' => 'success',
                'error_message' => null,
            ]);

            return $pathway;
        });

        $totalLatency = (int) ((microtime(true) - $startTime) * 1000);

        Log::info('Pathway generation completed', [
            'user_id' => $user->id,
            'pathway_id' => $pathway->id,
            'generation_count' => $pathway->generation_count,
            'total_latency_ms' => $totalLatency,
        ]);

        return $pathway;
    }

    /**
     * Log failure ke pathway_generation_logs.
     *
     * Dipisah dari main flow karena failure log perlu di-insert
     * di luar transaction (transaction sudah rollback saat failure).
     */
    private function logFailure(
        User $user,
        Target $target,
        float $startTime,
        string $errorType,
        string $errorMessage,
    ): void {
        $latencyMs = (int) ((microtime(true) - $startTime) * 1000);

        try {
           PathwayGenerationLog::create([
    'user_id' => $user->id,
    'target_id' => $target->id,
    'pathway_id' => null,
    'model_used' => config('services.gemini.model'),
    'prompt_tokens' => 0,
    'completion_tokens' => 0,
    'cost_idr' => 0,
    'latency_ms' => $latencyMs,
    'status' => 'failed',
    'error_message' => substr($errorMessage, 0, 1000),
]);
        } catch (\Throwable $e) {
            // Logging failure itself fails - log to Laravel log only
            Log::error('Failed to write pathway_generation_logs', [
                'original_error' => $errorMessage,
                'logging_error' => $e->getMessage(),
            ]);
        }
    }
}