<?php
// Unified dashboard handles both Admin and SuperAdmin roles perfectly.
// Redirecting to prevent showing the old, deprecated layout.
header('Location: /dashboard/index.php');
exit();
