<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Target;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TargetController extends Controller
{
    /**
     * GET /admin/targets
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->input('status');

        $query = Target::query();

        if ($statusFilter === 'deleted') {
            $query->onlyTrashed();
        }

        $targets = $query
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->input('search');
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('country', 'like', "%{$term}%");
                });
            })
            ->when($statusFilter === 'active', fn ($query) => $query->where('is_active', true))
            ->when($statusFilter === 'inactive', fn ($query) => $query->where('is_active', false))
            ->withCount(['userTargets', 'pathways'])
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.targets.index', [
            'targets' => $targets,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * GET /admin/targets/create
     */
    public function create(): View
    {
        return view('admin.targets.create');
    }

    /**
     * POST /admin/targets
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTarget($request);

        Target::create($validated);

        return redirect()
            ->route('admin.targets.index')
            ->with('success', "Target \"{$validated['name']}\" berhasil ditambahkan.");
    }

    /**
     * GET /admin/targets/{target}/edit
     */
    public function edit(Target $target): View
    {
        return view('admin.targets.edit', compact('target'));
    }

    /**
     * PUT /admin/targets/{target}
     */
    public function update(Request $request, Target $target): RedirectResponse
    {
        $validated = $this->validateTarget($request, $target->id);

        $target->update($validated);

        return redirect()
            ->route('admin.targets.index')
            ->with('success', "Target \"{$target->name}\" berhasil diperbarui.");
    }

    /**
     * PATCH /admin/targets/{target}/toggle-active
     *
     * Nonaktifkan/aktifkan target tanpa menghapus data.
     * Ini aksi yang AMAN untuk target yang sedang dipakai user
     * (tidak memutus relasi userTargets/pathways yang sudah ada).
     */
    public function toggleActive(Target $target): RedirectResponse
    {
        $target->update(['is_active' => ! $target->is_active]);

        $status = $target->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Target \"{$target->name}\" berhasil {$status}.");
    }

    /**
     * DELETE /admin/targets/{target}
     *
     * Soft delete. DIBLOKIR jika target masih punya user_targets
     * atau pathways aktif — karena relasi belongsTo ke target yang
     * soft-deleted akan mengembalikan null (SoftDeletingScope ikut
     * berlaku di relasi), yang bisa merusak tampilan pathway/target
     * user yang masih mereferensikan target ini.
     *
     * Untuk target yang masih dipakai, arahkan admin pakai
     * toggleActive() (nonaktifkan) sebagai gantinya.
     */
    public function destroy(Target $target): RedirectResponse
    {
        $hasUserTargets = $target->userTargets()->exists();
        $hasPathways = $target->pathways()->exists();

        if ($hasUserTargets || $hasPathways) {
            return back()->with(
                'error',
                "Target \"{$target->name}\" tidak bisa dihapus karena masih memiliki data terkait (user yang memilih target ini atau pathway yang sudah dibuat). Gunakan tombol Nonaktifkan sebagai gantinya."
            );
        }

        $target->delete();

        return back()->with('success', "Target \"{$target->name}\" berhasil dihapus.");
    }

    /**
     * PATCH /admin/targets/{target}/restore
     *
     * Restore target yang sudah soft-deleted.
     * Route menggunakan ->withTrashed() supaya route model binding
     * bisa menemukan target yang sudah dihapus.
     */
    public function restore(Target $target): RedirectResponse
    {
        $target->restore();

        return back()->with('success', "Target \"{$target->name}\" berhasil direstore.");
    }

    /**
     * Validasi shared untuk store() & update().
     */
    private function validateTarget(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:150',
                'unique:targets,name' . ($ignoreId ? ",{$ignoreId}" : ''),
            ],
            'country' => ['required', 'string', 'max:50'],
            'education_level' => ['required', 'in:S1,S2,S3,Exchange,Internship'],
            'program_type' => ['required', 'in:scholarship,exchange,internship,dual_degree'],
            'requirements_summary' => ['required', 'string'],
            'structured_requirements' => ['nullable', 'string'],
            'typical_deadline' => ['nullable', 'string', 'max:50'],
            'official_url' => ['required', 'string', 'max:500', 'url'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        // structured_requirements dikirim sebagai string JSON mentah dari textarea.
        // Decode dulu supaya tersimpan sebagai JSON asli di database (bukan
        // string ganda ter-escape), dan validasi formatnya benar JSON.
        if (! empty($validated['structured_requirements'])) {
            $decoded = json_decode($validated['structured_requirements'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'structured_requirements' => 'Format JSON tidak valid: ' . json_last_error_msg(),
                ]);
            }

            $validated['structured_requirements'] = $decoded;
        } else {
            $validated['structured_requirements'] = null;
        }

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}