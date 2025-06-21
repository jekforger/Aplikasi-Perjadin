document.addEventListener('DOMContentLoaded', function() {
    // =============================
    // Fungsi untuk dropdown (jika masih dipakai)
    // =============================
    window.setDiusulkan = function(value) {
        const input = document.getElementById('diusulkan_kepada');
        if (input) input.value = value;
        return false;
    };

    window.setPembiayaan = function(value) {
        const input = document.getElementById('SumberDana');
        if (input) input.value = value;
        return false;
    };

    window.setProvinsi = function(value) {
        const input = document.getElementById('provinsi');
        if (input) input.value = value;
        return false;
    };

    window.setPagu = function(value) {
        const input = document.getElementById('Pagu');
        if (input) input.value = value;
        return false;
    };

    // =============================
    // Load Provinsi dari API EMSIFA
    // =============================
    fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
        .then(response => response.json())
        .then(data => {
            const provinsiSelect = document.getElementById('provinsi');
            if (!provinsiSelect) return;

            // Kosongkan dulu
            provinsiSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';

            // Ambil nilai old dari Blade melalui atribut data-old
            const oldProvinsi = provinsiSelect.getAttribute('data-old');

            data.forEach(function (provinsi) {
                const option = document.createElement('option');
                option.value = provinsi.name;
                option.textContent = provinsi.name;

                if (oldProvinsi === provinsi.name) {
                    option.selected = true;
                }

                provinsiSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Gagal memuat daftar provinsi:', error);
        });
});
