@extends('layouts.direktur.layout')

@section('title', 'Review Surat Tugas')
@section('direktur_content')
<div class="container-fluid">
    <h1 class="mb-4">Review Surat Tugas Direktur</h1>

    <div class="card shadow mb-4 p-0">
        {{-- Styling untuk surat tugas, termasuk perbaikan untuk posisi dan ukuran paraf --}}
        <style>
            /* ========================= */
            /*    GLOBAL & LAYOUT        */
            /* ========================================================= */
            .surat-tugas-body { font-family: 'Times New Roman', serif; }
            .document-container {
              width: 100%; max-width: 21cm; min-height: 29.7cm; background-color: white;
              padding: 2.5cm 3cm; box-sizing: border-box; line-height: 1.5; margin: 0 auto; box-shadow: none;
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
            .surat-tugas-detail-row { display: flex !important; margin-bottom: 4px; }
            .surat-tugas-detail-label { flex-basis: 150px !important; min-width: 150px !important; text-align: left; color: black !important; white-space: nowrap; }
            .surat-tugas-detail-separator { flex-basis: 10px !important; text-align: center; color: black !important; }
            .surat-tugas-detail-value { flex-grow: 1; text-align: left; color: black !important; }
            .surat-tugas-detail-value img { max-width: 100px; height: auto; }

            /* Footer sections */
            .surat-tugas-footer-wrapper { display: grid; grid-template-columns: 1fr auto; grid-template-rows: auto auto auto; column-gap: 20px; margin-top: 40px; width: 100%; }
            .surat-tugas-date-block { grid-column: 2; grid-row: 1; text-align: left; }
            .surat-tugas-date-block .date { margin: 0; margin-bottom: 2px; font-size: 11pt; line-height: 1.2; color: black !important; }
            .surat-tugas-date-block .ditandatangani-oleh { margin: 0; font-size: 11pt; line-height: 1.2; color: black !important; }
            .surat-tugas-tembusan-label { grid-column: 1; grid-row: 2; font-size: 10pt; line-height: 1.5; margin-top: 10px; color: black !important; }
            .surat-tugas-tembusan-list { grid-column: 1; grid-row: 3; font-size: 10pt; line-height: 1.5; margin-top: 4px; }
            .surat-tugas-tembusan-list ol { margin: 0; padding-left: 20px; list-style-type: decimal; }
            .surat-tugas-tembusan-list li { margin-bottom: 2px; color: black !important; }

            /* Perbaikan Styling Signature Block & Paraf */
            .surat-tugas-signature-block {
                grid-column: 2; grid-row: 3; align-self: start; font-size: 11pt; line-height: 1.5; text-align: left;
                position: relative; padding-right: 90px;
            }
            .surat-tugas-signature-block p { margin: 0; color: black !important; }
            .surat-tugas-paraf-img-container {
                position: absolute; right: 0; top: -15px; width: 60px; height: 60px; overflow: hidden;
                display: flex; align-items: center; justify-content: center;
            }
            .surat-tugas-paraf-img-container img {
                max-width: 100% !important; max-height: 100% !important; width: auto; height: auto;
                object-fit: contain; border: 0; vertical-align: top;
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

              {{-- Daftar Personel yang Ditugaskan --}}
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
                        @else {{-- Mahasiswa --}}
                            <div class="surat-tugas-detail-row">
                                <div class="surat-tugas-detail-label">NIM</div>
                                <div class="surat-tugas-detail-separator">:</div>
                                <div class="surat-tugas-detail-value">{{ $personel->nim ?? '-' }}</div>
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
                Untuk mengikuti kegiatan <span class="fw-bold">{{ $suratTugas->perihal_tugas }}</span>, diselenggarakan oleh <span class="fw-bold">{{ $suratTugas->sumber_dana }}</span> pada:
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
                  <p class="date">Bandung, {{ $suratTugas->tanggal_paraf_wadir ? $suratTugas->tanggal_paraf_wadir->translatedFormat('j F Y') : Carbon\Carbon::now()->translatedFormat('j F Y') }}</p>
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
                  {{-- Gambar Paraf Digital Wadir --}}
                  @if($suratTugas->wadirApprover && $suratTugas->wadirApprover->para_file_path)
                      <div class="surat-tugas-paraf-img-container">
                          <img src="{{ Storage::url($suratTugas->wadirApprover->para_file_path) }}" alt="Paraf Wadir">
                      </div>
                  @endif
                  {{-- Placeholder Tanda Tangan Direktur --}}
                  <div style="height: 60px;"></div>
                  <p><strong>Maryani, S.E., M.Si., Ph.D.</strong></p>
                  <p>NIP 196405041990032001</p>
                </div>
              </div>
            </div>
        </div>

        {{-- Form Aksi Direktur --}}
        <div class="card-footer bg-light p-4">
            <h4>Keputusan Direktur</h4>
            <form action="{{ route('direktur.process.review.surat_tugas', $suratTugas->surat_tugas_id) }}" method="POST" id="direkturReviewForm">
                @csrf
                <div class="mb-3">
                    <label for="catatan_revisi_direktur" class="form-label">Catatan / Komentar (Jika Perlu Revisi/Ditolak)</label>
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
                    <button type="button" class="btn btn-success" id="btnApproveAndPublish">
                        <i class="fas fa-check-circle"></i> Setujui & Terbitkan
                    </button>
                </div>
            </form>
            <div class="mt-3">
                <a href="{{ route('direktur.persetujuan') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Persetujuan</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnApprove = document.getElementById('btnApproveAndPublish');
    const formReview = document.getElementById('direkturReviewForm');

    if (btnApprove && formReview) {
        btnApprove.addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah perilaku default jika ada

            Swal.fire({
                title: 'Konfirmasi Persetujuan & Penerbitan',
                text: "Apakah Anda yakin ingin menyetujui dan menerbitkan Surat Tugas ini? Tindakan ini akan menghasilkan PDF resmi.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Setujui & Terbitkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let hiddenActionInput = formReview.querySelector('input[name="action"]');
                    if (!hiddenActionInput) {
                        hiddenActionInput = document.createElement('input');
                        hiddenActionInput.type = 'hidden';
                        hiddenActionInput.name = 'action';
                        formReview.appendChild(hiddenActionInput);
                    }
                    hiddenActionInput.value = 'approve';
                    
                    formReview.submit();
                }
            });
        });
    }
});
</script>
@endpush