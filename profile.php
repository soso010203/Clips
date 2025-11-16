<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>profile</title>
</head>
<body>




<?php
session_start();
if (isset($_SESSION['user'])) {
    $displayName = $_SESSION['user']['firstname'] . ' ';
} else {
    $displayName = "Guest";
}
?>

<h1> Hello <?php echo "$displayName"?>!</h1>
    
</body>
</html>