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
$portalPage  = 'system_reports';
$portalTitle = 'System Reports';
$portalSvgIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>';

$sidebarSections = [
    'Analytics' => [
        ['tab' => 'admin-perf', 'label' => 'Admin Performance', 'active' => true],
        ['tab' => 'infrastructure', 'label' => 'Infrastructure'],
        ['tab' => 'comparative', 'label' => 'Comparative Analytics'],
    ],
    'Transactions' => [
        ['tab' => 'transactions', 'label' => 'Master Transaction Log'],
        ['tab' => 'receipts', 'label' => 'System Receipts'],
        ['tab' => 'data-export', 'label' => 'Data Export'],
    ]
];
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Helport | System Reports</title>
    
    <?php include __DIR__ . '/../../includes/theme-head.php'; ?>
    
    <link rel="stylesheet" href="../../myaccount/myaccount.css?v=6" />
    <style>
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 20px; text-align: center; }
        .stat-card-value { font-size: 32px; font-weight: 700; color: var(--hp-primary); margin-bottom: 8px; }
        .stat-card-label { font-size: 13px; color: var(--hp-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; }
    </style>
</head>
<body>

<div class="account-wrapper">
    <div class="bg-layer" aria-hidden="true"></div>

    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <main class="main-portal-container" role="main">
        <?php include __DIR__ . '/../../includes/portal-sidebar.php'; ?>

        <section class="form-panel glass" aria-label="System Reports Data">
            <div class="panel-header">
                <h2 class="panel-header-title">Admin Performance</h2>
                <p class="panel-header-desc">Performance metrics and activity levels for all administrators.</p>
            </div>

            <!-- Tab: Admin Performance -->
            <div id="tab-admin-perf" class="tab-content active">
                
                <!-- Filters -->
                <div style="display: flex; flex-direction: row; gap: 16px; align-items: flex-end; margin-bottom: 24px; width: 100%;">
                    <div style="flex: 1.5;">
                        <label class="field-label-pill">Select Admin</label>
                        <select class="form-field-input">
                            <option value="all">All Admins</option>
                            <option value="admin1">Admin1</option>
                            <option value="admin2">Admin2</option>
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
                    <button type="button" class="send-sms-btn" style="width: auto; padding: 0 24px; height: 40px; margin-bottom: 0;">Filter</button>
                </div>

                <!-- Total Summary -->
                <div style="display: grid; grid-template-columns: 1fr; gap: 16px; margin-bottom: 24px;">
                    <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 12px; padding: 24px; text-align: center;">
                        <div style="font-size: 36px; font-weight: 700; color: var(--hp-primary); margin-bottom: 8px;">57,400</div>
                        <div style="font-size: 13px; color: var(--hp-text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Total Messages Sent</div>
                    </div>
                </div>

                <table class="queue-table">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Messages Sent</th>
                            <th>Replies</th>
                            <th>Response Rate</th>
                            <th>Last Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Admin1</td>
                            <td>45,000</td>
                            <td>1,200</td>
                            <td>2.6%</td>
                            <td>Today, 10:30 AM</td>
                        </tr>
                        <tr>
                            <td>Admin2</td>
                            <td>12,400</td>
                            <td>450</td>
                            <td>3.6%</td>
                            <td>Today, 09:15 AM</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tab: Infrastructure -->
            <div id="tab-infrastructure" class="tab-content">
                <table class="queue-table">
                    <thead>
                        <tr>
                            <th>Device/SIM</th>
                            <th>Status</th>
                            <th>Messages Today</th>
                            <th>Total Volume</th>
                            <th>Last Ping</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Device Alpha</td>
                            <td><span style="color: var(--hp-success);">Online</span></td>
                            <td>1,540</td>
                            <td>150,000</td>
                            <td>Just now</td>
                        </tr>
                        <tr>
                            <td>Device Beta</td>
                            <td><span style="color: var(--hp-warning);">Syncing</span></td>
                            <td>890</td>
                            <td>85,400</td>
                            <td>2 mins ago</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tab: Comparative Analytics -->
            <div id="tab-comparative" class="tab-content">
                <form class="compose-form" onsubmit="event.preventDefault();">
                    <div class="form-group">
                        <label class="field-label-pill">Period 1</label>
                        <div style="display: flex; gap: 16px;">
                            <input type="date" class="form-field-input" placeholder="From" />
                            <input type="date" class="form-field-input" placeholder="To" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="field-label-pill">Period 2</label>
                        <div style="display: flex; gap: 16px;">
                            <input type="date" class="form-field-input" placeholder="From" />
                            <input type="date" class="form-field-input" placeholder="To" />
                        </div>
                    </div>
                    <button type="button" class="send-sms-btn">Generate Report</button>
                </form>
            </div>

            <!-- Tab: Master Transaction Log -->
            <div id="tab-transactions" class="tab-content">
                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-bottom: 16px;">
                    <button type="button" class="send-sms-btn" style="width: auto; padding: 0 16px; background: rgba(255,255,255,0.1); color: var(--hp-text);">Export CSV</button>
                    <button type="button" class="send-sms-btn" style="width: auto; padding: 0 16px; background: rgba(255,255,255,0.1); color: var(--hp-text);">Export Excel</button>
                </div>
                <table class="queue-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Admin</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Reference</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2026-07-19</td>
                            <td>Admin1</td>
                            <td>Credit Top-up</td>
                            <td>$50.00</td>
                            <td>REF-884920</td>
                            <td><span style="color: var(--hp-success);">Completed</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tab: System Receipts -->
            <div id="tab-receipts" class="tab-content">
                <form onsubmit="event.preventDefault();" style="display: flex; flex-direction: row; gap: 16px; align-items: flex-end; margin-bottom: 24px; width: 100%;">
                    <div style="flex: 1;">
                        <label class="field-label-pill">Date From</label>
                        <input type="date" class="form-field-input" />
                    </div>
                    <div style="flex: 1;">
                        <label class="field-label-pill">Date To</label>
                        <input type="date" class="form-field-input" />
                    </div>
                    <div style="flex: 1;">
                        <label class="field-label-pill">Min Amount</label>
                        <input type="number" class="form-field-input" placeholder="0.00" />
                    </div>
                    <div style="flex: 1;">
                        <label class="field-label-pill">Reference</label>
                        <input type="text" class="form-field-input" placeholder="REF-..." />
                    </div>
                    <button type="button" class="send-sms-btn" style="width: auto; padding: 0 24px; height: 40px; margin-bottom: 0;">Filter</button>
                </form>
                
                <table class="queue-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Admin</th>
                            <th>Amount</th>
                            <th>Reference</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2026-07-19</td>
                            <td>Admin1</td>
                            <td>$50.00</td>
                            <td>REF-884920</td>
                            <td>Paid</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tab: Data Export -->
            <div id="tab-data-export" class="tab-content">
                <!-- Filters -->
                <div style="display: flex; flex-direction: row; gap: 16px; align-items: flex-end; margin-bottom: 24px; width: 100%;">
                    <div style="flex: 1.5;">
                        <label class="field-label-pill">Data Type</label>
                        <select class="form-field-input">
                            <option value="load_receipts">Load Receipts</option>
                            <option value="message_receipts">Message Receipts</option>
                            <option value="system_logs">System Audit Logs</option>
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
                    <button type="button" class="send-sms-btn" style="width: auto; padding: 0 24px; height: 40px; margin-bottom: 0;">Apply Filter</button>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 32px;">
                    <div style="background: rgba(255,255,255,0.03); padding: 24px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); text-align: center;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 32px; height: 32px; color: #ff5252; margin-bottom: 12px; display: inline-block;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        <h3 style="font-size: 15px; color: #fff; margin: 0 0 8px;">PDF Summary Report</h3>
                        <p style="font-size: 13px; color: var(--hp-text-secondary); margin: 0 0 16px;">Visual report of system-wide usage and performance.</p>
                        <button class="send-sms-btn" style="width: auto; padding: 8px 24px; font-size: 13px;">Download PDF</button>
                    </div>

                    <div style="background: rgba(255,255,255,0.03); padding: 24px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); text-align: center;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 32px; height: 32px; color: #4ade80; margin-bottom: 12px; display: inline-block;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="12" y1="18" x2="12" y2="12"></line>
                            <line x1="9" y1="15" x2="15" y2="15"></line>
                        </svg>
                        <h3 style="font-size: 15px; color: #fff; margin: 0 0 8px;">Raw CSV Logs</h3>
                        <p style="font-size: 13px; color: var(--hp-text-secondary); margin: 0 0 16px;">Detailed, row-by-row data extract of system activity.</p>
                        <button class="send-sms-btn" style="width: auto; padding: 8px 24px; font-size: 13px; background: rgba(74, 222, 128, 0.2); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.4);">Download CSV</button>
                    </div>
                </div>
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
        'admin-perf': 'Performance metrics and activity levels for all administrators.',
        'infrastructure': 'Hardware and connection status for all routing devices.',
        'comparative': 'Compare system usage and performance across different time periods.',
        'transactions': 'Comprehensive audit log of all financial and system transactions.',
        'receipts': 'View and download system billing receipts and invoices.',
        'data-export': 'Export system-wide data, receipts, and logs in PDF or CSV formats.'
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
});
</script>
</body>
</html>
