<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <title>login</title>

</head>



<header>

<!--Navigation-->
<nav class="navbar navbar-expand">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">CLIPS</a>
    
    
      <div class="navbar-nav ">
        <a class="nav-link active" aria-current="page" href="index.php">home</a>
        <a class="nav-link" href="search.php">search</a>
        <a class="nav-link" href="upload.php">upload</a>
        <a class="nav-link" href="admin.php">admin</a>

        <a class="nav-link rounded-pill  text-bg-dark  mx-1" href="register.php">register</a>
        <a class="nav-link rounded-pill  border border-dark text-bg-light mx-1" href="login.php">login</a>
        
      </div>
    </div>
  </div>
</nav>
</header>

<body class="m-3">
 
<h1 >Please Login!</h1>


<form action="action_page.php" method="post">

  <div class="mb-3">
    <label for="LoginEmail" class="form-label">Email address</label>
    <input type="username" class="form-control" id="LoginEmail"  aria-describedby="emailHelp" required>
    
  </div>
  <div class="mb-3">
    <label for="LoginPassword" class="form-label">Password</label>
    <input type="password" class="form-control" id="LoginPassword" required>
  </div>
  
  </div >
  <button type="submit" class="btn btn-dark mb-3">Submit </button>
</form>

<?php include("loginAction");

 
</body>
</html>