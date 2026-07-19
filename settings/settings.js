/**
 * reports.js – Client-side script for Reports portal
 * Handles: sidebar tab switching
 */

(function () {
    'use strict';

    const sidebarButtons = document.querySelectorAll('.sidebar-link-btn');
    const tabContents    = document.querySelectorAll('.tab-content');

    sidebarButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const tabId = button.getAttribute('data-tab');

            // Toggle sidebar active states
            sidebarButtons.forEach(btn => btn.classList.remove('active-item'));
            button.classList.add('active-item');

            // Toggle main content views
            tabContents.forEach(tab => tab.classList.remove('active'));
            const targetTab = document.getElementById(`tab-${tabId}`);
            if (targetTab) {
                targetTab.classList.add('active');
            }
        });
    });

})();