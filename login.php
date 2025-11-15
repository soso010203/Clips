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
<!-- Navbar -->
<?php include 'parts/navbar.php';?> 

</header>


<?php
$pdo = new PDO('mysql:host=localhost;dbname=Accounts;charset=utf8', 'username', 'password');
?>



<?php
$pdo = new PDO('mysql:host=localhost;dbname=test', 'username', 'password');
 
$statement = $pdo->prepare("INSERT INTO users (email, vorname, nachname) VALUES (?, ?, ?)");
$statement->execute(array('info@php-einfach.de', 'Klaus', 'Neumann'));   
?>

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