<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-transparent.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} · {{ config('app.name', 'LINGKUP') }}</title>

    {{-- Fonts (Phase 5.5.A: Inter with all weights for modern typography) --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    {{-- Vite assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="lingkup-app">
        <div class="lingkup-main">

            {{-- Sidebar (varies by role) --}}
            @include('components.sidebar')

            {{-- Backdrop untuk mobile --}}
            <div class="lingkup-backdrop" id="sidebar-backdrop"></div>

            {{-- Content wrapper --}}
            <div class="lingkup-content">

                {{-- Top Navbar --}}
                @include('components.topbar')

                {{-- Page Content --}}
                <main class="lingkup-page">
                    {{-- Flash messages --}}
                    @if (session('error'))
                        <div class="lingkup-alert lingkup-alert-error">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="lingkup-alert" style="background: #FFFBEB; border: 1px solid #FCD34D; color: #92400E;">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ session('warning') }}
                        </div>
                    @endif
                    
                    @if (session('success'))
                        <div class="lingkup-alert lingkup-alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Slot konten --}}
                    @yield('content')
                </main>

                {{-- Footer --}}
                <footer class="lingkup-footer">
                    © {{ date('Y') }} LINGKUP · Your Global Pathway Starts Here
                </footer>
            </div>

        </div>
    </div>

    {{-- Toggle sidebar mobile --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebar-toggle');
            const sidebar = document.querySelector('.lingkup-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');

            if (toggleBtn && sidebar && backdrop) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                    backdrop.classList.toggle('show');
                });

                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    backdrop.classList.remove('show');
                });
            }
        });
    </script>

    {{-- Tempat injeksi script spesifik dari halaman anak (seperti asset JS generator) --}}
    @stack('scripts')
</body>
</html>