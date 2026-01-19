<?php
session_start();
require_once __DIR__ . '/../config/db.php'; 

// search for the current post id
$postId = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT); 


//delete the post with the current post id
$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
$stmt->execute([$postId]);

//go back to the profil page, to see that the post is deleted
header("Location: ../profile.php");
exit;
