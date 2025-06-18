document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk dropdown Diusulkan Kepada
    window.setDiusulkan = function(value) {
        document.getElementById('DiusulkanKepada').value = value;
        return false; // Mencegah default behavior
    };

    // Fungsi untuk dropdown Pembiayaan
    window.setPembiayaan = function(value) {
        document.getElementById('SumberDana').value = value;
        return false;
    };

    // Fungsi untuk dropdown Provinsi
    window.setProvinsi = function(value) {
        document.getElementById('Provinsi').value = value;
        return false;
    };

    // Fungsi untuk dropdown Pagu
    window.setPagu = function(value) {
        document.getElementById('Pagu').value = value;
        return false;
    };
});