<!-- UserStory 7 - Upload for registered users/admin -->
<!DOCTYPE html>
<html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Upload-Page</title>
 </head>

 <body> 
    <h1>Create and upload your own post</h1>
    <h2>Upload your post and share your creative ideas with the community!</h2>

    <form method="POST" action="uploadAction.php" enctype="multipart/form-data">
    <div class="mb-3">  <!-- Bootstrap Upload + fileInput, upload your post with text-->  
        <label for="fileInput" class="form-label">Upload your file: </label>
         <input class="form-control" type="file" name="fileInput" id="fileInput">
    </div>

    <div class="mb-3">
        <label for="caption" class="form-label">here is the place for your creative description...</label>
        <textarea class="form-control" id="caption" name="caption" rows="3"></textarea>
    </div>

        <button type="submit">Publish your post</button>
    </form>


 </body>