<?php
session_start();

// Check if development preview mode is requested
$previewMode = isset($_GET['preview']) && $_GET['preview'] === 'true';

// Guard portal access – redirect if user is not logged in (bypassed in preview mode)
if (!isset($_SESSION['user_id']) && !$previewMode) {
    header('Location: ../HelportLogin/login.php');
    exit();
}

$username = $previewMode ? 'Developer' : ($_SESSION['username'] ?? 'User');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Helport My Account | Compose Message Page" />
    <title>Helport | My Account</title>
    <link rel="icon" href="../Asset/favicon.svg" type="image/svg+xml" />
    <link rel="icon" href="../Asset/favicon.png" type="image/png" sizes="32x32" />
    <link rel="apple-touch-icon" href="../Asset/apple-touch-icon.png" />
    <?php include __DIR__ . '/../includes/theme-head.php'; ?>
    <link rel="stylesheet" href="myaccount.css?v=6" />
</head>
<body>

    <div class="account-wrapper">
        <!-- Immersive background and overlay -->
        <div class="bg-layer" aria-hidden="true"></div>

        <!-- Top Navigation / Header -->
        <?php
        $portalPage  = 'myaccount';
        $portalTitle = 'My Account';
        $portalSvgIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
        include __DIR__ . '/../includes/header.php';
        ?>

        <!-- Main Account Grid Portal -->
        <div class="main-portal-container">
            <?php
            $sidebarSections = [
                'Messaging' => [
                    ['tab' => 'compose',    'label' => 'Compose Message',  'active' => true],
                    ['tab' => 'inbox',      'label' => 'Inbox'],
                    ['tab' => 'schedule',   'label' => 'Schedule Message'],
                    ['tab' => 'send-file',  'label' => 'Send From File'],
                    ['tab' => 'template',   'label' => 'Message Template'],
                ],
                'Configuration' => [
                    ['tab' => 'preferences',  'label' => 'Preferences'],
                    ['tab' => 'user-config', 'label' => 'User Configuration'],
                ],
            ];
            include __DIR__ . '/../includes/portal-sidebar.php';
            ?>

            <!-- Right Form Section -->
            <main class="form-panel" role="main">
                
                <!-- Compose Message Tab -->
                <div id="tab-compose" class="tab-content active">
                    <div class="panel-header">
                        <h2 class="panel-header-title">Compose Message</h2>
                        <p class="panel-header-desc">Send SMS to one or multiple recipients instantly.</p>
                    </div>
                    <form id="composeSmsForm" class="compose-form" method="POST" action="#" onsubmit="event.preventDefault();" novalidate>
                        <!-- Recipients (Manual or Bulk) -->
                        <div class="form-group">
                            <div class="field-label-pill">Recipients</div>
                            <div style="display: flex; gap: 16px; margin-bottom: 12px; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 200px;">
                                    <label style="font-size: 13px; color: var(--hp-text-secondary); margin-bottom: 6px; display: block;">Manual Entry</label>
                                    <input 
                                        type="text" 
                                        id="mobileNumber" 
                                        name="mobile_number" 
                                        class="form-field-input" 
                                        placeholder="e.g 9519366943 (comma separated)"
                                    />
                                </div>
                                <div style="flex: 1; min-width: 200px;">
                                    <label style="font-size: 13px; color: var(--hp-text-secondary); margin-bottom: 6px; display: block;">Or Upload Bulk CSV</label>
                                    <input 
                                        type="file" 
                                        id="csvUpload" 
                                        name="csv_file" 
                                        accept=".csv"
                                        class="form-field-input" 
                                        style="padding: 8px;"
                                    />
                                </div>
                            </div>
                            <p style="font-size: 12px; color: var(--hp-text-secondary); margin: 0; line-height: 1.4;">
                                <strong>CSV Format Guideline:</strong> The file must contain a single column with a header named <strong>MobileNumber</strong>. Each row below the header should contain one valid mobile number.
                            </p>
                        </div>

                        <!-- Sender ID / Name -->
                        <div class="form-group">
                            <div class="field-label-pill">Sender ID / Name</div>
                            <input 
                                type="text" 
                                id="senderId" 
                                name="sender_id" 
                                class="form-field-input" 
                                value="HELPORT"
                                required
                            />
                        </div>

                        <!-- Select a Template -->
                        <div class="form-group">
                            <div class="field-label-pill">Select a Template</div>
                            <select 
                                id="messageContent" 
                                name="message_content" 
                                class="form-field-input" 
                                required
                            >
                                <option value="" disabled selected>-- Choose a pre-approved template --</option>
                                <option value="Payment Reminder: Your account has an overdue amount of Php [Amount].">Payment Reminder</option>
                                <option value="Welcome Message: Welcome to our service, [Name]!">Welcome Message</option>
                                <option value="Promo Alert: Get 50% off on your next purchase using code [Code].">Promo Alert</option>
                            </select>
                            
                            <div class="textarea-footer" style="margin-top: 12px; justify-content: flex-end;">
                                <button type="submit" id="sendSmsBtn" class="send-sms-btn">Send SMS</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Inbox Tab -->
                <div id="tab-inbox" class="tab-content">
                    <div class="panel-header">
                        <h2 class="panel-header-title">Inbox</h2>
                        <p class="panel-header-desc">View and reply to incoming messages.</p>
                    </div>
                    <div class="inbox-container">
                        <div class="inbox-list-col">
                            <div class="search-bar-wrap">
                                <svg class="search-icon" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="7"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <input type="text" id="inboxSearchInput" class="inbox-search-input" placeholder="Search sender or text..." />
                            </div>

                            <div class="filter-pills-wrap">
                                <button type="button" class="filter-pill active" data-filter="all">All</button>
                                <button type="button" class="filter-pill" data-filter="unread">Unread</button>
                                <button type="button" class="filter-pill" data-filter="replied">Replied</button>
                            </div>

                            <div class="inbox-list-scroll" id="inboxListScroll">
                                <!-- Conversation items rendered here by JS -->
                            </div>
                        </div>

                        <div class="inbox-chat-col" id="inboxChatCol">
                            <p class="inbox-placeholder-text">Select a conversation to view messages.</p>
                        </div>
                    </div>
                </div>

                <!-- Schedule Message Tab -->
                <div id="tab-schedule" class="tab-content">
                    <div class="panel-header">
                        <h2 class="panel-header-title">Schedule Message</h2>
                        <p class="panel-header-desc">Set a date and time to send messages automatically.</p>
                    </div>
                    <form id="scheduleSmsForm" class="compose-form" method="POST" action="#" onsubmit="event.preventDefault();" novalidate>
                        <!-- Recipients (Manual or Bulk) -->
                        <div class="form-group">
                            <div class="field-label-pill">Recipients</div>
                            <div style="display: flex; gap: 16px; margin-bottom: 12px; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 200px;">
                                    <label style="font-size: 13px; color: var(--hp-text-secondary); margin-bottom: 6px; display: block;">Manual Entry</label>
                                    <input 
                                        type="text" 
                                        id="scheduleMobileNumber" 
                                        name="mobile_number" 
                                        class="form-field-input" 
                                        placeholder="e.g 9519366943 (comma separated)"
                                    />
                                </div>
                                <div style="flex: 1; min-width: 200px;">
                                    <label style="font-size: 13px; color: var(--hp-text-secondary); margin-bottom: 6px; display: block;">Or Upload Bulk CSV</label>
                                    <input 
                                        type="file" 
                                        id="scheduleCsvUpload" 
                                        name="csv_file" 
                                        accept=".csv"
                                        class="form-field-input" 
                                        style="padding: 8px;"
                                    />
                                </div>
                            </div>
                            <p style="font-size: 12px; color: var(--hp-text-secondary); margin: 0; line-height: 1.4;">
                                <strong>CSV Format Guideline:</strong> The file must contain a single column with a header named <strong>MobileNumber</strong>. Each row below the header should contain one valid mobile number.
                            </p>
                        </div>

                        <!-- Sender ID / Name -->
                        <div class="form-group">
                            <div class="field-label-pill">Sender ID / Name</div>
                            <input 
                                type="text" 
                                id="scheduleSenderId" 
                                name="sender_id" 
                                class="form-field-input" 
                                value="HELPORT"
                                required
                            />
                        </div>

                        <!-- Schedule Date & Time -->
                        <div class="form-group">
                            <div class="field-label-pill">Schedule Date & Time</div>
                            <input 
                                type="datetime-local" 
                                id="scheduleDateTime" 
                                name="schedule_date_time" 
                                class="form-field-input" 
                                required
                            />
                        </div>

                        <!-- Select a Template -->
                        <div class="form-group">
                            <div class="field-label-pill">Select a Template</div>
                            <select 
                                id="scheduleMessageContent" 
                                name="message_content" 
                                class="form-field-input" 
                                required
                            >
                                <option value="" disabled selected>-- Choose a pre-approved template --</option>
                                <option value="Payment Reminder: Your account has an overdue amount of Php [Amount].">Payment Reminder</option>
                                <option value="Welcome Message: Welcome to our service, [Name]!">Welcome Message</option>
                                <option value="Promo Alert: Get 50% off on your next purchase using code [Code].">Promo Alert</option>
                            </select>
                            
                            <div class="textarea-footer" style="margin-top: 12px; justify-content: flex-end;">
                                <button type="submit" id="scheduleSmsBtn" class="send-sms-btn">Schedule SMS</button>
                            </div>
                        </div>
                    </form>

                    <!-- Scheduled Messages Queue -->
                    <div class="scheduled-queue-container">
                        <h2 class="queue-heading">Scheduled Messages Queue</h2>
                        <div class="table-responsive">
                            <table class="queue-table">
                                <thead>
                                    <tr>
                                        <th>Destination</th>
                                        <th>Sender ID</th>
                                        <th>Message</th>
                                        <th>Schedule Time</th>
                                        <th style="text-align: center;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="scheduledQueueBody">
                                    <!-- Dynamic rows loaded from localStorage -->
                                    <tr>
                                        <td colspan="5" class="empty-queue-row">No scheduled messages found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Send From File Tab -->
                <div id="tab-send-file" class="tab-content">
                    <div class="panel-header">
                        <h2 class="panel-header-title">Send From File</h2>
                        <p class="panel-header-desc">Upload a CSV file to send bulk SMS messages.</p>
                    </div>

                    <div class="csv-upload-wrap">
                        <input
                            type="file"
                            id="csvFileInput"
                            class="csv-file-input-hidden"
                            accept=".csv,text/csv"
                            aria-label="Upload CSV file"
                        />
                        <button type="button" class="csv-upload-btn-only" id="csvUploadBtn">
                            Upload CSV File
                        </button>
                    </div>

                    <div style="margin-top: 32px; padding: 20px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px;">
                        <h3 style="font-size: 14px; color: var(--hp-text); margin-bottom: 12px;">Required CSV Format</h3>
                        <p style="font-size: 13px; color: var(--hp-text-secondary); margin-bottom: 16px;">
                            The uploaded CSV file must follow the standard format below to ensure proper message delivery and variable replacement:
                        </p>
                        <table class="queue-table" style="background: rgba(0,0,0,0.2);">
                            <thead>
                                <tr>
                                    <th>Mobile Number</th>
                                    <th>Template ID</th>
                                    <th>Var 1 (e.g. Name)</th>
                                    <th>Var 2 (e.g. Amount)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>09171234567</td>
                                    <td>PAYMENT_REM</td>
                                    <td>Juan Cruz</td>
                                    <td>2,340.00</td>
                                </tr>
                                <tr>
                                    <td>09181234567</td>
                                    <td>WELCOME_MSG</td>
                                    <td>Maria Santos</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                        <a href="#" style="display: inline-block; margin-top: 16px; font-size: 13px; color: #4ade80; text-decoration: none;">Download Sample CSV</a>
                    </div>
                </div>

                <!-- Message Template Tab -->
                <div id="tab-template" class="tab-content">
                    <div class="panel-header">
                        <h2 class="panel-header-title">Message Template</h2>
                        <p class="panel-header-desc">Manage and import SMS templates via CSV to populate dropdowns.</p>
                    </div>

                    <form class="compose-form" onsubmit="event.preventDefault();">
                        <div class="form-group">
                            <div class="field-label-pill">Template Name</div>
                            <input type="text" class="form-field-input" placeholder="e.g. Promo Campaign 1" />
                        </div>
                        <div class="form-group">
                            <div class="field-label-pill">Template Body</div>
                            <textarea class="form-field-textarea" placeholder="Hello {{name}}, here is your code: {{code}}"></textarea>
                        </div>
                        <div class="textarea-footer" style="margin-top: 12px; justify-content: flex-end;">
                            <button type="submit" class="send-sms-btn">Save Template</button>
                        </div>
                    </form>

                    <h3 style="margin: 32px 0 16px; font-size: 14px; color: var(--hp-text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Saved Templates</h3>
                    <table class="queue-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Preview</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Welcome Message</td>
                                <td>Welcome to our service, {{name}}!</td>
                                <td>
                                    <button class="send-sms-btn" style="background: rgba(255,255,255,0.1); width: auto; padding: 4px 12px; font-size: 12px; color: var(--hp-text);">Edit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Preferences Tab -->
                <div id="tab-preferences" class="tab-content">
                    <div class="panel-header">
                        <h2 class="panel-header-title">Preferences</h2>
                        <p class="panel-header-desc">Configure backend settings via built-in system dropdowns.</p>
                    </div>

                    <form class="compose-form" onsubmit="event.preventDefault();">
                        <h3 style="margin: 0 0 16px; font-size: 15px; color: var(--hp-primary); text-transform: uppercase; letter-spacing: 0.05em;">Login Information</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                            <div class="form-group">
                                <div class="field-label-pill">Username</div>
                                <input type="text" class="form-field-input" value="admin" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Name</div>
                                <input type="text" class="form-field-input" value="Administrator" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Password</div>
                                <input type="password" class="form-field-input" placeholder="" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Re-type password</div>
                                <input type="password" class="form-field-input" placeholder="" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Email</div>
                                <input type="email" class="form-field-input" value="support@openvox.cn" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Mobile</div>
                                <input type="text" class="form-field-input" value="+6200000000" />
                            </div>
                        </div>

                        <h3 style="margin: 0 0 16px; font-size: 15px; color: var(--hp-primary); text-transform: uppercase; letter-spacing: 0.05em;">Personal Information</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <div class="field-label-pill">Address</div>
                                <input type="text" class="form-field-input" placeholder="" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">City</div>
                                <input type="text" class="form-field-input" placeholder="" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">State or Province</div>
                                <input type="text" class="form-field-input" placeholder="" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Country</div>
                                <select class="form-field-input">
                                    <option value="China" selected>China</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="USA">United States</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Zipcode</div>
                                <input type="text" class="form-field-input" placeholder="" />
                            </div>
                        </div>

                        <div class="textarea-footer" style="margin-top: 24px; justify-content: flex-end;">
                            <button type="submit" class="send-sms-btn">Save</button>
                        </div>
                    </form>
                </div>

                <!-- User Configuration Tab -->
                <div id="tab-user-config" class="tab-content">
                    <div class="panel-header">
                        <h2 class="panel-header-title">User Configuration</h2>
                        <p class="panel-header-desc">Manage personal account profile settings and security.</p>
                    </div>

                    <form class="compose-form" onsubmit="event.preventDefault();">
                        <h3 style="margin: 0 0 16px; font-size: 15px; color: var(--hp-primary); text-transform: uppercase; letter-spacing: 0.05em;">Application Options</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div class="form-group">
                                <div class="field-label-pill">Username</div>
                                <input type="text" class="form-field-input" value="admin" readonly style="opacity: 0.7; cursor: not-allowed;" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Access Control List</div>
                                <select class="form-field-input">
                                    <option value="DEFAULT">DEFAULT</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Default sender ID</div>
                                <select class="form-field-input">
                                    <option value="None">None</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Default message footer</div>
                                <input type="text" class="form-field-input" placeholder="" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Credit</div>
                                <input type="text" class="form-field-input" value="10000098.6" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Enable credit unicode SMS as normal SMS</div>
                                <select class="form-field-input">
                                    <option value="yes">yes</option>
                                    <option value="no">no</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Webservices username</div>
                                <input type="text" class="form-field-input" value="admin" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Webservices token</div>
                                <input type="text" class="form-field-input" placeholder="" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Renew webservices token</div>
                                <select class="form-field-input">
                                    <option value="no">no</option>
                                    <option value="yes">yes</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Enable webservices</div>
                                <select class="form-field-input">
                                    <option value="no">no</option>
                                    <option value="yes">yes</option>
                                </select>
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <div class="field-label-pill">Webservices IP range</div>
                                <input type="text" class="form-field-input" value="127.0.0.1, 192.168.*.*" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Active language</div>
                                <select class="form-field-input">
                                    <option value="English (United States)">English (United States)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Timezone</div>
                                <input type="text" class="form-field-input" value="+0700" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Forward message to inbox</div>
                                <select class="form-field-input">
                                    <option value="yes">yes</option>
                                    <option value="no">no</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Forward message to email</div>
                                <select class="form-field-input">
                                    <option value="yes">yes</option>
                                    <option value="no">no</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Forward message to mobile</div>
                                <select class="form-field-input">
                                    <option value="no">no</option>
                                    <option value="yes">yes</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Local number length</div>
                                <input type="number" class="form-field-input" value="0" />
                            </div>
                            <div class="form-group">
                                <div class="field-label-pill">Prefix or country code</div>
                                <input type="text" class="form-field-input" placeholder="" />
                            </div>
                        </div>

                        <div class="textarea-footer" style="margin-top: 24px; justify-content: flex-end;">
                            <button type="submit" class="send-sms-btn">Save</button>
                        </div>
                    </form>
                </div>

            </main>

        </div>
    </div>

    <script src="../js/theme.js"></script>
    <script src="myaccount.js?v=4"></script>
</body>
</html>