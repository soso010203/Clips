<?php
session_start();
require_once 'config/db.php';

$postId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$postId) {
    die("Ungültige Post-ID");
}

// Post holen
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch();

// Poster info
$userStmt = $pdo->prepare("SELECT firstname, lastname FROM accounts WHERE id = ?");
$userStmt->execute([$post['user_id'] ?? 0]);
$user = $userStmt->fetch();

// Prüfen ob eingeloggter User der Owner ist
$currentUserId = $_SESSION['user']['id'] ?? null;
$isOwner = ($currentUserId && $currentUserId == $post['user_id']);
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
        <p class="fw-bold mb-0">
            posted by: <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>
        </p>
        <small class="text-muted">
            <?php echo date("d.m.Y H:i", strtotime($post['created_at'])); ?>
        </small>
    </div>

    <div class="text-center mb-4">
        <img src="<?php echo htmlspecialchars($post['file_path']); ?>" class="post-image rounded" alt="Post image">
    </div>

    <div class="mb-4">
        <p class="post-text"><?php echo nl2br(htmlspecialchars($post['text'])); ?></p>

        <?php if ($isOwner): ?>
    <div class="mt-2 d-flex gap-2">
        <a href="changepost.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">
            Edit your post
        </a>

        <form action="actions/deletepostAction.php" method="post" onsubmit="return confirm('Bist du sicher, dass du diesen Post löschen willst?');">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <button type="submit" class="btn btn-danger">
                Delete post
            </button>
        </form>
    </div>
<?php endif; ?>
    </div>

<?php else: ?>
    <div class="alert alert-warning text-center">
        Dieser Post existiert nicht.
    </div>
<?php endif; ?>

</div>
</body>
</html>
