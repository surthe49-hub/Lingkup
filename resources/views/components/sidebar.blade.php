@php
    $isAdmin = Auth::user()?->isAdmin() ?? false;
    $sidebarClass = $isAdmin ? 'lingkup-sidebar lingkup-sidebar-admin' : 'lingkup-sidebar';

    // Phase 5.5.B: User card context (for non-admin)
    if (! $isAdmin) {
        $currentUser = Auth::user();
        $userInitial = $currentUser ? strtoupper(substr($currentUser->name, 0, 1)) : '?';
        $activeTargetName = $currentUser?->userTarget?->target?->name;
    }
@endphp

<aside class="{{ $sidebarClass }}">
    {{-- Brand --}}
    <div class="lingkup-sidebar-brand">
        <a href="{{ $isAdmin ? route('admin.dashboard') : route('home') }}"
           class="lingkup-sidebar-brand-link"
           @if (! $isAdmin) title="Kembali ke Home" @endif>
            <div class="lingkup-sidebar-brand-logo">L</div>
            <span>LINGKUP</span>
            @if ($isAdmin)
                <span class="lingkup-sidebar-admin-badge">ADMIN</span>
            @endif
        </a>
    </div>

    @if ($isAdmin)
        {{-- ============================ --}}
        {{-- Admin Menu                   --}}
        {{-- ============================ --}}

        <div class="lingkup-sidebar-label">Management</div>
        <ul class="lingkup-sidebar-nav">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="lingkup-sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}"
                   class="lingkup-sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.targets.index') }}"
                   class="lingkup-sidebar-link {{ request()->routeIs('admin.targets.*') ? 'active' : '' }}">
                    <i class="bi bi-bullseye"></i>
                    <span>Targets</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.feedback.index') }}"
                   class="lingkup-sidebar-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots"></i>
                    <span>Feedback</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.testimonials.index') }}"
                   class="lingkup-sidebar-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-quote"></i>
                    <span>Testimonials</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.page-contents.edit', 'landing') }}"
                   class="lingkup-sidebar-link {{ request()->routeIs('admin.page-contents.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Konten Landing</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.study-destinations.index') }}"
                   class="lingkup-sidebar-link {{ request()->routeIs('admin.study-destinations.*') ? 'active' : '' }}">
                    <i class="bi bi-globe-americas"></i>
                    <span>Negara Tujuan</span>
                </a>
            </li>
        </ul>

        <div class="lingkup-sidebar-label">Akun</div>
        <ul class="lingkup-sidebar-nav">
            <li>
                <form method="POST" action="{{ route('logout') }}" class="lingkup-sidebar-form">
                    @csrf
                    <button type="submit" class="lingkup-sidebar-link lingkup-sidebar-logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>

    @else
        {{-- ============================ --}}
        {{-- Phase 5.5.B: User Card        --}}
        {{-- ============================ --}}
        @if ($currentUser)
            <div class="lingkup-sidebar-user-card">
                <div class="lingkup-sidebar-user-avatar">{{ $userInitial }}</div>
                <div class="lingkup-sidebar-user-info">
                    <div class="lingkup-sidebar-user-name">{{ $currentUser->name }}</div>
                    @if ($activeTargetName)
                        <div class="lingkup-sidebar-user-target">
                            <i class="bi bi-bullseye"></i>
                            <span>{{ \Illuminate\Support\Str::limit($activeTargetName, 22) }}</span>
                        </div>
                    @else
                        <div class="lingkup-sidebar-user-target lingkup-sidebar-user-target-empty">
                            <i class="bi bi-circle"></i>
                            <span>Belum ada target</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- ============================ --}}
        {{-- User Menu                    --}}
        {{-- ============================ --}}

        <div class="lingkup-sidebar-label">Menu Utama</div>
        <ul class="lingkup-sidebar-nav">
            <li>
                <a href="{{ route('dashboard') }}"
                   class="lingkup-sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('profile-assessment.index') }}"
                   class="lingkup-sidebar-link {{ request()->routeIs('profile-assessment.*') ? 'active' : '' }}">
                    <i class="bi bi-mortarboard"></i>
                    <span>Profil Akademik</span>
                </a>
            </li>
            <li>
                <a href="{{ route('target.index') }}"
                   class="lingkup-sidebar-link {{ request()->routeIs('target.*') ? 'active' : '' }}">
                    <i class="bi bi-bullseye"></i>
                    <span>Pilih Target</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.pathway.index') }}"
                   class="lingkup-sidebar-link {{ request()->routeIs('user.pathway.*') ? 'active' : '' }}">
                    <i class="bi bi-map"></i>
                    <span>Pathway Saya</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.progress.index') }}"
                   class="lingkup-sidebar-link {{ request()->routeIs('user.progress.*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i>
                    <span>Progress</span>
                </a>
            </li>
        </ul>

        <div class="lingkup-sidebar-label">Akun</div>
        <ul class="lingkup-sidebar-nav">
            <li>
                <form method="POST" action="{{ route('logout') }}" class="lingkup-sidebar-form">
                    @csrf
                    <button type="submit" class="lingkup-sidebar-link lingkup-sidebar-logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>

    @endif
</aside>