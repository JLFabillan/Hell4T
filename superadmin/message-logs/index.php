<?php
session_start();

$previewMode = isset($_GET['preview']) && $_GET['preview'] === 'true';

if (!$previewMode) {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
        header('Location: ../../HelportLogin/login.php');
        exit();
    }
}

// Config for header and sidebar
$portalPage  = 'message_logs';
$portalTitle = 'Message Logs';
$portalSvgIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>';

$sidebarSections = [
    'Audit Trail' => [
        ['tab' => 'all-logs', 'label' => 'All Message Logs', 'active' => true],
        ['tab' => 'filters', 'label' => 'Advanced Filters'],
    ]
];
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Helport | Message Logs</title>
    
    <?php include __DIR__ . '/../../includes/theme-head.php'; ?>
    
    <link rel="stylesheet" href="../../myaccount/myaccount.css?v=6" />
</head>
<body>

<div class="account-wrapper">
    <div class="bg-layer" aria-hidden="true"></div>

    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <main class="main-portal-container" role="main">
        <?php include __DIR__ . '/../../includes/portal-sidebar.php'; ?>

        <section class="form-panel glass" aria-label="Message Logs Data">
            <div class="panel-header">
                <h2 class="panel-header-title">All Message Logs</h2>
                <p class="panel-header-desc">View and search through the global audit trail of all messages sent.</p>
            </div>

            <!-- Tab: All Logs -->
            <div id="tab-all-logs" class="tab-content active">
                <div style="display: flex; flex-direction: row; gap: 16px; align-items: flex-end; margin-bottom: 24px; width: 100%;">
                    <div style="flex: 1;">
                        <label class="field-label-pill">Admin</label>
                        <select class="form-field-input">
                            <option value="">All Admins</option>
                            <option value="1">Admin1</option>
                        </select>
                    </div>
                    <div style="flex: 1;">
                        <label class="field-label-pill">Date From</label>
                        <input type="date" class="form-field-input" />
                    </div>
                    <div style="flex: 1;">
                        <label class="field-label-pill">Date To</label>
                        <input type="date" class="form-field-input" />
                    </div>
                    <div style="flex: 1;">
                        <label class="field-label-pill">Sender ID</label>
                        <input type="text" class="form-field-input" placeholder="Sender ID" />
                    </div>
                    <button type="button" class="send-sms-btn" style="width: auto; padding: 0 24px; height: 40px; margin-bottom: 0;">Search</button>
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-bottom: 16px;">
                    <button type="button" class="send-sms-btn" style="width: auto; padding: 0 16px; background: rgba(255,255,255,0.1); color: var(--hp-text);">Export CSV</button>
                    <button type="button" class="send-sms-btn" style="width: auto; padding: 0 16px; background: rgba(255,255,255,0.1); color: var(--hp-text);">Export Excel</button>
                </div>
                
                <table class="queue-table">
                    <thead>
                        <tr>
                            <th>Date/Time</th>
                            <th>Admin</th>
                            <th>Destination</th>
                            <th>Sender ID</th>
                            <th>Preview</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2026-07-19 14:30:22</td>
                            <td>Admin1</td>
                            <td>+1234567890</td>
                            <td>Helport</td>
                            <td><a href="#" class="view-convo-btn" style="color: var(--hp-primary); text-decoration: none; border-bottom: 1px dashed var(--hp-primary); cursor: pointer;">Hello, this is a test...</a></td>
                            <td><span style="color: var(--hp-success);">Sent</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tab: Advanced Filters -->
            <div id="tab-filters" class="tab-content">
                <form onsubmit="event.preventDefault();">
                    <div style="display: flex; flex-direction: row; gap: 16px; margin-bottom: 24px; width: 100%;">
                        <div style="flex: 1;">
                            <label class="field-label-pill">Admin</label>
                            <select class="form-field-input">
                                <option value="">All Admins</option>
                            </select>
                        </div>
                        <div style="flex: 1;">
                            <label class="field-label-pill">Device / SIM</label>
                            <select class="form-field-input">
                                <option value="">All Devices</option>
                            </select>
                        </div>
                        <div style="flex: 1;">
                            <label class="field-label-pill">Template Used</label>
                            <select class="form-field-input">
                                <option value="">Any Template</option>
                            </select>
                        </div>
                        <div style="flex: 1;">
                            <label class="field-label-pill">Status</label>
                            <select class="form-field-input">
                                <option value="">All Statuses</option>
                                <option value="sent">Sent</option>
                                <option value="failed">Failed</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>
                    
                    <h3 style="margin: 24px 0 16px; font-size: 14px; color: var(--hp-text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Campaign Intelligence</h3>
                    
                    <div style="margin-bottom: 24px;">
                        <label class="field-label-pill">Variable Fields Search</label>
                        <input type="text" class="form-field-input" placeholder="Search within dynamically injected variables (e.g. name, code)" />
                    </div>
                    
                    <div style="display: flex; gap: 16px;">
                        <button type="button" class="send-sms-btn" style="flex: 1;">Apply Advanced Filters</button>
                        <button type="reset" class="send-sms-btn" style="flex: 1; background: rgba(255,255,255,0.1); color: var(--hp-text);">Reset Filters</button>
                    </div>
                </form>
            </div>

        </section>
    </main>
