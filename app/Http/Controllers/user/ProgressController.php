<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PathwayTask;
use App\Models\TaskProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk halaman Progress: checklist task per-phase + timeline visual.
 *
 * Scope: read pathway aktif milik user, tampilkan status pengerjaan tiap task,
 * dan izinkan user update status task (belum_dimulai / sedang_dikerjakan / selesai).
 */
class ProgressController extends Controller
{
    /**
     * GET /progress
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $pathway = $user->pathway;

        if (! $pathway) {
            return view('user.progress.index', [
                'pathway' => null,
            ]);
        }

        $pathway->load([
            'target',
            'phases.tasks.progress' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            },
        ]);

        $phasesData = $pathway->phases->map(function ($phase) {
            $tasks = $phase->tasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'category' => $task->category,
                    'priority' => $task->priority,
                    'estimated_duration' => $task->estimated_duration,
                    'status' => $task->progress?->status ?? 'belum_dimulai',
                ];
            });

            $totalTasks = $tasks->count();
            $completedTasks = $tasks->where('status', 'selesai')->count();
            $hasInProgress = $tasks->contains('status', 'sedang_dikerjakan');

            return [
                'id' => $phase->id,
                'phase_order' => $phase->phase_order,
                'title' => $phase->title,
                'description' => $phase->description,
                'estimated_duration' => $phase->estimated_duration,
                'tasks' => $tasks,
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'percentage' => $totalTasks > 0
                    ? round(($completedTasks / $totalTasks) * 100, 1)
                    : 0.0,
                'is_complete' => $totalTasks > 0 && $completedTasks === $totalTasks,
                'has_in_progress' => $hasInProgress,
            ];
        });

        // Tentukan current phase:
        // 1. Phase yang punya task 'sedang_dikerjakan'
        // 2. Kalau tidak ada, phase pertama yang belum 100% selesai
        // 3. Kalau semua selesai, current = phase terakhir (ditandai complete)
        $currentPhaseId = null;

        $phaseWithInProgress = $phasesData->firstWhere('has_in_progress', true);
        if ($phaseWithInProgress) {
            $currentPhaseId = $phaseWithInProgress['id'];
        } else {
            $firstIncomplete = $phasesData->firstWhere('is_complete', false);
            $currentPhaseId = $firstIncomplete
                ? $firstIncomplete['id']
                : $phasesData->last()['id'] ?? null;
        }

        $overallTotal = $phasesData->sum('total_tasks');
        $overallCompleted = $phasesData->sum('completed_tasks');
        $overallPercentage = $overallTotal > 0
            ? round(($overallCompleted / $overallTotal) * 100, 1)
            : 0.0;

        return view('user.progress.index', [
            'pathway' => $pathway,
            'phases' => $phasesData,
            'currentPhaseId' => $currentPhaseId,
            'overallPercentage' => $overallPercentage,
            'overallCompleted' => $overallCompleted,
            'overallTotal' => $overallTotal,
        ]);
    }

    /**
     * PATCH /progress/tasks/{task}
     *
     * Update status task untuk user yang sedang login.
     * AJAX endpoint dari checklist di view.
     */
    public function update(Request $request, PathwayTask $task): JsonResponse
    {
        $user = $request->user();

        // Authorization: task harus milik pathway aktif user yang login
        $task->load('phase.pathway');
        if (! $task->phase || $task->phase->pathway->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:belum_dimulai,sedang_dikerjakan,selesai',
        ]);

        $progress = TaskProgress::updateOrCreate(
            ['task_id' => $task->id, 'user_id' => $user->id],
            []
        );

        match ($validated['status']) {
            'sedang_dikerjakan' => $progress->markAsInProgress(),
            'selesai' => $progress->markAsCompleted(),
            default => $progress->reset(),
        };

        // Hitung ulang persentase phase terkait untuk response
        $phase = $task->phase;
        $phase->load(['tasks.progress' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }]);

        $totalTasks = $phase->tasks->count();
        $completedTasks = $phase->tasks->filter(
            fn ($t) => ($t->progress?->status ?? 'belum_dimulai') === 'selesai'
        )->count();

        return response()->json([
            'success' => true,
            'task_id' => $task->id,
            'status' => $progress->status,
            'phase_id' => $phase->id,
            'phase_completed_tasks' => $completedTasks,
            'phase_total_tasks' => $totalTasks,
            'phase_percentage' => $totalTasks > 0
                ? round(($completedTasks / $totalTasks) * 100, 1)
                : 0.0,
        ]);
    }
}