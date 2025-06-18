// public/js/main.js

document.addEventListener('DOMContentLoaded', function() {
    // === Sidebar Toggle Logic ===
    const toggleBtn = document.getElementById('toggle-btn');
    const sidebar = document.getElementById('sidebar');
    const mainWrapper = document.querySelector('.main-wrapper');

    if (toggleBtn && sidebar && mainWrapper) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('sidebar-collapsed');

            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('active');
            }
        });

        if (window.innerWidth <= 768) {
            document.addEventListener('click', function(event) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target) && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            });
        }
    } else {
        console.warn('Sidebar toggle elements or main wrapper not found.');
    }

    // === Logout Confirmation Modal Logic (PERBAIKAN DI SINI) ===
    const confirmLogoutBtn = document.getElementById('confirmLogoutBtn'); // Tombol "Logout" di dalam modal
    if (confirmLogoutBtn) {
        confirmLogoutBtn.addEventListener('click', function() {
            // **Dapatkan form logout menggunakan ID**
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.submit(); // Kirim form logout
            } else {
                console.error('Logout form with ID "logout-form" not found.');
            }
        });
    }
});