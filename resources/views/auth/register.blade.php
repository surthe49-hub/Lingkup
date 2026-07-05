<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-transparent.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — LINGKUP</title>

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
        }

        /* ── SPLIT LAYOUT ── */
        .auth-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* LEFT PANEL */
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

        .auth-left::after {
            content: '';
            position: absolute;
            top: 15%;
            left: 50%;
            transform: translateX(-50%);
            width: 420px;
            height: 420px;
            background: radial-gradient(ellipse, rgba(0,184,159,0.12) 0%, transparent 65%);
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
            max-width: 400px;
        }

        /* Steps illustration */
        .steps-wrap {
            margin-bottom: 2rem;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
            animation: stepIn 0.5s ease both;
        }

        .step-item:nth-child(1) { animation-delay: 0.1s; }
        .step-item:nth-child(2) { animation-delay: 0.25s; }
        .step-item:nth-child(3) { animation-delay: 0.4s; }
        .step-item:nth-child(4) { animation-delay: 0.55s; }

        @keyframes stepIn {
            from { opacity: 0; transform: translateX(-16px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .step-num {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: 0.85rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .step-num-1 { background: rgba(0,212,255,0.12);  color: var(--accent-cyan); }
        .step-num-2 { background: rgba(0,184,159,0.12);  color: var(--accent-teal); }
        .step-num-3 { background: rgba(245,166,35,0.12); color: var(--accent-amber); }
        .step-num-4 { background: rgba(130,80,255,0.12); color: #a78bfa; }

        .step-text strong {
            display: block;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 0.15rem;
        }

        .step-text span {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 300;
            line-height: 1.5;
        }

        /* Stats row */
        .stats-row {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            padding-top: 1.75rem;
            border-top: 1px solid var(--border);
        }

        .stat-item .num {
            font-family: 'Syne', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            background: linear-gradient(90deg, var(--accent-cyan), var(--accent-teal));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }

        .stat-item .lbl {
            font-size: 0.72rem;
            color: var(--text-muted);
            margin-top: 0.2rem;
            letter-spacing: 0.04em;
        }

        /* ── RIGHT PANEL ── */
        .auth-right {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2rem 1.5rem 3rem;
            position: relative;
            overflow-y: auto;
            background: var(--bg-deep);
        }

        .auth-right::before {
            content: '';
            position: fixed;
            top: -80px;
            right: -80px;
            width: 350px;
            height: 350px;
            background: radial-gradient(ellipse, rgba(0,184,159,0.06), transparent 65%);
            pointer-events: none;
        }

        .form-panel {
            width: 100%;
            max-width: 440px;
            padding-top: 1.5rem;
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
            margin-bottom: 2rem;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--accent-cyan); }

        /* Brand */
        .form-brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1.75rem;
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

        .form-heading {
            font-family: 'Syne', sans-serif;
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.35rem;
        }

        .form-subheading {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 1.75rem;
            font-weight: 300;
        }

        /* Row for 2-column fields */
        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.85rem;
        }

        @media (max-width: 480px) {
            .field-row { grid-template-columns: 1fr; }
        }

        /* Field */
        .field-group {
            margin-bottom: 1rem;
        }

        .field-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-bottom: 0.4rem;
        }

        .field-wrap { position: relative; }

        .field-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.95rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .icon-left  { left: 1rem; }
        .icon-right { left: auto; right: 1rem; pointer-events: all; cursor: pointer; }

        .field-wrap input {
            width: 100%;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            padding: 0.72rem 2.75rem 0.72rem 2.75rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            -webkit-appearance: none;
        }

        .field-wrap input::placeholder { color: var(--text-muted); opacity: 0.55; }

        .field-wrap input:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(0,212,255,0.08);
        }

        .field-wrap input:focus ~ .icon-left { color: var(--accent-cyan); }

        .field-error {
            font-size: 0.73rem;
            color: #f87171;
            margin-top: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .is-invalid { border-color: rgba(248,113,113,0.5) !important; }

        /* Password strength */
        .pw-strength {
            margin-top: 0.5rem;
        }

        .pw-strength-bars {
            display: flex;
            gap: 3px;
            margin-bottom: 0.3rem;
        }

        .pw-bar {
            flex: 1;
            height: 3px;
            background: rgba(255,255,255,0.07);
            border-radius: 2px;
            transition: background 0.3s;
        }

        .pw-bar.active-weak   { background: #f87171; }
        .pw-bar.active-fair   { background: var(--accent-amber); }
        .pw-bar.active-good   { background: var(--accent-teal); }
        .pw-bar.active-strong { background: var(--accent-cyan); }

        .pw-label {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        /* Terms checkbox */
        .terms-check {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            margin-bottom: 1.25rem;
        }

        .terms-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            margin-top: 2px;
            accent-color: var(--accent-cyan);
            border: 1px solid var(--border);
            background: var(--bg-card);
            cursor: pointer;
        }

        .terms-check span {
            font-size: 0.8rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .terms-check a { color: var(--accent-cyan); text-decoration: none; }
        .terms-check a:hover { opacity: 0.75; }

        /* Submit */
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
            margin: 1.5rem 0 1.25rem;
        }

        .form-divider::before,
        .form-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .form-divider span { font-size: 0.75rem; color: var(--text-muted); white-space: nowrap; }

        /* Footer */
        .form-footer {
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .form-footer a {
            color: var(--accent-cyan);
            text-decoration: none;
            font-weight: 600;
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
    </style>
</head>
<body>
<div class="auth-wrapper">

    {{-- ── LEFT PANEL ── --}}
    <div class="auth-left">
        <div class="left-content">

            <div style="margin-bottom: 2rem;">
                <span style="font-size: 0.72rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--accent-teal);">Mulai dalam 4 langkah</span>
                <h2 style="font-family: 'Syne', sans-serif; font-size: 1.55rem; font-weight: 800; letter-spacing: -0.02em; margin-top: 0.5rem; line-height: 1.2;">Bangun Karir Global dari Indonesia</h2>
            </div>

            <div class="steps-wrap">
                <div class="step-item">
                    <div class="step-num step-num-1">01</div>
                    <div class="step-text">
                        <strong>Buat Profil</strong>
                        <span>Isi data diri dan latar belakang akademikmu secara lengkap.</span>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num step-num-2">02</div>
                    <div class="step-text">
                        <strong>Ikuti Asesmen</strong>
                        <span>Kenali potensi, minat, dan kekuatanmu lewat asesmen AI kami.</span>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num step-num-3">03</div>
                    <div class="step-text">
                        <strong>Dapatkan Peta Karir</strong>
                        <span>AI menyusun jalur karir personal yang realistis untukmu.</span>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num step-num-4">04</div>
                    <div class="step-text">
                        <strong>Lacak Progress</strong>
                        <span>Pantau setiap langkah dan raih milestone karirmu.</span>
                    </div>
                </div>
            </div>

            <div class="stats-row">
                <div class="stat-item">
                    <div class="num">12K+</div>
                    <div class="lbl">Pengguna Aktif</div>
                </div>
                <div class="stat-item">
                    <div class="num">80+</div>
                    <div class="lbl">Negara Tujuan</div>
                </div>
                <div class="stat-item">
                    <div class="num">Gratis</div>
                    <div class="lbl">Untuk Mulai</div>
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

            <h1 class="form-heading">Buat Akun Baru</h1>
            <p class="form-subheading">Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--accent-cyan); text-decoration: none; font-weight: 500;">Masuk di sini</a></p>

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

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div class="field-group">
                    <label for="name">Nama Lengkap</label>
                    <div class="field-wrap">
                        <i class="bi bi-person field-icon icon-left"></i>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Nama lengkapmu"
                            autocomplete="name"
                            autofocus
                            class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                            required
                        >
                    </div>
                    @error('name')
                        <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

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
                            placeholder="Minimal 8 karakter"
                            autocomplete="new-password"
                            class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                            required
                        >
                        <i class="bi bi-eye field-icon icon-right" id="togglePassword" title="Tampilkan password"></i>
                    </div>
                    @error('password')
                        <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror

                    {{-- Password strength indicator --}}
                    <div class="pw-strength" id="pwStrengthWrap" style="display:none;">
                        <div class="pw-strength-bars">
                            <div class="pw-bar" id="bar1"></div>
                            <div class="pw-bar" id="bar2"></div>
                            <div class="pw-bar" id="bar3"></div>
                            <div class="pw-bar" id="bar4"></div>
                        </div>
                        <span class="pw-label" id="pwLabel"></span>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div class="field-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="field-wrap">
                        <i class="bi bi-lock-fill field-icon icon-left"></i>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi password"
                            autocomplete="new-password"
                            class="{{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                            required
                        >
                        <i class="bi bi-eye field-icon icon-right" id="toggleConfirm" title="Tampilkan password"></i>
                    </div>
                    @error('password_confirmation')
                        <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Terms --}}
                <div class="terms-check">
                    <input type="checkbox" id="terms" name="terms" required>
                    <span>
                        Saya menyetujui <a href="#">Syarat & Ketentuan</a> dan
                        <a href="#">Kebijakan Privasi</a> LINGKUP
                    </span>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit">
                    Buat Akun Gratis
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="form-divider"><span>sudah punya akun?</span></div>

            <div class="form-footer">
                <a href="{{ route('login') }}">
                    <i class="bi bi-box-arrow-in-right me-1"></i>
                    Masuk ke Akun LINGKUP
                </a>
            </div>

        </div>
    </div>

</div>

<script>
    // Toggle password visibility — main field
    const toggle = document.getElementById('togglePassword');
    const pwInput = document.getElementById('password');

    if (toggle && pwInput) {
        toggle.addEventListener('click', () => {
            const isText = pwInput.type === 'text';
            pwInput.type = isText ? 'password' : 'text';
            toggle.classList.toggle('bi-eye',       isText);
            toggle.classList.toggle('bi-eye-slash', !isText);
        });
    }

    // Toggle confirm password
    const toggleC = document.getElementById('toggleConfirm');
    const pwConfirm = document.getElementById('password_confirmation');

    if (toggleC && pwConfirm) {
        toggleC.addEventListener('click', () => {
            const isText = pwConfirm.type === 'text';
            pwConfirm.type = isText ? 'password' : 'text';
            toggleC.classList.toggle('bi-eye',       isText);
            toggleC.classList.toggle('bi-eye-slash', !isText);
        });
    }

    // Password strength meter
    const bars  = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3'), document.getElementById('bar4')];
    const label = document.getElementById('pwLabel');
    const wrap  = document.getElementById('pwStrengthWrap');

    const levels = [
        { cls: 'active-weak',   text: 'Lemah',   color: '#f87171' },
        { cls: 'active-fair',   text: 'Cukup',   color: '#f5a623' },
        { cls: 'active-good',   text: 'Baik',    color: '#00b89f' },
        { cls: 'active-strong', text: 'Kuat',    color: '#00d4ff' },
    ];

    function getStrength(pw) {
        let score = 0;
        if (pw.length >= 8)  score++;
        if (pw.length >= 12) score++;
        if (/[A-Z]/.test(pw) && /[0-9]/.test(pw)) score++;
        if (/[^A-Za-z0-9]/.test(pw)) score++;
        return Math.min(score, 4);
    }

    if (pwInput) {
        pwInput.addEventListener('input', () => {
            const pw = pwInput.value;
            if (!pw) { wrap.style.display = 'none'; return; }
            wrap.style.display = 'block';

            const strength = getStrength(pw);
            const level = levels[Math.max(0, strength - 1)];

            bars.forEach((bar, i) => {
                bar.className = 'pw-bar';
                if (i < strength) bar.classList.add(level.cls);
            });

            label.textContent   = `Kekuatan password: ${level.text}`;
            label.style.color   = level.color;
        });
    }
</script>
</body>
</html>