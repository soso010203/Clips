<!-- User Story number 3 -->
 
<?php

$userId = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);

// Username from the post i clicked on and who posted the picture
$userStmt = $pdo->prepare("SELECT username FROM accounts WHERE id = ?");
$userStmt->execute([$userId]);
$user = $userStmt->fetch();
$username = $user['username'];

// all posts from the user who posted the posts
$postsStmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$postsStmt->execute([$userId]);
$posts = $postsStmt->fetchAll();
?>