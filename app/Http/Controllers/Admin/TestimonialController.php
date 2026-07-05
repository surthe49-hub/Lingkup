<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    /**
     * GET /admin/testimonials
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->input('status');

        $query = Testimonial::query();

        if ($statusFilter === 'deleted') {
            $query->onlyTrashed();
        }

        $testimonials = $query
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->input('search');
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('role', 'like', "%{$term}%");
                });
            })
            ->when($statusFilter === 'active', fn ($query) => $query->where('is_active', true))
            ->when($statusFilter === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderBy('display_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.testimonials.index', [
            'testimonials' => $testimonials,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * GET /admin/testimonials/create
     */
    public function create(): View
    {
        $nextOrder = (int) (Testimonial::max('display_order') ?? 0) + 1;

        return view('admin.testimonials.create', compact('nextOrder'));
    }

    /**
     * POST /admin/testimonials
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTestimonial($request);

        Testimonial::create($validated);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', "Testimonial \"{$validated['name']}\" berhasil ditambahkan.");
    }

    /**
     * GET /admin/testimonials/{testimonial}/edit
     */
    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * PUT /admin/testimonials/{testimonial}
     */
    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $this->validateTestimonial($request);

        $testimonial->update($validated);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', "Testimonial \"{$testimonial->name}\" berhasil diperbarui.");
    }

    /**
     * PATCH /admin/testimonials/{testimonial}/toggle-active
     *
     * Sembunyikan/tampilkan testimonial dari halaman publik /reviews
     * tanpa menghapus datanya.
     */
    public function toggleActive(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['is_active' => ! $testimonial->is_active]);

        $status = $testimonial->is_active ? 'ditampilkan' : 'disembunyikan';

        return back()->with('success', "Testimonial \"{$testimonial->name}\" berhasil {$status} dari halaman publik.");
    }

    /**
     * DELETE /admin/testimonials/{testimonial}
     */
    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return back()->with('success', "Testimonial \"{$testimonial->name}\" berhasil dihapus.");
    }

    /**
     * PATCH /admin/testimonials/{testimonial}/restore
     *
     * Route menggunakan ->withTrashed() supaya route model binding
     * bisa menemukan testimonial yang sudah dihapus.
     */
    public function restore(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->restore();

        return back()->with('success', "Testimonial \"{$testimonial->name}\" berhasil direstore.");
    }

    /**
     * Validasi shared untuk store() & update().
     */
    private function validateTestimonial(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'role' => ['required', 'string', 'max:150'],
            'avatar_color' => ['required', 'in:primary,peach,teal,green,pink'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'message' => ['required', 'string'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['display_order'] = $validated['display_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
