<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyDestination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudyDestinationController extends Controller
{
    /**
     * GET /admin/study-destinations
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->input('status');

        $query = StudyDestination::query();

        if ($statusFilter === 'deleted') {
            $query->onlyTrashed();
        }

        $destinations = $query
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->input('search');
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('scholarship_name', 'like', "%{$term}%");
                });
            })
            ->when($statusFilter === 'active', fn ($query) => $query->where('is_active', true))
            ->when($statusFilter === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderBy('display_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.study-destinations.index', [
            'destinations' => $destinations,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * GET /admin/study-destinations/create
     */
    public function create(): View
    {
        $nextOrder = (int) (StudyDestination::max('display_order') ?? 0) + 1;

        return view('admin.study-destinations.create', compact('nextOrder'));
    }

    /**
     * POST /admin/study-destinations
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateDestination($request, imageRequired: true);

        DB::transaction(function () use ($request, $validated) {
            // Auto-geser: buka celah di posisi yang diminta, dorong sisanya maju
            $this->reorderForNewPosition($validated['display_order']);

            $validated['image_path'] = $this->storeImage($request);

            StudyDestination::create($validated);
        });

        return redirect()
            ->route('admin.study-destinations.index')
            ->with('success', "Negara \"{$validated['name']}\" berhasil ditambahkan.");
    }

    /**
     * GET /admin/study-destinations/{studyDestination}/edit
     */
    public function edit(StudyDestination $studyDestination): View
    {
        return view('admin.study-destinations.edit', ['destination' => $studyDestination]);
    }

    /**
     * PUT /admin/study-destinations/{studyDestination}
     */
    public function update(Request $request, StudyDestination $studyDestination): RedirectResponse
    {
        $validated = $this->validateDestination($request, imageRequired: false);
        $oldOrder = $studyDestination->display_order;
        $newOrder = $validated['display_order'];

        DB::transaction(function () use ($request, $studyDestination, $validated, $oldOrder, $newOrder) {
            if ($newOrder !== $oldOrder) {
                // Auto-geser: dorong item lain sesuai arah perpindahan
                $this->reorderForNewPosition($newOrder, excludeId: $studyDestination->id, oldOrder: $oldOrder);
            }

            // Cuma replace gambar kalau admin upload file baru
            if ($request->hasFile('image')) {
                $oldImagePath = $studyDestination->image_path;

                $validated['image_path'] = $this->storeImage($request);

                if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }

            $studyDestination->update($validated);
        });

        return redirect()
            ->route('admin.study-destinations.index')
            ->with('success', "Negara \"{$studyDestination->name}\" berhasil diperbarui.");
    }

    /**
     * PATCH /admin/study-destinations/{studyDestination}/toggle-active
     */
    public function toggleActive(StudyDestination $studyDestination): RedirectResponse
    {
        $studyDestination->update(['is_active' => ! $studyDestination->is_active]);

        $status = $studyDestination->is_active ? 'ditampilkan' : 'disembunyikan';

        return back()->with('success', "Negara \"{$studyDestination->name}\" berhasil {$status} dari halaman publik.");
    }

    /**
     * DELETE /admin/study-destinations/{studyDestination}
     *
     * Soft delete + tutup celah otomatis: semua negara aktif yang
     * urutannya lebih besar dari yang dihapus akan maju 1 nomor,
     * supaya urutan aktif selalu rapat (1,2,3...N) tanpa lubang.
     *
     * File gambar TIDAK dihapus fisik (supaya bisa dipulihkan lewat
     * restore()).
     */
    public function destroy(StudyDestination $studyDestination): RedirectResponse
    {
        DB::transaction(function () use ($studyDestination) {
            $deletedOrder = $studyDestination->display_order;

            $studyDestination->delete();

            StudyDestination::where('display_order', '>', $deletedOrder)
                ->whereNull('deleted_at')
                ->decrement('display_order');
        });

        return back()->with('success', "Negara \"{$studyDestination->name}\" berhasil dihapus.");
    }

    /**
     * PATCH /admin/study-destinations/{studyDestination}/restore
     *
     * Karena destroy() menutup celah otomatis, nomor urutan lama milik
     * item yang di-restore hampir pasti sudah terisi negara lain.
     * Restore SELALU taruh item di akhir list (max+1) — predictable,
     * tidak pernah gagal. Admin bisa reorder manual lagi kalau perlu.
     */
    public function restore(StudyDestination $studyDestination): RedirectResponse
    {
        $newOrder = (int) (StudyDestination::whereNull('deleted_at')->max('display_order') ?? 0) + 1;

        $studyDestination->display_order = $newOrder;
        $studyDestination->restore();

        return back()->with('success', "Negara \"{$studyDestination->name}\" berhasil direstore dengan urutan tampil {$newOrder}.");
    }

    /**
     * Auto-geser urutan tampil supaya tidak ada duplikat di antara
     * negara yang AKTIF (soft-deleted diabaikan).
     *
     * - $oldOrder null (skenario CREATE): dorong semua yang >= $newOrder maju 1,
     *   membuka celah untuk item baru.
     * - $oldOrder terisi (skenario UPDATE): dorong item DI ANTARA posisi lama
     *   dan posisi baru, arahnya tergantung item pindah naik atau turun.
     */
    private function reorderForNewPosition(int $newOrder, ?int $excludeId = null, ?int $oldOrder = null): void
    {
        if ($oldOrder === null) {
            StudyDestination::where('display_order', '>=', $newOrder)
                ->whereNull('deleted_at')
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->increment('display_order');

            return;
        }

        if ($newOrder === $oldOrder) {
            return;
        }

        if ($newOrder < $oldOrder) {
            // Pindah ke nomor lebih kecil: dorong [newOrder, oldOrder) maju 1
            StudyDestination::where('display_order', '>=', $newOrder)
                ->where('display_order', '<', $oldOrder)
                ->whereNull('deleted_at')
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->increment('display_order');
        } else {
            // Pindah ke nomor lebih besar: tutup celah (oldOrder, newOrder] mundur 1
            StudyDestination::where('display_order', '>', $oldOrder)
                ->where('display_order', '<=', $newOrder)
                ->whereNull('deleted_at')
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->decrement('display_order');
        }
    }

    /**
     * Validasi shared untuk store() & update().
     * Field 'image' divalidasi terpisah dari kolom database lainnya
     * karena butuh rule beda (required saat create, nullable saat update).
     */
    private function validateDestination(Request $request, bool $imageRequired): array
    {
        $validated = $request->validate([
            'flag_emoji' => ['required', 'string', 'max:10'],
            'name' => ['required', 'string', 'max:100'],
            'scholarship_name' => ['required', 'string', 'max:150'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        // 'image' bukan kolom database (itu file upload, ditangani terpisah
        // via storeImage()) — buang dari array sebelum dipakai untuk create/update
        unset($validated['image']);

        $validated['display_order'] = $validated['display_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }

    /**
     * Simpan file upload ke Storage disk 'public', folder 'countries/'.
     * Nama file di-generate random supaya tidak ada collision antar upload.
     */
    private function storeImage(Request $request): string
    {
        return $request->file('image')->store('countries', 'public');
    }
}