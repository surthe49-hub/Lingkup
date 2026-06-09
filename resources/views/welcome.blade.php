<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LINGKUP — AI-Powered Global Career Navigator untuk mahasiswa Indonesia menuju karir internasional.">
    <title>LINGKUP — Global Career Navigator</title>

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Google Fonts: Syne (display) + DM Sans (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">

    <style>
        /* =============================================
           CSS VARIABLES & BASE
        ============================================= */
        :root {
            --bg-deep:       #050b18;
            --bg-surface:    #0a1628;
            --bg-card:       #0f1e38;
            --accent-cyan:   #00d4ff;
            --accent-teal:   #00b89f;
            --accent-amber:  #f5a623;
            --text-primary:  #e8f0fe;
            --text-muted:    #7a92b8;
            --border:        rgba(0, 212, 255, 0.15);
            --glow-cyan:     0 0 40px rgba(0, 212, 255, 0.25);
            --glow-sm:       0 0 16px rgba(0, 212, 255, 0.18);
            --radius-lg:     1.25rem;
            --radius-sm:     0.625rem;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--bg-deep);
            color: var(--text-primary);
            overflow-x: hidden;
            line-height: 1.7;
        }

        h1, h2, h3, h4, h5, .brand-text {
            font-family: 'Syne', sans-serif;
        }

        /* =============================================
           NOISE TEXTURE OVERLAY
        ============================================= */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: 0.5;
        }

        /* =============================================
           NAVBAR
        ============================================= */
        .navbar-lingkup {
            background: rgba(5, 11, 24, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: background 0.3s;
        }

        .navbar-lingkup.scrolled {
            background: rgba(5, 11, 24, 0.97);
            box-shadow: var(--glow-sm);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-teal));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: var(--bg-deep);
            font-weight: 800;
            box-shadow: var(--glow-sm);
        }

        .brand-text {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            background: linear-gradient(90deg, var(--accent-cyan), var(--accent-teal));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link-custom {
            color: var(--text-muted) !important;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.03em;
            transition: color 0.2s;
            padding: 0.4rem 0.9rem !important;
        }

        .nav-link-custom:hover { color: var(--text-primary) !important; }

        .btn-nav-login {
            color: var(--accent-cyan) !important;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.45rem 1.1rem !important;
            transition: all 0.25s;
        }

        .btn-nav-login:hover {
            background: rgba(0, 212, 255, 0.08);
            border-color: var(--accent-cyan);
        }

        .btn-nav-register {
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-teal));
            color: var(--bg-deep) !important;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 700;
            padding: 0.45rem 1.2rem !important;
            border: none;
            transition: opacity 0.2s, transform 0.2s;
            box-shadow: var(--glow-sm);
        }

        .btn-nav-register:hover {
            opacity: 0.88;
            transform: translateY(-1px);
        }

        .navbar-toggler {
            border: 1px solid var(--border);
            padding: 0.3rem 0.6rem;
        }

        .navbar-toggler-icon {
            filter: invert(0.8);
        }

        /* =============================================
           HERO SECTION
        ============================================= */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            overflow: hidden;
            padding: 7rem 0 5rem;
        }

        /* Animated grid background */
        .hero-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,212,255,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,212,255,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridShift 20s linear infinite;
        }

        @keyframes gridShift {
            0%   { background-position: 0 0, 0 0; }
            100% { background-position: 60px 60px, 60px 60px; }
        }

        /* Radial glow blobs */
        .hero-blob-1 {
            position: absolute;
            top: -20%;
            right: -10%;
            width: 700px;
            height: 700px;
            background: radial-gradient(ellipse, rgba(0,212,255,0.12) 0%, transparent 65%);
            pointer-events: none;
            animation: blobFloat 8s ease-in-out infinite alternate;
        }

        .hero-blob-2 {
            position: absolute;
            bottom: -15%;
            left: -5%;
            width: 600px;
            height: 600px;
            background: radial-gradient(ellipse, rgba(0,184,159,0.1) 0%, transparent 65%);
            pointer-events: none;
            animation: blobFloat 10s ease-in-out infinite alternate-reverse;
        }

        @keyframes blobFloat {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(20px, -20px) scale(1.05); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--accent-cyan);
            background: rgba(0, 212, 255, 0.08);
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 100px;
            padding: 0.35rem 1rem;
            margin-bottom: 1.5rem;
            animation: fadeSlideUp 0.7s ease both;
        }

        .hero-eyebrow .dot {
            width: 6px;
            height: 6px;
            background: var(--accent-cyan);
            border-radius: 50%;
            animation: pulse 1.5s ease infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.4; transform: scale(0.6); }
        }

        .hero-headline {
            font-size: clamp(2.4rem, 5.5vw, 4.2rem);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.02em;
            margin-bottom: 1.5rem;
            animation: fadeSlideUp 0.7s 0.1s ease both;
        }

        .hero-headline .highlight {
            background: linear-gradient(135deg, var(--accent-cyan) 0%, var(--accent-teal) 60%, var(--accent-amber) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-sub {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 540px;
            margin-bottom: 2.5rem;
            font-weight: 300;
            animation: fadeSlideUp 0.7s 0.2s ease both;
        }

        .hero-cta-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
            animation: fadeSlideUp 0.7s 0.3s ease both;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-teal));
            color: var(--bg-deep);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.85rem 2rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            box-shadow: var(--glow-cyan);
            transition: all 0.25s;
            border: none;
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 50px rgba(0,212,255,0.4);
            color: var(--bg-deep);
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-primary);
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.85rem 1.5rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            text-decoration: none;
            background: transparent;
            transition: all 0.25s;
        }

        .btn-hero-secondary:hover {
            background: rgba(255,255,255,0.04);
            border-color: rgba(0,212,255,0.3);
            color: var(--accent-cyan);
        }

        /* Hero stats */
        .hero-stats {
            display: flex;
            gap: 2.5rem;
            margin-top: 3.5rem;
            flex-wrap: wrap;
            animation: fadeSlideUp 0.7s 0.45s ease both;
        }

        .stat-item .stat-number {
            font-family: 'Syne', sans-serif;
            font-size: 1.7rem;
            font-weight: 800;
            background: linear-gradient(90deg, var(--accent-cyan), var(--accent-teal));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }

        .stat-item .stat-label {
            font-size: 0.78rem;
            color: var(--text-muted);
            letter-spacing: 0.05em;
            margin-top: 0.25rem;
        }

        /* Hero visual - floating card */
        .hero-visual {
            position: relative;
            z-index: 2;
            animation: fadeSlideLeft 0.9s 0.2s ease both;
        }

        @keyframes fadeSlideLeft {
            from { opacity: 0; transform: translateX(40px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .hero-card-mock {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.8rem;
            box-shadow: 0 24px 80px rgba(0,0,0,0.5), var(--glow-sm);
            position: relative;
            overflow: hidden;
        }

        .hero-card-mock::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--accent-cyan), var(--accent-teal), transparent);
        }

        .mock-label {
            font-size: 0.7rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--accent-cyan);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .mock-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .mock-avatar {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-teal));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: var(--bg-deep);
            font-size: 1rem;
            flex-shrink: 0;
        }

        .mock-name { font-weight: 600; font-size: 0.95rem; }
        .mock-sub  { font-size: 0.78rem; color: var(--text-muted); }

        .mock-match-bar {
            margin-bottom: 0.8rem;
        }

        .mock-match-bar .bar-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-bottom: 0.35rem;
        }

        .mock-match-bar .bar-track {
            background: rgba(255,255,255,0.07);
            border-radius: 100px;
            height: 6px;
            overflow: hidden;
        }

        .mock-match-bar .bar-fill {
            height: 100%;
            border-radius: 100px;
            background: linear-gradient(90deg, var(--accent-cyan), var(--accent-teal));
            animation: barGrow 1.5s 0.8s ease both;
            transform-origin: left;
        }

        @keyframes barGrow {
            from { transform: scaleX(0); }
            to   { transform: scaleX(1); }
        }

        .mock-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-top: 1.2rem;
        }

        .mock-tag {
            font-size: 0.7rem;
            padding: 0.2rem 0.65rem;
            border-radius: 100px;
            font-weight: 600;
        }

        .tag-blue  { background: rgba(0,212,255,0.1); color: var(--accent-cyan); border: 1px solid rgba(0,212,255,0.2); }
        .tag-teal  { background: rgba(0,184,159,0.1); color: var(--accent-teal); border: 1px solid rgba(0,184,159,0.2); }
        .tag-amber { background: rgba(245,166,35,0.1); color: var(--accent-amber); border: 1px solid rgba(245,166,35,0.2); }

        /* Floating badge */
        .mock-badge {
            position: absolute;
            top: -12px;
            right: 1.5rem;
            background: linear-gradient(135deg, var(--accent-amber), #e8952f);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.28rem 0.75rem;
            border-radius: 100px;
            letter-spacing: 0.04em;
            box-shadow: 0 4px 16px rgba(245,166,35,0.35);
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* =============================================
           SECTION SHARED STYLES
        ============================================= */
        section { position: relative; z-index: 1; }

        .section-eyebrow {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--accent-cyan);
            margin-bottom: 0.75rem;
            display: block;
        }

        .section-title {
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.02em;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .section-desc {
            color: var(--text-muted);
            font-size: 1rem;
            max-width: 520px;
            font-weight: 300;
        }

        .divider-line {
            width: 48px;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-cyan), var(--accent-teal));
            border-radius: 2px;
            margin: 1.25rem 0;
        }

        /* =============================================
           FEATURES SECTION
        ============================================= */
        .features-section {
            padding: 6rem 0;
            background: var(--bg-surface);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .feature-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 2rem 1.75rem;
            height: 100%;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s, border-color 0.3s, box-shadow 0.3s;
            cursor: default;
        }

        .feature-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at top left, rgba(0,212,255,0.06), transparent 60%);
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            border-color: rgba(0,212,255,0.3);
            box-shadow: 0 20px 60px rgba(0,0,0,0.35), var(--glow-sm);
        }

        .feature-card:hover::after { opacity: 1; }

        .feature-icon-wrap {
            width: 52px;
            height: 52px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1.25rem;
            position: relative;
        }

        .icon-cyan  { background: rgba(0,212,255,0.12); color: var(--accent-cyan); }
        .icon-teal  { background: rgba(0,184,159,0.12); color: var(--accent-teal); }
        .icon-amber { background: rgba(245,166,35,0.12); color: var(--accent-amber); }
        .icon-purple { background: rgba(130,80,255,0.12); color: #a78bfa; }

        .feature-card h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.65rem;
            letter-spacing: -0.01em;
        }

        .feature-card p {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.65;
            margin-bottom: 1.25rem;
        }

        .feature-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            padding: 0.25rem 0.7rem;
            border-radius: 100px;
            background: rgba(0,212,255,0.06);
            color: var(--accent-cyan);
            border: 1px solid rgba(0,212,255,0.15);
        }

        .feature-num {
            position: absolute;
            top: 1.5rem;
            right: 1.75rem;
            font-family: 'Syne', sans-serif;
            font-size: 3rem;
            font-weight: 800;
            color: rgba(255,255,255,0.03);
            line-height: 1;
            user-select: none;
        }

        /* =============================================
           ABOUT SECTION
        ============================================= */
        .about-section {
            padding: 6rem 0;
            background: var(--bg-deep);
        }

        .about-visual {
            position: relative;
        }

        .about-globe-wrap {
            position: relative;
            aspect-ratio: 1;
            max-width: 420px;
            margin: 0 auto;
        }

        .globe-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(0,212,255,0.12);
            animation: ringRotate 20s linear infinite;
        }

        .globe-ring:nth-child(1) {
            inset: 0;
            border-color: rgba(0,212,255,0.15);
            animation-duration: 18s;
        }

        .globe-ring:nth-child(2) {
            inset: 8%;
            border-color: rgba(0,184,159,0.1);
            animation-duration: 24s;
            animation-direction: reverse;
        }

        .globe-ring:nth-child(3) {
            inset: 18%;
            border-color: rgba(245,166,35,0.08);
            animation-duration: 32s;
        }

        @keyframes ringRotate {
            from { transform: rotate(0deg) rotateY(60deg); }
            to   { transform: rotate(360deg) rotateY(60deg); }
        }

        .globe-center {
            position: absolute;
            inset: 24%;
            background: radial-gradient(circle at 38% 38%, rgba(0,212,255,0.22), rgba(0,184,159,0.12) 50%, rgba(5,11,24,0.9));
            border-radius: 50%;
            border: 1px solid rgba(0,212,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            box-shadow: 0 0 60px rgba(0,212,255,0.15), inset 0 0 30px rgba(0,212,255,0.05);
        }

        .globe-dot {
            position: absolute;
            width: 8px;
            height: 8px;
            background: var(--accent-cyan);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--accent-cyan);
            animation: dotPulse 2s ease infinite;
        }

        .globe-dot:nth-child(5) { top: 18%; left: 25%; animation-delay: 0s; }
        .globe-dot:nth-child(6) { top: 35%; right: 15%; animation-delay: 0.5s; background: var(--accent-teal); box-shadow: 0 0 8px var(--accent-teal); }
        .globe-dot:nth-child(7) { bottom: 22%; left: 30%; animation-delay: 1s; background: var(--accent-amber); box-shadow: 0 0 8px var(--accent-amber); }

        @keyframes dotPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50%       { transform: scale(1.5); opacity: 0.5; }
        }

        .about-list { list-style: none; padding: 0; margin: 0; }

        .about-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            margin-bottom: 1.1rem;
            font-size: 0.925rem;
            color: var(--text-muted);
        }

        .about-list li .bi {
            color: var(--accent-cyan);
            font-size: 1rem;
            margin-top: 0.1rem;
            flex-shrink: 0;
        }

        .about-list li strong { color: var(--text-primary); }

        .about-badge-row {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .about-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0.6rem 1rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .about-badge .bi { color: var(--accent-cyan); font-size: 1rem; }

        /* =============================================
           CTA BANNER
        ============================================= */
        .cta-section {
            padding: 5rem 0;
            background: var(--bg-surface);
            border-top: 1px solid var(--border);
        }

        .cta-card {
            background: linear-gradient(135deg, rgba(0,212,255,0.08) 0%, rgba(0,184,159,0.06) 100%);
            border: 1px solid rgba(0,212,255,0.2);
            border-radius: 1.5rem;
            padding: 3.5rem 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-card::before {
            content: '';
            position: absolute;
            top: 0; left: 50%; transform: translateX(-50%);
            width: 60%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--accent-cyan), transparent);
        }

        .cta-card h2 {
            font-size: clamp(1.6rem, 3vw, 2.4rem);
            margin-bottom: 0.75rem;
        }

        .cta-card p {
            color: var(--text-muted);
            margin-bottom: 2rem;
            font-size: 1rem;
        }

        /* =============================================
           FOOTER
        ============================================= */
        .footer {
            background: var(--bg-deep);
            border-top: 1px solid var(--border);
            padding: 3.5rem 0 2rem;
        }

        .footer-brand p {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 0.75rem;
            max-width: 260px;
            line-height: 1.6;
        }

        .footer-heading {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .footer-links { list-style: none; padding: 0; margin: 0; }

        .footer-links li { margin-bottom: 0.55rem; }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s;
        }

        .footer-links a:hover { color: var(--accent-cyan); }

        .footer-divider {
            border-color: var(--border);
            margin: 2rem 0;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-bottom p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin: 0;
        }

        .social-links {
            display: flex;
            gap: 0.75rem;
        }

        .social-links a {
            width: 34px;
            height: 34px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .social-links a:hover {
            background: rgba(0,212,255,0.1);
            border-color: rgba(0,212,255,0.3);
            color: var(--accent-cyan);
        }

        /* =============================================
           SCROLL ANIMATIONS
        ============================================= */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.65s ease, transform 0.65s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }

        /* =============================================
           RESPONSIVE
        ============================================= */
        @media (max-width: 991.98px) {
            .hero { padding: 5rem 0 4rem; }
            .hero-visual { margin-top: 3rem; }
            .hero-stats { gap: 1.5rem; }
            .about-globe-wrap { max-width: 280px; margin-bottom: 2rem; }
        }

        @media (max-width: 575.98px) {
            .hero-headline { letter-spacing: -0.01em; }
            .hero-cta-group { flex-direction: column; }
            .btn-hero-primary, .btn-hero-secondary { width: 100%; justify-content: center; }
            .cta-card { padding: 2.5rem 1.5rem; }
        }
    </style>
