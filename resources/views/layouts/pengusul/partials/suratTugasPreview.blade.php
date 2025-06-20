<div class="document-container">

    <!-- =========== HEADER =========== -->
    <div class="header">
      <img src="{{ asset('img/polban.png') }}" alt="POLBAN Logo" /> {{-- Perbaikan path gambar --}}
      <div class="header-text">
        <h1>KEMENTERIAN PENDIDIKAN TINGGI, SAINS,<br>DAN TEKNOLOGI</h1>
        <h2>POLITEKNIK NEGERI BANDUNG</h2>
        <p>Jalan Gegerkalong Hilir, Desa Ciwaruga, Bandung 40012, Kotak Pos 1234,</p>
        <p>Telepon: (022) 2013789, Faksimile: (022) 2013889</p>
        <p>Laman: <a href="https://www.polban.ac.id" target="_blank">www.polban.ac.id</a>,
           Pos Elektronik: polban@polban.ac.id</p>
      </div>
    </div>
    <hr class="header-line" />

    <!-- =========== ISI UTAMA =========== -->
    <div class="content">

      <div class="title-wrapper">
        <div class="title-inner">
          <h3>SURAT TUGAS</h3>
          {{-- Nomor surat akan diisi dinamis oleh JS --}}
          <p class="nomor">Nomor: <span id="nomor_surat_tugas_display"></span></p>
        </div>
      </div>

      <p style="margin-bottom: 10px;">
        Direktur Politeknik Negeri Bandung menugaskan kepada yang nama-namanya tercantum di dalam lampiran pada surat tugas ini:
      </p>

      {{-- Ini adalah tempat daftar personel terpilih akan di-inject oleh JavaScript --}}
      <div class="personel-list" id="preview_personel_list">
        <!-- Daftar personel akan diisi di sini oleh JS -->
      </div>

      <p style="margin-top: 20px; margin-bottom: 10px;">
        Untuk mengikuti kegiatan <span id="nama_kegiatan_display_prev"></span>, diselenggarakan pada:
      </p>

      <!-- Detail: Hari/tanggal, Tempat, Kegiatan -->
      <div class="detail-row">
        <div class="detail-label">Hari/tanggal</div>
        <div class="detail-separator">:</div>
        <div class="detail-value" id="tanggal_pelaksanaan_display_prev"></div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Tempat</div>
        <div class="detail-separator">:</div>
        <div class="detail-value" id="tempat_kegiatan_display_prev"></div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Alamat Kegiatan</div> {{-- Perbaiki label --}}
        <div class="detail-separator">:</div>
        <div class="detail-value" id="alamat_kegiatan_display_prev"></div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Ditugaskan Sebagai</div> {{-- Sesuaikan label --}}
        <div class="detail-separator">:</div>
        <div class="detail-value" id="ditugaskan_sebagai_display_prev"></div>
      </div>
      {{-- Tanggal berlaku bisa sama dengan tanggal pelaksanaan, atau dipisah --}}
      <div class="detail-row">
        <div class="detail-label">Tanggal Berlaku</div>
        <div class="detail-separator">:</div>
        <div class="detail-value" id="tanggal_berlaku_display_prev"></div>
      </div>


      <p style="margin-top: 20px;">
        Surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.
      </p>

      <!-- =========== FOOTER‐GRID (Tembusan & Signature) =========== -->
      <div class="footer-wrapper">

        <!-- Baris 1 Kolom 2: Tanggal Surat -->
        <div class="date-block">
          <p class="date">Bandung, <span id="tanggal_surat_formatted_display"></span></p>
          <p class="ditandatangani-oleh">Direktur,</p>
        </div>

        <!-- Baris 2 Kolom 1: Label “Tembusan:” -->
        <div class="tembusan-label">
          <p>Tembusan:</p>
        </div>

        <!-- Baris 3 Kolom 1: Daftar Tembusan (HARDCODED sesuai permintaan) -->
        <div class="tembusan-list">
          <ol>
            <li>Para Wakil Direktur</li>
            <li>Ketua Jurusan</li>
          </ol>
        </div>

        <!-- Baris 3 Kolom 2: Signature‐Block (HARDCODED sesuai permintaan) -->
        <div class="signature-block">
          <p>Maryani, S.E., M.Si., Ph.D.</p>
          <p>NIP 196405041990032001</p>
        </div>

      </div>
      <!-- =========== AKHIR FOOTER‐GRID =========== -->

    </div>
    <!-- =========== AKHIR ISI UTAMA =========== -->

  </div>