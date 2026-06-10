@php
    $isAdmin = Auth::user()?->isAdmin() ?? false;
    $sidebarClass = $isAdmin ? 'lingkup-sidebar lingkup-sidebar-admin' : 'lingkup-sidebar';
@endphp

<aside class="{{ $sidebarClass }}">
    {{-- Brand --}}
    <div class="lingkup-sidebar-brand">
        <a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}" class="lingkup-sidebar-brand-link">
            <div class="lingkup-sidebar-brand-logo">L</div>
            <span>LINGKUP</span>
            @if ($isAdmin)
                <span class="lingkup-sidebar-admin-badge">ADMIN</span>
            @endif
        </a>
    </div>

    @if ($isAdmin)
        {{-- ============================ --}}
        {{-- Admin Menu                    --}}
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
                {{-- Disable link sampai sprint berikutnya --}}
                <a href="#" class="lingkup-sidebar-link" style="opacity: 0.5; cursor: not-allowed;">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
            </li>
            <li>
                <a href="#" class="lingkup-sidebar-link" style="opacity: 0.5; cursor: not-allowed;">
                    <i class="bi bi-bullseye"></i>
                    <span>Targets</span>
                </a>
            </li>
            <li>
                <a href="#" class="lingkup-sidebar-link" style="opacity: 0.5; cursor: not-allowed;">
                    <i class="bi bi-chat-dots"></i>
                    <span>Feedback</span>
                </a>
            </li>
        </ul>

        <div class="lingkup-sidebar-label">Akun</div>
        <ul class="lingkup-sidebar-nav">
            <li>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="lingkup-sidebar-link" style="width: 100%; background: none; border: none; text-align: left;">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>

    @else
        {{-- ============================ --}}
        {{-- User Menu                     --}}
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
                <a href="#" class="lingkup-sidebar-link" style="opacity: 0.5; cursor: not-allowed;">
                    <i class="bi bi-bullseye"></i>
                    <span>Pilih Target</span>
                </a>
            </li>
            <li>
                <a href="#" class="lingkup-sidebar-link" style="opacity: 0.5; cursor: not-allowed;">
                    <i class="bi bi-map"></i>
                    <span>Pathway Saya</span>
                </a>
            </li>
            <li>
                <a href="#" class="lingkup-sidebar-link" style="opacity: 0.5; cursor: not-allowed;">
                    <i class="bi bi-graph-up"></i>
                    <span>Progress</span>
                </a>
            </li>
        </ul>

        <div class="lingkup-sidebar-label">Akun</div>
        <ul class="lingkup-sidebar-nav">
            <li>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="lingkup-sidebar-link" style="width: 100%; background: none; border: none; text-align: left;">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>

    @endif
</aside>