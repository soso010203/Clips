<?php
session_start();
$successfulUpload= $_SESSION['successfulUpload'] ?? "Upload successful!";
?>

<!DOCTYPE html>
<html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Upload-Successful</title>
 </head>
<body>

<div class="Uploadsuccessful">
    <h1>Upload successful!</h1>
    <p><?php echo $successfulUpload; ?></p>
    <p>You can view your post under your<a href="">Profile</a> now.</p>
</div>

</body>
</html>
