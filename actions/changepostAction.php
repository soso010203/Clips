<!-- User Story number 9 -->
<?php

$postId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch();

//check if the current user id is the same user id from the post
$currentUserId = $_SESSION['user']['id'];

if (!$currentUserId || $currentUserId != $post['user_id']) 
    {
    die("No access!");
    }

// data from the formular of the post change 
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $newText = trim($_POST['text'] ?? '');

        $uploadDir = 'uploads/';
        $filePath = $post['file_path']; 

    //checks if a new file is uploaded
    if (!empty($_FILES['file']['name'])) 
    {
        $fileName = basename($_FILES['file']['name']);
        $targetFile = $uploadDir . time() . '_' . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];

        if (in_array($fileType, $allowed)) 
        {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
                $filePath = $targetFile;
            } 
            else 
            {
                echo "<div class='alert alert-danger'>The file couldn't upload!</div>";
            }
        } 
        else 
        {
            echo "<div class='alert alert-danger'>This format is not allowed.</div>";
        }
    }

    // to update the database
    $updateStmt = $pdo->prepare("UPDATE posts SET text = :text, file_path = :file_path WHERE id = :id");
    $updateStmt->execute
    ([  'text' => $newText,
        'file_path' => $filePath,
        'id' => $postId
    ]);

 
    header("Location: post.php?id=" . $postId);
    exit;
}
?>