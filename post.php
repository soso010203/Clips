<?php
session_start();
require_once 'config/db.php';

// filters the id after the ? in the URL 
$postId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$stmt = $pdo->prepare("SELECT posts.*, accounts.username 
                       FROM posts 
                       JOIN accounts ON posts.user_id = accounts.id
                       WHERE posts.id = ?");

$stmt->execute([$postId]);
$post = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Post ansehen</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.post-container { max-width: 700px; margin: 0 auto; }
.post-image { width: 100%; height: auto; max-height: 500px; object-fit: contain; }
.post-text { word-wrap: break-word; }
</style>

</head>
<body>

<?php include 'parts/navbar.php'; ?>

<div class="container mt-5 post-container">

<?php if ($post): ?>

    <div class="mb-4">
        <!--shows the username of the logged in user -->
        <p class="fw-bold mb-0">
            posted by: <?php echo htmlspecialchars($post['username']); ?>
        </p>
        <!--shows the date, when the account was created (of the logged in user)-->
        <small class="text-muted">
            <?php echo date("d.m.Y H:i", strtotime($post['created_at'])); ?>
        </small>
    </div>

    <div class="text-center mb-4">
        <img src="<?php echo htmlspecialchars($post['file_path']); ?>" class="post-image rounded" alt="Post image">
    </div>

    <div class="mb-4">
        <p class="post-text"><?php echo nl2br(htmlspecialchars($post['text'])); ?></p>
    </div>

<?php else: ?>
    <div class="alert alert-warning text-center">
        This post doesn't exist.
    </div>
<?php endif; ?>

</div>
</body>
</html>
