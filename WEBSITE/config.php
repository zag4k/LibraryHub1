<?php
// config.php
// Use Railway environment variables if available, otherwise use local defaults
$DB_HOST = getenv('MYSQL_HOST') ?: '127.0.0.1';
$DB_NAME = getenv('MYSQL_DATABASE') ?: 'libraryhub';
$DB_USER = getenv('MYSQL_USER') ?: 'root';
$DB_PASS = getenv('MYSQL_PASSWORD') ?: '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Database connection failed: ' . htmlspecialchars($e->getMessage());
    exit;
}
