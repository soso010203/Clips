<?php
session_start();

// Vor- und Nachname aus Session
$displayFirstName = $_SESSION['user']['firstname'] ?? '';
$displayLastName  = $_SESSION['user']['lastname'] ?? '';

// Erstellungsdatum: zuerst aus Session, ansonsten aus DB holen (falls user id gesetzt)
$created_at = $_SESSION['user']['created_at'] ?? null;
if (empty($created_at) && !empty($_SESSION['user']['id'])) {
    try {
        $dbHost = 'localhost';
        $dbUser = 'root';
        $dbPass = 'root';
        $dbName = 'clips_accounts';
        $table  = 'accounts';

        $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $stmt = $pdo->prepare("SELECT created_at FROM `{$table}` WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => (int)$_SESSION['user']['id']]);
        $row = $stmt->fetch();
        if ($row && !empty($row['created_at'])) {
            $created_at = $row['created_at'];
        }
    } catch (PDOException $e) {
        // Fehler stillschweigend ignorieren, Anzeige bleibt leer
        $created_at = null;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>profile</title>
</head>

<header>
<!-- Navbar -->
<?php include 'parts/navbar.php';?> 
</header>

<body>







<div class="container mt-4">

<h1> <?php echo "$displayFirstName $displayLastName" ?></h1>

    <?php if (!empty($created_at)): ?>
        <p class="text-muted mb-4">Account erstellt: <?php echo htmlspecialchars(date('d.m.Y H:i', strtotime($created_at))); ?></p>
    <?php endif; ?>

    <h2 class="mb-3">My Posts</h2>

    <div class="row row-cols-1 row-cols-md-3 g-3">

        <div class="col">
            <div class="card profile-card h-100">
                <img src="pics/Bild.jpg" class="card-img-top profile-img" alt="Post 1">
                <div class="card-body profile-body">
                    <p class="card-text">Enjoying the view from a rocky cliff with a breathtaking landscape behind me</p>
                    <a href="post.php?id=101" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card profile-card h-100">
                <img src="pics/Feld.jpg" class="card-img-top profile-img" alt="Post 2">
                <div class="card-body profile-body">
                    <p class="card-text">A peaceful field stretches out to the horizon.</p>
                    <a href="post.php?id=102" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card profile-card h-100">
                <img src="pics/Hase.jpg" class="card-img-top profile-img" alt="Post 3">
                <div class="card-body profile-body">
                    <p class="card-text">This little bunny was hopping around, so cute!</p>
                    <a href="post.php?id=103" class="stretched-link"></a>
                </div>
            </div>
        </div>

    </div>
</div>
    
</body>
</html>