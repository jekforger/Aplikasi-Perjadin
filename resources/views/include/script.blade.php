<!-- js -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-btn');
            const sidebar = document.getElementById('sidebar');
            
            // Inisialisasi state sidebar
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
            }
            
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                
                // Posisi tombol menyesuaikan dengan state sidebar
                const isCollapsed = sidebar.classList.contains('collapsed');
                this.style.left = isCollapsed ? (window.innerWidth <= 768 ? '15px' : '95px') : '265px';
            });
            
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.add('active');
                    sidebar.style.transform = 'none';
                }
            });

             // === DROPDOWN TO INPUT ===
            const diusulkanInput = document.getElementById('diusulkanKepada');
            const dropdownItems = document.querySelectorAll('.dropdown-item');

            dropdownItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    const value = this.textContent.trim();
                    diusulkanInput.value = value;
                });
            });

            flatpickr("#tanggalPelaksanaan", {
                mode: "range",
                dateFormat: "d-m-Y", // format hasil input
                locale: {
                rangeSeparator: " → "
                }
            });

            document.querySelector('.toggle-btn').addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('active');
            });

            // Fungsi untuk memuat data provinsi
            function loadProvinsi() {
            const dropdownList = document.getElementById('listProvinsi');
            
            // API Kemendagri
            fetch('https://sig.bps.go.id/rest-bridging/getwilayah?level=provinsi')
                .then(response => response.json())
                .then(data => {
                dropdownList.innerHTML = ''; // Kosongkan loading
                
                // Urutkan provinsi berdasarkan nama
                const sortedProvinsi = data.sort((a, b) => a.nama.localeCompare(b.nama));
                
                // Tambahkan ke dropdown
                sortedProvinsi.forEach(prov => {
                    const item = document.createElement('li');
                    item.innerHTML = `
                    <button class="dropdown-item" type="button" onclick="setProvinsi('${prov.nama}')">
                        ${prov.nama}
                    </button>
                    `;
                    dropdownList.appendChild(item);
                });
                })
                .catch(error => {
                console.error('Gagal memuat provinsi:', error);
                dropdownList.innerHTML = `
                    <li><div class="dropdown-item text-center py-2 text-danger">
                    Gagal memuat data. Coba lagi.
                    </div></li>
                `;
                });
            }

            // Fungsi untuk mengisi nilai provinsi
            function setProvinsi(namaProvinsi) {
            document.getElementById('provinsi').value = namaProvinsi;
            }

            // Load provinsi saat halaman dimuat
            document.addEventListener('DOMContentLoaded', loadProvinsi);
        });
</script>

{{-- Script untuk navigasi dan helper --}}
  <script>
    let currentStep = 1;
    const totalSteps = 2; // Sesuaikan dengan jumlah langkah yang Anda miliki

    function showStep(stepNumber) {
      // Sembunyikan semua langkah
      document.querySelectorAll('.form-step').forEach(step => {
        step.classList.remove('form-step-active');
      });
      // Tampilkan langkah yang diminta
      const activeStep = document.getElementById(`step-${stepNumber}`);
      if (activeStep) {
        activeStep.classList.add('form-step-active');
      }
    }

    function nextStep() {
      if (currentStep < totalSteps) {
        // Validasi step saat ini sebelum lanjut (opsional tapi direkomendasikan)
        // if (!validateStep(currentStep)) return; 
        currentStep++;
        showStep(currentStep);
      }
    }

    function prevStep() {
      if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
      }
    }

    // Helper function untuk dropdown "Diusulkan Kepada"
    function setDiusulkan(value) {
      document.getElementById('diusulkanKepada').value = value;
    }

    // Helper function untuk dropdown "Provinsi"
    function setProvinsi(value) {
      document.getElementById('provinsi').value = value;
    }

    // Inisialisasi: tampilkan langkah pertama
    document.addEventListener('DOMContentLoaded', function() {
      showStep(currentStep);

      // Jika Anda menggunakan Flatpickr atau datepicker lain, inisialisasi di sini
      // Contoh untuk tanggalPelaksanaan (membutuhkan library seperti Flatpickr)
      // Pastikan Anda telah menyertakan CSS dan JS Flatpickr di layout Anda
      // if (typeof flatpickr !== 'undefined') {
      //   flatpickr("#tanggalPelaksanaan", {
      //     mode: "range",
      //     dateFormat: "d-m-Y",
      //     altInput: true,
      //     altFormat: "j F Y",
      //     locale: "id", // jika menggunakan lokalisasi Indonesia
      //     onChange: function(selectedDates, dateStr, instance) {
      //       if (selectedDates.length === 2) {
      //         const from = instance.formatDate(selectedDates[0], "j F Y");
      //         const to = instance.formatDate(selectedDates[1], "j F Y");
      //         instance.input.value = `${from} → ${to}`;
      //       } else if (selectedDates.length === 1) {
      //          const from = instance.formatDate(selectedDates[0], "j F Y");
      //          instance.input.value = `${from} → ...`;
      //       }
      //     }
      //   });
      // }
    });

    // Opsional: Fungsi validasi per langkah
    // function validateStep(stepNumber) {
    //   let isValid = true;
    //   const stepElement = document.getElementById(`step-${stepNumber}`);
    //   const inputs = stepElement.querySelectorAll('input[required], textarea[required], select[required]');
      
    //   inputs.forEach(input => {
    //     if (!input.value.trim()) {
    //       // Anda bisa menambahkan feedback error di sini, misal border merah
    //       input.style.borderColor = 'red'; 
    //       isValid = false;
    //     } else {
    //       input.style.borderColor = ''; // Reset border
    //     }
    //     // Untuk radio button
    //     if (input.type === 'radio') {
    //         const radioGroup = document.querySelectorAll(`input[name="${input.name}"]:checked`);
    //         if (radioGroup.length === 0) {
    //             // Tandai grup radio jika tidak ada yang dipilih
    //             // Anda mungkin perlu menambahkan style khusus atau pesan error di dekat grup radio
    //             isValid = false;
    //         }
    //     }
    //   });
      
    //   if (!isValid) {
    //     alert('Mohon lengkapi semua field yang wajib diisi pada langkah ini.');
    //   }
    //   return isValid;
    // }
  </script>