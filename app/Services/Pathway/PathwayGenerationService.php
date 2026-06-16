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
use App\Services\Pathway\PathwayRateLimiter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Orchestrator service untuk Pathway generation.
 *
 * Phase 5.1 implementation dengan:
 * - Rate limiter precheck (sebelum AI call)
 * - Retry strategy untuk transient errors
 * - Archive pathway lama (status='archived', bukan hard delete)
 * - DB transaction untuk consistency
 */
class PathwayGenerationService
{
    public function __construct(
        private readonly PathwayUserPromptBuilder $promptBuilder,
        private readonly AiClient $aiClient,
        private readonly PathwayOutputValidator $validator,
        private readonly PathwayRateLimiter $rateLimiter,
        private readonly PathwayRetryStrategy $retryStrategy,
    ) {
    }

    /**
     * Generate pathway dari profile + target untuk user.
     *
     * @throws PathwayGenerationException
     */
    public function generate(Profile $profile, Target $target, User $user): Pathway
    {
        $totalStartTime = microtime(true);

        // ============================================
        // STEP 0: Rate limit precheck (OUT of retry loop)
        // ============================================
        if (! $this->rateLimiter->canGenerate($user, $target)) {
            $currentUsage = $this->rateLimiter->getCurrentUsage($user, $target);
            $resetAt = $this->rateLimiter->getResetAt($user, $target);

            Log::warning('Pathway generation blocked by rate limiter', [
                'user_id' => $user->id,
                'target_id' => $target->id,
                'current_usage' => $currentUsage,
                'reset_at' => $resetAt?->toIso8601String(),
            ]);

            throw PathwayGenerationException::rateLimitExceeded(
                $currentUsage,
                PathwayRateLimiter::MAX_GENERATIONS_PER_WINDOW,
                PathwayRateLimiter::WINDOW_DAYS,
            );
        }

        Log::info('Pathway generation started', [
            'user_id' => $user->id,
            'target_id' => $target->id,
            'profile_id' => $profile->id,
            'remaining_quota_before' => $this->rateLimiter->getRemainingGenerations($user, $target),
        ]);

        // Build prompt sekali saja (tidak berubah di retry)
        $userPrompt = $this->promptBuilder->build($profile, $target);
        $systemPrompt = PathwaySystemPrompt::get();
        $schema = PathwayJsonSchema::get();

        // ============================================
        // STEP 1-3: AI call + validation dengan RETRY loop
        // ============================================
        $output = $this->generateWithRetry(
            $systemPrompt,
            $userPrompt,
            $schema,
            $user,
            $target,
        );

        // ============================================
        // STEP 4: Persist to database (transactional)
        // ============================================
        $pathway = DB::transaction(function () use ($output, $user, $target, $totalStartTime) {
            // 4a. Hitung generation_count dari pathway_generation_logs
            $generationCount = PathwayGenerationLog::where('user_id', $user->id)
                ->where('target_id', $target->id)
                ->where('status', 'success')
                ->count() + 1;

            // 4b. Archive pathway lama user (Phase 5.1 approach)
            $archivedCount = Pathway::where('user_id', $user->id)
                ->where('status', 'active')
                ->update(['status' => 'archived']);

            if ($archivedCount > 0) {
                Log::info("Archived {$archivedCount} old pathway(s) for user {$user->id}", [
                    'user_id' => $user->id,
                ]);
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

            // 4d. Loop insert phases & tasks & task_progress
            foreach ($pathwayData['phases'] as $phaseData) {
                $phase = PathwayPhase::create([
                    'pathway_id' => $pathway->id,
                    'phase_order' => $phaseData['phase_order'],
                    'title' => $phaseData['title'],
                    'description' => $phaseData['description'],
                    'estimated_duration' => $phaseData['estimated_duration'],
                ]);

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

                    TaskProgress::create([
                        'task_id' => $task->id,
                        'user_id' => $user->id,
                        'status' => 'belum_dimulai',
                    ]);
                }
            }

            // 4e. Insert success log
            $latencyMs = (int) ((microtime(true) - $totalStartTime) * 1000);
            PathwayGenerationLog::create([
                'user_id' => $user->id,
                'target_id' => $target->id,
                'pathway_id' => $pathway->id,
                'model_used' => config('services.gemini.model'),
                'prompt_tokens' => 0,       // Phase 3B: extract from Gemini response
                'completion_tokens' => 0,   // Phase 3B
                'cost_idr' => 0,             // Phase 3B
                'latency_ms' => $latencyMs,
                'status' => 'success',
                'error_message' => null,
            ]);

            return $pathway;
        });

        $totalLatency = (int) ((microtime(true) - $totalStartTime) * 1000);

        Log::info('Pathway generation completed', [
            'user_id' => $user->id,
            'pathway_id' => $pathway->id,
            'generation_count' => $pathway->generation_count,
            'total_latency_ms' => $totalLatency,
        ]);

        return $pathway;
    }

    /**
     * AI call + validation dengan retry mechanism.
     *
     * @throws PathwayGenerationException
     */
    private function generateWithRetry(
        string $systemPrompt,
        string $userPrompt,
        array $schema,
        User $user,
        Target $target,
    ): array {
        $maxAttempts = $this->retryStrategy->getMaxAttempts();
        $attempt = 0;
        $lastException = null;

        while ($attempt < $maxAttempts) {
            $attempt++;
            $attemptStartTime = microtime(true);

            try {
                Log::info("Pathway generation attempt {$attempt}/{$maxAttempts}", [
                    'user_id' => $user->id,
                    'target_id' => $target->id,
                ]);

                // AI call
                $output = $this->aiClient->generateStructured(
                    $systemPrompt,
                    $userPrompt,
                    $schema,
                );

                // Validation
                $validationResult = $this->validator->validate($output);

                if (! $validationResult->passed) {
                    throw PathwayGenerationException::validationFailed(
                        $validationResult->errors,
                    );
                }

                // Success! Return output
                Log::info("Pathway generation succeeded on attempt {$attempt}", [
                    'user_id' => $user->id,
                    'attempt_latency_ms' => (int) ((microtime(true) - $attemptStartTime) * 1000),
                ]);

                return $output;
            } catch (PathwayGenerationException $e) {
                $lastException = $e;

                // Log failed attempt
                $this->logFailure(
                    $user,
                    $target,
                    $attemptStartTime,
                    $e->type,
                    $e->getMessage(),
                );

                // Decide retry
                $decision = $this->retryStrategy->getRetryDecision($e, $attempt);

                Log::warning("Pathway generation attempt {$attempt} failed", [
                    'user_id' => $user->id,
                    'error_type' => $e->type,
                    'should_retry' => $decision['should_retry'],
                    'reason' => $decision['reason'],
                ]);

                if (! $decision['should_retry']) {
                    // No retry — throw immediately
                    throw $e;
                }

                // Backoff before retry
                $backoff = $decision['backoff_seconds'];
                Log::info("Backoff {$backoff}s before retry", [
                    'user_id' => $user->id,
                    'next_attempt' => $attempt + 1,
                ]);
                sleep($backoff);
            }
        }

        // All retries exhausted
        Log::error("Pathway generation failed after {$maxAttempts} attempts", [
            'user_id' => $user->id,
            'last_error_type' => $lastException?->type,
        ]);

        throw $lastException ?? PathwayGenerationException::apiError(0, 'Unknown error after retries');
    }

    /**
     * Log failure ke pathway_generation_logs.
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