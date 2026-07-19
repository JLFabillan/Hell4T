<?php
/**
 * Shared header for portal pages.
 * Determines navigation items based on user role (admin or superadmin).
 *
 * The calling page MUST call session_start() before including this file.
 * Set $portalPage, $portalTitle, $portalIcon before including.
 */
$role = $_SESSION['role'] ?? 'admin';

// Build modules array based on role
$modules = [
    'myaccount' => [
        'href'  => '/myaccount/index.php',
        'label' => 'My Account',
        'svg_icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    ],
    'reports' => [
        'href'  => '/reports/index.php',
        'label' => 'Reports',
        'svg_icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>',
    ],
    'settings' => [
        'href'  => '/settings/index.php',
        'label' => 'Settings',
        'svg_icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
    ],
];

// Super admin gets additional module links
if ($role === 'superadmin') {
    $modules['system_reports'] = [
        'href'  => '/superadmin/reports/index.php',
        'label' => 'System Reports',
        'svg_icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>',
    ];
    $modules['message_logs'] = [
        'href'  => '/superadmin/message-logs/index.php',
        'label' => 'Message Logs',
        'svg_icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
    ];
}

$portalPage  = $portalPage  ?? 'myaccount';
$portalTitle = $portalTitle ?? 'My Account';
$portalIcon  = $portalIcon  ?? '/Asset/HomepageAsset/MyAccountIcon.png';
?>
<header class="top-header">
    <div class="header-left">
        <img src="/Asset/HomepageAsset/HomepageLogo.png" alt="Helport" class="header-home-logo" />
    </div>

    <nav class="header-center" aria-label="Portal modules">
        <div class="header-module-nav">
            <?php foreach ($modules as $key => $mod): ?>
            <a
                href="<?php echo htmlspecialchars($mod['href']); ?>"
                class="header-nav-item<?php echo $portalPage === $key ? ' active' : ''; ?>"
                title="<?php echo htmlspecialchars($mod['label']); ?>"
                <?php echo $portalPage === $key ? 'aria-current="page"' : ''; ?>
            >
                <?php if (isset($mod['svg_icon'])): ?>
                    <span class="header-nav-svg-icon" aria-hidden="true"><?php echo $mod['svg_icon']; ?></span>
                <?php else: ?>
                    <img src="<?php echo htmlspecialchars($mod['icon']); ?>" alt="" class="header-nav-icon" aria-hidden="true" />
                <?php endif; ?>
                <span class="header-nav-label"><?php echo htmlspecialchars($mod['label']); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </nav>

    <div class="header-right">
        <?php include __DIR__ . '/theme-toggle.php'; ?>
        <a href="/HelportLogin/logout.php" class="portal-home-close" title="Log out" aria-label="Log out" style="color: #fca5a5;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </a>
    </div>
</header>
