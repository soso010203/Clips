<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only Admins zulassen
if (empty($_SESSION['user']['id']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('Location: index.php');
    exit;
}

// CSRF-Token für Löschaktionen
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
require_once 'config/db.php';
$table  = 'accounts';

$users = [];
$error = null;
$messages = []; // lokale Anzeige zusätzlich zu session messages

try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    $error = 'Datenbankfehler: ' . htmlspecialchars($e->getMessage());
}

// Löschanfrage verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $deleteId = intval($_POST['delete_id'] ?? 0);
    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $messages[] = ['type' => 'danger', 'text' => 'Ungültiger Request (CSRF).'];
    } elseif ($deleteId <= 0) {
        $messages[] = ['type' => 'danger', 'text' => 'Ungültige Benutzer-ID.'];
    } elseif ($deleteId == ($_SESSION['user']['id'] ?? 0)) {
        $messages[] = ['type' => 'warning', 'text' => 'Du kannst deinen eigenen Admin-Account nicht löschen.'];
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM `{$table}` WHERE id = :id");
            $stmt->execute(['id' => $deleteId]);
            if ($stmt->rowCount() > 0) {
                $messages[] = ['type' => 'success', 'text' => 'Benutzer erfolgreich gelöscht.'];
            } else {
                $messages[] = ['type' => 'info', 'text' => 'Kein Benutzer gefunden oder bereits gelöscht.'];
            }
        } catch (PDOException $e) {
            $messages[] = ['type' => 'danger', 'text' => 'Fehler beim Löschen: ' . htmlspecialchars($e->getMessage())];
        }
    }
}

// Benutzer laden (immer)
if (!$error) {
    try {
    
        $stmt = $pdo->query("SELECT id, email, username, firstname, lastname, role FROM `{$table}` ORDER BY id DESC");
        $users = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = 'Datenbankfehler: ' . htmlspecialchars($e->getMessage());
    }
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
