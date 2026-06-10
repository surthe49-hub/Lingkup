<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Target;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TargetController extends Controller
{
    /**
     * Tampilkan daftar target aktif.
     */
    public function index(Request $request): View|RedirectResponse
    {
        // Guard: pastikan profile complete sebelum pilih target
        if ($redirect = $this->ensureProfileComplete($request)) {
            return $redirect;
        }

        $user = $request->user();
        $targets = Target::active()->orderBy('name')->get();
        $activeTarget = $user->userTarget?->target;

        return view('user.targets.index', compact('targets', 'activeTarget'));
    }

    /**
     * Tampilkan detail target.
     * Route model binding sudah handle target lookup.
     * Defensive: pastikan target aktif (di-handle via route scoping).
     */
    public function show(Request $request, Target $target): View|RedirectResponse
    {
        // Guard: pastikan profile complete
        if ($redirect = $this->ensureProfileComplete($request)) {
            return $redirect;
        }

        // Defensive: walaupun route scoping seharusnya handle, tetap cek
        abort_if(!$target->is_active, 404);

        $user = $request->user();
        $activeTarget = $user->userTarget?->target;
        $isCurrentlyActive = $activeTarget?->id === $target->id;

        return view('user.targets.show', compact('target', 'activeTarget', 'isCurrentlyActive'));
    }

    /**
     * Pilih atau ganti target aktif user.
     * Pattern: delete-and-insert (1 user = 1 row di user_targets).
     */
    public function select(Request $request, Target $target): RedirectResponse
    {
        // Guard: pastikan profile complete
        if ($redirect = $this->ensureProfileComplete($request)) {
            return $redirect;
        }

        // Defensive: hanya target aktif yang bisa dipilih
        abort_if(!$target->is_active, 404);

        $user = $request->user();
        $previousTarget = $user->userTarget?->target;

        // Transaction: delete existing, insert new
        DB::transaction(function () use ($user, $target) {
            // Hapus semua row user_targets milik user ini (safety net)
            $user->userTargets()->delete();

            // Insert row baru
            $user->userTargets()->create([
                'target_id' => $target->id,
                'selected_at' => now(),
            ]);
        });

        // Flash message yang context-aware
        $message = $previousTarget
            ? "Target berhasil diganti dari {$previousTarget->name} ke {$target->name}."
            : "Target aktif: {$target->name}.";

        return redirect()
            ->route('target.index')
            ->with('success', $message);
    }

    /**
     * Guard helper: pastikan user punya profile lengkap.
     * Return RedirectResponse jika tidak, null jika OK.
     */
    private function ensureProfileComplete(Request $request): ?RedirectResponse
    {
        $profile = $request->user()->profile;

        if (!$profile || !$profile->isComplete()) {
            return redirect()
                ->route('profile-assessment.index')
                ->with('warning', 'Lengkapi Profil Akademik terlebih dahulu sebelum memilih target.');
        }

        return null;
    }
}