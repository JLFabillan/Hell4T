<?php
session_start();

// Check if development preview mode is requested
$previewMode = isset($_GET['preview']) && $_GET['preview'] === 'true';

// Guard portal access – must be superadmin role or redirect (bypassed in preview mode)
if (!$previewMode) {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
        header('Location: ../login.php');
        exit();
    }
}

$username = $previewMode ? 'Developer' : ($_SESSION['username'] ?? 'SuperAdmin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Helport Super Admin | Admin Management" />
    <title>Helport | Admin Management</title>
    <link rel="icon" href="../../Asset/favicon.svg" type="image/svg+xml" />
    <link rel="icon" href="../../Asset/favicon.png" type="image/png" sizes="32x32" />
    <link rel="apple-touch-icon" href="../../Asset/apple-touch-icon.png" />
    <?php include __DIR__ . '/../../includes/theme-head.php'; ?>
    <link rel="stylesheet" href="../../myaccount/myaccount.css?v=6" />
</head>
<body>

    <div class="account-wrapper">
        <!-- Immersive background and overlay -->
        <div class="bg-layer" aria-hidden="true"></div>

        <!-- Top Navigation / Header -->
        <?php
        $portalPage  = 'admin_mgmt';
        $portalTitle = 'Admin Management';
        $portalSvgIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>';
        include __DIR__ . '/../../includes/header.php';
        ?>

        <!-- Main Account Grid Portal -->
        <div class="main-portal-container">
            <?php
            $sidebarSections = [
                'Admin Accounts' => [
                    ['tab' => 'directory',   'label' => 'Admin Directory',  'active' => true],
                    ['tab' => 'create',      'label' => 'Create Admin'],
                    ['tab' => 'credentials', 'label' => 'Reset Credentials'],
                ],
            ];
            include __DIR__ . '/../../includes/portal-sidebar.php';
            ?>

            <!-- Right Form Section -->
            <main class="form-panel" role="main">

                <!-- Admin Directory Tab -->
                <div id="tab-directory" class="tab-content active">
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
                            <tbody id="adminDirectoryBody">
                                <!-- Dynamic rows loaded via JS/API -->
                                <tr>
                                    <td colspan="6" class="empty-queue-row">No admin accounts found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Create Admin Tab -->
                <div id="tab-create" class="tab-content">
                    <div class="panel-header">
                        <h2 class="panel-header-title">Create Admin</h2>
                        <p class="panel-header-desc">Register a new admin account for the portal.</p>
                    </div>
                    <form id="createAdminForm" class="compose-form" method="POST" action="#" onsubmit="event.preventDefault();" novalidate>
                        <!-- Username -->
                        <div class="form-group">
                            <div class="field-label-pill">Username</div>
                            <input 
                                type="text" 
                                id="newAdminUsername" 
                                name="username" 
                                class="form-field-input" 
                                placeholder="Enter admin username"
                                required
                            />
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <div class="field-label-pill">Password</div>
                            <input 
                                type="password" 
                                id="newAdminPassword" 
                                name="password" 
                                class="form-field-input" 
                                placeholder="Enter password"
                                required
                            />
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <div class="field-label-pill">Confirm Password</div>
                            <input 
                                type="password" 
                                id="newAdminConfirmPassword" 
                                name="confirm_password" 
                                class="form-field-input" 
                                placeholder="Re-enter password"
                                required
                            />
                        </div>

                        <!-- Role -->
                        <div class="form-group">
                            <div class="field-label-pill">Role</div>
                            <select 
                                id="newAdminRole" 
                                name="role" 
                                class="form-field-input" 
                                required
                            >
                                <option value="" disabled selected>Select a role</option>
                                <option value="admin">Admin</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </div>

                        <!-- Submit -->
                        <div class="form-group">
                            <button type="submit" id="createAdminBtn" class="send-sms-btn">Create Account</button>
                        </div>
                    </form>
                </div>

                <!-- Reset Credentials Tab -->
                <div id="tab-credentials" class="tab-content">
                    <div class="panel-header">
                        <h2 class="panel-header-title">Reset Credentials</h2>
                        <p class="panel-header-desc">Reset the password for an existing admin account.</p>
                    </div>
                    <form id="resetCredentialsForm" class="compose-form" method="POST" action="#" onsubmit="event.preventDefault();" novalidate>
                        <!-- Admin Selector -->
                        <div class="form-group">
                            <div class="field-label-pill">Admin Account</div>
                            <select 
                                id="resetAdminSelect" 
                                name="admin_id" 
                                class="form-field-input" 
                                required
                            >
                                <option value="" disabled selected>Select an admin</option>
                                <!-- Options populated via JS/API -->
                            </select>
                        </div>

                        <!-- New Password -->
                        <div class="form-group">
                            <div class="field-label-pill">New Password</div>
                            <input 
                                type="password" 
                                id="resetNewPassword" 
                                name="new_password" 
                                class="form-field-input" 
                                placeholder="Enter new password"
                                required
                            />
                        </div>

                        <!-- Confirm New Password -->
                        <div class="form-group">
                            <div class="field-label-pill">Confirm New Password</div>
                            <input 
                                type="password" 
                                id="resetConfirmPassword" 
                                name="confirm_new_password" 
                                class="form-field-input" 
                                placeholder="Re-enter new password"
                                required
                            />
                        </div>

                        <!-- Submit -->
                        <div class="form-group">
                            <button type="submit" id="resetPasswordBtn" class="send-sms-btn">Reset Password</button>
                        </div>
                    </form>
                </div>

            </main>

        </div>
    </div>

    <script src="../../js/theme.js"></script>
    <script>
    /* ── Tab switching via sidebar clicks ── */
    document.querySelectorAll('.sidebar-link[data-tab]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            // Deactivate all sidebar links
            document.querySelectorAll('.sidebar-link').forEach(function (el) {
                el.classList.remove('active');
            });
            // Activate clicked link
            this.classList.add('active');

            // Hide all tab panels
            document.querySelectorAll('.tab-content').forEach(function (panel) {
                panel.classList.remove('active');
            });
            // Show target tab panel
            var targetTab = this.getAttribute('data-tab');
            var targetPanel = document.getElementById('tab-' + targetTab);
            if (targetPanel) {
                targetPanel.classList.add('active');
            }
        });
    });
    </script>
</body>
</html>
