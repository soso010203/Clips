<?php
// Erstellt die Datenbank + Tabelle accounts (einmalig ausführen, z.B. im Browser oder CLI)
// Achtung: führt CREATE/DROP/ALTER auf deiner lokalen DB aus.
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = 'root';
$dbName = 'clips_accounts';
$table  = 'accounts';

try {
    $pdo = new PDO("mysql:host={$dbHost};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // DB anlegen
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

    // Tabelle anlegen (falls nicht vorhanden)
    $pdo->exec("USE `{$dbName}`;");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `{$table}` (
          `id` INT AUTO_INCREMENT PRIMARY KEY,
          `email` VARCHAR(255) NOT NULL UNIQUE,
          `password` VARCHAR(255) NOT NULL,
          `firstname` VARCHAR(100) NOT NULL,
          `lastname` VARCHAR(100) NOT NULL,
          `bio` TEXT NULL,
          `role` ENUM('user','admin') NOT NULL DEFAULT 'user',
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    echo "Datenbank und Tabelle erfolgreich angelegt oder existieren bereits.";
} catch (PDOException $e) {
    echo "Fehler beim Anlegen: " . htmlspecialchars($e->getMessage());
    exit;
}
?>