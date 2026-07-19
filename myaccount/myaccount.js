/**
 * myaccount.js – Client-side script for My Account portal
 * Handles: tab switching, character counters, input checks, and scheduled message queues
 */

(function () {
    'use strict';

    /* ── DOM References ─────────────────────────────────────── */
    // Compose Tab Elements
    const composeForm       = document.getElementById('composeSmsForm');
    const mobileNumberInput = document.getElementById('mobileNumber');
    const senderIdInput     = document.getElementById('senderId');
    const messageTextarea   = document.getElementById('messageContent');
    const charCounter       = document.getElementById('charCounter');
    const sendBtn           = document.getElementById('sendSmsBtn');

    // Schedule Tab Elements
    const scheduleForm       = document.getElementById('scheduleSmsForm');
    const schedMobileInput   = document.getElementById('scheduleMobileNumber');
    const schedSenderInput   = document.getElementById('scheduleSenderId');
    const schedDateTimeInput = document.getElementById('scheduleDateTime');
    const schedMessageText   = document.getElementById('scheduleMessageContent');
    const schedCharCounter   = document.getElementById('scheduleCharCounter');
    const schedSendBtn       = document.getElementById('scheduleSmsBtn');
    const queueBody          = document.getElementById('scheduledQueueBody');

    // Navigation Elements
    const sidebarButtons = document.querySelectorAll('.sidebar-link-btn');
    const tabContents    = document.querySelectorAll('.tab-content');

    /* ── Tab Switching Logic ─────────────────────────────────── */
   sidebarButtons.forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();
        const tabId = button.getAttribute('data-tab');
        const label = button.textContent.trim();

        sidebarButtons.forEach(btn => btn.classList.remove('active-item'));
        button.classList.add('active-item');

        tabContents.forEach(tab => tab.classList.remove('active'));
        const targetTab = document.getElementById(`tab-${tabId}`);
        if (targetTab) {
            targetTab.classList.add('active');
        }
    });
});

    /* ── Real-time Character Counter ── */
    function setupCharCounter(textarea, counter) {
        if (textarea && counter) {
            textarea.addEventListener('input', function () {
                const textLength = textarea.value.length;
                counter.textContent = `Characters: ${textLength}`;

                if (textLength > 160) {
                    counter.style.color = '#ff8f8f'; // red for multi-part SMS warning
                } else {
                    counter.style.color = 'rgba(210, 255, 240, 0.6)';
                }
            });
        }
    }
    setupCharCounter(messageTextarea, charCounter);
    setupCharCounter(schedMessageText, schedCharCounter);

    /* ── Compose Form Submit Handler ── */
    if (composeForm) {
        composeForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const mobile = mobileNumberInput.value.trim();
            const sender = senderIdInput.value.trim();
            const message = messageTextarea.value.trim();

            if (mobile === '') {
                alert('Please enter a destination mobile number.');
                mobileNumberInput.focus();
                return;
            }
            if (sender === '') {
                alert('Please enter a Sender ID / Name.');
                senderIdInput.focus();
                return;
            }
            if (message === '') {
                alert('Please enter message content.');
                messageTextarea.focus();
                return;
            }

            // Mock success state and button loading animation
            const originalBtnText = sendBtn.textContent;
            sendBtn.textContent = 'Sending...';
            sendBtn.disabled = true;
            sendBtn.style.opacity = '0.7';

            setTimeout(function () {
                alert(`SMS successfully dispatched!\n\nDestination: ${mobile}\nSender ID: ${sender}\nLength: ${message.length} characters`);
                
                // Clear fields
                messageTextarea.value = '';
                mobileNumberInput.value = '';
                charCounter.textContent = 'Characters: 0';
                charCounter.style.color = 'rgba(210, 255, 240, 0.6)';

                // Reset button
                sendBtn.textContent = originalBtnText;
                sendBtn.disabled = false;
                sendBtn.style.opacity = '1';
            }, 1000);
        });
    }

    /* ── Schedule Messages Storage & Queue Logic ── */
    const STORAGE_KEY = 'helport_scheduled_messages';

    function getScheduledMessages() {
        try {
            const data = localStorage.getItem(STORAGE_KEY);
            return data ? JSON.parse(data) : [];
        } catch (e) {
            console.error('Error parsing local storage scheduled messages', e);
            return [];
        }
    }

    function saveScheduledMessages(messages) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(messages));
    }

    function renderQueue() {
        if (!queueBody) return;
        const messages = getScheduledMessages();

        if (messages.length === 0) {
            queueBody.innerHTML = `
                <tr>
                    <td colspan="5" class="empty-queue-row">No scheduled messages found.</td>
                </tr>
            `;
            return;
        }

        // Sort by date/time ascending
        messages.sort((a, b) => new Date(a.datetime) - new Date(b.datetime));

        queueBody.innerHTML = messages.map(msg => {
            // Format datetime nicely
            const dateObj = new Date(msg.datetime);
            const formattedDate = isNaN(dateObj.getTime()) 
                ? msg.datetime 
                : dateObj.toLocaleString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric', 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });

            // Escape text helper
            const escapeHtml = (text) => text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");

            return `
                <tr data-id="${msg.id}">
                    <td>${escapeHtml(msg.mobile)}</td>
                    <td>${escapeHtml(msg.sender)}</td>
                    <td>${escapeHtml(msg.message)}</td>
                    <td style="white-space: nowrap;">${formattedDate}</td>
                    <td style="text-align: center;">
                        <button class="cancel-schedule-btn" data-action="cancel" data-id="${msg.id}">Cancel</button>
                    </td>
                </tr>
            `;
        }).join('');

        // Attach event listeners for cancel buttons
        queueBody.querySelectorAll('.cancel-schedule-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = parseInt(this.getAttribute('data-id'), 10);
                cancelMessage(id);
            });
        });
    }

    function cancelMessage(id) {
        let messages = getScheduledMessages();
        messages = messages.filter(msg => msg.id !== id);
        saveScheduledMessages(messages);
        renderQueue();
    }

    /* ── Schedule Form Submit Handler ── */
    if (scheduleForm) {
        // Set default minimum datetime to current time
        if (schedDateTimeInput) {
            const now = new Date();
            // Format now as YYYY-MM-DDTHH:MM
            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const dd = String(now.getDate()).padStart(2, '0');
            const hh = String(now.getHours()).padStart(2, '0');
            const min = String(now.getMinutes()).padStart(2, '0');
            schedDateTimeInput.min = `${yyyy}-${mm}-${dd}T${hh}:${min}`;
        }

        scheduleForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const mobile = schedMobileInput.value.trim();
            const sender = schedSenderInput.value.trim();
            const datetime = schedDateTimeInput.value;
            const message = schedMessageText.value.trim();

            if (mobile === '') {
                alert('Please enter a destination mobile number.');
                schedMobileInput.focus();
                return;
            }
            if (sender === '') {
                alert('Please enter a Sender ID / Name.');
                schedSenderInput.focus();
                return;
            }
            if (datetime === '') {
                alert('Please pick a date and time to schedule.');
                schedDateTimeInput.focus();
                return;
            }
            
            // Check if datetime is in the past
            const pickedTime = new Date(datetime);
            const now = new Date();
            if (pickedTime <= now) {
                alert('Please select a future date and time for scheduling.');
                schedDateTimeInput.focus();
                return;
            }

            if (message === '') {
                alert('Please enter message content.');
                schedMessageText.focus();
                return;
            }

            // Save scheduled message
            const newSchedule = {
                id: Date.now(),
                mobile: mobile,
                sender: sender,
                datetime: datetime,
                message: message
            };

            const messages = getScheduledMessages();
            messages.push(newSchedule);
            saveScheduledMessages(messages);

            // Mock success feedback
            const originalBtnText = schedSendBtn.textContent;
            schedSendBtn.textContent = 'Scheduling...';
            schedSendBtn.disabled = true;
            schedSendBtn.style.opacity = '0.7';

            setTimeout(function () {
                alert(`SMS successfully scheduled for ${pickedTime.toLocaleString()}!`);
                
                // Clear fields
                schedMessageText.value = '';
                schedMobileInput.value = '';
                schedDateTimeInput.value = '';
                schedCharCounter.textContent = 'Characters: 0';
                schedCharCounter.style.color = 'rgba(210, 255, 240, 0.6)';

                // Reset button
                schedSendBtn.textContent = originalBtnText;
                schedSendBtn.disabled = false;
                schedSendBtn.style.opacity = '1';

                // Refresh queue display
                renderQueue();
            }, 800);
        });
    }
