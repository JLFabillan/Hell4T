<?php
require_once 'c:/xampp/htdocs/db.php';

$password = 'Admin123';
$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username IN ('Admin', 'SuperAdmin')");
$stmt->execute([$hashed]);

echo "Passwords reset successfully.\n";
?>
