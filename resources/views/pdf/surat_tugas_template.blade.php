<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Tugas - {{ $suratTugas->nomor_surat_tugas_resmi }}</title>
    <style>
        /* CSS yang sudah teruji di preview surat */
        @page { margin: 2.5cm 3cm 2.5cm 3cm; } /* Atur margin untuk PDF */
        body { font-family: 'Times New Roman', Times, serif; }
        /* Header styles */
        .surat-tugas-header table { width: 100%; border: none; }
        .surat-tugas-header .logo-cell { width: 90px; vertical-align: top; }
        .surat-tugas-header img { width: 80px; height: auto; }
        .surat-tugas-header .text-cell { text-align: center; font-size: 10pt; }
        .surat-tugas-header-text h1 { font-size: 14pt; font-weight: bold; margin: 0; line-height: 1.1; text-transform: uppercase; }
        .surat-tugas-header-text h2 { font-size: 12pt; font-weight: bold; margin: 3px 0 0; line-height: 1.1; text-transform: uppercase; }
        .surat-tugas-header-text p { font-size: 9.5pt; margin: 1px 0; line-height: 1.4; }
        hr.header-line { border: 0; border-top: 1px solid #000; margin-top: 5px; margin-bottom: 20px; }

        /* Content styles */
        .surat-tugas-content { font-size: 11pt; line-height: 1.6; }
        .surat-tugas-title-wrapper { text-align: center; margin-bottom: 20px; }
        .surat-tugas-title-inner h3 { font-size: 13pt; font-weight: bold; margin: 0; text-transform: uppercase; text-decoration: underline; }
        .surat-tugas-title-inner .nomor { font-size: 11pt; margin: 2px 0 0; }
        .surat-tugas-content p { margin: 15px 0; text-align: justify; }
        .detail-table { width: 100%; border-collapse: collapse; }
        .detail-table td { padding: 2px 0; vertical-align: top; }
        .detail-label { width: 160px; white-space: nowrap; }
        .detail-separator { width: 10px; }
        .detail-value { }

        /* Footer sections */
        .footer-table { width: 100%; border: none; margin-top: 40px; }
        .footer-table .tembusan-cell { width: 50%; vertical-align: top; font-size: 10pt; }
        .footer-table .signature-cell { width: 50%; vertical-align: top; text-align: left; font-size: 11pt; }
        .tembusan-list { padding-left: 20px; margin: 0; list-style-type: decimal; }
        .tembusan-list li { margin-bottom: 2px; }
        .signature-block { position: relative; }
        .signature-block .paraf-wadir { position: absolute; right: 0; top: -15px; width: 60px; height: 60px; }
        .signature-block .ttd-direktur { position: absolute; bottom: 30px; left: -10px; width: 120px; height: 80px; }
    </style>
</head>
<body>
<div class="document-container">
    <div class="surat-tugas-header">
        <table>
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('img/polban.png') }}" alt="POLBAN Logo" />
                </td>
                <td class="text-cell">
                    <div class="surat-tugas-header-text">
                        <h1>KEMENTERIAN PENDIDIKAN TINGGI, SAINS,<br>DAN TEKNOLOGI</h1>
                        <h2>POLITEKNIK NEGERI BANDUNG</h2>
                        <p>Jalan Gegerkalong Hilir, Desa Ciwaruga, Bandung 40012, Kotak Pos 1234,</p>
                        <p>Telepon: (022) 2013789, Faksimile: (022) 2013889</p>
                        <p>Laman: www.polban.ac.id, Pos Elektronik: polban@polban.ac.id</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <hr class="header-line" />

    <div class="surat-tugas-content">
        <div class="surat-tugas-title-wrapper">
            <div class="surat-tugas-title-inner">
                <h3>SURAT TUGAS</h3>
                <p class="nomor">Nomor: {{ $suratTugas->nomor_surat_tugas_resmi }}</p>
            </div>
        </div>

        <p>Direktur memberi tugas kepada:</p>
        
        @foreach ($suratTugas->detailPelaksanaTugas as $detail)
            @php $personel = $detail->personable; @endphp
            <table class="detail-table" style="margin-bottom: 15px;">
                <tr><td class="detail-label">Nama</td><td class="detail-separator">:</td><td class="detail-value">{{ $personel->nama ?? '-' }}</td></tr>
                @if ($detail->personable_type === \App\Models\Pegawai::class)
                    <tr><td class="detail-label">NIP</td><td class="detail-separator">:</td><td class="detail-value">{{ $personel->nip ?? '-' }}</td></tr>
                    <tr><td class="detail-label">Pangkat/golongan</td><td class="detail-separator">:</td><td class="detail-value">{{ ($personel->pangkat ?? '-') . ' / ' . ($personel->golongan ?? '-') }}</td></tr>
                    <tr><td class="detail-label">Jabatan</td><td class="detail-separator">:</td><td class="detail-value">{{ $personel->jabatan ?? '-' }}</td></tr>
                @else
                    <tr><td class="detail-label">NIM</td><td class="detail-separator">:</td><td class="detail-value">{{ $personel->nim ?? '-' }}</td></tr>
                    <tr><td class="detail-label">Jurusan</td><td class="detail-separator">:</td><td class="detail-value">{{ $personel->jurusan ?? '-' }}</td></tr>
                @endif
            </table>
        @endforeach

        <p>Untuk mengikuti kegiatan <strong>{{ $suratTugas->perihal_tugas }}</strong>, diselenggarakan oleh <strong>{{ $suratTugas->sumber_dana }}</strong> pada:</p>

        <table class="detail-table">
            <tr><td class="detail-label">Hari/tanggal</td><td class="detail-separator">:</td><td class="detail-value">{{ $suratTugas->tanggal_berangkat->translatedFormat('j F Y') }} → {{ $suratTugas->tanggal_kembali->translatedFormat('j F Y') }}</td></tr>
            <tr><td class="detail-label">Tempat</td><td class="detail-separator">:</td><td class="detail-value">{{ $suratTugas->tempat_kegiatan }}<br>{!! nl2br(e($suratTugas->alamat_kegiatan)) !!}</td></tr>
            <tr><td class="detail-label">Kegiatan</td><td class="detail-separator">:</td><td class="detail-value">{{ $suratTugas->ditugaskan_sebagai }}</td></tr>
        </table>

        <p>Surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.</p>

        <table class="footer-table">
            <tr>
                <td class="tembusan-cell">
                    <p>Tembusan:</p>
                    <ol class="tembusan-list">
                        <li>Para Wakil Direktur</li>
                        <li>Ketua Jurusan</li>
                    </ol>
                </td>
                <td class="signature-cell">
                    <p>Bandung, {{ Carbon\Carbon::parse($suratTugas->tanggal_persetujuan_direktur)->translatedFormat('j F Y') }}</p>
                    <p>Direktur,</p>
                    <div class="signature-block">
                        <div class="ttd-direktur">
                            @if(isset($tandaTanganDirekturPath) && $tandaTanganDirekturPath && file_exists(storage_path('app/public/' . $tandaTanganDirekturPath)))
                                <img src="{{ storage_path('app/public/' . $tandaTanganDirekturPath) }}" alt="TTD Direktur">
                            @endif
                        </div>
                        <div class="paraf-wadir">
                            @if($suratTugas->wadirApprover && $suratTugas->wadirApprover->para_file_path && file_exists(storage_path('app/public/' . $suratTugas->wadirApprover->para_file_path)))
                                <img src="{{ storage_path('app/public/' . $suratTugas->wadirApprover->para_file_path) }}" alt="Paraf Wadir">
                            @endif
                        </div>
                    </div>
                    <div style="margin-top: 80px;">
                        <p><strong>Maryani, S.E., M.Si., Ph.D.</strong></p>
                        <p>NIP 196405041990032001</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>