/* ── Inbox Mock Data & Rendering ── */
const mockConversations = [
    { id: 1, name: 'Juan Cruz', number: '09171234567', lastMessage: 'Okay po, babayaran ko na po ngayon.', time: '10:24 AM', unread: true,
      thread: [
          { from: 'out', text: 'Good morning, your account has an overdue amount of Php 2,340.', time: '9:50 AM' },
          { from: 'in', text: 'Okay po, babayaran ko na po ngayon.', time: '10:24 AM' }
      ]
    },
    { id: 2, name: 'Maria Santos', number: '09181234567', lastMessage: 'Thank you for the reminder!', time: 'Yesterday', unread: false,
      thread: [
          { from: 'out', text: 'Reminder: your payment is due tomorrow.', time: 'Yesterday, 3:00 PM' },
          { from: 'in', text: 'Thank you for the reminder!', time: 'Yesterday, 3:12 PM' }
      ]
    },
    { id: 3, name: 'Pedro Reyes', number: '09201234567', lastMessage: 'Sino po ito?', time: '2 days ago', unread: true,
      thread: [
          { from: 'out', text: 'Good day! This is Helport Collections regarding your account.', time: '2 days ago' },
          { from: 'in', text: 'Sino po ito?', time: '2 days ago' }
      ]
    }
];

