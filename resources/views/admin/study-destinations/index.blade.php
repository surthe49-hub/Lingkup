@extends('layouts.dashboard')

@section('content')
<div class="lingkup-page-header lingkup-page-header-with-action">
    <div>
        <h1 class="lingkup-page-title">Manajemen Negara Tujuan</h1>
        <p class="lingkup-page-subtitle">
            Kelola kartu negara yang ditampilkan di section "Negara Impianmu" halaman Home.
        </p>
    </div>
    <a href="{{ route('admin.study-destinations.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Negara
    </a>
</div>

{{-- ============================ --}}
{{-- Filter & Search              --}}
{{-- ============================ --}}
<form method="GET" action="{{ route('admin.study-destinations.index') }}" class="lingkup-users-filter">
    <input type="text"
           name="search"
           value="{{ $filters['search'] ?? '' }}"
           placeholder="Cari nama negara atau beasiswa..."
           class="lingkup-users-filter-input">

    <select name="status" class="lingkup-users-filter-select">
        <option value="">Semua Status</option>
        <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Ditampilkan</option>
        <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Disembunyikan</option>
        <option value="deleted" {{ ($filters['status'] ?? '') === 'deleted' ? 'selected' : '' }}>Terhapus</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    @if (($filters['search'] ?? '') || ($filters['status'] ?? ''))
        <a href="{{ route('admin.study-destinations.index') }}" class="lingkup-users-filter-reset">Reset</a>
    @endif
</form>

{{-- ============================ --}}
{{-- Tabel Destinations           --}}
{{-- ============================ --}}
<div class="lingkup-card lingkup-users-table-card">
    <table class="lingkup-users-table">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Urutan</th>
                <th>Negara</th>
                <th>Beasiswa</th>
                <th>Status</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($destinations as $destination)
                <tr>
                    <td>
                        @if (! $destination->trashed())
                            <img src="{{ $destination->image_url }}"
                                 alt="{{ $destination->name }}"
                                 class="lingkup-destination-thumb">
                        @else
                            <span class="lingkup-feedback-no-comment">—</span>
                        @endif
                    </td>
                    <td>{{ $destination->display_order }}</td>
                    <td>{{ $destination->flag_emoji }} {{ $destination->name }}</td>
                    <td>{{ $destination->scholarship_name }}</td>
                    <td>
                        @if ($destination->trashed())
                            <span class="lingkup-badge lingkup-badge-neutral">Terhapus</span>
                        @elseif ($destination->is_active)
                            <span class="lingkup-badge lingkup-badge-success">Ditampilkan</span>
                        @else
                            <span class="lingkup-badge lingkup-badge-neutral">Disembunyikan</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="lingkup-users-actions">
                            @if ($destination->trashed())
                                <form method="POST"
                                      action="{{ route('admin.study-destinations.restore', $destination) }}"
                                      class="lingkup-users-inline-form"
                                      data-confirm-title="Restore Negara"
                                      data-confirm-message="Kembalikan &quot;{{ $destination->name }}&quot; yang telah dihapus?">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="lingkup-users-action-btn lingkup-users-action-activate">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('admin.study-destinations.edit', $destination) }}" class="lingkup-users-action-edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.study-destinations.toggle-active', $destination) }}"
                                      class="lingkup-users-inline-form"
                                      data-confirm-title="{{ $destination->is_active ? 'Sembunyikan Negara' : 'Tampilkan Negara' }}"
                                      data-confirm-message="{{ $destination->is_active
                                            ? "Sembunyikan \"{$destination->name}\" dari halaman publik?"
                                            : "Tampilkan kembali \"{$destination->name}\" di halaman publik?" }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="lingkup-users-action-btn {{ $destination->is_active ? 'lingkup-users-action-suspend' : 'lingkup-users-action-activate' }}">
                                        {{ $destination->is_active ? 'Sembunyikan' : 'Tampilkan' }}
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('admin.study-destinations.destroy', $destination) }}"
                                      class="lingkup-users-inline-form"
                                      data-confirm-title="Hapus Negara"
                                      data-confirm-message="Yakin ingin menghapus &quot;{{ $destination->name }}&quot;?">
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
                    <td colspan="6" class="lingkup-users-empty">Belum ada negara ditambahkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="lingkup-users-pagination">
    {{ $destinations->links() }}
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