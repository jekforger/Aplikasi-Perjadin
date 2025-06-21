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
    initFormSteps();
    initDataTables();
    initPaguToggle();
    initFormNavigation();
    initValidationAlerts();
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
  
  function initFormSteps() {
    window.currentStep = 1;
    window.formSteps = document.querySelectorAll('.form-step');
    showStep(currentStep);
  }
  
  function showStep(step) {
    // Tampilkan form-step sesuai tahap
    document.querySelectorAll('.tab-pane').forEach(el => {
      el.classList.remove('show', 'active');
    });
  
    const selected = document.querySelector(`#step-${step}`);
    if (selected) selected.classList.add('show', 'active');
  
    // Update nav-link aktif
    document.querySelectorAll('.nav-link').forEach(el => {
      el.classList.remove('active');
    });
  
    const nav = document.querySelector(`[data-bs-target="#step-${step}"]`);
    if (nav) nav.classList.add('active');
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
  
  function adjustTablesOnStep2() {
    const section = $('#data-selection-dropdown').text();
    if (section.includes('Pegawai')) {
      $('#data-pegawai-table').show();
      $('#data-mahasiswa-table').hide();
    } else {
      $('#data-mahasiswa-table').show();
      $('#data-pegawai-table').hide();
    }
  
    ['#pegawaiTable', '#mahasiswaTable'].forEach(id => {
      if ($.fn.DataTable.isDataTable(id)) {
        $(id).DataTable().columns.adjust().draw();
      }
    });
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
  
  function initFormNavigation() {
    $('#next-to-personel').click(e => {
      if (!$('#pengusulanForm')[0].reportValidity()) {
        Swal.fire('Error Validasi!', 'Isi data wajib sebelum lanjut.', 'error');
        return;
      }
      currentStep = 2;
      showStep(2);
    });
  
    $('#back').click(() => {
      currentStep = 1;
      showStep(1);
    });
  }
  
  function initValidationAlerts() {
    if (window.laravelErrors && window.laravelErrors.length) {
      let html = '<ul>';
      window.laravelErrors.forEach(e => html += `<li>${e}</li>`);
      html += '</ul>';
      Swal.fire({ icon: 'error', title: 'Error Validasi', html });
      showStep(1);
    }
  }
  