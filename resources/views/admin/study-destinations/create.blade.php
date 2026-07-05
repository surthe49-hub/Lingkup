@extends('layouts.dashboard')

@section('content')
<div class="lingkup-page-header">
    <h1 class="lingkup-page-title">Tambah Negara Tujuan</h1>
    <p class="lingkup-page-subtitle">Tambahkan negara baru untuk section "Negara Impianmu" di halaman Home.</p>
</div>

<form method="POST" action="{{ route('admin.study-destinations.store') }}" enctype="multipart/form-data">
    @include('admin.study-destinations._form')
</form>
@endsection