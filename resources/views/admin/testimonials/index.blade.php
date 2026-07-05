@extends('layouts.dashboard')

@section('content')
<div class="lingkup-page-header lingkup-page-header-with-action">
    <div>
        <h1 class="lingkup-page-title">Manajemen Testimonial</h1>
        <p class="lingkup-page-subtitle">
            Kelola testimonial yang ditampilkan di halaman publik /reviews.
        </p>
    </div>
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Testimonial
    </a>
</div>

{{-- ============================ --}}
{{-- Filter & Search              --}}
{{-- ============================ --}}
<form method="GET" action="{{ route('admin.testimonials.index') }}" class="lingkup-users-filter">
    <input type="text"
           name="search"
           value="{{ $filters['search'] ?? '' }}"
           placeholder="Cari nama atau role..."
           class="lingkup-users-filter-input">

    <select name="status" class="lingkup-users-filter-select">
        <option value="">Semua Status</option>
        <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Ditampilkan</option>
        <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Disembunyikan</option>
        <option value="deleted" {{ ($filters['status'] ?? '') === 'deleted' ? 'selected' : '' }}>Terhapus</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    @if (($filters['search'] ?? '') || ($filters['status'] ?? ''))
        <a href="{{ route('admin.testimonials.index') }}" class="lingkup-users-filter-reset">Reset</a>
    @endif
</form>

{{-- ============================ --}}
{{-- Tabel Testimonials           --}}
{{-- ============================ --}}
<div class="lingkup-card lingkup-users-table-card">
    <table class="lingkup-users-table">
        <thead>
            <tr>
                <th>Urutan</th>
                <th>Nama</th>
                <th>Role</th>
                <th>Rating</th>
                <th>Komentar</th>
                <th>Status</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($testimonials as $testimonial)
                <tr>
                    <td>{{ $testimonial->display_order }}</td>
                    <td>{{ $testimonial->name }}</td>
                    <td>{{ $testimonial->role }}</td>
                    <td>
                        <span class="lingkup-feedback-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $testimonial->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </span>
                    </td>
                    <td class="lingkup-feedback-comment">
                        <span title="{{ $testimonial->message }}">{{ \Illuminate\Support\Str::limit($testimonial->message, 50) }}</span>
                    </td>
                    <td>
                        @if ($testimonial->trashed())
                            <span class="lingkup-badge lingkup-badge-neutral">Terhapus</span>
                        @elseif ($testimonial->is_active)
                            <span class="lingkup-badge lingkup-badge-success">Ditampilkan</span>
                        @else
                            <span class="lingkup-badge lingkup-badge-neutral">Disembunyikan</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="lingkup-users-actions">
                            @if ($testimonial->trashed())
                                <form method="POST"
                                      action="{{ route('admin.testimonials.restore', $testimonial) }}"
                                      class="lingkup-users-inline-form"
                                      data-confirm-title="Restore Testimonial"
                                      data-confirm-message="Kembalikan testimonial &quot;{{ $testimonial->name }}&quot; yang telah dihapus?">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="lingkup-users-action-btn lingkup-users-action-activate">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="lingkup-users-action-edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.testimonials.toggle-active', $testimonial) }}"
                                      class="lingkup-users-inline-form"
                                      data-confirm-title="{{ $testimonial->is_active ? 'Sembunyikan Testimonial' : 'Tampilkan Testimonial' }}"
                                      data-confirm-message="{{ $testimonial->is_active
                                            ? "Sembunyikan testimonial \"{$testimonial->name}\" dari halaman publik?"
                                            : "Tampilkan kembali testimonial \"{$testimonial->name}\" di halaman publik?" }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="lingkup-users-action-btn {{ $testimonial->is_active ? 'lingkup-users-action-suspend' : 'lingkup-users-action-activate' }}">
                                        {{ $testimonial->is_active ? 'Sembunyikan' : 'Tampilkan' }}
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('admin.testimonials.destroy', $testimonial) }}"
                                      class="lingkup-users-inline-form"
                                      data-confirm-title="Hapus Testimonial"
                                      data-confirm-message="Yakin ingin menghapus testimonial &quot;{{ $testimonial->name }}&quot;?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="lingkup-users-action-btn lingkup-users-action-delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="lingkup-users-empty">Belum ada testimonial.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="lingkup-users-pagination">
    {{ $testimonials->links() }}
</div>

{{-- ============================ --}}
{{-- Shared Confirmation Modal    --}}
{{-- ============================ --}}
<div class="modal fade" id="confirmActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmActionModalTitle">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="confirmActionModalBody">
                Apakah Anda yakin?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmActionModalConfirmBtn" data-bs-dismiss="modal">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

<button type="button"
        id="confirmModalHiddenTrigger"
        data-bs-toggle="modal"
        data-bs-target="#confirmActionModal"
        style="display: none;"></button>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('confirmActionModal');
    const modalTitleEl = document.getElementById('confirmActionModalTitle');
    const modalBodyEl = document.getElementById('confirmActionModalBody');
    const confirmBtn = document.getElementById('confirmActionModalConfirmBtn');
    const hiddenTrigger = document.getElementById('confirmModalHiddenTrigger');

    let pendingForm = null;

    document.querySelectorAll('form[data-confirm-title]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            modalTitleEl.textContent = form.dataset.confirmTitle;
            modalBodyEl.textContent = form.dataset.confirmMessage;
            pendingForm = form;
            hiddenTrigger.click();
        });
    });

    confirmBtn.addEventListener('click', function () {
        if (pendingForm) {
            pendingForm.submit();
        }
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        pendingForm = null;
    });
});
</script>
@endpush
@endsection