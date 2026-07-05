@extends('layouts.dashboard')

@section('content')
<div class="lingkup-page-header">
    <h1 class="lingkup-page-title">Tambah Target Beasiswa</h1>
    <p class="lingkup-page-subtitle">Isi detail target beasiswa baru.</p>
</div>

<form method="POST" action="{{ route('admin.targets.store') }}">
    @include('admin.targets._form')
</form>
@endsection