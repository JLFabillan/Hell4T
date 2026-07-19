/**
 * forgot_password.js – Helport Forgot Password Page Logic
 */

(function () {
    'use strict';

    const form       = document.getElementById('fpForm');
    const emailInput = document.getElementById('fpEmail');
    const emailError = document.getElementById('fpEmailError');
    const fpBtn      = document.getElementById('fpBtn');

    /* ── Helpers ─────────────────────────────────────────────── */
    function showError(el, msg, input) {
        el.textContent = msg;
        el.classList.add('visible');
        input.classList.add('input-error');
        input.setAttribute('aria-invalid', 'true');
    }

    function clearError(el, input) {
        el.textContent = '';
        el.classList.remove('visible');
        input.classList.remove('input-error');
        input.removeAttribute('aria-invalid');
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    /* ── Real-time validation ─────────────────────────────────── */
    emailInput.addEventListener('input', function () {
        if (emailInput.value.trim() !== '') {
            clearError(emailError, emailInput);
        }
    });

    /* ── Submit ───────────────────────────────────────────────── */
    form.addEventListener('submit', function (e) {
        const email = emailInput.value.trim();

        if (email === '') {
            e.preventDefault();
            showError(emailError, 'Email address is required.', emailInput);
            emailInput.focus();
            return;
        }

        if (!isValidEmail(email)) {
            e.preventDefault();
            showError(emailError, 'Please enter a valid email address.', emailInput);
            emailInput.focus();
            return;
        }

        // Show loading state
        fpBtn.classList.add('loading');
        fpBtn.disabled = true;
        fpBtn.setAttribute('aria-busy', 'true');
    });

})();
