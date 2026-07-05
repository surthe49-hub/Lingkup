@extends('layouts.dashboard')

@section('content')
<div class="lingkup-page-header lingkup-page-header-with-action">
    <div>
        <h1 class="lingkup-page-title">Manajemen Target Beasiswa</h1>
        <p class="lingkup-page-subtitle">
            Kelola daftar target beasiswa: tambah, edit, nonaktifkan, atau hapus.
        </p>
    </div>
    <a href="{{ route('admin.targets.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Target
    </a>
</div>

@if (session('error'))
    <div class="lingkup-alert lingkup-alert-error">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ session('error') }}
    </div>
@endif

{{-- ============================ --}}
{{-- Filter & Search              --}}
{{-- ============================ --}}
<form method="GET" action="{{ route('admin.targets.index') }}" class="lingkup-users-filter">
    <input type="text"
           name="search"
           value="{{ $filters['search'] ?? '' }}"
           placeholder="Cari nama atau negara..."
           class="lingkup-users-filter-input">

    <select name="status" class="lingkup-users-filter-select">
        <option value="">Semua Status</option>
        <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
        <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        <option value="deleted" {{ ($filters['status'] ?? '') === 'deleted' ? 'selected' : '' }}>Terhapus</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    @if (($filters['search'] ?? '') || ($filters['status'] ?? ''))
        <a href="{{ route('admin.targets.index') }}" class="lingkup-users-filter-reset">Reset</a>
    @endif
</form>

{{-- ============================ --}}
{{-- Tabel Targets                --}}
{{-- ============================ --}}
<div class="lingkup-card lingkup-users-table-card">
    <table class="lingkup-users-table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Negara</th>
                <th>Jenjang</th>
                <th>Tipe Program</th>
                <th>Status</th>
                <th>Dipakai Oleh</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($targets as $target)
                <tr>
                    <td>{{ $target->name }}</td>
                    <td>{{ $target->country }}</td>
                    <td>{{ $target->education_level }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $target->program_type)) }}</td>
                    <td>
                        @if ($target->trashed())
                            <span class="lingkup-badge lingkup-badge-neutral">Terhapus</span>
                        @elseif ($target->is_active)
                            <span class="lingkup-badge lingkup-badge-success">Aktif</span>
                        @else
                            <span class="lingkup-badge lingkup-badge-neutral">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <span class="lingkup-targets-usage" title="Jumlah user yang pernah memilih target ini">
                            <i class="bi bi-people"></i> {{ $target->user_targets_count }}
                        </span>
                        <span class="lingkup-targets-usage" title="Jumlah pathway yang dibuat dari target ini">
                            <i class="bi bi-map"></i> {{ $target->pathways_count }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="lingkup-users-actions">
                            @if ($target->trashed())
                                <form method="POST"
                                      action="{{ route('admin.targets.restore', $target) }}"
                                      class="lingkup-users-inline-form"
                                      data-confirm-title="Restore Target"
                                      data-confirm-message="Kembalikan target &quot;{{ $target->name }}&quot; yang telah dihapus?">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="lingkup-users-action-btn lingkup-users-action-activate">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('admin.targets.edit', $target) }}" class="lingkup-users-action-btn lingkup-users-action-edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.targets.toggle-active', $target) }}"
                                      class="lingkup-users-inline-form"
                                      data-confirm-title="{{ $target->is_active ? 'Nonaktifkan Target' : 'Aktifkan Target' }}"
                                      data-confirm-message="{{ $target->is_active
                                            ? "Nonaktifkan target \"{$target->name}\"? Target tidak akan muncul lagi di pilihan user."
                                            : "Aktifkan kembali target \"{$target->name}\"?" }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="lingkup-users-action-btn {{ $target->is_active ? 'lingkup-users-action-suspend' : 'lingkup-users-action-activate' }}">
                                        {{ $target->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('admin.targets.destroy', $target) }}"
                                      class="lingkup-users-inline-form"
                                      data-confirm-title="Hapus Target"
                                      data-confirm-message="Yakin ingin menghapus target &quot;{{ $target->name }}&quot;? Aksi ini akan diblokir jika target masih dipakai user.">
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
                    <td colspan="7" class="lingkup-users-empty">Belum ada target beasiswa.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="lingkup-users-pagination">
    {{ $targets->links() }}
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
    let confirmed = false;

    document.querySelectorAll('form[data-confirm-title]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            modalTitleEl.textContent = form.dataset.confirmTitle;
            modalBodyEl.textContent = form.dataset.confirmMessage;
            pendingForm = form;
            confirmed = false;
            hiddenTrigger.click();
        });
    });

    confirmBtn.addEventListener('click', function () {
        confirmed = true;
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