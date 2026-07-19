(function () {
    'use strict';

    var STORAGE_KEY = 'helport-theme';

    function getPreferredTheme() {
        if (window.matchMedia('(prefers-color-scheme: light)').matches) {
            return 'light';
        }
        return 'dark';
    }

    function getStoredTheme() {
        var stored = localStorage.getItem(STORAGE_KEY);
        return stored === 'light' || stored === 'dark' ? stored : null;
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
            var label = theme === 'dark'
                ? 'Switch to light mode'
                : 'Switch to dark mode';
            btn.setAttribute('aria-label', label);
            btn.setAttribute('title', label);
        });
    }

    function setTheme(theme, persist) {
        applyTheme(theme);
        if (persist !== false) {
            localStorage.setItem(STORAGE_KEY, theme);
        }
    }

    function toggleTheme() {
        var current = document.documentElement.getAttribute('data-theme') || 'dark';
        var next = current === 'dark' ? 'light' : 'dark';

        document.documentElement.classList.add('theme-transition');
        setTheme(next);

        window.setTimeout(function () {
            document.documentElement.classList.remove('theme-transition');
        }, 400);
    }

    /* Init on load */
    setTheme(getStoredTheme() || getPreferredTheme(), false);

    document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
        btn.addEventListener('click', toggleTheme);
    });

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
        if (!getStoredTheme()) {
            setTheme(e.matches ? 'dark' : 'light', false);
        }
    });
})();
