<?php
session_start();

require_once 'config/db.php'; //connects to the database

//catches the search word
$searchTerm = $_GET['q'] ?? "";
$searchTerm = trim($searchTerm);

$results = [];

if ($searchTerm !== "") {
    // Prepared Statement: searches in the database in the column text
    $stmt = $pdo->prepare("
        SELECT id, file_path, text
        FROM posts
        WHERE text LIKE ?
        ORDER BY created_at DESC
        LIMIT 50
    ");
    $stmt->execute(["%$searchTerm%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

<body>

<header>
    <?php include 'parts/navbar.php'; ?> 
</header>

<div class="container py-4">

    <h1 class="mb-4">Search for posts</h1>

    <!-- Suchformular -->
    <form class="d-flex mb-4" role="search" method="get">
        <input class="form-control me-2" type="search" name="q" placeholder="Suchbegriff eingeben..." aria-label="Search" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button class="btn btn-dark" type="submit">Suchen</button>
    </form>

    <?php if ($searchTerm !== ""): ?>
        <h5>Results for "<?php echo htmlspecialchars($searchTerm); ?>":</h5>

        <?php if (!empty($results)): ?>
            <div class="row row-cols-1 row-cols-md-2 g-4 mt-2">
                <?php foreach ($results as $post): ?>
                    <div class="col">
                        <div class="card h-100 position-relative">

                            <!-- Post Image -->
                            <img src="<?php echo htmlspecialchars($post['file_path']); ?>" class="card-img-top" alt="Post image">

                            <!-- Card Body -->
                            <div class="card-body d-flex flex-column">
                                <p class="card-text">
                                    <?php
                                    $text = htmlspecialchars($post['text']);
                                    echo strlen($text) > 50 ? substr($text, 0, 50) . "..." : $text;
                                    ?>
                                </p>

                                <!-- Link zum Post -->
                                <a href="post.php?id=<?php echo $post['id']; ?>" class="stretched-link"></a>
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
