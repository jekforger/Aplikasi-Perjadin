{{-- resources/views/wadir/layout.blade.php --}}
@extends('layouts.main')

@section('sidebar')
    @include('layouts.Wadir.partials.sidebar')
@endsection

@section('content')
    @yield('wadir_content')
@endsection