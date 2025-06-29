{{-- resources/views/layouts/Wadir/reviewSuratTugas.blade.php --}}
@extends('layouts.Wadir.layout')

@section('title', 'Review Surat Tugas')
@section('wadir_content')

@php
use Illuminate\Support\Facades\Storage;
@endphp
<div class="container-fluid">
    <h1 class="mb-4">Review Surat Tugas</h1>

    <div class="card shadow mb-4 p-0">
        {{-- Styling untuk surat tugas di sini --}}
        <style>
            /* ========================= */
            /*    GLOBAL & LAYOUT        */
            /* ========================================================= */
            .surat-tugas-body { font-family: 'Times New Roman', serif; }
            .document-container {
              width: 100%; max-width: 21cm; min-height: 29.7cm; background-color: white;
              padding-top: 2.5cm; padding-right: 3cm; padding-bottom: 2.5cm; padding-left: 3cm;
              box-sizing: border-box; line-height: 1.5; margin: 0 auto; box-shadow: none;
            }

            /* Header styles */
            .surat-tugas-header { display: grid; grid-template-columns: auto 1fr; gap: 20px; align-items: flex-start; margin-bottom: 10px; }
            .surat-tugas-header img { width: 80px; height: auto; align-self: flex-start; }
            .surat-tugas-header-text { text-align: center; font-size: 10pt; margin-top: 10px; }
            .surat-tugas-header-text h1 { font-size: 14pt; font-weight: bold; margin: 0; line-height: 1.1; text-transform: uppercase; }
            .surat-tugas-header-text h2 { font-size: 12pt; font-weight: bold; margin: 3px 0 0; line-height: 1.1; text-transform: uppercase; }
            .surat-tugas-header-text p { font-size: 9.5pt; margin: 1px 0; line-height: 1.4; }
            .surat-tugas-header-line { border: 0; border-top: 1px solid #000; margin-top: 0; margin-bottom: 20px; }

            /* Content styles */
            .surat-tugas-content { font-size: 11pt; line-height: 1.6; }
            .surat-tugas-title-wrapper { text-align: center; margin-bottom: 10px; }
            .surat-tugas-title-inner { display: inline-block; text-align: left; }
            .surat-tugas-title-inner h3 { font-size: 13pt; font-weight: bold; margin: 0; line-height: 1.2; text-transform: uppercase; }
            .surat-tugas-title-inner .nomor { font-size: 11pt; margin: 2px 0 0; line-height: 1.2; }
            .surat-tugas-content p { margin-bottom: 10px; text-align: justify; }
            .surat-tugas-detail-row { display: flex !important; margin-bottom: 4px; } /* !important to override Bootstrap defaults */
            .surat-tugas-detail-label { flex-basis: 150px !important; min-width: 150px !important; text-align: left; color: black !important; white-space: nowrap; }
            .surat-tugas-detail-separator { flex-basis: 10px !important; text-align: center; color: black !important; }
            .surat-tugas-detail-value { flex-grow: 1; text-align: left; color: black !important; }
            .surat-tugas-detail-value img { max-width: 100px; height: auto; } /* For images in detail value */

            /* Footer sections */
            .surat-tugas-footer-wrapper { display: grid; grid-template-columns: 1fr auto; grid-template-rows: auto auto auto; column-gap: 20px; margin-top: 40px; width: 100%; }
            .surat-tugas-date-block { grid-column: 2; grid-row: 1; text-align: left; }
            .surat-tugas-date-block .date { margin: 0; margin-bottom: 2px; font-size: 11pt; line-height: 1.2; color: black !important; }
            .surat-tugas-date-block .ditandatangani-oleh { margin: 0; font-size: 11pt; line-height: 1.2; color: black !important; }
            .surat-tugas-tembusan-label { grid-column: 1; grid-row: 2; font-size: 10pt; line-height: 1.5; margin-top: 10px; color: black !important; }
            .surat-tugas-tembusan-list { grid-column: 1; grid-row: 3; font-size: 10pt; line-height: 1.5; margin-top: 4px; }
            .surat-tugas-tembusan-list ol { margin: 0; padding-left: 20px; list-style-type: decimal; }
            .surat-tugas-tembusan-list li { margin-bottom: 2px; color: black !important; }

            /* ======================================================= */
            /* Perbaikan Styling Signature Block & Paraf (Ukuran dan Posisi) */
            /* ======================================================= */
            .surat-tugas-signature-block {
                grid-column: 2;
                grid-row: 3;
                align-self: start;
                font-size: 11pt;
                line-height: 1.5;
                text-align: left;
                position: relative; /* Menjadi posisi relatif untuk menampung elemen absolut */
                padding-right: 90px; /* Beri ruang di kanan untuk nama Direktur */
                /* Hapus height: 60px; dari sini jika ada, biarkan konten yang mengatur tinggi */
            }
            .surat-tugas-signature-block p {
                margin: 0;
                color: black !important;
            }

            .surat-tugas-paraf-img-container {
                position: absolute; /* Posisikan absolut relatif terhadap signature-block */
                right: 0; /* Posisikan di paling kanan */
                top: -15px; /* Geser ke atas agar sedikit di atas nama Direktur. SESUAIKAN JIKA PERLU */
                width: 60px; /* Lebar container paraf */
                height: 60px; /* Tinggi container paraf. SESUAIKAN JIKA PERLU */
                overflow: hidden; /* Penting: Potong gambar jika melebihi container */
                display: flex; /* Gunakan flexbox untuk centering gambar di dalam container */
                align-items: center;
                justify-content: center;
                cursor: pointer; /* Tambahkan cursor pointer untuk interaksi */
                border: 1px dashed transparent; /* Border untuk preview positioning */
                transition: border-color 0.3s ease;
            }
            .surat-tugas-paraf-img-container:hover {
                border-color: #007bff; /* Border biru saat hover */
            }
            .surat-tugas-paraf-img-container.dragging {
                border-color: #28a745; /* Border hijau saat drag */
                z-index: 1000;
            }
            .surat-tugas-paraf-img-container img {
                max-width: 100% !important; /* Paksa gambar mengisi container */
                max-height: 100% !important; /* Paksa gambar mengisi container */
                width: auto;
                height: auto;
                object-fit: contain; /* Memastikan gambar tidak terdistorsi */
                border: 0;
                vertical-align: top;
            }


            .surat-tugas-body a { color: black !important; text-decoration: none; }
        </style>

        <div class="document-container surat-tugas-body">
            <!-- =========== HEADER HALAMAN =========== -->
            <div class="surat-tugas-header">
              <img src="{{ asset('img/polban.png') }}" alt="POLBAN Logo" />
              <div class="surat-tugas-header-text">
                <h1>KEMENTERIAN PENDIDIKAN TINGGI, SAINS,<br>DAN TEKNOLOGI</h1>
                <h2>POLITEKNIK NEGERI BANDUNG</h2>
                <p>Jalan Gegerkalong Hilir, Desa Ciwaruga, Bandung 40012, Kotak Pos 1234,</p>
                <p>Telepon: (022) 2013789, Faksimile: (022) 2013889</p>
                <p>Laman: <a href="https://www.polban.ac.id" target="_blank">www.polban.ac.id</a>,
                   Pos Elektronik: polban@polban.ac.id</p>
              </div>
            </div>
            <hr class="surat-tugas-header-line" />

            <!-- =========== ISI UTAMA HALAMAN =========== -->
            <div class="surat-tugas-content">

              <div class="surat-tugas-title-wrapper">
                <div class="surat-tugas-title-inner">
                  <h3>SURAT TUGAS</h3>
                  <p class="nomor">Nomor: {{ $suratTugas->nomor_surat_usulan_jurusan }}/PL12.C01/KP/{{ $suratTugas->created_at->format('Y') }}</p>
                </div>
              </div>

              <p style="margin-bottom: 10px;">
                Direktur memberi tugas kepada:
              </p>

              {{-- Daftar Personel yang Ditugaskan (loop dari relasi) --}}
              <div id="daftar_personel_surat_tugas" style="margin-bottom: 15px;">
                @forelse ($suratTugas->detailPelaksanaTugas as $detail)
                    @php
                        $personel = $detail->personable;
                        $isPegawai = $detail->personable_type === \App\Models\Pegawai::class;
                    @endphp
                    <div style="margin-bottom: 15px;">
                        <div class="surat-tugas-detail-row">
                            <div class="surat-tugas-detail-label">Nama</div>
                            <div class="surat-tugas-detail-separator">:</div>
                            <div class="surat-tugas-detail-value">{{ $personel->nama ?? '-' }}</div>
                        </div>
                        @if ($isPegawai)
                            <div class="surat-tugas-detail-row">
                                <div class="surat-tugas-detail-label">NIP</div>
                                <div class="surat-tugas-detail-separator">:</div>
                                <div class="surat-tugas-detail-value">{{ $personel->nip ?? '-' }}</div>
                            </div>
                            <div class="surat-tugas-detail-row">
                                <div class="surat-tugas-detail-label">Pangkat/golongan</div>
                                <div class="surat-tugas-detail-separator">:</div>
                                <div class="surat-tugas-detail-value">{{ ($personel->pangkat ?? '-') . ' / ' . ($personel->golongan ?? '-') }}</div>
                            </div>
                            <div class="surat-tugas-detail-row">
                                <div class="surat-tugas-detail-label">Jabatan</div>
                                <div class="surat-tugas-detail-separator">:</div>
                                <div class="surat-tugas-detail-value">{{ $personel->jabatan ?? '-' }}</div>
                            </div>
                        @else
                            <div class="surat-tugas-detail-row">
                                <div class="surat-tugas-detail-label">NIM</div>
                                <div class="surat-tugas-detail-separator">:</div>
                                <div class="surat_tugas-detail-value">{{ $personel->nim ?? '-' }}</div>
                            </div>
                            <div class="surat-tugas-detail-row">
                                <div class="surat-tugas-detail-label">Jurusan</div>
                                <div class="surat_tugas-detail-separator">:</div>
                                <div class="surat-tugas-detail-value">{{ $personel->jurusan ?? '-' }}</div>
                            </div>
                            <div class="surat-tugas-detail-row">
                                <div class="surat_tugas-detail-label">Program Studi</div>
                                <div class="surat_tugas-detail-separator">:</div>
                                <div class="surat-tugas-detail-value">{{ $personel->prodi ?? '-' }}</div>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-muted fst-italic">Tidak ada personel yang ditugaskan.</p>
                @endforelse
              </div>


              <p style="margin-top: 20px; margin-bottom: 10px;">
                Untuk mengikuti kegiatan <span class="fw-bold">{{ $suratTugas->perihal_tugas }}</span>, diselenggarakan oleh <span class="fw-bold">{{ $suratTugas->sumber_dana == 'Penyelenggara' ? $suratTugas->tempat_kegiatan : $suratTugas->sumber_dana }}</span> pada:
              </p>

              <!-- Detail Kegiatan -->
              <div class="surat-tugas-detail-row">
                <div class="surat-tugas-detail-label">Hari/tanggal</div>
                <div class="surat-tugas-detail-separator">:</div>
                <div class="surat-tugas-detail-value">
                    {{ $suratTugas->tanggal_berangkat->translatedFormat('j F Y') }} → {{ $suratTugas->tanggal_kembali->translatedFormat('j F Y') }}
                </div>
              </div>
              <div class="surat-tugas-detail-row">
                <div class="surat-tugas-detail-label">Tempat</div>
                <div class="surat-tugas-detail-separator">:</div>
                <div class="surat-tugas-detail-value">
                  {{ $suratTugas->tempat_kegiatan }}
                  <div style="margin-left: 0;">{!! nl2br(e($suratTugas->alamat_kegiatan)) !!}</div>
                </div>
              </div>
              <div class="surat-tugas-detail-row">
                <div class="surat-tugas-detail-label">Kegiatan</div>
                <div class="surat-tugas-detail-separator">:</div>
                <div class="surat-tugas-detail-value">{{ $suratTugas->ditugaskan_sebagai }}</div>
              </div>

              {{-- Jika ada surat undangan --}}
              @if ($suratTugas->path_file_surat_usulan)
                <p style="margin-top: 20px;">
                    <a href="{{ Storage::url($suratTugas->path_file_surat_usulan) }}" target="_blank" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-file-alt"></i> Unduh Surat Undangan
                    </a>
                </p>
              @endif


              <p style="margin-top: 20px;">
                Surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.
              </p>

              <!-- =========== FOOTER‐GRID (Tembusan & Signature) =========== -->
              <div class="surat-tugas-footer-wrapper">
                <div class="surat-tugas-date-block">
                  <p class="date">{{ Carbon\Carbon::now()->translatedFormat('j F Y') }}</p>
                  <p class="ditandatangani-oleh">Direktur,</p>
                </div>
                <div class="surat-tugas-tembusan-label">
                  <p>Tembusan:</p>
                </div>
                <div class="surat-tugas-tembusan-list">
                  <ol>
                    <li>Para Wakil Direktur</li>
                    <li>Ketua Jurusan</li>
                  </ol>
                </div>
                <div class="surat-tugas-signature-block">
                  {{-- Kontainer baru untuk gambar paraf, diposisikan secara absolut --}}
                  @if($suratTugas->wadir_signature_data)
                      @php
                          $position = $suratTugas->wadir_signature_position ?? ['x' => 0, 'y' => -15, 'width' => 60, 'height' => 60];
                      @endphp
                      <div class="surat-tugas-paraf-img-container" style="
                          left: {{ $position['x'] }}px; 
                          top: {{ $position['y'] }}px; 
                          width: {{ $position['width'] }}px; 
                          height: {{ $position['height'] }}px;
                      ">
                          <img src="{{ $suratTugas->wadir_signature_data }}" alt="Tanda Tangan Wadir">
                      </div>
                  @elseif($suratTugas->wadirApprover && $suratTugas->wadirApprover->para_file_path)
                      <div class="surat-tugas-paraf-img-container">
                          <img src="{{ Storage::url($suratTugas->wadirApprover->para_file_path) }}" alt="Paraf Wadir">
                      </div>
                  @endif
                  <p>Maryani, S.E., M.Si., Ph.D.</p>
                  <p>NIP 196405041990032001</p>
                </div>
              </div>
            </div>
        </div>

        {{-- Form Aksi Wadir --}}
        <div class="card-footer bg-light p-4">
            <h4>Catatan / Komentar (Jika Perlu Revisi/Ditolak)</h4>
            <form action="{{ route('wadir.process.review.surat_tugas', $suratTugas->surat_tugas_id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <textarea name="catatan_revisi" class="form-control @error('catatan_revisi') is-invalid @enderror" rows="3" placeholder="Masukkan catatan atau alasan penolakan/revisi...">{{ old('catatan_revisi', $suratTugas->catatan_revisi) }}</textarea>
                    @error('catatan_revisi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" name="action" value="revert" class="btn btn-warning">
                        <i class="fas fa-redo"></i> Kembalikan untuk Revisi
                    </button>
                    <button type="submit" name="action" value="reject" class="btn btn-danger">
                        <i class="fas fa-times-circle"></i> Tolak
                    </button>
                    <button type="button" class="btn btn-success" id="btnSetujui"> {{-- Ubah type ke button --}}
                        <i class="fas fa-check-circle"></i> Setujui
                    </button>
                </div>
            </form>
            <div class="mt-3">
                <a href="{{ route('wadir.dashboard') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI SUMBER DANA --}}
<div class="modal fade" id="modalKonfirmasiSumberDana" tabindex="-1" aria-labelledby="modalKonfirmasiSumberDanaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalKonfirmasiSumberDanaLabel">Konfirmasi Persetujuan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah sumber dana sudah sesuai?</p>
        <p class="text-muted small">Sumber Dana saat ini: <strong>{{ $suratTugas->sumber_dana }}</strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btnEditSumberDana">Edit Sumber Dana</button>
        <button type="button" class="btn btn-success" id="btnKonfirmasiSetuju">Ya</button>
      </div>
    </div>
  </div>
</div>

{{-- MODAL EDIT SUMBER DANA --}}
<div class="modal fade" id="modalEditSumberDana" tabindex="-1" aria-labelledby="modalEditSumberDanaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditSumberDanaLabel">Pilih Sumber Dana</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="sumber_dana_baru" id="radioRM" value="RM" {{ $suratTugas->sumber_dana == 'RM' ? 'checked' : '' }}>
          <label class="form-check-label" for="radioRM">
            RM
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="sumber_dana_baru" id="radioPNBP" value="PNBP" {{ $suratTugas->sumber_dana == 'PNBP' ? 'checked' : '' }}>
          <label class="form-check-label" for="radioPNBP">
            PNBP
          </label>
        </div>
        {{-- Anda bisa tambahkan opsi lain jika ada, contoh: 'Polban', 'Penyelenggara', 'Polban dan Penyelenggara' --}}
        {{-- PENTING: Anda perlu logika untuk memetakan opsi lama ke RM/PNBP jika diperlukan --}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
        <button type="button" class="btn btn-success" id="btnSimpanSumberDana">Paraf</button>
      </div>
    </div>
  </div>
</div>

{{-- MODAL SIGNATURE PAD --}}
<div class="modal fade" id="modalSignaturePad" tabindex="-1" aria-labelledby="modalSignaturePadLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalSignaturePadLabel">Tanda Tangan Digital</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3">
          <p>Silakan buat tanda tangan digital Anda di area di bawah ini:</p>
        </div>
        <div class="signature-container" style="border: 2px solid #ccc; border-radius: 5px; background-color: #fff;">
          <canvas id="signature-pad" width="600" height="300" style="display: block; margin: 0 auto;"></canvas>
        </div>
        <div class="text-center mt-3">
          <button type="button" class="btn btn-outline-danger" id="clearSignature">
            <i class="fas fa-eraser"></i> Hapus
          </button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-success" id="saveSignature">
          <i class="fas fa-check"></i> Lanjut ke Posisi
        </button>
      </div>
    </div>
  </div>
</div>

{{-- MODAL SIGNATURE POSITION --}}
<div class="modal fade" id="modalSignaturePosition" tabindex="-1" aria-labelledby="modalSignaturePositionLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalSignaturePositionLabel">Atur Posisi Tanda Tangan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8">
            <p class="text-muted">Seret tanda tangan ke posisi yang diinginkan pada dokumen:</p>
            <div id="documentPreview" style="
              border: 2px solid #ddd; 
              border-radius: 5px; 
              background: white; 
              position: relative; 
              min-height: 400px; 
              overflow: hidden;
              font-family: 'Times New Roman', serif;
              font-size: 11pt;
              padding: 20px;
            ">
              <!-- Mini preview dokumen -->
              <div style="text-align: center; margin-bottom: 15px;">
                <strong>SURAT TUGAS</strong><br>
                <small>Nomor: {{ $suratTugas->nomor_surat_usulan_jurusan }}/PL12.C01/KP/{{ $suratTugas->created_at->format('Y') }}</small>
              </div>
              
              <div style="margin-bottom: 20px;">
                <strong>Direktur memberi tugas kepada:</strong><br>
                @forelse ($suratTugas->detailPelaksanaTugas->take(2) as $detail)
                  {{ $detail->personable->nama ?? '-' }}<br>
                @empty
                  -
                @endforelse
                @if($suratTugas->detailPelaksanaTugas->count() > 2)
                  <small>... dan {{ $suratTugas->detailPelaksanaTugas->count() - 2 }} lainnya</small>
                @endif
              </div>

              <div style="margin-bottom: 20px;">
                <strong>Kegiatan:</strong> {{ $suratTugas->perihal_tugas }}<br>
                <strong>Tanggal:</strong> {{ $suratTugas->tanggal_berangkat->translatedFormat('j F Y') }} → {{ $suratTugas->tanggal_kembali->translatedFormat('j F Y') }}<br>
                <strong>Tempat:</strong> {{ $suratTugas->tempat_kegiatan }}
              </div>

              <!-- Area tanda tangan -->
              <div style="
                position: absolute; 
                bottom: 40px; 
                right: 40px; 
                text-align: left;
                min-width: 200px;
              ">
                <div style="margin-bottom: 5px;">{{ Carbon\Carbon::now()->translatedFormat('j F Y') }}</div>
                <div style="margin-bottom: 5px;">Direktur,</div>
                <div style="margin-top: 60px;">
                  <div>Maryani, S.E., M.Si., Ph.D.</div>
                  <div>NIP 196405041990032001</div>
                </div>
              </div>

              <!-- Draggable signature -->
              <div id="draggableSignature" style="
                position: absolute;
                top: 300px;
                right: 120px;
                width: 80px;
                height: 60px;
                border: 2px dashed #007bff;
                cursor: move;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(0, 123, 255, 0.1);
                border-radius: 4px;
                z-index: 10;
              ">
                <img id="signaturePreview" style="max-width: 100%; max-height: 100%; object-fit: contain;" alt="Signature Preview">
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <h6>Kontrol Posisi</h6>
            <div class="mb-3">
              <label for="positionX" class="form-label">Posisi X (px)</label>
              <input type="range" class="form-range" id="positionX" min="-200" max="200" value="0">
              <small class="form-text text-muted" id="positionXValue">0px</small>
            </div>
            <div class="mb-3">
              <label for="positionY" class="form-label">Posisi Y (px)</label>
              <input type="range" class="form-range" id="positionY" min="-100" max="100" value="-15">
              <small class="form-text text-muted" id="positionYValue">-15px</small>
            </div>
            <div class="mb-3">
              <label for="signatureWidth" class="form-label">Lebar (px)</label>
              <input type="range" class="form-range" id="signatureWidth" min="40" max="150" value="80">
              <small class="form-text text-muted" id="signatureWidthValue">80px</small>
            </div>
            <div class="mb-3">
              <label for="signatureHeight" class="form-label">Tinggi (px)</label>
              <input type="range" class="form-range" id="signatureHeight" min="30" max="120" value="60">
              <small class="form-text text-muted" id="signatureHeightValue">60px</small>
            </div>
            <div class="mb-3">
              <button type="button" class="btn btn-outline-primary btn-sm" id="resetPosition">
                <i class="fas fa-undo"></i> Reset Posisi
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="backToSignature">
          <i class="fas fa-arrow-left"></i> Kembali ke Tanda Tangan
        </button>
        <button type="button" class="btn btn-success" id="finalSaveSignature">
          <i class="fas fa-check"></i> Simpan & Setujui
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnSetujui = document.getElementById('btnSetujui');
    const modalKonfirmasiSumberDana = new bootstrap.Modal(document.getElementById('modalKonfirmasiSumberDana'));
    const modalEditSumberDana = new bootstrap.Modal(document.getElementById('modalEditSumberDana'));
    const modalSignaturePad = new bootstrap.Modal(document.getElementById('modalSignaturePad'));
    const modalSignaturePosition = new bootstrap.Modal(document.getElementById('modalSignaturePosition'));
    const btnEditSumberDana = document.getElementById('btnEditSumberDana');
    const btnKonfirmasiSetuju = document.getElementById('btnKonfirmasiSetuju');
    const btnSimpanSumberDana = document.getElementById('btnSimpanSumberDana');
    const formProsesReview = document.querySelector('form[action*="process-review"]');

    // Initialize Signature Pad
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgba(255, 255, 255, 0)',
        penColor: 'rgb(0, 0, 0)',
        velocityFilterWeight: 0.7,
        minWidth: 0.5,
        maxWidth: 2.5,
        throttle: 16,
        minDistance: 5
    });

    // Position controls
    let signatureData = '';
    let signaturePosition = { x: 0, y: -15, width: 80, height: 60 };

    // Clear signature
    document.getElementById('clearSignature').addEventListener('click', function() {
        signaturePad.clear();
    });

    // Menampilkan modal konfirmasi saat tombol "Setujui" diklik
    if (btnSetujui) {
        btnSetujui.addEventListener('click', function() {
            modalKonfirmasiSumberDana.show();
        });
    }

    // Menampilkan modal edit sumber dana saat "Edit Sumber Dana" di modal konfirmasi diklik
    if (btnEditSumberDana) {
        btnEditSumberDana.addEventListener('click', function() {
            modalKonfirmasiSumberDana.hide();
            modalEditSumberDana.show();
        });
    }

    // Aksi Setujui tanpa perubahan sumber dana - menampilkan signature pad
    if (btnKonfirmasiSetuju) {
        btnKonfirmasiSetuju.addEventListener('click', function() {
            modalKonfirmasiSumberDana.hide();
            modalSignaturePad.show();
        });
    }

    // Aksi Simpan Sumber Dana (menampilkan signature pad)
    if (btnSimpanSumberDana) {
        btnSimpanSumberDana.addEventListener('click', function() {
            const selectedSumberDana = document.querySelector('input[name="sumber_dana_baru"]:checked');

            if (selectedSumberDana) {
                modalEditSumberDana.hide();
                modalSignaturePad.show();
            } else {
                alert('Mohon pilih sumber dana baru.');
            }
        });
    }

    // Save signature and move to position modal
    document.getElementById('saveSignature').addEventListener('click', function() {
        if (signaturePad.isEmpty()) {
            alert('Mohon buat tanda tangan terlebih dahulu.');
            return;
        }

        signatureData = signaturePad.toDataURL();
        modalSignaturePad.hide();
        modalSignaturePosition.show();
        
        // Set signature preview
        document.getElementById('signaturePreview').src = signatureData;
        updateSignaturePosition();
    });

    // Back to signature modal
    document.getElementById('backToSignature').addEventListener('click', function() {
        modalSignaturePosition.hide();
        modalSignaturePad.show();
    });

    // Position controls
    const positionX = document.getElementById('positionX');
    const positionY = document.getElementById('positionY');
    const signatureWidth = document.getElementById('signatureWidth');
    const signatureHeight = document.getElementById('signatureHeight');
    const draggableSignature = document.getElementById('draggableSignature');

    function updateSignaturePosition() {
        const x = parseInt(positionX.value);
        const y = parseInt(positionY.value);
        const width = parseInt(signatureWidth.value);
        const height = parseInt(signatureHeight.value);

        signaturePosition = { x, y, width, height };

        // Update draggable element
        draggableSignature.style.right = (120 - x) + 'px';
        draggableSignature.style.top = (300 + y) + 'px';
        draggableSignature.style.width = width + 'px';
        draggableSignature.style.height = height + 'px';

        // Update value displays
        document.getElementById('positionXValue').textContent = x + 'px';
        document.getElementById('positionYValue').textContent = y + 'px';
        document.getElementById('signatureWidthValue').textContent = width + 'px';
        document.getElementById('signatureHeightValue').textContent = height + 'px';
    }

    // Event listeners for controls
    positionX.addEventListener('input', updateSignaturePosition);
    positionY.addEventListener('input', updateSignaturePosition);
    signatureWidth.addEventListener('input', updateSignaturePosition);
    signatureHeight.addEventListener('input', updateSignaturePosition);

    // Reset position
    document.getElementById('resetPosition').addEventListener('click', function() {
        positionX.value = 0;
        positionY.value = -15;
        signatureWidth.value = 80;
        signatureHeight.value = 60;
        updateSignaturePosition();
    });

    // Drag functionality
    let isDragging = false;
    let startX, startY, initialRight, initialTop;

    draggableSignature.addEventListener('mousedown', function(e) {
        isDragging = true;
        startX = e.clientX;
        startY = e.clientY;
        
        const rect = draggableSignature.getBoundingClientRect();
        const parentRect = document.getElementById('documentPreview').getBoundingClientRect();
        
        initialRight = parentRect.right - rect.right;
        initialTop = rect.top - parentRect.top;
        
        draggableSignature.style.cursor = 'grabbing';
        e.preventDefault();
    });

    document.addEventListener('mousemove', function(e) {
        if (!isDragging) return;

        const deltaX = startX - e.clientX;
        const deltaY = e.clientY - startY;

        const newRight = initialRight + deltaX;
        const newTop = initialTop + deltaY;

        draggableSignature.style.right = newRight + 'px';
        draggableSignature.style.top = newTop + 'px';

        // Update sliders
        const newX = 120 - newRight;
        const newY = newTop - 300;
        
        positionX.value = Math.max(-200, Math.min(200, newX));
        positionY.value = Math.max(-100, Math.min(100, newY));
        
        updateSignaturePosition();
    });

    document.addEventListener('mouseup', function() {
        if (isDragging) {
            isDragging = false;
            draggableSignature.style.cursor = 'move';
        }
    });

    // Final save
    document.getElementById('finalSaveSignature').addEventListener('click', function() {
        if (!signatureData) {
            alert('Tanda tangan tidak ditemukan.');
            return;
        }

        // Get selected sumber dana if any
        const selectedSumberDana = document.querySelector('input[name="sumber_dana_baru"]:checked');

        // Hide position modal
        modalSignaturePosition.hide();

        // Create hidden inputs
        const hiddenSignatureInput = document.createElement('input');
        hiddenSignatureInput.type = 'hidden';
        hiddenSignatureInput.name = 'signature_data';
        hiddenSignatureInput.value = signatureData;
        formProsesReview.appendChild(hiddenSignatureInput);

        const hiddenPositionInput = document.createElement('input');
        hiddenPositionInput.type = 'hidden';
        hiddenPositionInput.name = 'signature_position';
        hiddenPositionInput.value = JSON.stringify(signaturePosition);
        formProsesReview.appendChild(hiddenPositionInput);

        if (selectedSumberDana) {
            const hiddenSumberDanaInput = document.createElement('input');
            hiddenSumberDanaInput.type = 'hidden';
            hiddenSumberDanaInput.name = 'updated_sumber_dana';
            hiddenSumberDanaInput.value = selectedSumberDana.value;
            formProsesReview.appendChild(hiddenSumberDanaInput);
        }

        // Add action approve
        const hiddenActionInput = document.createElement('input');
        hiddenActionInput.type = 'hidden';
        hiddenActionInput.name = 'action';
        hiddenActionInput.value = 'approve';
        formProsesReview.appendChild(hiddenActionInput);

        // Submit form
        formProsesReview.submit();
    });

    // Resize canvas when modal is shown
    document.getElementById('modalSignaturePad').addEventListener('shown.bs.modal', function() {
        signaturePad.clear();
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear();
        }
        resizeCanvas();
    });

    // Initialize position modal when shown
    document.getElementById('modalSignaturePosition').addEventListener('shown.bs.modal', function() {
        updateSignaturePosition();
    });
});
</script>
@endpush