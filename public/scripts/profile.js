document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const passwordInput = document.getElementById('nouveau_mot_de_passe');
    const confirmPasswordInput = document.getElementById('confirmer_mot_de_passe');
    const errorContainer = document.getElementById('form-error-message');

    form.addEventListener('submit', (event) => {
        const password = passwordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();
        errorContainer.textContent = ''; // Reset message
    });
});