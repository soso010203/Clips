<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
    <title>Upload Page</title>

</head>
<body>

<?php include 'parts/navbar.php';?>

<!-- User Story number 8 -->
 
<div class="upload-div">
    <h1>Create Your Post</h1>
    <h2>Share your creative ideas with the community!</h2>

    <form method="POST" action="uploadAction.php" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="fileInput" class="form-label">Upload your file:</label>
            <input class="form-control" type="file" name="fileInput" id="fileInput" required> <!-- name = important for the php -->
        </div>

        <div class="mb-3">
            <label for="caption" class="form-label">Your description:</label>
            <textarea class="form-control" id="caption" name="caption" rows="4" placeholder="Write something creative..." required></textarea>
        </div>

        <button class="btn btn-dark" type="submit">Publish</button>
    </form>
</div>

</body>
</html>
