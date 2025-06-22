document.addEventListener('DOMContentLoaded', function () {
  // --- Inisialisasi Event Delegation untuk Dropdown Dinamis ---
  document.addEventListener('click', function (e) {
      if (e.target.classList.contains('pilih-option')) {
          e.preventDefault();
          const targetInputId = e.target.dataset.target;
          const input = document.getElementById(targetInputId);
          if (input) input.value = e.target.dataset.value;
      }
  });

  // --- Inisialisasi Umum ---
  initDropdowns();
  loadProvinsiAPI();
  initDataTables();
  initPaguToggle();
  initFormNavigation();
  initValidationAlerts();

  // ======== Tambahan: Inisialisasi Date Picker untuk tanggal_pelaksanaan ========
  $('#tanggal_pelaksanaan').daterangepicker({
    autoUpdateInput: false,
    locale: {
        cancelLabel: 'Clear',
        format: 'DD/MM/YYYY'
    }
    })
    .on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' → ' + picker.endDate.format('DD/MM/YYYY'));
    })
    .on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    // ==============================
    // FUNGSI UTAMA
    // ==============================

    function initDropdowns() {
    // Fallback untuk item statis jika ada (tidak wajib kalau sudah pakai event delegation)
    document.querySelectorAll('.pilih-option').forEach(item => {
        item.addEventListener('click', e => {
            e.preventDefault();
            const input = document.getElementById(item.dataset.target);
            if (input) input.value = item.dataset.value;
        });
    });

    // Pembiayaan: Isi hidden value berdasarkan radio
    document.querySelectorAll('input[name="pembiayaan_option"]').forEach(radio => {
        radio.addEventListener('change', () => {
            const hidden = document.getElementById('pembiayaan_value');
            if (hidden) hidden.value = radio.value;
        });
    });
    }

    function loadProvinsiAPI() {
    const dropdownList = document.getElementById('provinsi-dropdown');
    const inputProvinsi = document.getElementById('provinsi');
    if (!dropdownList || !inputProvinsi) return;

    const oldVal = inputProvinsi.dataset.old || '';

    fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
        .then(r => r.json())
        .then(data => {
            dropdownList.innerHTML = ''; // Kosongkan sebelum isi

            data.forEach(p => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <a href="#" class="dropdown-item pilih-option" data-target="provinsi" data-value="${p.name}">
                        ${p.name}
                    </a>`;
                dropdownList.appendChild(li);
            });

            // Set nilai lama jika tersedia
            if (oldVal) inputProvinsi.value = oldVal;
        })
        .catch(console.error);
    }

    function initDataTables() {
    $('.form-step-active').length; // pastikan form ada

    if (!$.fn.DataTable.isDataTable('#pegawaiTable')) {
        $('#pegawaiTable').DataTable({
            paging: true,
            searching: true,
            info: true,
            pageLength: 5,
            lengthMenu: [5, 10, 15],
            order: [[1, 'asc']],
            columnDefs: [{ orderable: false, targets: 0 }]
        });
    }

    if (!$.fn.DataTable.isDataTable('#mahasiswaTable')) {
        $('#mahasiswaTable').DataTable({
            paging: true,
            searching: true,
            info: true,
            pageLength: 5,
            lengthMenu: [5, 10, 15],
            order: [[1, 'asc']],
            columnDefs: [{ orderable: false, targets: 0 }]
        });
    }
    }

    function initPaguToggle() {
    const checkbox = $('#pagu_desentralisasi_checkbox');
    const group = $('#pagu_nominal_input_group');

    group.toggle(checkbox.is(':checked'));

    checkbox.on('change', function () {
        group.toggle(this.checked);
        if (!this.checked) $('#pagu_nominal').val('');
    });
    }

    // ==============================
    // PERBAIKAN: NAVIGASI STEP FORM
    // ==============================
    function initFormNavigation() {
    // Step 1 ke Step 2
    const nextBtn = document.getElementById('next-step-1');
    const step1 = document.getElementById('step-1-form');
    const step2 = document.getElementById('step-2-personnel');
    if (nextBtn && step1 && step2) {
        nextBtn.addEventListener('click', function (e) {
            e.preventDefault();
            // Validasi HTML5
            const form = document.getElementById('pengusulanForm');
            if (form && !form.checkValidity()) {
                form.reportValidity();
                return;
            }
            step1.style.display = 'none';
            step2.style.display = '';
        });
    }

    // Step 2 ke Step 1 (Kembali)
    const prevBtn = document.getElementById('prev-step-2');
    if (prevBtn && step1 && step2) {
        prevBtn.addEventListener('click', function (e) {
            e.preventDefault();
            step2.style.display = 'none';
            step1.style.display = '';
        });
    }
    }

    function initValidationAlerts() {
    if (window.laravelErrors && window.laravelErrors.length) {
        let html = '<ul>';
        window.laravelErrors.forEach(e => html += `<li>${e}</li>`);
        html += '</ul>';
        Swal.fire({ icon: 'error', title: 'Error Validasi', html });
        // Tampilkan step 1 jika error validasi
        document.getElementById('step-1-form').style.display = '';
        document.getElementById('step-2-personnel').style.display = 'none';
        }
    }
});