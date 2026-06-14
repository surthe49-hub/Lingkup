@props(['reason'])

@php
    $config = match ($reason) {
        'profile_incomplete' => [
            'icon' => 'bi-person-circle',
            'title' => 'Lengkapi Profile Assessment',
            'description' => 'Sebelum generate pathway, Anda perlu mengisi data akademik, kemampuan bahasa, skill, dan target karier melalui Profile Assessment. Data ini akan menjadi dasar AI untuk menyusun roadmap personal Anda.',
            'cta' => 'Mulai Profile Assessment',
            'url' => route('profile-assessment.edit'),
        ],
        'no_target' => [
            'icon' => 'bi-flag',
            'title' => 'Pilih Target Studi Anda',
            'description' => 'Pilih satu target beasiswa atau program studi internasional yang ingin Anda kejar. Roadmap akan disusun berdasarkan persyaratan target tersebut.',
            'cta' => 'Lihat Daftar Target',
            'url' => route('target.index'),
        ],
        default => [
            'icon' => 'bi-exclamation-circle',
            'title' => 'Persiapan Diperlukan',
            'description' => 'Anda perlu menyelesaikan beberapa langkah sebelum dapat generate pathway.',
            'cta' => 'Kembali ke Dashboard',
            'url' => route('dashboard'),
        ],
    };
@endphp

<div class="pathway-empty-state">
    <div class="pathway-empty-icon">
        <i class="bi {{ $config['icon'] }}"></i>
    </div>
    <h2 class="pathway-empty-title">{{ $config['title'] }}</h2>
    <p class="pathway-empty-description">{{ $config['description'] }}</p>
    <a href="{{ $config['url'] }}" class="btn btn-primary btn-lg pathway-empty-cta">
        {{ $config['cta'] }}
        <i class="bi bi-arrow-right"></i>
    </a>
</div>