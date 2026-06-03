document.addEventListener('DOMContentLoaded', () => {
    // Form Toggle Logic
    const loginForm = document.getElementById('login-form-container');
    const registerForm = document.getElementById('register-form-container');
    const showRegisterBtn = document.getElementById('show-register');
    const showLoginBtn = document.getElementById('show-login');

    if (loginForm && registerForm && showRegisterBtn && showLoginBtn) {
        showRegisterBtn.addEventListener('click', (e) => {
            e.preventDefault();
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            registerForm.classList.add('fade-in');
        });

        showLoginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            registerForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
            loginForm.classList.add('fade-in');
        });
    }

    // Hamburger Menu Logic
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const dropdownMenu = document.getElementById('dropdown-menu');

    if (hamburgerIcon && dropdownMenu) {
        hamburgerIcon.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!dropdownMenu.contains(e.target) && !hamburgerIcon.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }

    // Client-side validation example for Registration
    const regForm = document.getElementById('register-form');
    if (regForm) {
        regForm.addEventListener('submit', (e) => {
            const password = document.getElementById('reg-password').value;
            const confirm = document.getElementById('reg-confirm').value;

            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match!');
            } else if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long.');
            }
        });
    }

    // Delete confirmation
    const deleteForm = document.getElementById('delete-form');
    if (deleteForm) {
        deleteForm.addEventListener('submit', (e) => {
            if (!confirm('Are you absolutely sure you want to delete your account? This cannot be undone.')) {
                e.preventDefault();
            }
        });
    }
});
