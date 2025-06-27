// Initial setup for form steps
let currentStep = 1;
const formSteps = document.querySelectorAll('.form-step');

function showStep(stepNumber) {
    formSteps.forEach((step, index) => {
        if (index + 1 === stepNumber) {
            step.classList.add('form-step-active');
        } else {
            step.classList.remove('form-step-active');
        }
    });
    // Ensure correct table is shown and DataTables redraws if needed
    if (stepNumber === 2) {
        // Restore correct table visibility
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

$(document).ready(function() {
    // Initial setup for form steps
    let currentStep = 1;
    const formSteps = document.querySelectorAll('.form-step');

    function showStep(stepNumber) {
        formSteps.forEach((step, index) => {
            if (index + 1 === stepNumber) {
                step.classList.add('form-step-active');
            } else {
                step.classList.remove('form-step-active');
            }
        });
        // Ensure correct table is shown and DataTables redraws if needed
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

    showStep(1);

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

    // PERBAIKAN: Use global variable injected from Blade
    $('input[name="pembiayaan_option"][value="' + window.pembiayaan + '"]').prop('checked', true);
    $('#pagu_desentralisasi_checkbox').prop('checked', window.paguDesentralisasi === 'true');

    $('#pagu_nominal_input_group').toggle($('#pagu_desentralisasi_checkbox').is(':checked'));

    initializeSelectedPersonel();

    let selectedPersonel = [];

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

    window.updateSelectedPersonel = updateSelectedPersonel;

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

    window.removePersonel = function(personelId, type) {
        const checkbox = $(`input.personel-checkbox[data-id="${personelId}"][data-type="${type}"]`);
        if (checkbox.length) {
            checkbox.prop('checked', false);
            updateSelectedPersonel(checkbox[0]);
        }
    }

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

        fetch("{{ route('pengusul.store.pengusulan') }}", {
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
                Swal.fire('Draft Disimpan!', data.message || 'Draft berhasil disimpan.','success')
                .then(() => { /* No reload for draft, stay on page, maybe clear selection */ });
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

    $('#create-task').on('click', function () {
        if (selectedPersonel.length === 0) {
            Swal.fire('Peringatan!', 'Pilih setidaknya satu personel!', 'warning'); return;
        }
        Swal.fire({
            title: 'Konfirmasi', text: 'Yakin untuk membuat surat tugas?', icon: 'question',
            showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                currentStep = 3;
                showStep(currentStep);
                const form = $('#pengusulanForm');

                $('#nomor_surat_display').text(form.find('#nomor_surat_usulan').val() ? form.find('#nomor_surat_usulan').val() + '/PL12.C01/KP/' + new Date().getFullYear() : "___/PL12.C01/KP/" + new Date().getFullYear());
                $('#nama_kegiatan_display_text').text(form.find('#nama_kegiatan').val() || '-');
                let tempatKegiatanVal = form.find('#tempat_kegiatan').val();
                $('#penyelenggara_display').text(tempatKegiatanVal.split(',')[0] || 'Penyelenggara Kegiatan');
                $('#tanggal_pelaksanaan_display').text(form.find('#tanggal_pelaksanaan').val() || '-');
                $('#tempat_kegiatan_display').text(tempatKegiatanVal || '-');
                $('#alamat_kegiatan_display_detail').html((form.find('#alamat_kegiatan').val() || '-').replace(/\n/g, '<br>'));
                $('#namaPenyelenggara_display').text(form.find('#namaPenyelenggara').val() || '-');
                const today = new Date();
                $('#tanggal_surat_display').text(`Bandung, ${String(today.getDate()).padStart(2, '0')} ${["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"][today.getMonth()]} ${today.getFullYear()}`);

                const daftarPersonelContainer = $('#daftar_personel_surat_tugas');
                daftarPersonelContainer.html('');
                if (selectedPersonel.length > 0) {
                    let personelHtml = '';
                    selectedPersonel.forEach((personel) => {
                        personelHtml += `
                            <div style="margin-bottom: 15px;">
                                <div class="surat-tugas-detail-row">
                                    <div class="surat-tugas-detail-label">Nama</div>
                                    <div class="surat-tugas-detail-separator">:</div>
                                    <div class="surat-tugas-detail-value">${personel.nama || '-'}</div>
                                </div>`;
                        if (personel.type === 'pegawai') {
                            personelHtml += `
                                <div class="surat-tugas-detail-row">
                                    <div class="surat-tugas-detail-label">NIP</div>
                                    <div class="surat-tugas-detail-separator">:</div>
                                    <div class="surat-tugas-detail-value">${personel.nip || '-'}</div>
                                </div>
                                <div class="surat-tugas-detail-row">
                                    <div class="surat-tugas-detail-label">Pangkat/golongan</div>
                                    <div class="surat-tugas-detail-separator">:</div>
                                    <div class="surat-tugas-detail-value">${personel.pangkat || '-'} / ${personel.golongan || '-'}</div>
                                </div>
                                <div class="surat-tugas-detail-row">
                                    <div class="surat-tugas-detail-label">Jabatan</div>
                                    <div class="surat-tugas-detail-separator">:</div>
                                    <div class="surat-tugas-detail-value">${personel.jabatan || '-'}</div>
                                </div>`;
                        } else if (personel.type === 'mahasiswa') {
                            personelHtml += `
                                <div class="surat-tugas-detail-row">
                                    <div class="surat-tugas-detail-label">NIM</div>
                                    <div class="surat-tugas-detail-separator">:</div>
                                    <div class="surat-tugas-detail-value">${personel.nim || '-'}</div>
                                </div>
                                <div class="surat-tugas-detail-row">
                                    <div class="surat-tugas-detail-label">Jurusan</div>
                                    <div class="surat-tugas-detail-separator">:</div>
                                    <div class="surat-tugas-detail-value">${personel.jurusan || '-'}</div>
                                </div>
                                <div class="surat-tugas-detail-row">
                                    <div class="surat-tugas-detail-label">Program Studi</div>
                                    <div class="surat-tugas-detail-separator">:</div>
                                    <div class="surat-tugas-detail-value">${personel.prodi || '-'}</div>
                                </div>`;
                        }
                        personelHtml += `</div>`;
                    });
                    daftarPersonelContainer.append(personelHtml);
                } else {
                    daftarPersonelContainer.html('<p class="text-muted fst-italic">(Tidak ada personel yang dipilih)</p>');
                }
            }
        });
    });

    $('#back-to-form').on('click', () => { currentStep = 2; showStep(currentStep); });

    $('#submit-surat').on('click', function () {
        Swal.fire({
            title: 'Konfirmasi Pengusulan', text: 'Data sudah benar?', icon: 'question',
            showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData(document.getElementById('pengusulanForm'));
                selectedPersonel.forEach(p => {
                    if (p.type === 'pegawai') formData.append('pegawai_ids[]', p.id);
                    else if (p.type === 'mahasiswa') formData.append('mahasiswa_ids[]', p.id);
                });
                formData.append('status_pengajuan', 'diajukan');

                fetch("{{ route('pengusul.store.pengusulan') }}", {
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
                        Swal.fire('Berhasil!', data.message || 'Surat tugas berhasil diusulkan!','success')
                        .then(() => window.location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message || 'Gagal mengusulkan.','error');
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
            }
        });
    });

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

    $('.pilih-option').on('click', function () {
        const targetInputId = $(this).data('target');
        const selectedValue = $(this).data('value');
        $('#' + targetInputId).val(selectedValue);
    });

    $('input[name="pembiayaan_option"]').on('change', function(){
        $('#pembiayaan_value').val($(this).val());
    });

    $('#pagu_desentralisasi_checkbox').on('change', function() {
        $('#pagu_nominal_input_group').toggle(this.checked);
        if (!this.checked) {
            $('#pagu_nominal').val('');
        }
    });

    // Inisialisasi daterangepicker pada input tanggal_pelaksanaan
    $('#tanggal_pelaksanaan').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY',
            cancelLabel: 'Clear'
        }
    });

    // Ketika tanggal dipilih, isi input dengan format custom
    $('#tanggal_pelaksanaan').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(
            picker.startDate.format('DD/MM/YYYY') + ' → ' + picker.endDate.format('DD/MM/YYYY')
        );
    });

    // Ketika tombol clear dipilih, kosongkan input
    $('#tanggal_pelaksanaan').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    // Jika ada value lama (old value), set nilai picker dan input
    var val = $('#tanggal_pelaksanaan').val();
    if (val && val.includes('→')) {
        var parts = val.split('→').map(function(part) { return part.trim(); });
        var picker = $('#tanggal_pelaksanaan').data('daterangepicker');
        if (picker && parts.length === 2) {
            picker.setStartDate(moment(parts[0], 'DD/MM/YYYY'));
            picker.setEndDate(moment(parts[1], 'DD/MM/YYYY'));
            $('#tanggal_pelaksanaan').val(parts[0] + ' → ' + parts[1]);
        }
    }
});