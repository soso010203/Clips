<?php
session_start();
require_once 'config/db.php';

// user_id aus der URL
$userId = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);
if (!$userId) {
    die("Ungültiger User.");
}

// User-Daten abrufen
$userStmt = $pdo->prepare("SELECT username FROM accounts WHERE id = ?");
$userStmt->execute([$userId]);
$user = $userStmt->fetch();
if (!$user) {
    die("User nicht gefunden.");
}

// Alle Posts dieses Users abrufen
$postsStmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$postsStmt->execute([$userId]);
$posts = $postsStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($user['username']); ?> - Profil</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.post-container { max-width: 700px; margin: 20px auto; }
.post-image { width: 100%; max-height: 400px; object-fit: contain; margin-bottom: 10px; }
</style>
</head>
<body>
<?php include 'parts/navbar.php'; ?>

<div class="container mt-5 post-container">
    <h1><?php echo htmlspecialchars($user['username']); ?></h1>
    <hr>

    <?php if ($posts): ?>
        <?php foreach ($posts as $post): ?>
            <div class="mb-4 border p-3 rounded">
                <div class="text-center mb-2">
                    <img src="<?php echo htmlspecialchars($post['file_path']); ?>" class="post-image rounded" alt="Post image">
                </div>
                <p><?php echo nl2br(htmlspecialchars($post['text'])); ?></p>
                <small class="text-muted">
                    <?php echo date("d.m.Y H:i", strtotime($post['created_at'])); ?>
                </small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            Dieser User hat noch keine Posts.
        </div>
    <?php endif; ?>
</div>

</body>
</html>
