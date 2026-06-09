<section class="lingkup-card mb-4">
    <header class="mb-4">
        <h3 class="lingkup-card-title">Ubah Password</h3>
        <p style="color: var(--lingkup-text-muted); font-size: 0.9375rem; margin: 0;">
            Pastikan akun kamu menggunakan password yang panjang dan acak untuk keamanan optimal.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        {{-- Status: berhasil --}}
        @if (session('status') === 'password-updated')
            <div class="lingkup-alert lingkup-alert-success mb-4">
                <i class="bi bi-check-circle me-2"></i>
                Password berhasil diperbarui.
            </div>
        @endif

        {{-- Password Saat Ini --}}
        <div class="lingkup-form-group">
            <label for="current_password" class="lingkup-form-label">Password Saat Ini</label>
            <input
                type="password"
                id="current_password"
                name="current_password"
                class="lingkup-form-control{{ $errors->updatePassword->has('current_password') ? ' lingkup-form-control-error' : '' }}"
                autocomplete="current-password">
            @if ($errors->updatePassword->has('current_password'))
                <div class="lingkup-form-error">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ $errors->updatePassword->first('current_password') }}
                </div>
            @endif
        </div>

        {{-- Password Baru --}}
        <div class="lingkup-form-group">
            <label for="password" class="lingkup-form-label">Password Baru</label>
            <input
                type="password"
                id="password"
                name="password"
                class="lingkup-form-control{{ $errors->updatePassword->has('password') ? ' lingkup-form-control-error' : '' }}"
                autocomplete="new-password">
            <div class="lingkup-form-help">
                <i class="bi bi-info-circle me-1"></i>
                Minimal 8 karakter. Disarankan kombinasi huruf, angka, dan simbol.
            </div>
            @if ($errors->updatePassword->has('password'))
                <div class="lingkup-form-error">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ $errors->updatePassword->first('password') }}
                </div>
            @endif
        </div>

        {{-- Konfirmasi Password Baru --}}
        <div class="lingkup-form-group">
            <label for="password_confirmation" class="lingkup-form-label">Konfirmasi Password Baru</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="lingkup-form-control{{ $errors->updatePassword->has('password_confirmation') ? ' lingkup-form-control-error' : '' }}"
                autocomplete="new-password">
            @if ($errors->updatePassword->has('password_confirmation'))
                <div class="lingkup-form-error">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="lingkup-form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-shield-lock me-1"></i> Ubah Password
            </button>
            @if (session('status') === 'password-updated')
                <span style="color: var(--lingkup-success); font-size: 0.875rem;">
                    <i class="bi bi-check-circle me-1"></i>Tersimpan.
                </span>
            @endif
        </div>
    </form>
</section>