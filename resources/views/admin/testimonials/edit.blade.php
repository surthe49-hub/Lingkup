@extends('layouts.dashboard')

@section('content')
<div class="lingkup-page-header">
    <h1 class="lingkup-page-title">Edit Testimonial</h1>
    <p class="lingkup-page-subtitle">Perbarui testimonial: {{ $testimonial->name }}.</p>
</div>

<form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}">
    @method('PUT')
    @include('admin.testimonials._form')
</form>
@endsection