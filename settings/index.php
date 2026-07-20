<?php
session_start();

$previewMode = isset($_GET['preview']) && $_GET['preview'] === 'true';

if (!isset($_SESSION['user_id']) && !$previewMode) {
    header('Location: ../HelportLogin/login.php');
    exit();
}

$username = $previewMode ? 'Developer' : ($_SESSION['username'] ?? 'User');

$tabs = [
    'site'        => ['section' => 'Configuration', 'title' => 'Manage Site',   'desc' => 'Update site-wide settings and environment preferences.'],
    'credit'      => ['section' => 'Configuration', 'title' => 'Manage Credit', 'desc' => 'Monitor personal SMS credit consumption and budget allocation.'],
];

if (isset($_SESSION['role']) && $_SESSION['role'] === 'superadmin') {
    $tabs['directory']   = ['section' => 'Admin Management', 'title' => 'Admin Directory', 'desc' => 'View all admin accounts and their current session status.'];
    $tabs['create']      = ['section' => 'Admin Management', 'title' => 'Create Admin', 'desc' => 'Register a new admin account for the portal.'];
    $tabs['credentials'] = ['section' => 'Admin Management', 'title' => 'Reset Credentials', 'desc' => 'Reset passwords for existing admin accounts.'];
}

$sections = [];
foreach ($tabs as $id => $tab) {
    $sections[$tab['section']][] = $id;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Helport Settings Page" />
    <title>Helport | Settings</title>
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
        $portalPage  = 'settings';
        $portalTitle = 'Settings';
        $portalSvgIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>';
        include __DIR__ . '/../includes/header.php';
        ?>

        <div class="main-portal-container">
            <?php
            $sidebarSections = [];
            $firstTab        = true;
            foreach ($sections as $sectionName => $tabIds) {
                $items = [];
                foreach ($tabIds as $tabId) {
                    $items[] = [
                        'tab'    => $tabId,
                        'label'  => $tabs[$tabId]['title'],
                        'active' => $firstTab,
                    ];
                    $firstTab = false;
                }
                $sidebarSections[$sectionName] = $items;
            }
            include __DIR__ . '/../includes/portal-sidebar.php';
            ?>

            <main class="form-panel" role="main">
                <?php $first = true; foreach ($tabs as $id => $tab): ?>
                <div id="tab-<?php echo $id; ?>" class="tab-content<?php echo $first ? ' active' : ''; ?>">
                    <?php if ($id === 'credit'): ?>
                        <div class="panel-header">
                            <h2 class="panel-header-title">Manage Credit</h2>
                            <p class="panel-header-desc">Oversight of SMS credits, budget allocation, and balance management.</p>
                        </div>

                        <!-- Search & Bulk Actions Option -->
                        <div style="margin-bottom: 24px; display: flex; flex-direction: row; gap: 12px; align-items: center; width: 100%;">
                            <input type="text" id="searchTransactions" class="form-field-input hp-input" placeholder="Search by user or reference..." style="flex: 1; max-width: 400px; margin: 0;" />
                            <button type="button" class="send-sms-btn" style="white-space: nowrap; width: auto; padding: 10px 24px; margin: 0;">Search</button>
                            <button type="button" class="send-sms-btn" style="white-space: nowrap; width: auto; padding: 10px 24px; background: rgba(255, 60, 60, 0.1); color: #ff5252; border: 1px solid rgba(255, 60, 60, 0.25); box-shadow: none; margin: 0 0 0 auto;">Delete</button>
                        </div>

                        <!-- Transaction List -->
                        <div class="table-responsive" style="margin-bottom: 48px;">
                            <table class="queue-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <label style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px; margin: 0; font-weight: inherit;">
                                                <input type="checkbox" id="selectAllTransactions" title="Select All" /> 
                                                Transaction Date & Time
                                            </label>
                                        </th>
                                        <th>User</th>
                                        <th>Type</th>
                                        <th>Credits</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <label style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px; margin: 0; font-weight: inherit;">
                                                <input type="checkbox" class="transaction-row-checkbox" /> 
                                                2023-10-25 14:30:00
                                            </label>
                                        </td>
                                        <td>John Doe (Admin)</td>
                                        <td><span style="color: var(--hp-primary);">Import</span></td>
                                        <td style="color: var(--hp-text);">+5,000</td>
                                        <td style="color: var(--hp-text);">15,000</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px; margin: 0; font-weight: inherit;">
                                                <input type="checkbox" class="transaction-row-checkbox" /> 
                                                2023-10-24 09:15:22
                                            </label>
                                        </td>
                                        <td>Jane Smith (Admin)</td>
                                        <td><span style="color: #ff9f43;">Allocation</span></td>
                                        <td style="color: var(--hp-text);">-1,000</td>
                                        <td style="color: var(--hp-text);">4,000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Import Option -->
                        <div class="panel-header">
                            <h2 class="panel-header-title">Import Credits</h2>
                            <p class="panel-header-desc">Manually import or top up SMS credits for a specific user.</p>
                        </div>
                        <form class="compose-form" method="POST" action="#" onsubmit="event.preventDefault();" novalidate>
                            <div class="form-group" style="display: flex; gap: 16px;">
                                <div style="flex: 1;">
                                    <div class="field-label-pill">Select User</div>
                                    <select class="form-field-input hp-input" required>
                                        <option value="" disabled selected>-- Select an Admin --</option>
                                        <option value="1">John Doe (Admin)</option>
                                        <option value="2">Jane Smith (Admin)</option>
                                    </select>
                                </div>
                                <div style="flex: 1;">
                                    <div class="field-label-pill">Credit Amount</div>
                                    <input type="number" class="form-field-input hp-input" placeholder="e.g. 10000" required />
                                </div>
                            </div>
                            <div class="form-actions" style="margin-top: 12px; text-align: right;">
                                <button type="submit" class="send-sms-btn" style="width: auto; padding: 10px 24px;">Import Credits</button>
                            </div>
                        </form>
                    <?php elseif ($id === 'directory'): ?>
                        <div class="panel-header">
                            <h2 class="panel-header-title">Admin Directory</h2>
                            <p class="panel-header-desc">View all admin accounts and their current session status.</p>
                        </div>
                        <div class="table-responsive">
                            <table class="queue-table">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th>IP Address</th>
                                        <th>User Agent</th>
                                        <th>Last Active</th>
                                        <th style="text-align: center;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="empty-queue-row">No admin accounts found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php elseif ($id === 'create'): ?>
                        <div class="panel-header">
                            <h2 class="panel-header-title">Create Admin</h2>
                            <p class="panel-header-desc">Register a new admin account for the portal.</p>
                        </div>
                        <form class="compose-form" method="POST" action="#" onsubmit="event.preventDefault();" novalidate>
                            <div class="form-group">
                                <div class="field-label-pill">Username</div>
                                <input type="text" class="form-field-input" placeholder="Enter admin username" required />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Password</div>
                                <input type="password" class="form-field-input" placeholder="Enter password" required />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Confirm Password</div>
                                <input type="password" class="form-field-input" placeholder="Re-enter password" required />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Role</div>
                                <select class="form-field-input" required>
                                    <option value="" disabled selected>Select a role</option>
                                    <option value="admin">Admin</option>
                                    <option value="superadmin">Super Admin</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="send-sms-btn">Create Account</button>
                            </div>
                        </form>
                    <?php elseif ($id === 'credentials'): ?>
                        <div class="panel-header">
                            <h2 class="panel-header-title">Reset Credentials</h2>
                            <p class="panel-header-desc">Reset the password for an existing admin account.</p>
                        </div>
                        <form class="compose-form" method="POST" action="#" onsubmit="event.preventDefault();" novalidate>
                            <div class="form-group">
                                <div class="field-label-pill">Admin Account</div>
                                <select class="form-field-input" required>
                                    <option value="" disabled selected>Select an admin</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">New Password</div>
                                <input type="password" class="form-field-input" placeholder="Enter new password" required />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Confirm New Password</div>
                                <input type="password" class="form-field-input" placeholder="Re-enter new password" required />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="send-sms-btn">Reset Password</button>
                            </div>
                        </form>
                    <?php elseif ($id === 'site'): ?>
                        <div class="panel-header" style="display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 1px solid var(--hp-border); padding-bottom: 16px; margin-bottom: 24px;">
                            <div>
                                <h2 class="panel-header-title">Manage site</h2>
                                <p class="panel-header-desc">Site configuration and Information page settings.</p>
                            </div>
                            <div style="display: flex; gap: 24px; color: var(--hp-text-secondary); font-size: 14px;">
                                <span style="color: var(--hp-primary); border-bottom: 2px solid var(--hp-primary); padding-bottom: 16px; margin-bottom: -17px; cursor: pointer;">Site configuration</span>
                                <span style="cursor: pointer;">Information page</span>
                            </div>
                        </div>

                        <form class="compose-form" method="POST" action="#" onsubmit="event.preventDefault();" novalidate style="max-width: 800px;">
                            <h3 style="font-size: 16px; color: var(--hp-text); margin-bottom: 16px; font-weight: 500;">Site configuration</h3>
                            
                            <?php
                            $renderRow = function($label, $inputHtml, $hasTooltip = false) {
                                $tooltip = $hasTooltip ? '<svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" style="margin-left:6px; opacity:0.5; cursor:help;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>' : '';
                                echo '<div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--hp-border-strong);">';
                                echo '<div style="font-size: 13px; color: var(--hp-text-secondary); flex: 1; display:flex; align-items:center;">' . $label . $tooltip . '</div>';
                                echo '<div style="flex: 2;">' . $inputHtml . '</div>';
                                echo '</div>';
                            };
                            ?>

                            <?php 
                            $textInput = '<input type="text" class="form-field-input" style="padding: 8px 12px; height: auto;" />';
                            $noYesHtml = '<select class="form-field-input" style="padding: 8px 12px; height: auto;"><option>no</option><option>yes</option></select>';
                            $defaultHtml = '<select class="form-field-input" style="padding: 8px 12px; height: auto;"><option>default</option></select>';

                            $renderRow('Domain', $textInput, true);
                            $renderRow('Website title', $textInput);
                            $renderRow('Email service', $textInput);
                            $renderRow('Email footer', $textInput);
                            $renderRow('Main website name', $textInput);
                            $renderRow('Main website URL', $textInput);
                            $renderRow('Active themes', $defaultHtml);
                            $renderRow('Default credit upon registration', $textInput);
                            $renderRow('Enable public registration', $noYesHtml);
                            $renderRow('Enable forgot password', $noYesHtml);
                            $renderRow('Default language', '<select class="form-field-input" style="padding: 8px 12px; height: auto;"><option>English</option></select>');
                            $renderRow('Enable logo', $noYesHtml, true);
                            $renderRow('Replace website title with logo', $noYesHtml);
                            $renderRow('Logo URL', $textInput);
                            $renderRow('Layout/theme', $defaultHtml);
                            ?>

                            <div class="form-actions" style="margin-top: 24px; text-align: right;">
                                <button type="submit" class="send-sms-btn" style="width: auto; padding: 10px 24px;">Save Configuration</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="panel-header">
                            <h2 class="panel-header-title"><?php echo htmlspecialchars($tab['title']); ?></h2>
                            <p class="panel-header-desc"><?php echo htmlspecialchars($tab['desc']); ?></p>
                        </div>
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                </svg>
                            </div>
                            <p class="empty-state-title">Configuration coming soon</p>
                            <p class="empty-state-desc"><?php echo htmlspecialchars($tab['desc']); ?> This section is under development.</p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php $first = false; endforeach; ?>
            </main>
        </div>
    </div>

    <script src="../js/theme.js"></script>
    <script src="settings.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectAll = document.getElementById('selectAllTransactions');
            if (selectAll) {
                selectAll.addEventListener('change', (e) => {
                    const checkboxes = document.querySelectorAll('.transaction-row-checkbox');
                    checkboxes.forEach(cb => cb.checked = e.target.checked);
                });
            }
        });
    </script>
</body>
</html>
