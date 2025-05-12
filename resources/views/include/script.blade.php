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
        });
</script>