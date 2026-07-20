<?php
session_start();

$previewMode = isset($_GET['preview']) && $_GET['preview'] === 'true';

if (!isset($_SESSION['user_id']) && !$previewMode) {
    header('Location: ../HelportLogin/login.php');
    exit();
}

$username = $previewMode ? 'Developer' : ($_SESSION['username'] ?? 'User');

$tabs = [
    'core-reporting' => ['title' => 'Core Reporting', 'desc' => 'Track daily/weekly/monthly performance, response rates, and template effectiveness.'],
    'campaign-logs'  => ['title' => 'Campaign Logs',  'desc' => 'Track personal campaign lifecycle status (new, sent, pending).'],
    'load-receipts'  => ['title' => 'Load Receipts',  'desc' => 'View handled receipts and import bulk receipts from GCash screenshots.'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Helport Reports Page" />
    <title>Helport | Reports</title>
    <link rel="icon" href="../Asset/favicon.svg" type="image/svg+xml" />
    <link rel="icon" href="../Asset/favicon.png" type="image/png" sizes="32x32" />
    <link rel="apple-touch-icon" href="../Asset/apple-touch-icon.png" />
    <?php include __DIR__ . '/../includes/theme-head.php'; ?>
    <link rel="stylesheet" href="../myaccount/myaccount.css?v=6" />
</head>
<body>

    <div class="account-wrapper">
        <div class="bg-layer" aria-hidden="true"></div>

        <?php
        $portalPage  = 'reports';
        $portalTitle = 'Reports';
        $portalSvgIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>';
        include __DIR__ . '/../includes/header.php';
        ?>

        <div class="main-portal-container">
            <?php
            $sidebarSections = [
                'Reports' => [
                    ['tab' => 'core-reporting', 'label' => 'Core Reporting', 'active' => true],
                    ['tab' => 'campaign-logs',  'label' => 'Campaign Logs'],
                    ['tab' => 'load-receipts',  'label' => 'Load Receipts'],
                    ['tab' => 'data-export',    'label' => 'Data Export'],
                ],
            ];
            include __DIR__ . '/../includes/portal-sidebar.php';
            ?>

            <main class="form-panel" role="main">
                <!-- Core Reporting Tab -->
                <div id="tab-core-reporting" class="tab-content active">
                    <div class="panel-header">
                        <h2 class="panel-header-title">Core Reporting</h2>
                        <p class="panel-header-desc">Track daily/weekly/monthly performance, response rates, and template effectiveness.</p>
                    </div>
                    
                    <div style="display: flex; flex-direction: row; gap: 12px; margin-bottom: 24px;">
                        <button class="send-sms-btn report-filter-btn" style="width: auto; padding: 6px 16px; font-size: 13px; background: var(--hp-primary); color: #fff;">Daily</button>
                        <button class="send-sms-btn report-filter-btn" style="width: auto; padding: 6px 16px; font-size: 13px; background: rgba(255,255,255,0.1); color: var(--hp-text);">Weekly</button>
                        <button class="send-sms-btn report-filter-btn" style="width: auto; padding: 6px 16px; font-size: 13px; background: rgba(255,255,255,0.1); color: var(--hp-text);">Monthly</button>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 32px;">
                        <div style="background: var(--hp-surface-hover); padding: 20px; border-radius: 12px; border: 1px solid var(--hp-border);">
                            <div style="font-size: 12px; color: var(--hp-text-secondary); text-transform: uppercase;">Total Sent</div>
                            <div style="font-size: 28px; font-weight: 700; color: var(--hp-text); margin-top: 8px;">12,450</div>
                        </div>
                        <div style="background: var(--hp-surface-hover); padding: 20px; border-radius: 12px; border: 1px solid var(--hp-border);">
                            <div style="font-size: 12px; color: var(--hp-text-secondary); text-transform: uppercase;">Response Rate</div>
                            <div style="font-size: 28px; font-weight: 700; color: #4ade80; margin-top: 8px;">18.2%</div>
                        </div>
                        <div style="background: var(--hp-surface-hover); padding: 20px; border-radius: 12px; border: 1px solid var(--hp-border);">
                            <div style="font-size: 12px; color: var(--hp-text-secondary); text-transform: uppercase;">Top Template</div>
                            <div style="font-size: 18px; font-weight: 700; color: var(--hp-text); margin-top: 8px;">Promo Alert V2</div>
                        </div>
                    </div>
                </div>

                <!-- Campaign Logs Tab -->
                <div id="tab-campaign-logs" class="tab-content">
                    <div class="panel-header">
                        <h2 class="panel-header-title">Campaign Logs</h2>
                        <p class="panel-header-desc">Track personal campaign lifecycle status (new, sent, pending).</p>
                    </div>
                    
                    <table class="queue-table" style="width: 100%; text-align: left; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--hp-border);">
                                <th style="padding: 12px; color: var(--hp-text-secondary); font-size: 12px;">Campaign Name</th>
                                <th style="padding: 12px; color: var(--hp-text-secondary); font-size: 12px;">Status</th>
                                <th style="padding: 12px; color: var(--hp-text-secondary); font-size: 12px;">Date Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid var(--hp-border);">
                                <td style="padding: 12px; color: var(--hp-text); font-size: 14px;">Holiday Promo Blast</td>
                                <td style="padding: 12px;"><span style="background: rgba(74, 222, 128, 0.2); color: #4ade80; padding: 4px 8px; border-radius: 12px; font-size: 11px;">Sent</span></td>
                                <td style="padding: 12px; color: var(--hp-text-secondary); font-size: 13px;">Oct 24, 2026</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--hp-border);">
                                <td style="padding: 12px; color: var(--hp-text); font-size: 14px;">Weekend Flash Sale</td>
                                <td style="padding: 12px;"><span style="background: rgba(255, 170, 0, 0.2); color: #ffaa00; padding: 4px 8px; border-radius: 12px; font-size: 11px;">Pending</span></td>
                                <td style="padding: 12px; color: var(--hp-text-secondary); font-size: 13px;">Oct 25, 2026</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--hp-border);">
                                <td style="padding: 12px; color: var(--hp-text); font-size: 14px;">New Users Onboarding</td>
                                <td style="padding: 12px;"><span style="background: rgba(0, 180, 210, 0.2); color: #00b4d2; padding: 4px 8px; border-radius: 12px; font-size: 11px;">New</span></td>
                                <td style="padding: 12px; color: var(--hp-text-secondary); font-size: 13px;">Oct 26, 2026</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Load Receipts Tab -->
                <div id="tab-load-receipts" class="tab-content">
                    <div class="panel-header">
                        <h2 class="panel-header-title">Load Receipts</h2>
                        <p class="panel-header-desc">View handled receipts and import bulk receipts from GCash screenshots.</p>
                    </div>

                    <div style="background: var(--hp-surface-hover); padding: 24px; border-radius: 12px; border: 1px dashed var(--hp-border-strong); margin-bottom: 32px; text-align: center;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 32px; height: 32px; color: var(--hp-primary); margin-bottom: 12px; display: inline-block;">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        <h3 style="font-size: 15px; color: var(--hp-text); margin: 0 0 8px;">Upload GCash Screenshots</h3>
                        <p style="font-size: 13px; color: var(--hp-text-secondary); margin: 0 0 16px;">Drag and drop or select your receipt images.</p>
                        <input type="file" accept="image/*" multiple style="display: none;" id="gcashUpload" />
                        <label for="gcashUpload" class="send-sms-btn" style="cursor: pointer; display: inline-block; padding: 8px 24px; width: auto; font-size: 13px;">Select Images</label>
                    </div>

                    <h3 style="margin: 0 0 16px; font-size: 14px; color: var(--hp-text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Recent Handled Receipts</h3>
                    <table class="queue-table" style="width: 100%; text-align: left; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--hp-border);">
                                <th style="padding: 12px; color: var(--hp-text-secondary); font-size: 12px;">Reference No.</th>
                                <th style="padding: 12px; color: var(--hp-text-secondary); font-size: 12px;">Amount</th>
                                <th style="padding: 12px; color: var(--hp-text-secondary); font-size: 12px;">Transaction Date</th>
                                <th style="padding: 12px; color: var(--hp-text-secondary); font-size: 12px;">Transaction Time</th>
                                <th style="padding: 12px; color: var(--hp-text-secondary); font-size: 12px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid var(--hp-border);">
                                <td style="padding: 12px; color: var(--hp-text); font-size: 14px;">92837482910</td>
                                <td style="padding: 12px; color: var(--hp-text); font-size: 14px;">₱ 500.00</td>
                                <td style="padding: 12px; color: var(--hp-text-secondary); font-size: 13px;">Oct 26, 2026</td>
                                <td style="padding: 12px; color: var(--hp-text-secondary); font-size: 13px;">14:30 PM</td>
                                <td style="padding: 12px;"><span style="background: rgba(74, 222, 128, 0.2); color: #4ade80; padding: 4px 8px; border-radius: 12px; font-size: 11px;">Verified</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--hp-border);">
                                <td style="padding: 12px; color: var(--hp-text); font-size: 14px;">38472910293</td>
                                <td style="padding: 12px; color: var(--hp-text); font-size: 14px;">₱ 1,000.00</td>
                                <td style="padding: 12px; color: var(--hp-text-secondary); font-size: 13px;">Oct 27, 2026</td>
                                <td style="padding: 12px; color: var(--hp-text-secondary); font-size: 13px;">09:15 AM</td>
                                <td style="padding: 12px;"><span style="background: rgba(255, 170, 0, 0.2); color: #ffaa00; padding: 4px 8px; border-radius: 12px; font-size: 11px;">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Data Export Tab -->
                <div id="tab-data-export" class="tab-content">
                    <div class="panel-header">
                        <h2 class="panel-header-title">Data Export</h2>
                        <p class="panel-header-desc">Download summarized PDF reports or raw CSV logs of personal activity.</p>
                    </div>

                    <!-- Filters -->
                    <div style="display: flex; flex-direction: row; gap: 16px; align-items: flex-end; margin-bottom: 24px; width: 100%;">
                        <div style="flex: 1.5;">
                            <label class="field-label-pill">Data Type</label>
                            <select class="form-field-input">
                                <option value="load_receipts">Load Receipts</option>
                                <option value="message_receipts">Message Receipts</option>
                                <option value="campaign_logs">Campaign Logs</option>
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
                        <div style="background: var(--hp-surface-hover); padding: 24px; border-radius: 12px; border: 1px solid var(--hp-border); text-align: center;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 32px; height: 32px; color: #ff5252; margin-bottom: 12px; display: inline-block;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <h3 style="font-size: 15px; color: var(--hp-text); margin: 0 0 8px;">PDF Summary Report</h3>
                            <p style="font-size: 13px; color: var(--hp-text-secondary); margin: 0 0 16px;">Visual report of your daily, weekly, and monthly performance.</p>
                            <button class="send-sms-btn" style="width: auto; padding: 8px 24px; font-size: 13px;">Download PDF</button>
                        </div>

                        <div style="background: var(--hp-surface-hover); padding: 24px; border-radius: 12px; border: 1px solid var(--hp-border); text-align: center;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 32px; height: 32px; color: #4ade80; margin-bottom: 12px; display: inline-block;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="12" y1="18" x2="12" y2="12"></line>
                                <line x1="9" y1="15" x2="15" y2="15"></line>
                            </svg>
                            <h3 style="font-size: 15px; color: var(--hp-text); margin: 0 0 8px;">Raw CSV Logs</h3>
                            <p style="font-size: 13px; color: var(--hp-text-secondary); margin: 0 0 16px;">Detailed, row-by-row data of your personal campaign activity.</p>
                            <button class="send-sms-btn" style="width: auto; padding: 8px 24px; font-size: 13px; background: rgba(74, 222, 128, 0.2); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.4);">Download CSV</button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="../js/theme.js"></script>
    <script src="reports.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterBtns = document.querySelectorAll('.report-filter-btn');
            filterBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    // Reset all
                    filterBtns.forEach(b => {
                        b.style.background = 'rgba(255,255,255,0.1)';
                        b.style.color = 'var(--hp-text)';
                    });
                    // Set active
                    btn.style.background = 'var(--hp-primary)';
                    btn.style.color = '#fff';
                });
            });
        });
    </script>
</body>
</html>
