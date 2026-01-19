<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only Admins zulassen
if (empty($_SESSION['user']['id']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('Location: index.php');
    exit;
}


// Tab-Auswahl: 'users' (default) oder 'posts'
$tab = isset($_GET['tab']) && $_GET['tab'] === 'posts' ? 'posts' : 'users';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>User Management</title>
</head>

<?php include __DIR__ . '/parts/navbar.php'; ?> 

<div class="container py-4">
    <h1 class="mb-4">Admin Panel</h1>
    
    <nav class="mb-4">
        <ul class="nav nav-tabs">
            <li class="nav-item">
              <a class="nav-link <?php echo $tab === 'users' ? 'active' : ''; ?>" href="admin.php?tab=users">User Management</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo $tab === 'posts' ? 'active' : ''; ?>" href="admin.php?tab=posts">Post Management</a>
            </li>
        </ul>
    </nav>

    <?php
    // sichere Include‑Map (verhindert Pfadmanipulation)
    $includeMap = [
        'users' => __DIR__ . '/admin/userManagment.php',
        'posts' => __DIR__ . '/admin/postManagement.php'
    ];

    $includeFile = $includeMap[$tab] ?? $includeMap['users'];

    if (file_exists($includeFile)) {
        include $includeFile;
    } else {
        echo '<div class="alert alert-danger">Include-Datei nicht gefunden.</div>';
    }
    ?>

</div>
</html>
