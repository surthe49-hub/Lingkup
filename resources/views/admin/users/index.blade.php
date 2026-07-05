@extends('layouts.dashboard')

@section('content')
<div class="lingkup-page-header">
    <h1 class="lingkup-page-title">Manajemen User</h1>
    <p class="lingkup-page-subtitle">
        Kelola akun pengguna: suspend, aktifkan, ubah role, atau hapus.
    </p>
</div>

{{-- ============================ --}}
{{-- Filter & Search              --}}
{{-- ============================ --}}
<form method="GET" action="{{ route('admin.users.index') }}" class="lingkup-users-filter">
    <input type="text"
           name="search"
           value="{{ $filters['search'] ?? '' }}"
           placeholder="Cari nama atau email..."
           class="lingkup-users-filter-input">

    <select name="role" class="lingkup-users-filter-select">
        <option value="">Semua Role</option>
        <option value="user" {{ ($filters['role'] ?? '') === 'user' ? 'selected' : '' }}>User</option>
        <option value="admin" {{ ($filters['role'] ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
    </select>

    <select name="status" class="lingkup-users-filter-select">
        <option value="">Semua Status</option>
        <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
        <option value="suspended" {{ ($filters['status'] ?? '') === 'suspended' ? 'selected' : '' }}>Ditangguhkan</option>
        <option value="deleted" {{ ($filters['status'] ?? '') === 'deleted' ? 'selected' : '' }}>Terhapus</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    @if (($filters['search'] ?? '') || ($filters['role'] ?? '') || ($filters['status'] ?? ''))
        <a href="{{ route('admin.users.index') }}" class="lingkup-users-filter-reset">Reset</a>
    @endif
</form>

{{-- ============================ --}}
{{-- Tabel Users                  --}}
{{-- ============================ --}}
<div class="lingkup-card lingkup-users-table-card">
    <table class="lingkup-users-table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Terdaftar</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="lingkup-badge {{ $user->isAdmin() ? 'lingkup-badge-primary' : 'lingkup-badge-neutral' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        @if ($user->trashed())
                            <span class="lingkup-badge lingkup-badge-neutral">Terhapus</span>
                        @elseif ($user->isSuspended())
                            <span class="lingkup-badge lingkup-badge-danger">Ditangguhkan</span>
                        @else
                            <span class="lingkup-badge lingkup-badge-success">Aktif</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td class="text-end">
                        <div class="lingkup-users-actions">
                            @if ($user->trashed())
                                {{-- User terhapus: hanya tombol Restore --}}
                                <form method="POST"
                                      action="{{ route('admin.users.restore', $user) }}"
                                      class="lingkup-users-inline-form"
                                      data-confirm-title="Restore User"
                                      data-confirm-message="Kembalikan akun {{ $user->name }} yang telah dihapus?">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="lingkup-users-action-btn lingkup-users-action-activate">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>
                                </form>
                            @else
                                {{-- Ubah Role --}}
                                <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="lingkup-users-inline-form">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role"
                                            class="lingkup-users-role-select"
                                            data-original-value="{{ $user->role }}"
                                            data-user-name="{{ $user->name }}"
                                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </form>

                                {{-- Suspend / Aktifkan --}}
                                @if ($user->isSuspended())
                                    <form method="POST"
                                          action="{{ route('admin.users.activate', $user) }}"
                                          class="lingkup-users-inline-form"
                                          data-confirm-title="Aktifkan User"
                                          data-confirm-message="Aktifkan kembali akun {{ $user->name }}? User akan bisa login seperti biasa.">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="lingkup-users-action-btn lingkup-users-action-activate">
                                            Aktifkan
                                        </button>
                                    </form>
                                @else
                                    <form method="POST"
                                          action="{{ route('admin.users.suspend', $user) }}"
                                          class="lingkup-users-inline-form"
                                          data-confirm-title="Tangguhkan User"
                                          data-confirm-message="Yakin ingin menangguhkan {{ $user->name }}? User tidak akan bisa login sampai diaktifkan kembali.">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="lingkup-users-action-btn lingkup-users-action-suspend"
                                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                            Suspend
                                        </button>
                                    </form>
                                @endif

                                {{-- Hapus --}}
                                <form method="POST"
                                      action="{{ route('admin.users.destroy', $user) }}"
                                      class="lingkup-users-inline-form"
                                      data-confirm-title="Hapus User"
                                      data-confirm-message="Yakin ingin menghapus {{ $user->name }}? Data bisa direstore lewat filter 'Terhapus'.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="lingkup-users-action-btn lingkup-users-action-delete"
                                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="lingkup-users-empty">Tidak ada user ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="lingkup-users-pagination">
    {{ $users->links() }}
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

{{-- Hidden trigger — dipakai untuk membuka modal secara programatik dari JS --}}
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
    let pendingRevert = null;
    let confirmed = false;

    function openConfirmModal({ title, message, form, revert }) {
        modalTitleEl.textContent = title;
        modalBodyEl.textContent = message;
        pendingForm = form;
        pendingRevert = revert || null;
        confirmed = false;
        hiddenTrigger.click();
    }

    confirmBtn.addEventListener('click', function () {
        confirmed = true;
        if (pendingForm) {
            pendingForm.submit();
        }
    });

    // Kalau modal ditutup TANPA konfirmasi (klik Batal / X / backdrop), jalankan revert
    modalEl.addEventListener('hidden.bs.modal', function () {
        if (! confirmed && pendingRevert) {
            pendingRevert();
        }
        pendingForm = null;
        pendingRevert = null;
    });

    // Intercept form suspend & hapus (yang punya data-confirm-title)
    document.querySelectorAll('form[data-confirm-title]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            openConfirmModal({
                title: form.dataset.confirmTitle,
                message: form.dataset.confirmMessage,
                form: form,
            });
        });
    });

    // Intercept dropdown ubah role
    document.querySelectorAll('.lingkup-users-role-select').forEach(function (select) {
        select.addEventListener('change', function () {
            const form = select.closest('form');
            const originalValue = select.dataset.originalValue;
            const newValue = select.value;
            const userName = select.dataset.userName;

            openConfirmModal({
                title: 'Ubah Role Pengguna',
                message: `Ubah role ${userName} dari "${originalValue}" menjadi "${newValue}"?`,
                form: form,
                revert: function () {
                    select.value = originalValue;
                },
            });
        });
    });
});
</script>
@endpush
@endsection