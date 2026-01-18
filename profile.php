<?php
session_start();


require_once 'config/db.php';

$displayFirstName = $_SESSION['user']['firstname'] ?? '';
$displayLastName  = $_SESSION['user']['lastname'] ?? '';
$username         = $_SESSION['user']['username'] ?? '';

$created_at = $_SESSION['user']['created_at'] ?? null;


$user_id = $_SESSION['user']['id'] ?? null;


$posts = [];
if ($user_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT id, text, file_path, created_at
            FROM posts
            WHERE user_id = :user_id
            ORDER BY created_at DESC
        ");
        $stmt->execute(['user_id' => $user_id]);
        $posts = $stmt->fetchAll();
    } catch (PDOException $e) {
        $posts = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Profile</title>
<style>
.profile-img, .profile-video {
    width: 100%;
    max-height: 250px;
    object-fit: cover;
}
</style>
</head>

<header>
<?php include 'parts/navbar.php';?> 
</header>

<body>
<div class="container mt-4">

    <h1><?php echo htmlspecialchars("$displayFirstName $displayLastName"); ?></h1>

    <?php if (!empty($username)): ?>
        <p class="text-muted mb-1">@<?php echo htmlspecialchars($username); ?></p>
    <?php else: ?>
        <p class="text-muted mb-1">Mach einen Usernamen!</p>
    <?php endif; ?>

    <?php if (!empty($created_at)): ?>
        <p class="text-muted mb-4">Account erstellt: <?php echo htmlspecialchars(date('d.m.Y H:i', strtotime($created_at))); ?></p>
    <?php endif; ?>

    <h2 class="mb-3">My Posts</h2>
    <div class="row row-cols-1 row-cols-md-3 g-3">

        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="col">
                    <div class="card profile-card h-100">
                        <?php if (!empty($post['file_path'])): ?>
                            <?php
                            $ext = strtolower(pathinfo($post['file_path'], PATHINFO_EXTENSION));
                            if (in_array($ext, ['jpg','jpeg','png','gif'])): ?>
                                <img src="<?php echo htmlspecialchars($post['file_path']); ?>" class="card-img-top profile-img" alt="Post">
                            <?php elseif (in_array($ext, ['mp4','mov'])): ?>
                                <video class="profile-video" controls>
                                    <source src="<?php echo htmlspecialchars($post['file_path']); ?>" type="video/<?php echo $ext; ?>">
                                    Dein Browser unterstützt dieses Videoformat nicht.
                                </video>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="card-body profile-body">

                            <!-- displays only 2 lines of the caption, that was posted -->
                            <p style="display: -webkit-box; -webkit-line-clamp:2; -webkit-box-orient: vertical; overflow: hidden;"> 
                            <?php echo htmlspecialchars($post['text']); ?>
                            </p>

                            <a href="post.php?id=<?php echo $post['id']; ?>" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Du hast noch keine Posts.</p>
        <?php endif; ?>

    </div>
</div>
</body>
</html>
