@extends('layouts.main')

@section('title', 'Home Page')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h1>Selamat Datang di Laravel</h1>
            <p>Ini adalah halaman yang dirancang sesuai desain UI/UX.</p>
            <a href="/about" class="btn btn-primary">Pelajari Lebih Lanjut</a>
        </div>
        <div class="col-md-6">
            <img src="https://via.placeholder.com/400" class="img-fluid" alt="Ilustrasi">
        </div>
    </div>
</div>
@endsection
