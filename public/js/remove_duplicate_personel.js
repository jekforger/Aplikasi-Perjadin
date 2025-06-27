document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    if (!form) return;

    form.addEventListener('submit', function (e) {
        const pegawaiMap = new Map();
        const mahasiswaMap = new Map();

        const pegawaiInputs = Array.from(document.querySelectorAll('input[name="pegawai_ids[]"]'));
        const mahasiswaInputs = Array.from(document.querySelectorAll('input[name="mahasiswa_ids[]"]'));

        // Filter Pegawai by unique NIP (value must be NIP!)
        pegawaiInputs.forEach(input => {
            const nip = input.dataset.nip; // make sure you pass data-nip
            if (!pegawaiMap.has(nip)) {
                pegawaiMap.set(nip, input);
            } else {
                input.remove(); // remove duplicate
                console.log('Removed duplicate pegawai with NIP:', nip);
            }
        });

        // Filter Mahasiswa by unique NIM
        mahasiswaInputs.forEach(input => {
            const nim = input.dataset.nim; // make sure you pass data-nim
            if (!mahasiswaMap.has(nim)) {
                mahasiswaMap.set(nim, input);
            } else {
                input.remove();
                console.log('Removed duplicate mahasiswa with NIM:', nim);
            }
        });
    });
});
