<section class="lingkup-card lingkup-card-danger mb-4">
    <header class="mb-3">
        <h3 class="lingkup-card-title">Hapus Akun</h3>
        <p style="color: #B91C1C; font-size: 0.9375rem; margin: 0;">
            Setelah akun dihapus, semua data akan dihapus permanen dan tidak dapat dipulihkan.
            Pastikan kamu sudah mengunduh semua data penting sebelum melanjutkan.
        </p>
    </header>

    {{-- Trigger button --}}
    <button
        type="button"
        class="btn btn-danger"
        data-bs-toggle="modal"
        data-bs-target="#confirmDeleteModal">
        <i class="bi bi-trash3 me-1"></i> Hapus Akun Saya
    </button>
</section>

{{-- ── Modal Konfirmasi Hapus Akun ── --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1"
     aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: var(--radius-lg); border: 1px solid #FECACA;">

            <div class="modal-header" style="border-bottom: 1px solid #FECACA; background: #FEF2F2;">
                <div class="d-flex align-items-center gap-2">
                    <div style="
                        width: 36px; height: 36px;
                        background: #FEE2E2;
                        border-radius: var(--radius-md);
                        display: flex; align-items: center; justify-content: center;
                        color: var(--lingkup-danger); font-size: 1.1rem;
                        flex-shrink: 0;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h5 class="modal-title mb-0" id="confirmDeleteModalLabel"
                        style="font-weight: 700; color: #991B1B; font-size: 1rem;">
                        Konfirmasi Hapus Akun
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body" style="padding: var(--space-lg);">
                <p style="color: var(--lingkup-text-muted); font-size: 0.9375rem; margin-bottom: var(--space-md);">
                    Tindakan ini <strong style="color: var(--lingkup-text);">tidak dapat dibatalkan</strong>.
                    Masukkan password kamu untuk mengonfirmasi penghapusan akun.
                </p>

                <form method="post" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                    @csrf
                    @method('delete')

                    <div class="lingkup-form-group mb-0">
                        <label for="delete_password" class="lingkup-form-label">Password</label>
                        <input
                            type="password"
                            id="delete_password"
                            name="password"
                            placeholder="Masukkan password kamu"
                            class="lingkup-form-control {{ $errors->userDeletion->has('password') ? 'lingkup-form-control-error' : '' }}"
                            autocomplete="current-password">
                        @if ($errors->userDeletion->has('password'))
                            <div class="lingkup-form-error">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                {{ $errors->userDeletion->first('password') }}
                            </div>
                        @endif
                    </div>
                </form>
            </div>

            <div class="modal-footer" style="border-top: 1px solid var(--lingkup-border); padding: var(--space-md) var(--space-lg);">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" form="deleteAccountForm" class="btn btn-danger">
                    <i class="bi bi-trash3 me-1"></i> Ya, Hapus Akun Saya
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Auto-open modal jika ada error validasi delete --}}
@if ($errors->userDeletion->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        modal.show();
    });
</script>
@endif