<?php
session_start();
require_once 'config/db.php';

require 'actions/postAction.php'; // loads the post, username + userid + owner

?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Post ansehen</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/clips/stylesheet.css">

<style>
.post-container { max-width: 700px; margin: 0 auto; }
.post-image { width: 100%; height: auto; max-height: 500px; object-fit: contain; }
.post-text { word-wrap: break-word; }
</style>

</head>
<body>

<?php include 'parts/navbar.php'; ?>

<!--User Story number 2 -->

<div class="container mt-5 post-container">

<?php if ($post): ?>

    <div class="mb-4">
    <!-- shows the username of the user who created the post -->
    <p class="fw-bold mb-0">
        <a href="userprofile.php?user_id=<?php echo $post['user_id']; ?>" style="text-decoration: none; color: inherit;">
           posted by: <?php echo htmlspecialchars($post['username']); ?>
        </a>
    </p>

    <!-- shows the date when the post was created -->
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

<!-- User Story number 9 -->
 
    <?php if ($isOwner): ?>
    <div class="mt-2 d-flex gap-2">
        <a href="changepost.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">
            Edit your post
        </a>

        <form action="actions/deletepostAction.php" method="post" onsubmit="return confirm('Are you sure you want to delete the post?');">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <button type="submit" class="btn btn-danger">
                Delete post
            </button>
        </form>
    </div>
    
<?php endif; ?>
<?php else: ?>
    <div class="alert alert-warning text-center">
        This post doesn't exist.
    </div>
<?php endif; ?>

</div>
</body>
</html>
