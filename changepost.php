<?php

session_start();
require_once 'config/db.php';

require 'actions/changepostAction.php';

?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Post bearbeiten</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/clips/stylesheet.css">


<style>
.post-container { max-width: 700px; margin: 0 auto; }
.post-image { width: 100%; height: auto; max-height: 500px; object-fit: contain; margin-bottom: 20px; }
</style>

</head>
<body>

<?php include 'parts/navbar.php'; ?>

<!-- User Story number 9 -->
 
<div class="container mt-5 post-container">
    <h1>Change your post</h1>

    <form method="post" enctype="multipart/form-data">
        <!-- current file -->
        <div class="text-center mb-3">
            <img src="<?php echo htmlspecialchars($post['file_path']); ?>" class="post-image rounded" alt="Post image">
        </div>

        <!-- to change the caption -->
        <div class="mb-3">
            <label class="form-label">Caption</label>
            <textarea name="text" class="form-control" rows="3"><?php echo htmlspecialchars($post['text']); ?></textarea>
        </div>

        <!-- to upload a new file -->
        <div class="mb-3">
            <label class="form-label">Upload a new file!</label>
            <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png">
        </div>

        <button type="submit" class="btn btn-primary">Update your post!</button>
    </form>
</div>

</body>
</html>
