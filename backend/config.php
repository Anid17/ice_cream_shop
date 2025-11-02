<?php
// backend/config.php
declare(strict_types=1);

$envFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
if (!file_exists($envFile)) {
    throw new RuntimeException(".env file not found at project root.");
}

$ini = parse_ini_file($envFile, true);
if (!$ini || !isset($ini['database'])) {
    throw new RuntimeException("Invalid .env format. Missing [database] section.");
}

$db = $ini['database'];
$host = $db['host'] ?? 'localhost';
$name = $db['name'] ?? '';
$user = $db['user'] ?? '';
$pass = $db['pass'] ?? '';

$dsn = "mysql:host={$host};dbname={$name};charset=utf8mb4";

$pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
]);

return $pdo;
