<?php
/**
 * Portal sidebar – set $sidebarSections before include:
 *
 * $sidebarSections = [
 *     'Messaging' => [
 *         ['tab' => 'compose', 'label' => 'Compose Message', 'active' => true],
 *         ['tab' => 'inbox',  'label' => 'Inbox'],
 *     ],
 *     'Configuration' => [
 *         ['tab' => 'preferences', 'label' => 'Preferences'],
 *     ],
 * ];
 */
$sidebarSections = $sidebarSections ?? [];
$isFirstSection  = true;
?>
<aside class="sidebar-panel">
    <?php foreach ($sidebarSections as $sectionName => $items): ?>
    <?php
        $menuClass = 'sidebar-menu';
        if (!$isFirstSection) {
            $menuClass .= ' sidebar-menu-secondary';
        }
        $isFirstSection = false;
    ?>
    <p class="sidebar-section-label"><?php echo htmlspecialchars($sectionName); ?></p>
    <div class="<?php echo $menuClass; ?>">
        <?php foreach ($items as $item): ?>
        <a
            href="#"
            class="sidebar-link-btn<?php echo !empty($item['active']) ? ' active-item' : ''; ?>"
            data-tab="<?php echo htmlspecialchars($item['tab']); ?>"
            aria-label="<?php echo htmlspecialchars($item['label']); ?>"
        ><?php echo htmlspecialchars($item['label']); ?></a>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</aside>
