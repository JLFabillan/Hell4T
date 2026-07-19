<?php
/**
 * Inline theme bootstrap – prevents flash of wrong theme.
 */
?>
<script>
(function(){var k='helport-theme',s=localStorage.getItem(k),t=s==='light'||s==='dark'?s:(window.matchMedia('(prefers-color-scheme: light)').matches?'light':'dark');document.documentElement.setAttribute('data-theme',t);})();
</script>
