<?php
session_start();

require_once 'config/db.php'; //connects to the database

require 'actions/indexAction.php'; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!--Bootstrap link-->
    <link rel="stylesheet" href="stylesheet.css">
    <title>Homepage</title>
</head>
<body>


<?php include 'parts/navbar.php'; ?> 

<div class="container-lg">

    <h1 class="text-center m-5">
        <!-- for registered user -->
        <?php if ($currentUserId): ?>
            Hello 
        <!-- for guest user -->
        <?php else: ?>
            Welcome! 
        <?php endif; ?>
    </h1>

<div class="row mx-auto" style="max-width:90%;">
        <!--when there are no posts secured in the database -->
        <?php if (count($posts) === 0): ?>
            <p class="text-center">There are no posts available.</p>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <div class="col-12 col-md-6 mb-4">
                <div class="card w-100"> 

                    <!-- Image from the database -->
                    <img
                        src="<?php echo htmlspecialchars($post['file_path']); ?>"
                        class="card-img-top"
                        alt="<?php echo htmlspecialchars($post['text']); ?>"
                    >

                    <!-- Caption from the database-->
                    <div class="card-body">
                        <p style=" display: -webkit-box; 
                                -webkit-line-clamp: 1;
                                -webkit-box-orient: vertical;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                "> <!--only one line of the description is visible -->
                        <?php echo htmlspecialchars($post['text']); ?>
                        </p>
                    </div>

                    <!-- Link to post with the right id -->
                        <a href="post.php?id=<?php echo $post['id']; ?>" class="stretched-link"></a>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>

<?php include 'parts/footer.php'; ?>

</body>
</html>
