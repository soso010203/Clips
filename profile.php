<!-- User Story number 7 -->

<?php

session_start();

require_once 'config/db.php'; //connects to the database

require 'actions/profileAction.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="stylesheet.css">
<title>Profile</title>
</head>

<header>
<?php include 'parts/navbar.php';?> 
</header>

<!-- User Story number 7 -->
<body>
<div class="container mt-4">

    <h1><?php echo htmlspecialchars("$displayFirstName $displayLastName"); ?></h1>

    <p class="text-muted mb-1">Your username: @<?php echo htmlspecialchars($username); ?></p>

    <p class="text-muted mb-4">Account created: <?php echo htmlspecialchars(date('d.m.Y', strtotime($created_at))); ?></p>
   

    <h2 class="mb-3">My Posts</h2>
    <div class="row row-cols-1 row-cols-md-3 g-3">

        <?php if (!empty($posts)): ?>
            
            <?php foreach ($posts as $post): ?>
                <div class="col">
                    <div class="card profile-card h-100">

                        <!--check if the file_path is not empty, then displays the post-->
                        <?php if (!empty($post['file_path'])): ?>
                            <img src="<?php echo htmlspecialchars($post['file_path']); ?>" class="card-img-top profile-imges" alt="Post">
                        <?php endif; ?>

                        <div class="card-body profile-body">

                            <!-- displays only 2 lines of the caption, that was posted -->
                            <p style="display: -webkit-box; -webkit-line-clamp:1; -webkit-box-orient: vertical; overflow: hidden;"> 
                            <?php echo htmlspecialchars($post['text']); ?>
                            </p>

                            <a href="post.php?id=<?php echo $post['id']; ?>" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>There are no posts available!</p>
        <?php endif; ?>

    </div>
</div>
</body>
</html>
