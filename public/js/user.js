// public/js/user.js

document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk mengaktifkan show/hide password pada input tertentu
    function setupPasswordToggle(toggleId, inputId, iconId) {
        const toggleBtn = document.getElementById(toggleId);
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);

        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                if (type === 'password') {
                    toggleIcon.classList.remove('bi-eye-slash-fill');
                    toggleIcon.classList.add('bi-eye-fill');
                } else {
                    toggleIcon.classList.remove('bi-eye-fill');
                    toggleIcon.classList.add('bi-eye-slash-fill');
                }
            });
        }
    }

    // Panggil fungsi untuk setiap field password
    setupPasswordToggle('toggleCurrentPassword', 'current_password', 'toggleCurrentPasswordIcon');
    setupPasswordToggle('toggleNewPassword', 'new_password', 'toggleNewPasswordIcon');
    setupPasswordToggle('toggleNewPasswordConfirmation', 'new_password_confirmation', 'toggleNewPasswordConfirmationIcon');
});