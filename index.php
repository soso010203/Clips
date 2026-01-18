<?php
session_start();

require_once 'config/db.php'; //connects to the database

$currentUserId = $_SESSION['user']['id'] ?? null; 

if ($currentUserId) {
    // If a user is logged in, their own posts shouldn't be displayed
    $stmt = $pdo->prepare("
        SELECT *
        FROM posts
        WHERE user_id != ?
        ORDER BY created_at DESC
        LIMIT 6
    ");
    $stmt->execute([$currentUserId]);
} else {
    // Not logged in → just latest 6 posts
    $stmt = $pdo->query("
        SELECT *
        FROM posts
        ORDER BY created_at DESC
        LIMIT 6
    ");
}

$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
    <title>Home</title>
</head>
<body>

<?php include 'parts/navbar.php'; ?>

<div class="container-lg">

    <h1 class="text-center m-5">
        <?php if ($currentUserId): ?>
            Hello
        <?php else: ?>
            Welcome!
        <?php endif; ?>
    </h1>

<div class="row mx-auto" style="max-width:90%;">

        <?php if (count($posts) === 0): ?>
            <p class="text-center">Noch keine Posts vorhanden.</p>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <div class="col-12 col-md-6 mb-4">
                <div class="card w-100">

                    <!-- Post Image -->
                    <img
                        src="<?php echo htmlspecialchars($post['file_path']); ?>"
                        class="card-img-top"
                        alt="Post image"
                    >

                    <!-- Caption -->
                    <div class="card-body">
                        <p class="card-text" style="
                            display: -webkit-box;
                            -webkit-line-clamp: 1;
                            -webkit-box-orient: vertical;
                            overflow: hidden;
                            text-overflow: ellipsis;
                        ">
                            <?php echo htmlspecialchars($post['text']); ?>
                        </p>

                        <!-- Link to Post Detail -->
                        <a href="post.php?id=<?php echo $post['id']; ?>" class="stretched-link"></a>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>

<?php include 'parts/footer.php'; ?>

</body>
</html>
