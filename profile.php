<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>profile</title>
</head>

<header>
<!-- Navbar -->
<?php include 'parts/navbar.php';?> 
</header>

<body>




<?php $displayFirstName = $_SESSION['user']['firstname'];
$displayLastName = $_SESSION['user']['lastname'];?>


<div class="container mt-4">

<h1> <?php echo "$displayFirstName $displayLastName" ?></h1>


    <h2 class="mb-3">My Posts</h2>

    <div class="row row-cols-1 row-cols-md-3 g-3">

        <div class="col">
            <div class="card profile-card h-100">
                <img src="pics/Bild.jpg" class="card-img-top profile-img" alt="Post 1">
                <div class="card-body profile-body">
                    <p class="card-text">Enjoying the view from a rocky cliff with a breathtaking landscape behind me</p>
                    <a href="post.php?id=101" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card profile-card h-100">
                <img src="pics/Feld.jpg" class="card-img-top profile-img" alt="Post 2">
                <div class="card-body profile-body">
                    <p class="card-text">A peaceful field stretches out to the horizon.</p>
                    <a href="post.php?id=102" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card profile-card h-100">
                <img src="pics/Hase.jpg" class="card-img-top profile-img" alt="Post 3">
                <div class="card-body profile-body">
                    <p class="card-text">This little bunny was hopping around, so cute!</p>
                    <a href="post.php?id=103" class="stretched-link"></a>
                </div>
            </div>
        </div>

    </div>
</div>
    
</body>
</html>