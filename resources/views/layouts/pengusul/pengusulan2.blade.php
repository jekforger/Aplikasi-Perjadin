@extends('layouts.pengusul.pagePengusul')

@section
<div class="card-container">
    <h3>Data Pegawai</h3>
    <div class="label">
        <h6>*Centang untuk memilih pegawai yang akan ditugaskan!</h6>
    </div>

    <!-- PILIH DATA -->
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Pilih
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Data Pegawai</a></li>
            <li><a class="dropdown-item" href="#">Data Mahasiswa</a></li>
        </ul>
    </div>
</div>
@endsection