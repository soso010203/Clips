<!--User Story Number 1 -->

<?php
$currentUserId = $_SESSION['user']['id'] ?? null; 

// If a user is logged in, their own posts shouldn't be displayed on the homepage
if ($currentUserId) 
{
    $stmt = $pdo->prepare //protection from SQL-injections
    ("  SELECT *
        FROM posts
        WHERE user_id != ?
        ORDER BY created_at DESC
        LIMIT 6
    ");

    $stmt->execute([$currentUserId]);
} 
// when not logged in, just latest 6 posts
else 
{
    $stmt = $pdo->query
    ("  SELECT *
        FROM posts
        ORDER BY created_at DESC
        LIMIT 6
    ");
}

$posts = $stmt->fetchAll(); //posts is an array from all data of the datatbase
?>