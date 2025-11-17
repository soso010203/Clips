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




<?php $displayName = $_SESSION['user']['firstname'];?>

<h1 class="m-3"> Hello <?php echo "$displayName"?>!</h1>
    
</body>
</html>