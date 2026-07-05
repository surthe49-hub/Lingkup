<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Welcome' }} · {{ config('app.name', 'LINGKUP') }}</title>

    {{-- Fonts (Phase 5.5.A: Inter with all weights for modern typography) --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    {{-- Vite assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="landing-body">
    {{--
        Sprint 5.6.A: Minimal placeholder layout.
        Phase 5.6.B will add:
        - Landing navbar component
        - Hero section
        - Feature sections
        - Footer
    --}}

    {{-- Page Content Slot --}}
    @yield('content')

    {{-- Page-specific scripts --}}
    @stack('scripts')
</body>
</html>