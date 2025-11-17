<?php
// Beispiel-Posts --> später durch Datenbank ersetzt
$posts = [
    1 => [
        "image" => "pics/Tier01.jpg",
        "description" => "Today I saw an interesting bird. Look at the photo!"
    ],
    2 => [
        "image" => "pics/Landschaft.png",
        "description" => "Look at the beautiful landscape! It's so beautiful that I could spend hours admiring it."
    ],
    3 => [
        "image" => "pics/Winter.jpg",
        "description" => "What a beautiful winter day."
     ],
     101 => [
        "image" => "pics/Bild.jpg",
        "description" => "Enjoying the view from a rocky cliff with a breathtaking landscape behind me."
    ],
    102 => [
        "image" => "pics/Feld.jpg",
        "description" => "A peaceful field stretches out to the horizon."
    ],
    103 => [
        "image" => "pics/Hase.jpg",
        "description" => "This little bunny was hopping around, so cute!"
    ]
];

// Suchbegriff abfangen
$searchTerm = $_GET['q'] ?? "";
$searchTerm = trim($searchTerm);

// Suchergebnisse filtern (nur description)
$results = [];
if ($searchTerm !== "") {
    foreach ($posts as $id => $post) {
        if (stripos($post['description'], $searchTerm) !== false) {
            $results[$id] = $post;
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Search Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>


<header>
<?php include 'parts/navbar.php';?> 
</header>

<body>

<div class="container" class="p-4">
    <h1 class="mb-4">Search for posts</h1>
    
    <form class="d-flex mb-4" role="search" method="get">
        <input class="form-control me-2" type="search" name="q" placeholder="Suchbegriff eingeben..." aria-label="Search" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button class="btn btn-dark" type="submit">Suchen</button>
    </form>

    <?php if ($searchTerm !== ""): ?>
        <h5>Results for "<?php echo htmlspecialchars($searchTerm); ?>":</h5>
        
        <?php if (!empty($results)): ?>
            <div class="row row-cols-1 row-cols-md-2 g-4 mt-2">
                <?php foreach ($results as $id => $post): ?>
                    <div class="col">
                        <div class="card position-relative">
                            <img src="<?php echo $post['image']; ?>" class="card-img-top" alt="Post image">
                            <div class="card-body">
                                <p class="card-text">
                                    <?php echo strlen($post['description']) > 50 ? substr($post['description'],0,50)."..." : $post['description']; ?>
                                </p>
                                <!-- Link um auf den Post zu kommen-->
                                <a href="post.php?id=<?php echo $id; ?>" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted mt-3">No posts found.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
