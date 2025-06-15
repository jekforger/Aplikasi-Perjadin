<!-- js -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggle-btn');
        const sidebar = document.getElementById('sidebar');

        // Pastikan elemen ada sebelum menambahkan event listener
        if (toggleBtn && sidebar) {
            // Inisialisasi state sidebar untuk mobile jika perlu
            if (window.innerWidth <= 768) {
                // Jangan collapsed otomatis, biarkan terbuka atau tentukan default
                // sidebar.classList.add('collapsed'); // Jika ingin default collapsed di mobile
            }

            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');

                // Toggle class di body atau main-wrapper untuk penyesuaian margin konten
                document.body.classList.toggle('sidebar-collapsed'); // Ini perlu CSS tambahan di main.css atau pengusul.css

                // Handle mobile sidebar activation (offcanvas-like)
                if (window.innerWidth <= 768) {
                    sidebar.classList.toggle('active'); // Untuk slide-in/out di mobile
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
            console.warn('Sidebar toggle elements not found (global script).');
        }
    });
</script>