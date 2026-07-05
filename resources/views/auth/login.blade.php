<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-transparent.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — LINGKUP</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-deep:      #050b18;
            --bg-surface:   #0a1628;
            --bg-card:      #0f1e38;
            --accent-cyan:  #00d4ff;
            --accent-teal:  #00b89f;
            --accent-amber: #f5a623;
            --text-primary: #e8f0fe;
            --text-muted:   #7a92b8;
            --border:       rgba(0, 212, 255, 0.15);
            --border-focus: rgba(0, 212, 255, 0.5);
            --glow-sm:      0 0 20px rgba(0, 212, 255, 0.15);
            --radius:       0.75rem;
            --radius-sm:    0.5rem;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--bg-deep);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            overflow: hidden;
        }

        /* ── SPLIT LAYOUT ── */
        .auth-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* LEFT PANEL — decorative */
        .auth-left {
            display: none;
            position: relative;
            overflow: hidden;
            background: var(--bg-surface);
            border-right: 1px solid var(--border);
        }

        @media (min-width: 992px) {
            .auth-left  { display: flex; flex: 0 0 48%; flex-direction: column; justify-content: center; align-items: center; padding: 3rem; }
            .auth-right { flex: 0 0 52%; }
        }

        /* Animated grid */
        .auth-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,212,255,0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,212,255,0.05) 1px, transparent 1px);
            background-size: 48px 48px;
            animation: gridMove 18s linear infinite;
        }

        @keyframes gridMove {
            0%   { background-position: 0 0, 0 0; }
            100% { background-position: 48px 48px, 48px 48px; }
        }

        /* Glow blobs */
        .auth-left::after {
            content: '';
            position: absolute;
            top: 15%;
            left: 50%;
            transform: translateX(-50%);
            width: 420px;
            height: 420px;
            background: radial-gradient(ellipse, rgba(0,212,255,0.13) 0%, transparent 65%);
            pointer-events: none;
            animation: blobPulse 6s ease-in-out infinite alternate;
        }

        @keyframes blobPulse {
            from { opacity: 0.7; transform: translateX(-50%) scale(1); }
            to   { opacity: 1;   transform: translateX(-50%) scale(1.08); }
        }

        .left-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 400px;
        }

        /* Orbit illustration */
        .orbit-wrap {
            width: 240px;
            height: 240px;
            position: relative;
            margin: 0 auto 2.5rem;
        }

        .orbit-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(0,212,255,0.18);
        }

        .orbit-ring:nth-child(1) { inset: 0;   animation: spin 16s linear infinite; }
        .orbit-ring:nth-child(2) { inset: 14%; animation: spin 22s linear infinite reverse; border-color: rgba(0,184,159,0.15); }
        .orbit-ring:nth-child(3) { inset: 28%; animation: spin 28s linear infinite; border-color: rgba(245,166,35,0.1); }

        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        .orbit-core {
            position: absolute;
            inset: 38%;
            background: radial-gradient(circle at 38% 38%, rgba(0,212,255,0.3), rgba(0,184,159,0.15));
            border-radius: 50%;
            border: 1px solid rgba(0,212,255,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            box-shadow: 0 0 40px rgba(0,212,255,0.2);
        }

        .orbit-dot {
            position: absolute;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            box-shadow: 0 0 8px currentColor;
            animation: dotBlink 2s ease infinite;
        }

        .orbit-dot-1 { background: var(--accent-cyan);  color: var(--accent-cyan);  top: 4%;  left: 46%; animation-delay: 0s; }
        .orbit-dot-2 { background: var(--accent-teal);  color: var(--accent-teal);  top: 48%; right: 3%; animation-delay: 0.7s; }
        .orbit-dot-3 { background: var(--accent-amber); color: var(--accent-amber); bottom: 5%; left: 44%; animation-delay: 1.4s; }

        @keyframes dotBlink {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.4; transform: scale(0.5); }
        }

        .left-content h2 {
            font-family: 'Syne', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }

        .left-content p {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.7;
            font-weight: 300;
        }

        /* Testimonial card */
        .testi-card {
            margin-top: 2.5rem;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            text-align: left;
            position: relative;
        }

        .testi-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--accent-cyan), var(--accent-teal), transparent);
            border-radius: var(--radius) var(--radius) 0 0;
        }

        .testi-text {
            font-size: 0.82rem;
            color: var(--text-muted);
            font-style: italic;
            line-height: 1.65;
            margin-bottom: 1rem;
        }

        .testi-author {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .testi-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-teal));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--bg-deep);
            flex-shrink: 0;
        }

        .testi-name  { font-size: 0.8rem; font-weight: 600; }
        .testi-role  { font-size: 0.72rem; color: var(--text-muted); }

        /* ── RIGHT PANEL — form ── */
        .auth-right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            position: relative;
            overflow-y: auto;
            background: var(--bg-deep);
        }

        /* Subtle glow at top-right for depth */
        .auth-right::before {
            content: '';
            position: fixed;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(ellipse, rgba(0,212,255,0.06), transparent 65%);
            pointer-events: none;
        }

        .form-panel {
            width: 100%;
            max-width: 420px;
            animation: panelIn 0.6s ease both;
        }

        @keyframes panelIn {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            color: var(--text-muted);
            font-size: 0.82rem;
            text-decoration: none;
            margin-bottom: 2.5rem;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--accent-cyan); }

        /* Brand */
        .form-brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 2rem;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            object-fit: contain;
        }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            background: linear-gradient(90deg, var(--accent-cyan), var(--accent-teal));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Heading */
        .form-heading {
            font-family: 'Syne', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.4rem;
        }

        .form-subheading {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
            font-weight: 300;
        }

        /* Form fields */
        .field-group {
            margin-bottom: 1.1rem;
        }

        .field-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-bottom: 0.45rem;
        }

        .field-wrap {
            position: relative;
        }

        .field-wrap .field-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.95rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .field-wrap input {
            width: 100%;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            padding: 0.75rem 2.75rem 0.75rem 2.75rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            -webkit-appearance: none;
        }

        .field-wrap input::placeholder { color: var(--text-muted); opacity: 0.6; }

        .field-wrap input:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(0,212,255,0.08);
        }

        .field-wrap input:focus + .field-icon,
        .field-wrap input:focus ~ .field-icon { color: var(--accent-cyan); }

        /* Fix: icon left of input, icon order */
        .field-wrap .icon-left  { left: 1rem; }
        .field-wrap .icon-right {
            left: auto;
            right: 1rem;
            cursor: pointer;
            pointer-events: all;
        }

        .field-wrap input { padding-left: 2.75rem; }

        /* Error state */
        .field-error {
            font-size: 0.75rem;
            color: #f87171;
            margin-top: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .is-invalid { border-color: rgba(248,113,113,0.5) !important; }

        /* Remember + Forgot row */
        .form-extras {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .custom-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .custom-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border: 1px solid var(--border);
            border-radius: 4px;
            background: var(--bg-card);
            accent-color: var(--accent-cyan);
            cursor: pointer;
        }

        .custom-check span {
            font-size: 0.82rem;
            color: var(--text-muted);
            user-select: none;
        }

        .forgot-link {
            font-size: 0.82rem;
            color: var(--accent-cyan);
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .forgot-link:hover { opacity: 0.75; }

        /* Submit button */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-teal));
            color: var(--bg-deep);
            font-family: 'Syne', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            border: none;
            border-radius: var(--radius-sm);
            padding: 0.85rem;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 0 24px rgba(0,212,255,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
            transform: skewX(-20deg);
            transition: left 0.5s ease;
        }

        .btn-submit:hover::before { left: 160%; }
        .btn-submit:hover { opacity: 0.92; transform: translateY(-1px); box-shadow: 0 0 36px rgba(0,212,255,0.35); }
        .btn-submit:active { transform: translateY(0); }

        /* Divider */
        .form-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .form-divider::before,
        .form-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .form-divider span {
            font-size: 0.75rem;
            color: var(--text-muted);
            white-space: nowrap;
        }

        /* Register link */
        .form-footer {
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 1.5rem;
        }

        .form-footer a {
            color: var(--accent-cyan);
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .form-footer a:hover { opacity: 0.75; }

        /* Alert */
        .alert-custom {
            background: rgba(248,113,113,0.08);
            border: 1px solid rgba(248,113,113,0.25);
            border-radius: var(--radius-sm);
            padding: 0.85rem 1rem;
            font-size: 0.82rem;
            color: #fca5a5;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .alert-success {
            background: rgba(0,212,255,0.06);
            border-color: rgba(0,212,255,0.2);
            color: var(--accent-cyan);
        }

        /* Session status */
        .session-status {
            background: rgba(0,184,159,0.08);
            border: 1px solid rgba(0,184,159,0.2);
            border-radius: var(--radius-sm);
            padding: 0.75rem 1rem;
            font-size: 0.82rem;
            color: var(--accent-teal);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @media (max-width: 575.98px) {
            .form-panel { max-width: 100%; }
            .form-extras { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
        }
    </style>
</head>
<body>
<div class="auth-wrapper">

    {{-- ── LEFT PANEL ── --}}
    <div class="auth-left">
        <div class="left-content">
            <div class="orbit-wrap">
                <div class="orbit-ring"></div>
                <div class="orbit-ring"></div>
                <div class="orbit-ring"></div>
                <div class="orbit-core">🌏</div>
                <div class="orbit-dot orbit-dot-1"></div>
                <div class="orbit-dot orbit-dot-2"></div>
                <div class="orbit-dot orbit-dot-3"></div>
            </div>

            <h2>Selamat Datang Kembali</h2>
            <p>Lanjutkan perjalanan karir globalmu.<br>Ribuan peluang menunggumu di luar sana.</p>

            <div class="testi-card">
                <p class="testi-text">"LINGKUP benar-benar mengubah cara saya melihat karir. Dalam 3 bulan, saya sudah diterima magang di startup teknologi di Singapura."</p>
                <div class="testi-author">
                    <div class="testi-avatar">DK</div>
                    <div>
                        <div class="testi-name">Dita Kusuma</div>
                        <div class="testi-role">Teknik Komputer · ITB · Magang di Singapore</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── RIGHT PANEL — FORM ── --}}
    <div class="auth-right">
        <div class="form-panel">

            <a href="{{ url('/') }}" class="back-link">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Beranda
            </a>

            <div class="form-brand">
                <img src="{{ asset('images/logo-transparent.png') }}" alt="LINGKUP" class="brand-icon">
                <span class="brand-name">LINGKUP</span>
            </div>

            <h1 class="form-heading">Masuk ke Akun</h1>
            <p class="form-subheading">Belum punya akun? <a href="{{ route('register') }}" style="color: var(--accent-cyan); text-decoration: none; font-weight: 500;">Daftar gratis</a></p>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="session-status">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('status') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert-custom">
                    <i class="bi bi-exclamation-circle-fill mt-1" style="flex-shrink:0;"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="field-group">
                    <label for="email">Email</label>
                    <div class="field-wrap">
                        <i class="bi bi-envelope field-icon icon-left"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            autocomplete="email"
                            autofocus
                            class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                            required
                        >
                    </div>
                    @error('email')
                        <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field-group">
                    <label for="password">Password</label>
                    <div class="field-wrap">
                        <i class="bi bi-lock field-icon icon-left"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                            required
                        >
                        <i class="bi bi-eye field-icon icon-right" id="togglePassword" title="Tampilkan password"></i>
                    </div>
                    @error('password')
                        <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="form-extras">
                    <label class="custom-check">
                        <input type="checkbox" name="remember" id="remember_me">
                        <span>Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit">
                    Masuk ke LINGKUP
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="form-divider"><span>atau</span></div>

            <div class="form-footer">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar Sekarang — Gratis</a>
            </div>

        </div>
    </div>

</div>

<script>
    // Toggle password visibility
    const toggle = document.getElementById('togglePassword');
    const pwInput = document.getElementById('password');
    if (toggle && pwInput) {
        toggle.addEventListener('click', () => {
            const isText = pwInput.type === 'text';
            pwInput.type = isText ? 'password' : 'text';
            toggle.classList.toggle('bi-eye',      isText);
            toggle.classList.toggle('bi-eye-slash', !isText);
        });
    }
</script>
</body>
</html>