<?php
/** Theme scripts – set $themeBase before include. */
$themeBase = isset($themeBase) ? rtrim($themeBase, '/') : '';
$themePrefix = $themeBase === '' ? '' : $themeBase . '/';
?>
<script src="<?php echo htmlspecialchars($themePrefix); ?>js/theme.js"></script>
