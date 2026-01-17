<?php
session_start();
require_once 'config/db.php';

$postId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$postId) die("Ungültige Post-ID");

// Post holen
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch();

// Prüfen Owner
$currentUserId = $_SESSION['user']['id'] ?? null;
if (!$currentUserId || $currentUserId != $post['user_id']) {
    die("Keine Berechtigung!");
}

// Verarbeitung des Formulars
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newText = trim($_POST['text'] ?? '');

    // Bild-Upload
    if (!empty($_FILES['file']['name'])) {
        $uploadDir = 'uploads/';
        $fileName = basename($_FILES['file']['name']);
        $targetFile = $uploadDir . time() . '_' . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','mp4','mov'];

        if (in_array($fileType, $allowed) && move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
            $post['file_path'] = $targetFile;
        }
    }

    // Update DB
    $updateStmt = $pdo->prepare("UPDATE posts SET text = :text, file_path = :file_path WHERE id = :id");
    $updateStmt->execute([
        'text' => $newText,
        'file_path' => $post['file_path'],
        'id' => $postId
    ]);

    // Redirect zurück auf die Post-Seite
    header("Location: post.php?id=" . $postId);
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Change your post</title>
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
    <!-- Aktuelles Bild -->
    <div class="text-center mb-3">
        <img src="<?php echo htmlspecialchars($post['file_path']); ?>" class="post-image rounded" alt="Post image">
    </div>

    <!-- Caption bearbeiten -->
    <div class="mb-3">
        <label class="form-label">Caption</label>
        <textarea name="text" class="form-control" rows="3"><?php echo htmlspecialchars($post['text']); ?></textarea>
    </div>

    <!-- Neues Bild / Video -->
    <div class="mb-3">
        <label class="form-label">Upload your new picture/video</label>
        <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png,.gif,.mp4,.mov">
    </div>

    <button type="submit" class="btn btn-primary">Update your post</button>
</form>

</div>
</body>
</html>
