<!-- User Story number 4 -->
 
<?php

//catches the search word
$searchTerm = $_GET['searchword'] ?? "";
$searchTerm = trim($searchTerm);

$results = [];

if ($searchTerm !== "") 
{
    // this is a prepared Statement: searches in the database in the column text
    $stmt = $pdo->prepare
    ("  SELECT id, file_path, text
        FROM posts
        WHERE text LIKE ?
        ORDER BY created_at DESC
        LIMIT 50
    ");

    $stmt->execute(["%$searchTerm%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>