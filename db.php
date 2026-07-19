<?php
/* ============================================
   Helport — Global Database Connection
   Single source of truth for all modules.
   Usage in any module:
       require_once __DIR__ . '/../db.php';
   ============================================ */

$host     = 'localhost';
$username = 'root';
$password = ''; // XAMPP default (no password)
$database = 'helport_db';
$charset  = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$database;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'status'  => 'error',
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit;
}