</head>
<body>

    {{-- =============================================
         NAVBAR
    ============================================= --}}
    <nav class="navbar navbar-expand-lg navbar-lingkup" id="mainNav">
        <div class="container">
            <a class="brand-logo" href="#">
                <div class="brand-icon">L</div>
                <span class="brand-text">LINGKUP</span>
            </a>

            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMenu"
                aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav mx-auto gap-1">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#">Blog</a>
                    </li>
                </ul>
                <div class="d-flex gap-2 mt-3 mt-lg-0">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="nav-link btn-nav-login">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-link btn-nav-register">
                            Mulai Gratis
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- =============================================
         HERO SECTION
    ============================================= --}}
    <section class="hero" id="hero">
        <div class="hero-grid"></div>
        <div class="hero-blob-1"></div>
        <div class="hero-blob-2"></div>

        <div class="container">
            <div class="row align-items-center gy-4">

                {{-- LEFT: Copy --}}
                <div class="col-lg-6 hero-content">
                    <div class="hero-eyebrow">
                        <span class="dot"></span>
                        AI-Powered Career Platform
                    </div>

                    <h1 class="hero-headline">
                        Global Career Navigator<br>
                        untuk <span class="highlight">Mahasiswa Indonesia</span>
                    </h1>

                    <p class="hero-sub">
                        LINGKUP membantu kamu memetakan potensi, menemukan jalur karir internasional, dan membangun langkah nyata menuju impian global — didukung kecerdasan buatan.
                    </p>

                    <div class="hero-cta-group">
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-hero-primary">
                            Mulai Perjalananmu
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        @endif
                        <a href="#features" class="btn-hero-secondary">
                            <i class="bi bi-play-circle"></i>
                            Lihat Cara Kerja
                        </a>
                    </div>

                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number">12K+</div>
                            <div class="stat-label">Mahasiswa Aktif</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">80+</div>
                            <div class="stat-label">Negara Tujuan</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">94%</div>
                            <div class="stat-label">Tingkat Kepuasan</div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Visual Mock Card --}}
                <div class="col-lg-6 hero-visual">
                    <div class="hero-card-mock mx-auto" style="max-width: 400px; position: relative;">
                        <div class="mock-badge">AI Match Score</div>

                        <div class="mock-label">Career Pathway Analysis</div>

                        <div class="mock-profile">
                            <div class="mock-avatar">AR</div>
                            <div>
                                <div class="mock-name">Rafi Awallaisal</div>
                                <div class="mock-sub">Sistem Informasi · TUP</div>
                            </div>
                        </div>

                        <div class="mock-match-bar">
                            <div class="bar-label">
                                <span>Machine Learning Engineer · Singapore</span>
                                <span style="color: var(--accent-cyan); font-weight: 700;">92%</span>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill" style="width: 92%;"></div>
                            </div>
                        </div>

                        <div class="mock-match-bar">
                            <div class="bar-label">
                                <span>Data Scientist · Belanda</span>
                                <span style="color: var(--accent-teal); font-weight: 700;">87%</span>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill" style="width: 87%; background: linear-gradient(90deg, var(--accent-teal), #00f2c3);"></div>
                            </div>
                        </div>

                        <div class="mock-match-bar">
                            <div class="bar-label">
                                <span>Software Architect · Jerman</span>
                                <span style="color: var(--accent-amber); font-weight: 700;">79%</span>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill" style="width: 79%; background: linear-gradient(90deg, var(--accent-amber), #f5d623);"></div>
                            </div>
                        </div>

                        <div class="mock-tags">
                            <span class="mock-tag tag-blue">Python</span>
                            <span class="mock-tag tag-teal">TensorFlow</span>
                            <span class="mock-tag tag-amber">Leadership</span>
                            <span class="mock-tag tag-blue">IELTS 8.0</span>
                            <span class="mock-tag tag-teal">Research</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- =============================================
         FEATURES SECTION
    ============================================= --}}
    <section class="features-section" id="features">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center reveal">
                    <span class="section-eyebrow">Platform Fitur</span>
                    <h2 class="section-title">Empat Pilar Navigasi Karir Global</h2>
                    <div class="divider-line mx-auto"></div>
                    <p class="section-desc mx-auto">Dari kenali diri hingga raih target — LINGKUP menyediakan sistem lengkap untuk setiap langkah perjalanan karirmu.</p>
                </div>
            </div>

            <div class="row g-4">
                {{-- Feature 1: Profile Assessment --}}
                <div class="col-md-6 col-lg-3 reveal reveal-delay-1">
                    <div class="feature-card">
                        <span class="feature-num">01</span>
                        <div class="feature-icon-wrap icon-cyan">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <h4>Profile Assessment</h4>
                        <p>Kenali potensi unikmu melalui asesmen multi-dimensi yang mengukur kompetensi, minat, dan gaya belajar secara komprehensif.</p>
                        <div class="feature-badge">
                            <i class="bi bi-circle-fill" style="font-size: 0.45rem;"></i>
                            Berbasis Psikometri
                        </div>
                    </div>
                </div>

                {{-- Feature 2: Target Selection --}}
                <div class="col-md-6 col-lg-3 reveal reveal-delay-2">
                    <div class="feature-card">
                        <span class="feature-num">02</span>
                        <div class="feature-icon-wrap icon-teal">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <h4>Target Selection</h4>
                        <p>Jelajahi ribuan peluang karir dan kampus di seluruh dunia, difilter sesuai profil dan aspirasimu secara personal.</p>
                        <div class="feature-badge" style="background: rgba(0,184,159,0.06); color: var(--accent-teal); border-color: rgba(0,184,159,0.15);">
                            <i class="bi bi-circle-fill" style="font-size: 0.45rem;"></i>
                            80+ Negara
                        </div>
                    </div>
                </div>

                {{-- Feature 3: AI Pathway Builder --}}
                <div class="col-md-6 col-lg-3 reveal reveal-delay-3">
                    <div class="feature-card">
                        <span class="feature-num">03</span>
                        <div class="feature-icon-wrap icon-amber">
                            <i class="bi bi-cpu-fill"></i>
                        </div>
                        <h4>AI Pathway Builder</h4>
                        <p>Mesin AI kami menyusun rencana aksi yang terstruktur — dari skill gap, sertifikasi, hingga timeline aplikasi yang realistis.</p>
                        <div class="feature-badge" style="background: rgba(245,166,35,0.06); color: var(--accent-amber); border-color: rgba(245,166,35,0.15);">
                            <i class="bi bi-circle-fill" style="font-size: 0.45rem;"></i>
                            Powered by AI
                        </div>
                    </div>
                </div>

                {{-- Feature 4: Progress Tracker --}}
                <div class="col-md-6 col-lg-3 reveal reveal-delay-4">
                    <div class="feature-card">
                        <span class="feature-num">04</span>
                        <div class="feature-icon-wrap icon-purple">
                            <i class="bi bi-bar-chart-line-fill"></i>
                        </div>
                        <h4>Progress Tracker</h4>
                        <p>Pantau setiap milestone perjalananmu dengan dashboard visual yang memotivasi dan mengingatkan langkah-langkah berikutnya.</p>
                        <div class="feature-badge" style="background: rgba(130,80,255,0.06); color: #a78bfa; border-color: rgba(130,80,255,0.15);">
                            <i class="bi bi-circle-fill" style="font-size: 0.45rem;"></i>
                            Real-time Update
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =============================================
         ABOUT SECTION
    ============================================= --}}
    <section class="about-section" id="about">
        <div class="container">
            <div class="row align-items-center gy-5">

                {{-- LEFT: Globe Visual --}}
                <div class="col-lg-5 reveal">
                    <div class="about-globe-wrap">
                        <div class="globe-ring"></div>
                        <div class="globe-ring"></div>
                        <div class="globe-ring"></div>
                        <div class="globe-center">🌏</div>
                        <div class="globe-dot"></div>
                        <div class="globe-dot"></div>
                        <div class="globe-dot"></div>
                    </div>
                </div>

                {{-- RIGHT: Content --}}
                <div class="col-lg-7 reveal reveal-delay-2">
                    <span class="section-eyebrow">Tentang LINGKUP</span>
                    <h2 class="section-title">Kenapa LINGKUP<br>Berbeda?</h2>
                    <div class="divider-line"></div>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.75rem; font-weight: 300;">
                        LINGKUP lahir dari keyakinan bahwa setiap mahasiswa Indonesia berhak mendapatkan akses ke peluang karir global yang setara. Bukan hanya informasi — melainkan panduan yang benar-benar personal.
                    </p>

                    <ul class="about-list">
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span><strong>Berbasis data nyata</strong> — rekomendasi dibangun dari profil ratusan ribu profesional sukses di berbagai industri global.</span>
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span><strong>Konteks Indonesia</strong> — kami memahami dinamika pendidikan lokal, sertifikasi yang relevan, dan jalur yang tepat dari sini.</span>
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span><strong>Terus berkembang</strong> — AI kami belajar dari setiap interaksi untuk memberikan panduan yang makin akurat setiap harinya.</span>
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span><strong>Komunitas aktif</strong> — terhubung dengan sesama mahasiswa dan alumni yang sudah berhasil menapaki karir global.</span>
                        </li>
                    </ul>

                    <div class="about-badge-row">
                        <div class="about-badge">
                            <i class="bi bi-shield-check"></i>
                            Data Aman
                        </div>
                        <div class="about-badge">
                            <i class="bi bi-award"></i>
                            Terverifikasi
                        </div>
                        <div class="about-badge">
                            <i class="bi bi-people-fill"></i>
                            Komunitas 12K+
                        </div>
                        <div class="about-badge">
                            <i class="bi bi-lightning-charge-fill"></i>
                            Selalu Diperbarui
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- =============================================
         CTA BANNER
    ============================================= --}}
    <section class="cta-section">
        <div class="container">
            <div class="cta-card reveal">
                <div class="hero-eyebrow mx-auto mb-3" style="display: inline-flex;">
                    <span class="dot"></span>
                    Bergabung Sekarang — Gratis
                </div>
                <h2 class="section-title">Siap Menavigasi Karir Globalmu?</h2>
                <p>Ribuan mahasiswa Indonesia sudah memulai perjalanan mereka. Kamu berikutnya.</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-hero-primary">
                        Daftar Gratis Sekarang
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    @endif
                    @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn-hero-secondary">
                        Sudah punya akun? Login
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- =============================================
         FOOTER
    ============================================= --}}
    <footer class="footer">
        <div class="container">
            <div class="row gy-4">

                <div class="col-lg-4 footer-brand">
                    <a class="brand-logo mb-3" href="#" style="display: inline-flex;">
                        <div class="brand-icon">L</div>
                        <span class="brand-text">LINGKUP</span>
                    </a>
                    <p>AI-Powered Global Career Navigator untuk mahasiswa Indonesia menuju panggung internasional.</p>
                </div>

                <div class="col-6 col-lg-2 offset-lg-1">
                    <div class="footer-heading">Platform</div>
                    <ul class="footer-links">
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="#about">Tentang</a></li>
                        <li><a href="#">Harga</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-2">
                    <div class="footer-heading">Akun</div>
                    <ul class="footer-links">
                        @if (Route::has('login'))
                        <li><a href="{{ route('login') }}">Login</a></li>
                        @endif
                        @if (Route::has('register'))
                        <li><a href="{{ route('register') }}">Daftar</a></li>
                        @endif
                        <li><a href="#">Dashboard</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-2">
                    <div class="footer-heading">Legal</div>
                    <ul class="footer-links">
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Kontak</a></li>
                    </ul>
                </div>

            </div>

            <hr class="footer-divider">

            <div class="footer-bottom">
                <p>© {{ date('Y') }} LINGKUP. Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> untuk mahasiswa Indonesia.</p>
                <div class="social-links">
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    <a href="#" aria-label="Twitter/X"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Navbar scroll effect
        const nav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 40);
        });

        // Scroll reveal observer
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        reveals.forEach(el => observer.observe(el));

        // Smooth scroll for nav anchors
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // Close mobile nav if open
                    const navMenu = document.getElementById('navMenu');
                    if (navMenu.classList.contains('show')) {
                        bootstrap.Collapse.getInstance(navMenu)?.hide();
                    }
                }
            });
        });
    </script>
</body>
</html>