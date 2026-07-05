@extends('layouts.dashboard')

@section('content')
<div class="lingkup-page-header">
    <h1 class="lingkup-page-title">Edit Negara Tujuan</h1>
    <p class="lingkup-page-subtitle">Perbarui data negara: {{ $destination->name }}.</p>
</div>

<form method="POST" action="{{ route('admin.study-destinations.update', $destination) }}" enctype="multipart/form-data">
    @method('PUT')
    @include('admin.study-destinations._form')
</form>
@endsection