<?php
session_start();

require_once __DIR__ . '/../db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ../myaccount/index.php');
    exit();
}

$error          = '';
$username_value = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $username_value = htmlspecialchars($username);

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $pdo->prepare(
            "SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1"
        );
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'] ?? 'admin';
            header('Location: ../myaccount/index.php');
            exit();
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Helport Login – Empower Every One to Work as an Expert." />
    <title>Helport | Sign In</title>
    <link rel="icon" href="../Asset/favicon.svg" type="image/svg+xml" />
    <link rel="icon" href="../Asset/favicon.png" type="image/png" sizes="32x32" />
    <link rel="apple-touch-icon" href="../Asset/apple-touch-icon.png" />
    <?php include __DIR__ . '/../includes/theme-head.php'; ?>
    <link rel="stylesheet" href="login.css?v=6" />
</head>
<body>

    <div class="login-float-actions">
        <?php include __DIR__ . '/../includes/theme-toggle.php'; ?>
    </div>

    <div class="login-wrapper">
        <div class="bg-layer" aria-hidden="true"></div>

        <main class="login-card" role="main">
            <a href="../index.php" class="login-close-btn" title="Back to Home" aria-label="Back to Home">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>

            <div class="logo-wrap">
                <img
                    src="../Asset/HelportLoginAsset/HelportLoginLogo.png"
                    alt="Helport Logo"
                    class="logo-img"
                />
                <p class="tagline">Empower Every One to Work as an Expert</p>
            </div>

            <div class="card-header">
                <h1 class="card-header-title">Welcome back</h1>
                <p class="card-header-sub">Sign in to your Helport account</p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="error-banner" role="alert" id="errorBanner">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <form id="loginForm" class="login-form" method="POST" action="login.php" novalidate>

                <div class="field-wrap">
                    <label for="username" class="field-label">Username</label>
                    <div class="input-icon-wrap">
                        <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="field-input"
                            placeholder="Enter your username"
                            value="<?php echo $username_value; ?>"
                            autocomplete="username"
                            required
                        />
                    </div>
                    <span class="field-error" id="usernameError" aria-live="polite"></span>
                </div>

                <div class="field-wrap">
                    <label for="password" class="field-label">Password</label>
                    <div class="input-icon-wrap">
                        <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="field-input"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        />
                    </div>
                    <span class="field-error" id="passwordError" aria-live="polite"></span>
                </div>

                <div class="forgot-wrap">
                    <a href="forgot_password.php" class="forgot-link" id="forgotPasswordLink">
                        Forgot Password?
                    </a>
                </div>

                <button type="submit" id="loginBtn" class="login-btn" aria-label="Login">
                    <span class="btn-text">Sign In</span>
                    <span class="btn-spinner" aria-hidden="true"></span>
                </button>

            </form>

            <footer class="login-footer">
                &copy; <?php echo date('Y'); ?> Helport. All rights reserved.
            </footer>

        </main>
    </div>

    <script src="../js/theme.js"></script>
    <script src="login.js"></script>
</body>
</html>
