<?php

$userId = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);

// User-Daten abrufen
$userStmt = $pdo->prepare("SELECT username FROM accounts WHERE id = ?");
$userStmt->execute([$userId]);
$user = $userStmt->fetch();
if (!$user) {
    die("User nicht gefunden.");
}
$username = $user['username'];

// Alle Posts dieses Users abrufen
$postsStmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$postsStmt->execute([$userId]);
$posts = $postsStmt->fetchAll();
?>