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

<header>

<!-- Navbar -->
<?php include 'parts/navbar.php';?> 


</header>

<body>

<div class="container d-flex flex-column align-items-center"> 


<h1 class="text-center m-5"> hello @ clips!</h1>

<!-- Row: bei md+ zwei Spalten (col-md-6), sonst eine (col-12).
     Auf kleinen Bildschirmen centered, auf md+ linksbündig innerhalb
     eines zentrierten Bereichs (so bleibt der einzelne letzte Post links). -->
<div class="row justify-content-md-start " style="max-width:90%;">

  <div class="col-12 col-md-6 d-flex">
    <div class="card w-100">
      <img src="pics/Tier01.jpg" class="card-img-top" alt="Tier01">
      <div class="card-body">
        <p class="card-text">Today i saw an interesting bird. Look at the photo!</p>
        <a href="post.php?id=1" class="stretched-link"></a>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-6 d-flex">
    <div class="card w-100">
      <img src="pics/Landschaft.png" class="card-img-top" alt="Landschaft">
      <div class="card-body">
        <p class="card-text">Look at the beautiful landscape!</p>
        <a href="post.php?id=2" class="stretched-link"></a>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-6 d-flex">
    <div class="card w-100">
      <img src="pics/Winter.jpg" class="card-img-top" alt="Winter">
      <div class="card-body">
        <p class="card-text">What a beautiful winter day.</p>
        <a href="post.php?id=3" class="stretched-link"></a>
      </div>
    </div>
  </div>

</div>

</div> 

<!-- Navbar -->
<?php include 'parts/footer.php';?> 

</body>
</html>