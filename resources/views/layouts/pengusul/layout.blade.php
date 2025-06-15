{{-- resources/views/pengusul/layout.blade.php --}}
@extends('layouts.main')

@section('sidebar')
    @include('layouts.pengusul.partials.sidebar')
@endsection

@section('content')
    @yield('pengusul_content')
@endsection