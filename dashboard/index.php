<?php
session_start();

$previewMode = isset($_GET['preview']) && $_GET['preview'] === 'true';

$isLoggedIn = isset($_SESSION['user_id']) || $previewMode;

if ($isLoggedIn) {
    header('Location: /myaccount/index.php');
    exit();
}

$username = $previewMode ? 'Developer' : ($_SESSION['username'] ?? 'User');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Helport Homepage - Empower Every One to Work as an Expert." />
    <title>Helport | Home</title>
    <link rel="icon" href="../Asset/favicon.svg" type="image/svg+xml" />
    <link rel="icon" href="../Asset/favicon.png" type="image/png" sizes="32x32" />
    <link rel="apple-touch-icon" href="../Asset/apple-touch-icon.png" />
    <?php include __DIR__ . '/../includes/theme-head.php'; ?>
    <link rel="stylesheet" href="dashboard.css?v=9" />
    <link rel="stylesheet" href="../superadmin/superadmin.css?v=1" />
    <style>
        .landing-btn {
            display: inline-block;
            padding: 12px 36px;
            font-size: 14px;
            text-decoration: none;
            font-weight: 700;
            background: linear-gradient(135deg, #0d9373, #2ee8b7, #00d4aa);
            color: #0a1a12;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            letter-spacing: 0.02em;
            box-shadow: 0 2px 14px rgba(46, 232, 183, 0.3);
            transition: all 0.3s ease;
            position: relative;
        }
        .landing-btn:hover {
            box-shadow: 0 0 20px rgba(46, 232, 183, 0.5), 0 0 40px rgba(46, 232, 183, 0.25), 0 0 60px rgba(46, 232, 183, 0.1);
            transform: translateY(-2px);
            background: linear-gradient(135deg, #10b888, #3df5c8, #00e8bb);
        }
        .landing-btn:active {
            transform: scale(0.92);
            box-shadow: 0 0 10px rgba(46, 232, 183, 0.4);
            transition: all 0.08s ease;
        }
    </style>
</head>
<body>

    <div class="homepage-wrapper">
        <div class="bg-layer" aria-hidden="true"></div>

        <div class="home-ambient" aria-hidden="true">
            <span class="home-orb home-orb--green"></span>
            <span class="home-orb home-orb--cyan"></span>
            <span class="home-orb home-orb--center"></span>
        </div>

        <div class="home-float-actions">
            <?php include __DIR__ . '/../includes/theme-toggle.php'; ?>
        </div>

        <main class="home-main" role="main">
            <section class="home-hero">
                <div class="home-brand-wrap">
                    <div class="home-brand">
                        <div class="home-brand-glow" aria-hidden="true"></div>
                        <img
                            src="../Asset/HomepageAsset/HomepageLogo.png"
                            alt="Helport"
                            class="home-logo"
                        />
                        <div class="home-brand-divider" aria-hidden="true">
                            <span class="home-brand-divider-line"></span>
                            <span class="home-brand-divider-dot"></span>
                            <span class="home-brand-divider-line"></span>
                        </div>
                        <p class="home-tagline">
                            Empower Every One to Work as an <span class="home-tagline-accent">Expert</span>
                        </p>
                    </div>
                </div>
            </section>

            <?php if (!$isLoggedIn): ?>
            <div style="text-align: center; margin-top: 48px; position: relative; z-index: 10;">
                <a href="../HelportLogin/login.php" class="landing-btn">Get Started</a>
            </div>
            <?php endif; ?>
        </main>

        <footer class="site-footer home-footer">
            &copy; <?php echo date('Y'); ?> Helport &mdash; Empower Every One to Work as an Expert
        </footer>
    </div>

    <script src="../js/theme.js"></script>
</body>
</html>
