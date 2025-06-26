@extends('layouts.bku.layout')

@section('title', 'Bukti')
@section('bku_content')
<div class="bku-container px-4 py-3">
    <h1 class="bku-page-title mb-4">Bukti</h1>

    <div class="p-4 shadow-sm bg-white rounded">
        <h5 class="mb-3">Detail</h5>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/bku_content.css') }}">
@endpush