// public/js/app.js

document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggle-btn');
    const sidebar = document.getElementById('sidebar');
    const mainWrapper = document.querySelector('.main-wrapper'); // Ambil referensi ke main-wrapper

    if (toggleBtn && sidebar && mainWrapper) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            // Toggle main-wrapper class for margin adjustment
            mainWrapper.classList.toggle('sidebar-collapsed'); // Add/remove a class to main-wrapper
            // CSS handles margin-left based on .sidebar.collapsed ~ .main-wrapper

            // Handle mobile sidebar activation (offcanvas-like)
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('active');
                // Optional: add overlay for mobile
                // document.body.classList.toggle('sidebar-overlay');
            }
        });

        // Close mobile sidebar if clicking outside when active
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
});