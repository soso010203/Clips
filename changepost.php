<?php

session_start();
require_once 'config/db.php';


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

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Post bearbeiten</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.post-container { max-width: 700px; margin: 0 auto; }
.post-image { width: 100%; height: auto; max-height: 500px; object-fit: contain; margin-bottom: 20px; }
</style>

</head>
<body>

<?php include 'parts/navbar.php'; ?>

<div class="container mt-5 post-container">
    <h1>Change your post</h1>

    <form method="post" enctype="multipart/form-data">
        <!-- current file -->
        <div class="text-center mb-3">
            <img src="<?php echo htmlspecialchars($post['file_path']); ?>" class="post-image rounded" alt="Post image">
        </div>

        <!-- to change the caption -->
        <div class="mb-3">
            <label class="form-label">Caption</label>
            <textarea name="text" class="form-control" rows="3"><?php echo htmlspecialchars($post['text']); ?></textarea>
        </div>

        <!-- to upload a new file -->
        <div class="mb-3">
            <label class="form-label">Upload a new file!</label>
            <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png">
        </div>

        <button type="submit" class="btn btn-primary">Update your post!</button>
    </form>
</div>

</body>
</html>
