<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Error message dari middleware admin (jika user akses /admin/*) --}}
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Greeting --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Halo, {{ $user->name }}!
                    </h3>
                    <p class="text-gray-600">
                        Selamat datang di LINGKUP. Mari mulai perjalanan menuju targetmu.
                    </p>
                </div>
            </div>

            {{-- Info Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Akun</div>
                    <div class="mt-2 text-lg font-semibold text-gray-900">
                        {{ $user->email }}
                    </div>
                    <div class="mt-1 text-sm text-gray-500">
                        Role: <span class="font-medium text-indigo-600">{{ ucfirst($user->role) }}</span>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Status</div>
                    <div class="mt-2 text-lg font-semibold text-gray-900">
                        Sprint 1.5 — Foundation
                    </div>
                    <div class="mt-1 text-sm text-gray-500">
                        Fitur lengkap tersedia di sprint berikutnya
                    </div>
                </div>
            </div>

            {{-- Coming Soon --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Fitur Mendatang</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li>Profile Assessment — lengkapi data akademikmu</li>
                        <li>Target Selection — pilih beasiswa atau program</li>
                        <li>AI Pathway Builder — dapatkan roadmap personal</li>
                        <li>Progress Tracker — pantau perkembanganmu</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>