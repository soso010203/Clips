<?php

$dbHost = 'localhost';  //myadminphp 
$dbUser = 'user';
$dbPass = 'jngwZ6tsl3toM_cb'; 
$dbName = 'clips_accounts'; //Database name 
$table  = 'posts'; //table of the database

try {
    $pdo = new PDO(
        "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

    // creates a table "posts", if the table doesnt't already exists
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            text TEXT NOT NULL,
            file_path VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES accounts(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    echo "Tabelle posts erfolgreich angelegt oder existiert bereits.";

} catch (PDOException $e) {
    die("DB-Fehler: " . $e->getMessage());
}
?>
