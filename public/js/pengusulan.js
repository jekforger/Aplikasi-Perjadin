// ===========================
// PENGUSULAN JS UTAMA
// ===========================

let currentStep = 1;
const formSteps = document.querySelectorAll('.form-step');
let selectedPersonel = [];

// ---------------------------
// Step Navigasi & DataTable
// ---------------------------
function showStep(stepNumber) {
    formSteps.forEach((step, index) => {
        if (index + 1 === stepNumber) {
            step.classList.add('form-step-active');
            step.style.display = '';
        } else {
            step.classList.remove('form-step-active');
            step.style.display = 'none';
        }
    });
    // Table visibility dan redraw
    if (stepNumber === 2) {
        const selectedDropdownText = $('#data-selection-dropdown').text();
        if (selectedDropdownText.includes('Pegawai')) {
            $('#data-pegawai-table').show();
            $('#data-mahasiswa-table').hide();
        } else if (selectedDropdownText.includes('Mahasiswa')) {
            $('#data-mahasiswa-table').show();
            $('#data-pegawai-table').hide();
        } else {
            $('#data-pegawai-table').show();
            $('#data-mahasiswa-table').hide();
            $('#data-selection-dropdown').text('Data Pegawai');
        }
        if ($.fn.DataTable.isDataTable('#pegawaiTable')) $('#pegawaiTable').DataTable().columns.adjust().draw();
        if ($.fn.DataTable.isDataTable('#mahasiswaTable')) $('#mahasiswaTable').DataTable().columns.adjust().draw();
    }
}

