// public/js/login.js

document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const togglePasswordIcon = document.getElementById('togglePasswordIcon');

    if (togglePassword && passwordInput && togglePasswordIcon) { // Pastikan elemen ada
        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the eye icon
            if (type === 'password') {
                togglePasswordIcon.classList.remove('bi-eye-slash-fill');
                togglePasswordIcon.classList.add('bi-eye-fill');
            } else {
                togglePasswordIcon.classList.remove('bi-eye-fill');
                togglePasswordIcon.classList.add('bi-eye-slash-fill');
            }
        });
    } else {
        console.warn('One or more elements for password toggle not found.');
    }
});