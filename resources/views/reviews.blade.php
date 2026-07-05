@extends('layouts.landing', ['title' => 'Reviews — LINGKUP'])

@section('content')
{{--
    Sprint 5.6.D: REVIEWS PAGE
    Content: Dummy testimonials with disclaimer
    Audience: Authenticated user
--}}

@php
    $currentUser = auth()->user();
    $userInitial = $currentUser ? strtoupper(substr($currentUser->name, 0, 1)) : '?';

    $avatarColors = [
        'primary' => ['#4F46E5', '#312E81'],
        'peach'   => ['#FB923C', '#F97066'],
        'teal'    => ['#2DD4BF', '#0D9488'],
        'green'   => ['#34D399', '#10B981'],
        'pink'    => ['#F472B6', '#DB2777'],
    ];
@endphp

{{-- ============================================ --}}
{{-- NAVBAR (Same as home for consistency)         --}}
{{-- ============================================ --}}
<nav class="landing-navbar home-nav-extended">
    <div class="landing-navbar-container">
        <a href="{{ route('home') }}" class="landing-navbar-brand">
            <div class="landing-navbar-logo">L</div>
            <span>LINGKUP</span>
        </a>

        <div class="home-nav-items">
            <a href="{{ route('dashboard') }}" class="home-nav-item">
                <i class="bi bi-grid-1x2"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('home') }}#countries" class="home-nav-item">
                <i class="bi bi-globe2"></i>
                <span>Negara</span>
            </a>
            <a href="{{ route('about') }}" class="home-nav-item">
                <i class="bi bi-info-circle"></i>
                <span>About Us</span>
            </a>
            <a href="{{ route('reviews') }}" class="home-nav-item active">
                <i class="bi bi-chat-quote"></i>
                <span>Reviews</span>
            </a>
        </div>

        <div class="landing-navbar-actions">
            <button type="button" class="home-mobile-toggle" id="mobile-menu-toggle" aria-label="Toggle menu">
                <i class="bi bi-list"></i>
            </button>

            @if ($currentUser)
                <div class="home-user-menu">
                    <button type="button" class="home-user-trigger" id="user-menu-trigger" aria-haspopup="true" aria-expanded="false">
                        <div class="home-user-avatar">{{ $userInitial }}</div>
                        <span class="home-user-name">{{ \Illuminate\Support\Str::limit($currentUser->name, 15) }}</span>
                        <i class="bi bi-chevron-down home-user-chevron"></i>
                    </button>

                    <div class="home-user-dropdown" id="user-menu-dropdown" role="menu">
                        <div class="home-user-dropdown-header">
                            <div class="home-user-dropdown-name">{{ $currentUser->name }}</div>
                            <div class="home-user-dropdown-email">{{ $currentUser->email }}</div>
                        </div>
                        <div class="home-user-dropdown-divider"></div>
                        <a href="{{ route('home') }}" class="home-user-dropdown-item">
                            <i class="bi bi-house"></i>
                            <span>Home</span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="home-user-dropdown-item">
                            <i class="bi bi-grid-1x2"></i>
                            <span>Dashboard</span>
                        </a>
                        <div class="home-user-dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" class="home-user-dropdown-form">
                            @csrf
                            <button type="submit" class="home-user-dropdown-item home-user-dropdown-logout">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="home-mobile-menu" id="mobile-menu">
        <a href="{{ route('dashboard') }}" class="home-mobile-menu-item">
            <i class="bi bi-grid-1x2"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('home') }}#countries" class="home-mobile-menu-item">
            <i class="bi bi-globe2"></i><span>Negara</span>
        </a>
        <a href="{{ route('about') }}" class="home-mobile-menu-item">
            <i class="bi bi-info-circle"></i><span>About Us</span>
        </a>
        <a href="{{ route('reviews') }}" class="home-mobile-menu-item active">
            <i class="bi bi-chat-quote"></i><span>Reviews</span>
        </a>
    </div>
</nav>