</div>

<script src="../../js/theme.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.sidebar-link-btn');
    const contents = document.querySelectorAll('.tab-content');
    const panelTitle = document.querySelector('.panel-header-title');
    const panelDesc = document.querySelector('.panel-header-desc');

    const descMap = {
        'all-logs': 'View and search through the global audit trail of all messages sent.',
        'filters': 'Use advanced parameters to drill down into specific messaging campaigns.'
    };

    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = tab.getAttribute('data-tab');
            
            tabs.forEach(t => t.classList.remove('active-item'));
            tab.classList.add('active-item');
            
            contents.forEach(c => {
                c.classList.remove('active');
                if(c.id === `tab-${targetId}`) {
                    c.classList.add('active');
                }
            });
            
            panelTitle.textContent = tab.textContent;
            if(descMap[targetId]) {
                panelDesc.textContent = descMap[targetId];
            }
        });
    });

    // Modal Logic
    const viewBtns = document.querySelectorAll('.view-convo-btn');
    const modal = document.getElementById('convo-modal');
    const closeBtn = document.getElementById('close-modal');

    if (modal && closeBtn) {
        viewBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                modal.style.display = 'flex';
            });
        });

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
        
        window.addEventListener('click', (e) => {
            if(e.target === modal) modal.style.display = 'none';
        });
    }
});
</script>

<!-- Conversation Modal -->
<div id="convo-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
    <div style="background: #111; width: 100%; max-width: 500px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.1); overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.5);">
        <div style="padding: 16px 24px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02);">
            <h3 style="margin: 0; color: #fff; font-size: 16px; font-weight: 500;">Conversation: +1234567890</h3>
            <button id="close-modal" style="background: none; border: none; color: var(--hp-text-secondary); cursor: pointer; font-size: 24px; line-height: 1; padding: 0;">&times;</button>
        </div>
        <div style="padding: 24px; display: flex; flex-direction: column; gap: 16px; max-height: 400px; overflow-y: auto; background: #0a0a0a;">
            <!-- Admin message -->
            <div style="align-self: flex-end; background: var(--hp-primary); color: #fff; padding: 12px 16px; border-radius: 16px 16px 0 16px; max-width: 85%;">
                <div style="font-size: 14px; line-height: 1.5;">Hello, this is a test message regarding your recent inquiry.</div>
                <div style="font-size: 11px; opacity: 0.7; text-align: right; margin-top: 6px;">Today, 14:30 PM</div>
            </div>
            <!-- User reply -->
            <div style="align-self: flex-start; background: rgba(255,255,255,0.05); color: #fff; padding: 12px 16px; border-radius: 16px 16px 16px 0; max-width: 85%; border: 1px solid rgba(255,255,255,0.1);">
                <div style="font-size: 14px; line-height: 1.5;">Got it, thanks for the update!</div>
                <div style="font-size: 11px; opacity: 0.7; text-align: left; margin-top: 6px;">Today, 14:35 PM</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
