<?php
/**
 * forgot_password.php – Helport Forgot Password Page
 * Renders the forgot-password form and handles the token email dispatch.
 */
session_start();

// ── Universal DB connection (available on every request) ────────
require_once __DIR__ . '/../db.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard/index.php');
    exit();
}

$message = '';
$message_type = ''; // 'success' | 'error'

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message      = 'Please enter a valid email address.';
        $message_type = 'error';
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Always show success to prevent email enumeration
        $message      = 'If that email is registered, a password reset link has been sent.';
        $message_type = 'success';

        if ($user) {
            $token   = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Store token in DB (assumes password_resets table exists)
            $ins = $pdo->prepare(
                "INSERT INTO password_resets (user_id, token, expires_at)
                 VALUES (?, ?, ?)
                 ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at)"
            );
            $ins->execute([$user['id'], $token, $expires]);

            // TODO: Send reset email via your mailer (PHPMailer / SMTP)
            // mail($email, 'Helport Password Reset', "Reset link: https://yourdomain.com/HelportLogin/reset_password.php?token=$token");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Helport – Reset your password." />
    <title>Helport | Forgot Password</title>
    <link rel="icon" href="../Asset/favicon.svg" type="image/svg+xml" />
    <link rel="icon" href="../Asset/favicon.png" type="image/png" sizes="32x32" />
    <link rel="apple-touch-icon" href="../Asset/apple-touch-icon.png" />
    <?php include __DIR__ . '/../includes/theme-head.php'; ?>
    <link rel="stylesheet" href="login.css?v=6" />
    <style>
        .field-input.no-icon { padding-left: 14px; }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 20px;
            font-size: 13px;
            font-weight: 500;
            color: var(--hp-text-secondary);
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .back-link:hover { color: var(--hp-primary); }
        .back-link svg { width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2; }

        .fp-title {
            color: var(--hp-text);
            font-size: 18px;
            font-weight: 600;
            letter-spacing: -0.01em;
            margin-bottom: 6px;
            text-align: center;
        }
        .fp-subtitle {
            color: var(--hp-text-muted);
            font-size: 13px;
            font-weight: 400;
            text-align: center;
            margin-bottom: 24px;
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.5;
        }

        .success-banner {
            width: 100%;
            margin-bottom: 18px;
            padding: 11px 14px;
            background: var(--hp-success-bg);
            border: 1px solid var(--hp-primary-border);
            border-radius: var(--hp-radius-sm);
            color: var(--hp-success);
            font-size: 13px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="login-float-actions">
        <?php include __DIR__ . '/../includes/theme-toggle.php'; ?>
    </div>

    <div class="login-wrapper">
        <div class="bg-layer" aria-hidden="true"></div>

        <main class="login-card" role="main">

            <div class="logo-wrap">
                <img src="../Asset/HelportLoginAsset/HelportLoginLogo.png" alt="Helport Logo" class="logo-img" />
            </div>

            <div class="card-header">
                <h1 class="card-header-title">Forgot Password</h1>
                <p class="card-header-sub">Enter your email and we'll send you a reset link.</p>
            </div>

            <!-- Server Message -->
            <?php if (!empty($message)): ?>
            <div class="<?php echo $message_type === 'success' ? 'success-banner' : 'error-banner'; ?>" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>

            <!-- Form -->
            <form id="fpForm" class="login-form" method="POST" action="forgot_password.php" novalidate>
                <div class="field-wrap">
                    <label for="fpEmail" class="field-label">Email Address</label>
                    <div class="input-icon-wrap">
                        <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input
                            type="email"
                            id="fpEmail"
                            name="email"
                            class="field-input"
                            placeholder="Enter your registered email"
                            autocomplete="email"
                            required
                        />
                    </div>
                    <span class="field-error" id="fpEmailError" aria-live="polite"></span>
                </div>

                <button type="submit" id="fpBtn" class="login-btn" aria-label="Send Reset Link">
                    <span class="btn-text">Send Reset Link</span>
                    <span class="btn-spinner" aria-hidden="true"></span>
                </button>
            </form>

            <footer class="login-footer">
                <a href="login.php" class="forgot-link">← Back to Sign In</a>
            </footer>

        </main>
    </div>

    <script src="../js/theme.js"></script>
    <script src="forgot_password.js"></script>
</body>
</html>
