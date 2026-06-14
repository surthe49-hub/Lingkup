@extends('layouts.dashboard')

@section('title', 'Pathway Saya')

@section('content')
    <x-page-header 
        title="Pathway Saya"
        subtitle="Roadmap personal menuju target studi internasional Anda."
    />

    <div class="pathway-container">
        @if (! $hasProfile)
            <x-pathway.empty-prerequisite reason="profile_incomplete" />
        @elseif (! $hasTarget)
            <x-pathway.empty-prerequisite reason="no_target" />
        @else
            <x-pathway.generate-button :target="$target" :profile="$profile" />
        @endif
    </div>
@endsection

@push('scripts')
    @if ($hasProfile && $hasTarget)
        <script src="{{ asset('js/pathway-generate.js') }}"></script>
    @endif
@endpush