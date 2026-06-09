<section class="lingkup-card mb-4">
    <header class="mb-4">
        <h3 class="lingkup-card-title">Informasi Akun</h3>
        <p style="color: var(--lingkup-text-muted); font-size: 0.9375rem; margin: 0;">
            Perbarui nama dan alamat email akun Anda.
        </p>
    </header>

    {{-- Form verifikasi email (jika perlu) --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        {{-- Status message --}}
        @if (session('status') === 'profile-updated')
            <div class="lingkup-alert lingkup-alert-success mb-4">
                <i class="bi bi-check-circle me-2"></i>
                Profil berhasil diperbarui.
            </div>
        @endif

        {{-- Field: Name --}}
        <div class="lingkup-form-group">
            <label for="name" class="lingkup-form-label">Nama Lengkap</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $user->name) }}"
                class="lingkup-form-control{{ $errors->has('name') ? ' lingkup-form-control-error' : '' }}"
                required
                autofocus
                autocomplete="name">
            @error('name')
                <div class="lingkup-form-error">
                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        {{-- Field: Email --}}
        <div class="lingkup-form-group">
            <label for="email" class="lingkup-form-label">Alamat Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $user->email) }}"
                class="lingkup-form-control{{ $errors->has('email') ? ' lingkup-form-control-error' : '' }}"
                required
                autocomplete="username">
            @error('email')
                <div class="lingkup-form-error">
                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="lingkup-form-help mt-2">
                    <i class="bi bi-info-circle me-1"></i>
                    Email kamu belum diverifikasi.
                    <button form="send-verification" type="submit"
                            class="btn btn-link p-0 align-baseline"
                            style="font-size: inherit; color: var(--lingkup-primary);">
                        Kirim ulang email verifikasi
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <div class="mt-1" style="color: var(--lingkup-success);">
                            <i class="bi bi-check-circle me-1"></i>
                            Link verifikasi baru telah dikirim ke email kamu.
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="lingkup-form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2 me-1"></i> Simpan Perubahan
            </button>
            @if (session('status') === 'profile-updated')
                <span style="color: var(--lingkup-success); font-size: 0.875rem;">
                    <i class="bi bi-check-circle me-1"></i>Tersimpan.
                </span>
            @endif
        </div>
    </form>
</section>