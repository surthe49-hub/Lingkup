@extends('layouts.dashboard')

@section('content')
<div class="lingkup-page-header">
    <h1 class="lingkup-page-title">Tambah Testimonial</h1>
    <p class="lingkup-page-subtitle">Tambahkan testimonial baru untuk halaman publik /reviews.</p>
</div>

<form method="POST" action="{{ route('admin.testimonials.store') }}">
    @include('admin.testimonials._form')
</form>
@endsection