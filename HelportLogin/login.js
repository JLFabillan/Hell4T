/**
 * login.js – Helport Login Page Client-Side Logic
 * Handles: form validation, loading state, input sanitisation
 */

(function () {
    'use strict';

    /* ── DOM References ─────────────────────────────────────── */
    const form          = document.getElementById('loginForm');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const loginBtn      = document.getElementById('loginBtn');
    const usernameError = document.getElementById('usernameError');
    const passwordError = document.getElementById('passwordError');

    /* ── Helpers ─────────────────────────────────────────────── */

    /**
     * Show an inline field error message.
     * @param {HTMLElement} el    – The <span class="field-error"> element.
     * @param {string}      msg   – Error text to display.
     * @param {HTMLElement} input – The related <input> element.
     */
    function showError(el, msg, input) {
        el.textContent = msg;
        el.classList.add('visible');
        input.classList.add('input-error');
        input.setAttribute('aria-invalid', 'true');
    }

    /**
     * Clear an inline field error.
     * @param {HTMLElement} el    – The <span class="field-error"> element.
     * @param {HTMLElement} input – The related <input> element.
     */
    function clearError(el, input) {
        el.textContent = '';
        el.classList.remove('visible');
        input.classList.remove('input-error');
        input.removeAttribute('aria-invalid');
    }

    /**
     * Validate all fields. Returns true when the form is valid.
     * @returns {boolean}
     */
    function validateForm() {
        let valid = true;

        const username = usernameInput.value.trim();
        const password = passwordInput.value;

        // Username — only check it's not blank (server validates the rest)
        if (username === '') {
            showError(usernameError, 'Username is required.', usernameInput);
            valid = false;
        } else {
            clearError(usernameError, usernameInput);
        }

        // Password — only check it's not blank (server validates the rest)
        if (password === '') {
            showError(passwordError, 'Password is required.', passwordInput);
            valid = false;
        } else {
            clearError(passwordError, passwordInput);
        }

        return valid;
    }

    /* ── Real-time validation (clear errors as user types) ───── */
    usernameInput.addEventListener('input', function () {
        if (usernameInput.value.trim() !== '') {
            clearError(usernameError, usernameInput);
        }
    });

    passwordInput.addEventListener('input', function () {
        if (passwordInput.value !== '') {
            clearError(passwordError, passwordInput);
        }
    });

    /* ── Form Submit Handler ─────────────────────────────────── */
    form.addEventListener('submit', function (e) {
        const isValid = validateForm();

        if (!isValid) {
            e.preventDefault();
            // Focus on first errored field
            if (usernameInput.classList.contains('input-error')) {
                usernameInput.focus();
            } else {
                passwordInput.focus();
            }
            return;
        }

        // Show loading spinner on the button
        setLoadingState(true);

        // Allow form to submit naturally to PHP
        // (PHP handles auth; no extra AJAX needed)
    });

    /* ── Loading State ───────────────────────────────────────── */
    /**
     * Toggle the login button loading state.
     * @param {boolean} loading
     */
    function setLoadingState(loading) {
        if (loading) {
            loginBtn.classList.add('loading');
            loginBtn.disabled = true;
            loginBtn.setAttribute('aria-busy', 'true');
        } else {
            loginBtn.classList.remove('loading');
            loginBtn.disabled = false;
            loginBtn.removeAttribute('aria-busy');
        }
    }

    /* ── Auto-dismiss server-side error banner after 5 s ─────── */
    const errorBanner = document.getElementById('errorBanner');
    if (errorBanner) {
        setTimeout(function () {
            errorBanner.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            errorBanner.style.opacity    = '0';
            errorBanner.style.transform  = 'translateY(-6px)';
            setTimeout(function () {
                errorBanner.style.display = 'none';
            }, 500);
        }, 5000);
    }

    /* ── Keyboard: Enter on username moves focus to password ─── */
    usernameInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            passwordInput.focus();
        }
    });

})();
