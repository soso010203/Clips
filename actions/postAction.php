<!-- User Story number 2 -->
 
<?php

// filters the id after the ? in the URL 
$postId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$stmt = $pdo->prepare("SELECT posts.*, accounts.username 
                       FROM posts 
                       JOIN accounts ON posts.user_id = accounts.id
                       WHERE posts.id = ?");

$stmt->execute([$postId]);
$post = $stmt->fetch();

$currentUserId = $_SESSION['user']['id'] ?? null;
$isOwner = ($currentUserId && $currentUserId == $post['user_id']);

?>