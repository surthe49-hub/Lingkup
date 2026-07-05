@extends('layouts.dashboard')

@section('content')
<div class="lingkup-page-header">
    <h1 class="lingkup-page-title">Edit Target Beasiswa</h1>
    <p class="lingkup-page-subtitle">Perbarui detail target: {{ $target->name }}.</p>
</div>

<form method="POST" action="{{ route('admin.targets.update', $target) }}">
    @method('PUT')
    @include('admin.targets._form')
</form>
@endsection