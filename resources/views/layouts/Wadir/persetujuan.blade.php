@extends('layouts.Wadir.layout')

@section('title', 'Persetujuan')
@section('content')
<div class="persetujuan-container px-4 py-3">
    <h1 class="persetujuan-page-title mb-4">Persetujuan</h1>

    <div class="p-4 shadow-sm bg-white rounded">
        <h5 class="mb-3">Detail</h5>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/persetujuan_wadir.css') }}">
@endpush