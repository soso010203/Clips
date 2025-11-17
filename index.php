<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="stylesheet.css">

    <title>Document</title>

</head>
<body>
<header>

<!-- Navbar -->
<?php include 'parts/navbar.php';?> 


</header>

<div class="container"> 
<h1 class="text-center"  style="margin-bottom: 80px;"> Welcome @ clips!</h1>


<div class="row row-cols-1 row-cols-md-2 g-4 center " > <!-- Bootstrap Grid card layout -->

  <div class="col">
    <div class="card">
      <img src="pics/Tier01.jpg" class="card-img-top" alt="Tier01">
      <div class="card-body">
        <p class="card-text">Today i saw an interesting bird. Look at the photo!</p>
        <a href="post.php?id=1" class="stretched-link"></a>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card">
      <img src="Landschaft.png" class="card-img-top" alt="Landschaft">
      <div class="card-body">
        <p class="card-text">Look at the beautiful landscape!</p>
        <a href="post.php?id=2" class="stretched-link"></a>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card">
      <img src="Winter.jpg" class="card-img-top" alt="Winter">
      <div class="card-body">
        <p class="card-text">What a beautiful winter day.</p>
        <a href="post.php?id=3" class="stretched-link"></a>
      </div>
    </div>
  </div>

  
</div>


    
</body>
</html>