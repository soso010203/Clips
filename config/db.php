<?php
// db.php
$dbHost = 'localhost';
$dbUser = 'user';
$dbPass = 'jngwZ6tsl3toM_cb';  
$dbName = 'clips_accounts';

try {
    $pdo = new PDO(
        "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("DB-Fehler: " . $e->getMessage());
}