{{-- ============================================ --}}
{{-- HERO REVIEWS                                  --}}
{{-- ============================================ --}}
<section class="page-hero">
    <div class="landing-hero-shapes" aria-hidden="true">
        <div class="hero-shape hero-shape-1"></div>
        <div class="hero-shape hero-shape-2"></div>
        <div class="hero-shape hero-shape-3"></div>
    </div>

    <div class="landing-container">
        <div class="page-hero-content">
            <div class="landing-hero-badge">
                <i class="bi bi-chat-quote"></i>
                <span>Reviews & Stories</span>
            </div>

            <h1 class="page-hero-title">
                Cerita dari mereka yang
                <span class="text-gradient-primary">memulai perjalanan</span>
            </h1>

            <p class="page-hero-subtitle">
                Testimonial dari pengguna LINGKUP yang telah menggunakan platform untuk
                menyusun roadmap persiapan studi internasional.
            </p>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- DEMO DATA DISCLAIMER                          --}}
{{-- ============================================ --}}
<section class="reviews-disclaimer-section">
    <div class="landing-container">
        <div class="reviews-disclaimer">
            <i class="bi bi-info-circle"></i>
            <div>
                <strong>Catatan:</strong> Testimoni di bawah ini merupakan data demo
                untuk keperluan presentasi platform. Reviews dari pengguna asli akan
                ditampilkan setelah LINGKUP diluncurkan secara publik.
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- TESTIMONIALS GRID                             --}}
{{-- ============================================ --}}
<section class="page-section testimonials-section">
    <div class="landing-container">
        <div class="testimonials-grid">
            @foreach ($testimonials as $testimonial)
                @php
                    $colors = $avatarColors[$testimonial['avatar_color']] ?? $avatarColors['primary'];
                    $avatarStyle = "background: linear-gradient(135deg, {$colors[0]} 0%, {$colors[1]} 100%);";
                    $testimonialInitial = strtoupper(substr($testimonial['name'], 0, 1));
                @endphp

                <div class="testimonial-card">
                    {{-- Rating stars --}}
                    <div class="testimonial-rating">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $testimonial['rating'])
                                <i class="bi bi-star-fill"></i>
                            @else
                                <i class="bi bi-star"></i>
                            @endif
                        @endfor
                    </div>

                    {{-- Quote icon --}}
                    <div class="testimonial-quote-icon">
                        <i class="bi bi-quote"></i>
                    </div>

                    {{-- Message --}}
                    <p class="testimonial-message">
                        {{ $testimonial['message'] }}
                    </p>

                    {{-- Author --}}
                    <div class="testimonial-author">
                        <div class="testimonial-avatar" style="{{ $avatarStyle }}">
                            {{ $testimonialInitial }}
                        </div>
                        <div class="testimonial-author-info">
                            <div class="testimonial-author-name">{{ $testimonial['name'] }}</div>
                            <div class="testimonial-author-role">{{ $testimonial['role'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- COMING SOON SECTION                           --}}
{{-- ============================================ --}}
<section class="page-section reviews-coming-soon-section">
    <div class="landing-container">
        <div class="reviews-coming-soon-card">
            <div class="reviews-coming-soon-icon">
                <i class="bi bi-people"></i>
            </div>
            <h2 class="reviews-coming-soon-title">
                Cerita pengguna nyata segera hadir
            </h2>
            <p class="reviews-coming-soon-text">
                LINGKUP saat ini dalam tahap pengembangan akhir. Setelah peluncuran publik,
                testimoni dari pengguna asli akan ditampilkan di halaman ini sebagai bukti
                dampak nyata platform.
            </p>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- FINAL CTA                                     --}}
{{-- ============================================ --}}
<section class="landing-cta-section">
    <div class="landing-container">
        <div class="landing-cta-card">
            <div class="landing-cta-shapes" aria-hidden="true">
                <div class="cta-shape cta-shape-1"></div>
                <div class="cta-shape cta-shape-2"></div>
            </div>

            <div class="landing-cta-content">
                <h2 class="landing-cta-title">Mulai cerita perjalananmu sendiri</h2>
                <p class="landing-cta-subtitle">
                    Buat pathway personal AI-mu dan jadilah bagian dari komunitas LINGKUP.
                </p>
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg landing-cta-button">
                    <i class="bi bi-grid-1x2 me-1"></i>
                    Masuk ke Dashboard
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- FOOTER                                        --}}
{{-- ============================================ --}}
<footer class="landing-footer">
    <div class="landing-container">
        <div class="landing-footer-content">
            <div class="landing-footer-brand">
                <div class="landing-footer-logo">L</div>
                <span>LINGKUP</span>
            </div>
            <p class="landing-footer-tagline">
                © {{ date('Y') }} LINGKUP · Your Global Pathway Starts Here
            </p>
        </div>
    </div>
</footer>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User menu dropdown
    const trigger = document.getElementById('user-menu-trigger');
    const dropdown = document.getElementById('user-menu-dropdown');

    if (trigger && dropdown) {
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = dropdown.classList.toggle('show');
            trigger.setAttribute('aria-expanded', isOpen);
        });
        document.addEventListener('click', function(e) {
            if (!trigger.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                dropdown.classList.remove('show');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Mobile menu
    const mobileToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileMenu.classList.toggle('show');
            mobileToggle.classList.toggle('active');
        });
    }
});
</script>
@endpush