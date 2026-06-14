<?php

namespace App\Http\Controllers\User;

use App\Exceptions\PathwayGenerationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pathway\PathwayGenerationRequest;
use App\Models\Pathway;
use App\Services\Pathway\PathwayGenerationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Controller untuk Pathway generation & view.
 *
 * Phase 3A scope:
 * - generate(): trigger generation, return JSON (testable via Postman/curl)
 * - show(): return JSON detail (untuk validasi via curl)
 *
 * Phase 4 akan ganti return JSON ke return view Blade.
 */
class PathwayController extends Controller
{
    public function __construct(
        private readonly PathwayGenerationService $pathwayService,
    ) {
    }

    /**
     * POST /pathway/generate
     *
     * Trigger generation. Return JSON dengan pathway_id jika sukses,
     * atau error message jika gagal.
     */
    public function generate(PathwayGenerationRequest $request): JsonResponse
    {
        // Set PHP execution time limit (Gemini timeout 60s + buffer)
        set_time_limit(70);

        $user = $request->user();
        $profile = $user->profile;
        $target = $user->userTarget->target;

        try {
            $pathway = $this->pathwayService->generate($profile, $target, $user);

            // Count phases dan tasks untuk response
            $pathway->loadCount('phases');
            $taskCount = $pathway->tasks()->count();

            return response()->json([
                'success' => true,
                'pathway_id' => $pathway->id,
                'title' => $pathway->title,
                'summary' => $pathway->summary,
                'estimated_total_duration' => $pathway->estimated_total_duration,
                'phase_count' => $pathway->phases_count,
                'task_count' => $taskCount,
                'generation_count' => $pathway->generation_count,
                'view_url' => route('user.pathway.show', $pathway),
                'message' => 'Pathway berhasil dibuat.',
            ], 201);
        } catch (PathwayGenerationException $e) {
            Log::warning('Pathway generation failed', [
                'user_id' => $user->id,
                'error_type' => $e->type,
                'error_message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error_type' => $e->type,
                'message' => $e->userMessage(),
            ], 500);
        } catch (\Throwable $e) {
            Log::error('Unexpected error in pathway generation', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error_type' => 'unknown',
                'message' => 'Terjadi kesalahan tak terduga. Silakan coba lagi.',
                'debug' => app()->environment('local') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * GET /pathway/{pathway}
     *
     * Phase 3A: return JSON detail untuk validasi.
     * Phase 4 akan ganti ke return view dengan accordion.
     */
    public function show(Pathway $pathway): JsonResponse
    {
        // Authorization: hanya owner yang boleh akses
        if ($pathway->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
            ], 403);
        }

        // Eager load relationships
        $pathway->load([
            'target',
            'phases.tasks.progress' => function ($query) {
                $query->where('user_id', auth()->id());
            },
        ]);

        return response()->json([
            'success' => true,
            'pathway' => [
                'id' => $pathway->id,
                'title' => $pathway->title,
                'summary' => $pathway->summary,
                'estimated_total_duration' => $pathway->estimated_total_duration,
                'status' => $pathway->status,
                'generation_count' => $pathway->generation_count,
                'generated_at' => $pathway->generated_at?->toIso8601String(),
                'target' => [
                    'id' => $pathway->target->id,
                    'name' => $pathway->target->name,
                    'country' => $pathway->target->country,
                ],
                'phases' => $pathway->phases->map(function ($phase) {
                    return [
                        'id' => $phase->id,
                        'phase_order' => $phase->phase_order,
                        'title' => $phase->title,
                        'description' => $phase->description,
                        'estimated_duration' => $phase->estimated_duration,
                        'task_count' => $phase->tasks->count(),
                        'tasks' => $phase->tasks->map(function ($task) {
                            return [
                                'id' => $task->id,
                                'task_order' => $task->task_order,
                                'title' => $task->title,
                                'description' => $task->description,
                                'category' => $task->category,
                                'priority' => $task->priority,
                                'estimated_duration' => $task->estimated_duration,
                                'progress_status' => $task->progress->first()?->status ?? 'not_started',
                            ];
                        }),
                    ];
                }),
            ],
        ]);
    }
}