// ---------------------------
// Document Ready
// ---------------------------
$(document).ready(function() {
    showStep(1);

    // Inisialisasi DataTable
    if (!$.fn.DataTable.isDataTable('#pegawaiTable')) {
        $('#pegawaiTable').DataTable({ paging: true, searching: true, info: true, pageLength: 5, lengthMenu: [5, 10, 15], order: [[1, 'asc']], columnDefs: [{ orderable: false, targets: 0 }] });
    } else {
        $('#pegawaiTable').DataTable().columns.adjust().draw();
    }
    if (!$.fn.DataTable.isDataTable('#mahasiswaTable')) {
        $('#mahasiswaTable').DataTable({ paging: true, searching: true, info: true, pageLength: 10, lengthMenu: [5, 10, 15], order: [[1, 'asc']], columnDefs: [{ orderable: false, targets: 0 }] });
    } else {
        $('#mahasiswaTable').DataTable().columns.adjust().draw();
    }

    $('#selectedPersonelContainer').hide();

    // Inisialisasi pagu toggle
    $('#pagu_nominal_input_group').toggle($('#pagu_desentralisasi_checkbox').is(':checked'));
    $('#pagu_desentralisasi_checkbox').on('change', function() {
        $('#pagu_nominal_input_group').toggle(this.checked);
        if (!this.checked) {
            $('#pagu_nominal').val('');
        }
    });

    // Pembiayaan radio sync ke hidden value
    $('input[name="pembiayaan_option"]').on('change', function(){
        $('#pembiayaan_value').val($(this).val());
    });

    // Set radio/checkbox dari old value (untuk edit/dari validasi)
    $('input[name="pembiayaan_option"][value="' + ($('input[name="pembiayaan_option"]').data('old') || 'Polban') + '"]').prop('checked', true);
    $('#pagu_desentralisasi_checkbox').prop('checked', $('#pagu_desentralisasi_checkbox').data('old') ? true : false);
    $('#pagu_nominal_input_group').toggle($('#pagu_desentralisasi_checkbox').is(':checked'));

    // Error validation (dari Laravel)
    if (window.laravelErrors && window.laravelErrors.length) {
        let html = '<ul>';
        window.laravelErrors.forEach(e => html += `<li>${e}</li>`);
        html += '</ul>';
        Swal.fire({ icon: 'error', title: 'Error Validasi!', html });
        showStep(1);
    }

    initializeSelectedPersonel();

    // Tombol navigasi antar step
    $('#next-to-personel').on('click', function (e) {
        const form = document.getElementById('pengusulanForm');
        if (!form.reportValidity()) {
            Swal.fire('Error Validasi!', 'Mohon lengkapi semua field yang wajib diisi pada formulir pertama.', 'error');
            return;
        }
        currentStep = 2;
        showStep(currentStep);
    });

    $('#back').on('click', () => {
        currentStep = 1;
        showStep(currentStep);
    });

    // Tombol simpan draft
    $('#save-draft').on('click', function () {
        if (selectedPersonel.length === 0) {
            Swal.fire('Peringatan!','Pilih setidaknya satu personel untuk simpan draft!','warning');
            return;
        }
        const formData = new FormData(document.getElementById('pengusulanForm'));
        selectedPersonel.forEach(p => {
            if (p.type === 'pegawai') formData.append('pegawai_ids[]', p.id);
            else if (p.type === 'mahasiswa') formData.append('mahasiswa_ids[]', p.id);
        });
        formData.append('status_pengajuan', 'draft');

        fetch($("#pengusulanForm").attr('action'), {
            method: 'POST', body: formData,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire('Draft Disimpan!', data.message || 'Draft berhasil disimpan.','success');
            } else {
                Swal.fire('Gagal!', data.message || 'Gagal menyimpan draft.','error');
            }
        }).catch(error => {
            console.error('Fetch Error:', error);
            let errorMessage = 'Terjadi kesalahan jaringan.';
            if (error.errors) {
                errorMessage = '<strong>Kesalahan Validasi:</strong><ul>';
                for (let key in error.errors) {
                    errorMessage += `<li>${error.errors[key].join(', ')}</li>`;
                }
                errorMessage += '</ul>';
            } else if (error.message) {
                errorMessage = error.message;
            }
            Swal.fire('Error!', errorMessage,'error');
        });
    });

    // DataTable: filter per pegawai/mahasiswa
    $(document).on('click', '#data-section .dropdown-item[data-value]', function (e) {
        e.preventDefault();
        const section = $(this).data('value');
        $('#data-selection-dropdown').text($(this).text());

        $('#data-pegawai-table, #data-mahasiswa-table').hide();
        if (section === 'data-pegawai') {
            $('#data-pegawai-table').show();
            if ($.fn.DataTable.isDataTable('#pegawaiTable')) {
                $('#pegawaiTable').DataTable().columns.adjust().draw();
            }
        } else if (section === 'data-mahasiswa') {
            $('#data-mahasiswa-table').show();
            if ($.fn.DataTable.isDataTable('#mahasiswaTable')) {
                $('#mahasiswaTable').DataTable().columns.adjust().draw();
            }
        }
    });

    // Search live pada datatable
    $('#search-input').on('keyup', function () {
        let tableAPI;
        if ($('#data-pegawai-table').is(':visible')) {
            tableAPI = $('#pegawaiTable').DataTable();
        } else if ($('#data-mahasiswa-table').is(':visible')) {
            tableAPI = $('#mahasiswaTable').DataTable();
        } else {
            return;
        }
        tableAPI.search(this.value).draw();
    });

    // Checkbox select all
    $('#select-all-pegawai').on('click', function () {
        const isChecked = this.checked;
        $('input.personel-checkbox[data-type="pegawai"]').each(function() {
            if ($(this).prop('checked') !== isChecked) {
                $(this).prop('checked', isChecked);
                updateSelectedPersonel(this);
            }
        });
    });
    $('#select-all-mahasiswa').on('click', function () {
        const isChecked = this.checked;
        $('input.personel-checkbox[data-type="mahasiswa"]').each(function() {
            if ($(this).prop('checked') !== isChecked) {
                $(this).prop('checked', isChecked);
                updateSelectedPersonel(this);
            }
        });
    });

    // Untuk dropdown dynamic (Diusulkan Kepada, Provinsi, dsb)
    $(document).on('click', '.pilih-option', function (e) {
        e.preventDefault();
        const targetInputId = $(this).data('target');
        const selectedValue = $(this).data('value');
        $('#' + targetInputId).val(selectedValue);

        // Jika provinsi, tutup dropdown setelah pilih (Bootstrap 5)
        if(targetInputId === 'provinsi') {
            const dropdownToggle = $(this).closest('.dropdown').find('[data-bs-toggle="dropdown"]')[0];
            if (dropdownToggle && window.bootstrap) {
                window.bootstrap.Dropdown.getOrCreateInstance(dropdownToggle).hide();
            }
        }
    });

    // ========== Date Picker Tanggal Pelaksanaan ==========
    if (typeof flatpickr !== 'undefined') {
        flatpickr("#tanggal_pelaksanaan", {
            mode: "range",
            dateFormat: "d-m-Y",
            altInput: true,
            altFormat: "d F Y",
            locale: "id"
        });
    }

    // ========== API Provinsi ==========
    loadProvinsiAPI();
});

