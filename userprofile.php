<!-- User Story number 3 -->

<?php
session_start();
require_once 'config/db.php';

require 'actions/userprofileAction.php'; //loads all the posts and username of the profile you click on
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Profil</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="userprofile.css">
</head>
<body>
<?php include 'parts/navbar.php'; ?>


 
<div class="container mt-5">

    <h2 class="userprofile-username text-center">@<?php echo htmlspecialchars($username); ?></h2>
    <h5 class="userprofile-subtitle text-center mb-4">Posts from this user:</h5>

   <div class="row row-cols-1 row-cols-md-3 g-3">
    <?php if (!empty($posts)): ?>

        <?php foreach ($posts as $post): ?>
            <div class="col">
                <a href="post.php?id=<?php echo $post['id']; ?>" class="text-decoration-none">
                    <div class="card h-100">

                        <?php if (!empty($post['file_path'])): ?>

                             <img src="<?php echo htmlspecialchars($post['file_path']); ?>" 
                             class="card-img-top" alt="Post" style="height:200px; width:100%; object-fit:cover;">

                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <!-- Caption only 1 line long-->
                            <p style="display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; text-overflow:ellipsis;">
                                <?php echo htmlspecialchars($post['text']); ?>
                            </p>

                            <small class="mt-auto text-muted">
                                <?php echo date("d.m.Y", strtotime($post['created_at'])); ?>
                            </small>

                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <div class="alert alert-warning text-center">
            This user has no posts. 
        </div>
    <?php endif; ?>
</div>

</div>
</body>
</html>
