<?php
session_start();
require_once 'config/db.php';

$userId = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);

// User holen
$userStmt = $pdo->prepare("SELECT username FROM accounts WHERE id = ?");
$userStmt->execute([$userId]);
$user = $userStmt->fetch();

if (!$user) {
    die("User nicht gefunden.");
}

// Posts holen
$postsStmt = $pdo->prepare("
    SELECT id, text, file_path, created_at
    FROM posts
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$postsStmt->execute([$userId]);
$posts = $postsStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($user['username']); ?> – Profil</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="css/userprofile.css" rel="stylesheet">
</head>

<body>

<?php include 'parts/navbar.php'; ?>

<div class="container mt-5 userprofile-container">

    <h1 class="userprofile-username">
        @<?php echo htmlspecialchars($user['username']); ?>
    </h1>

    <h5 class="userprofile-subtitle mb-4">
        Posts from this user
    </h5>

    <hr>

    <?php if ($posts): ?>
        <div class="row g-3">

            <?php foreach ($posts as $post): ?>
                <div class="col-12 col-sm-6 col-lg-4">

                    <a href="post.php?id=<?php echo $post['id']; ?>"
                       class="text-decoration-none text-dark">

                        <div class="card userprofile-post-card h-100">

                            <img src="<?php echo htmlspecialchars($post['file_path']); ?>"
                                 class="card-img-top userprofile-post-image"
                                 alt="Post image">

                            <div class="card-body d-flex flex-column">
                                <p class="card-text userprofile-post-text">
                                    <?php
                                    $text = htmlspecialchars($post['text']);
                                    echo strlen($text) > 80
                                        ? substr($text, 0, 80) . '...'
                                        : $text;
                                    ?>
                                </p>

                                <small class="userprofile-post-date mt-auto">
                                    <?php echo date("d.m.Y", strtotime($post['created_at'])); ?>
                                </small>
                            </div>

                        </div>
                    </a>
                </div>
            <?php endforeach; ?>

        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            Dieser User hat noch keine Posts.
        </div>
    <?php endif; ?>

</div>

</body>
</html>
