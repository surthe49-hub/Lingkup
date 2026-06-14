<?php

namespace App\Http\Controllers\User;

use App\Exceptions\PathwayGenerationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pathway\PathwayGenerationRequest;
use App\Models\Pathway;
use App\Services\Pathway\PathwayGenerationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Controller untuk Pathway generation & view.
 *
 * Phase 3A: generate() & show() return JSON
 * Phase 4: ditambah index() & dual-mode show() (JSON atau View berdasarkan Accept header)
 */
class PathwayController extends Controller
{
    public function __construct(
        private readonly PathwayGenerationService $pathwayService,
    ) {
    }

    /**
     * GET /pathway
     *
     * Landing page pathway. Logic routing:
     * - User tidak login → middleware auth redirect
     * - User profile belum complete → tampilkan empty state
     * - User belum pilih target → tampilkan empty state
     * - User punya active pathway → redirect ke /pathway/{id}
     * - User ready, belum generate → tampilkan landing dengan tombol Generate
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // Check prerequisites
        $hasProfile = $user->profile && $user->profile->isComplete();
        $hasTarget = $user->userTarget && $user->userTarget->target;
        $hasActivePathway = $user->pathway()->exists();

        // Skenario 1: Punya active pathway → redirect ke detail
        if ($hasActivePathway) {
            return redirect()->route('user.pathway.show', $user->pathway);
        }

        // Skenario 2/3/4: Tampilkan landing dengan state berbeda
        return view('user.pathway.index', [
            'hasProfile' => $hasProfile,
            'hasTarget' => $hasTarget,
            'target' => $hasTarget ? $user->userTarget->target : null,
            'profile' => $user->profile,
        ]);
    }

    /**
     * POST /pathway/generate
     *
     * Trigger generation. Return JSON karena di-call via AJAX dari frontend.
     */
    public function generate(PathwayGenerationRequest $request): JsonResponse
    {
        set_time_limit(70);

        $user = $request->user();
        $profile = $user->profile;
        $target = $user->userTarget->target;

        try {
            $pathway = $this->pathwayService->generate($profile, $target, $user);

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
     * Dual-mode:
     * - Accept: application/json → return JSON (untuk API testing & frontend)
     * - Otherwise → return Blade view
     */
    public function show(Pathway $pathway, Request $request): View|JsonResponse
    {
        // Authorization: hanya owner
        if ($pathway->user_id !== auth()->id()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak.',
                ], 403);
            }
            abort(403, 'Akses ditolak.');
        }

        // Eager load relationships
        $pathway->load([
            'target',
            'phases.tasks.progress' => function ($query) {
                $query->where('user_id', auth()->id());
            },
        ]);

        // JSON mode (untuk API testing)
        if ($request->wantsJson()) {
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
                                    'progress_status' => $task->progress->first()?->status ?? 'belum_dimulai',
                                ];
                            }),
                        ];
                    }),
                ],
            ]);
        }

        // View mode (default)
        return view('user.pathway.show', [
            'pathway' => $pathway,
        ]);
    }
}