let currentFilter = 'all';
let selectedConversationId = null;

const inboxListScroll   = document.getElementById('inboxListScroll');
const inboxChatCol      = document.getElementById('inboxChatCol');
const inboxSearchInput  = document.getElementById('inboxSearchInput');
const filterPills       = document.querySelectorAll('.filter-pill');

function getInitials(name) {
    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
}

function renderInboxList() {
    if (!inboxListScroll) return;

    const searchTerm = (inboxSearchInput?.value || '').toLowerCase();

    const filtered = mockConversations.filter(convo => {
        const matchesSearch = convo.name.toLowerCase().includes(searchTerm) ||
                               convo.number.includes(searchTerm) ||
                               convo.lastMessage.toLowerCase().includes(searchTerm);

        if (!matchesSearch) return false;
        if (currentFilter === 'unread') return convo.unread;
        if (currentFilter === 'replied') return !convo.unread;
        return true;
    });

    if (filtered.length === 0) {
        inboxListScroll.innerHTML = '<p class="inbox-placeholder-text">No conversations found.</p>';
        return;
    }

    inboxListScroll.innerHTML = filtered.map(convo => `
        <div class="conversation-item ${convo.unread ? 'unread' : ''} ${convo.id === selectedConversationId ? 'selected' : ''}" data-id="${convo.id}">
            <div class="conversation-avatar">${getInitials(convo.name)}</div>
            <div class="conversation-info">
                <div class="conversation-name">
                    <span>${convo.name}</span>
                    <span class="conversation-time">${convo.time}</span>
                </div>
                <div class="conversation-number">${convo.number}</div>
                <div class="conversation-preview">${convo.lastMessage}</div>
            </div>
            ${convo.unread ? '<span class="unread-dot"></span>' : ''}
        </div>
    `).join('');

    inboxListScroll.querySelectorAll('.conversation-item').forEach(item => {
        item.addEventListener('click', function () {
            const id = parseInt(this.getAttribute('data-id'), 10);
            selectedConversationId = id;
            const convo = mockConversations.find(c => c.id === id);
            convo.unread = false;
            renderInboxList();
            renderChatThread(convo);
        });
    });
}

