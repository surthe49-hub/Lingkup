@extends('layouts.dashboard')

@section('content')
<div class="lingkup-page-header">
    <h1 class="lingkup-page-title">Manajemen Feedback</h1>
    <p class="lingkup-page-subtitle">
        Lihat feedback dari pengguna terkait pathway yang mereka terima.
    </p>
</div>

{{-- ============================ --}}
{{-- Filter                       --}}
{{-- ============================ --}}
<form method="GET" action="{{ route('admin.feedback.index') }}" class="lingkup-users-filter">
    <select name="rating" class="lingkup-users-filter-select">
        <option value="">Semua Rating</option>
        @for ($i = 5; $i >= 1; $i--)
            <option value="{{ $i }}" {{ ($filters['rating'] ?? '') == $i ? 'selected' : '' }}>
                {{ $i }} Bintang
            </option>
        @endfor
    </select>

    <select name="status" class="lingkup-users-filter-select">
        <option value="">Semua Status</option>
        <option value="unread" {{ ($filters['status'] ?? '') === 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
        <option value="read" {{ ($filters['status'] ?? '') === 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
        <option value="deleted" {{ ($filters['status'] ?? '') === 'deleted' ? 'selected' : '' }}>Terhapus</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    @if (($filters['rating'] ?? '') || ($filters['status'] ?? ''))
        <a href="{{ route('admin.feedback.index') }}" class="lingkup-users-filter-reset">Reset</a>
    @endif
</form>

{{-- ============================ --}}
{{-- Tabel Feedback               --}}
{{-- ============================ --}}
<div class="lingkup-card lingkup-users-table-card">
    <table class="lingkup-users-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Target</th>
                <th>Rating</th>
                <th>Komentar</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($feedback as $item)
                <tr class="{{ ! $item->trashed() && ! $item->isRead() ? 'lingkup-feedback-row-unread' : '' }}">
                    <td>{{ $item->user->name ?? '—' }}</td>
                    <td>{{ $item->pathway->target->name ?? '—' }}</td>
                    <td>
                        <span class="lingkup-feedback-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $item->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </span>
                    </td>
                    <td class="lingkup-feedback-comment">
                        @if ($item->comment)
                            <span title="{{ $item->comment }}">{{ \Illuminate\Support\Str::limit($item->comment, 60) }}</span>
                        @else
                            <span class="lingkup-feedback-no-comment">Tidak ada komentar</span>
                        @endif
                    </td>
                    <td>
                        @if ($item->trashed())
                            <span class="lingkup-badge lingkup-badge-neutral">Terhapus</span>
                        @elseif ($item->isRead())
                            <span class="lingkup-badge lingkup-badge-neutral">Dibaca</span>
                        @else
                            <span class="lingkup-badge lingkup-badge-warning">Belum Dibaca</span>
                        @endif
                    </td>
                    <td>{{ $item->created_at->format('d M Y') }}</td>
                    <td class="text-end">
                        <div class="lingkup-users-actions">
                            @if ($item->trashed())
                                <form method="POST"
                                      action="{{ route('admin.feedback.restore', $item) }}"
                                      class="lingkup-users-inline-form"
                                      data-confirm-title="Restore Feedback"
                                      data-confirm-message="Kembalikan feedback dari {{ $item->user->name ?? 'user ini' }} yang telah dihapus?">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="lingkup-users-action-btn lingkup-users-action-activate">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.feedback.toggle-read', $item) }}" class="lingkup-users-inline-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="lingkup-users-action-btn lingkup-users-action-activate">
                                        {{ $item->isRead() ? 'Tandai Belum Dibaca' : 'Tandai Dibaca' }}
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('admin.feedback.destroy', $item) }}"
                                      class="lingkup-users-inline-form"
                                      data-confirm-title="Hapus Feedback"
                                      data-confirm-message="Yakin ingin menghapus feedback dari {{ $item->user->name ?? 'user ini' }}?">
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
                    <td colspan="7" class="lingkup-users-empty">Belum ada feedback masuk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="lingkup-users-pagination">
    {{ $feedback->links() }}
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