// ---------------------------
// Personel Selection
// ---------------------------
function initializeSelectedPersonel() {
    selectedPersonel = [];
    $('.personel-checkbox:checked').each(function() {
        const checkbox = this;
        const personelData = {
            id: checkbox.dataset.id, type: checkbox.dataset.type, nama: checkbox.dataset.nama,
            nip: checkbox.dataset.nip, pangkat: checkbox.dataset.pangkat,
            golongan: checkbox.dataset.golongan, jabatan: checkbox.dataset.jabatan,
            nim: checkbox.dataset.nim, jurusan: checkbox.dataset.jurusan, prodi: checkbox.dataset.prodi,
        };
        selectedPersonel.push(personelData);
    });
    renderSelectedPersonel();
}

function updateSelectedPersonel(checkbox) {
    const personelId = checkbox.dataset.id;
    const type = checkbox.dataset.type;
    const personelData = {
        id: personelId, type: type, nama: checkbox.dataset.nama,
        nip: checkbox.dataset.nip, pangkat: checkbox.dataset.pangkat,
        golongan: checkbox.dataset.golongan, jabatan: checkbox.dataset.jabatan,
        nim: checkbox.dataset.nim, jurusan: checkbox.dataset.jurusan, prodi: checkbox.dataset.prodi,
    };
    if (checkbox.checked) {
        if (!selectedPersonel.some(p => p.id === personelId && p.type === type)) {
            selectedPersonel.push(personelData);
        }
    } else {
        selectedPersonel = selectedPersonel.filter(p => !(p.id === personelId && p.type === type));
        if (type === 'pegawai') $('#select-all-pegawai').prop('checked', false);
        else if (type === 'mahasiswa') $('#select-all-mahasiswa').prop('checked', false);
    }
    renderSelectedPersonel();
}

function renderSelectedPersonel() {
    const container = $('#selectedPersonelContainer');
    const listElement = $('#selectedPersonelList');
    listElement.html('');
    if (selectedPersonel.length > 0) {
        container.show();
        selectedPersonel.forEach((personel, index) => {
            let identifier = personel.type === 'pegawai' ? (personel.nip || '-') : (personel.type === 'mahasiswa' ? (personel.nim || '-') : '-');
            let roleOrMajor = personel.type === 'pegawai' ? (personel.jabatan || '-') : (personel.type === 'mahasiswa' ? (personel.jurusan || '-') : '-');
            const rowHtml = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${personel.nama}</td>
                    <td>${identifier}</td>
                    <td>${roleOrMajor}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removePersonel('${personel.id}', '${personel.type}')">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </td>
                </tr>`;
            listElement.append(rowHtml);
        });
    } else {
        container.hide();
    }
}

function removePersonel(personelId, type) {
    const checkbox = $(`input.personel-checkbox[data-id="${personelId}"][data-type="${type}"]`);
    if (checkbox.length) {
        checkbox.prop('checked', false);
        updateSelectedPersonel(checkbox[0]);
    }
}

// ---------------------------
// API Provinsi
// ---------------------------
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