function renderChatThread(convo) {
    if (!inboxChatCol) return;

    // Helper to escape text for HTML attributes
    const escapeAttr = (text) => text.replace(/"/g, "&quot;").replace(/'/g, "&#39;");

    inboxChatCol.innerHTML = `
        <div class="chat-thread-header">
            <div class="conversation-avatar">${getInitials(convo.name)}</div>
            <div>
                <div class="chat-thread-name">${convo.name}</div>
                <div class="chat-thread-number">${convo.number}</div>
            </div>
        </div>
        <div class="chat-messages" id="chatMessagesScroll">
            ${convo.thread.map(msg => `
                <div class="chat-bubble chat-bubble-${msg.from}">
                    <div class="chat-bubble-text" style="display: flex; align-items: flex-start; justify-content: space-between; gap: 8px;">
                        <span>${msg.text}</span>
                        ${msg.from === 'in' ? `<button type="button" class="copy-msg-btn" data-text="${escapeAttr(msg.text)}" title="Copy message" aria-label="Copy message" style="background: none; border: none; cursor: pointer; opacity: 0.6; padding: 2px; flex-shrink: 0;">
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                        </button>` : ''}
                    </div>
                    <span class="chat-bubble-time">${msg.time}</span>
                </div>
            `).join('')}
        </div>
    `;

    // Auto-scroll to latest message
    const scrollBox = document.getElementById('chatMessagesScroll');
    if (scrollBox) scrollBox.scrollTop = scrollBox.scrollHeight;

    // Copy buttons logic
    const copyBtns = inboxChatCol.querySelectorAll('.copy-msg-btn');
    copyBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const textToCopy = this.getAttribute('data-text');
            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalHTML = this.innerHTML;
                this.innerHTML = `<svg viewBox="0 0 24 24" width="14" height="14" stroke="#4ade80" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>`;
                this.style.opacity = '1';
                setTimeout(() => {
                    this.innerHTML = originalHTML;
                    this.style.opacity = '0.6';
                }, 1500);
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        });
    });
}
if (inboxSearchInput) {
    inboxSearchInput.addEventListener('input', renderInboxList);
}

filterPills.forEach(pill => {
    pill.addEventListener('click', function () {
        filterPills.forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        currentFilter = this.getAttribute('data-filter');
        renderInboxList();
    });
});

renderInboxList();
    // Initialize queue list on page load
    renderQueue();

    /* ── Send From File (CSV Upload) ── */
    const csvFileInput  = document.getElementById('csvFileInput');
    const csvUploadBtn  = document.getElementById('csvUploadBtn');

    function countCsvRows(text) {
        const lines = text.split(/\r?\n/).map(l => l.trim()).filter(Boolean);
        return Math.max(0, lines.length - 1);
    }

    if (csvUploadBtn && csvFileInput) {
        csvUploadBtn.addEventListener('click', function () {
            csvFileInput.click();
        });

        csvFileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            if (!file.name.toLowerCase().endsWith('.csv')) {
                alert('Please upload a valid CSV file (.csv format only).');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            const originalText = csvUploadBtn.textContent;
            csvUploadBtn.textContent = 'Uploading...';
            csvUploadBtn.disabled = true;

            reader.onload = function (e) {
                const rowCount = countCsvRows(e.target.result);
                setTimeout(function () {
                    alert(`CSV file uploaded successfully!\n\nFile: ${file.name}\nRecipients: ${rowCount}`);
                    csvUploadBtn.textContent = originalText;
                    csvUploadBtn.disabled = false;
                    csvFileInput.value = '';
                }, 600);
            };

            reader.onerror = function () {
                alert('Failed to read the file. Please try again.');
                csvUploadBtn.textContent = originalText;
                csvUploadBtn.disabled = false;
                csvFileInput.value = '';
            };

            reader.readAsText(file);
        });
    }

})();
