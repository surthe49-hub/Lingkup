<header class="lingkup-topbar">
    {{-- Toggle mobile --}}
    <button class="lingkup-topbar-toggle" id="sidebar-toggle" aria-label="Toggle sidebar">
        <i class="bi bi-list"></i>
    </button>

    {{-- Breadcrumb / Page indicator --}}
    <div class="lingkup-topbar-breadcrumb">
        @yield('breadcrumb', '')
    </div>

    {{-- Actions --}}
    <div class="lingkup-topbar-actions">
        {{-- User dropdown --}}
        <div class="dropdown">
            <a href="#" class="lingkup-topbar-user dropdown-toggle text-decoration-none"
               data-bs-toggle="dropdown" aria-expanded="false">
                <div class="lingkup-topbar-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="d-none d-md-block">
                    <div style="font-size: 0.875rem; font-weight: 600;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 0.75rem; color: var(--lingkup-text-muted);">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person me-2"></i> Profil
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>