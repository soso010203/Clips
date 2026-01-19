<!-- User Story number 7 -->
 
<?php

$displayFirstName = $_SESSION['user']['firstname'] ?? '';
$displayLastName  = $_SESSION['user']['lastname'] ?? '';
$username         = $_SESSION['user']['username'] ?? '';

$created_at = $_SESSION['user']['created_at'] ?? null;

$user_id = $_SESSION['user']['id'] ?? null;


$posts = [];

if ($user_id) 
{
    
        $stmt = $pdo->prepare
        ("  SELECT id, text, file_path, created_at
            FROM posts
            WHERE user_id = :user_id
            ORDER BY created_at DESC
        ");

        $stmt->execute(['user_id' => $user_id]);
        // every post from the database from the current userid is saved in the posts array
        $posts = $stmt->fetchAll();
   
}
?>