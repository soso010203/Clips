<?php
session_start();

// Nur Admins zulassen
if (empty($_SESSION['user']['id']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    $_SESSION['messages'][] = ['type' => 'danger', 'text' => 'Zugriff verweigert. Admins only.'];
    header('Location: index.php');
    exit;
}

// CSRF-Token für Löschaktionen
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

// DB Einstellungen
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = 'root';
$dbName = 'clips_accounts';
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
        $stmt = $pdo->query("SELECT id, email, firstname, lastname, role, created_at FROM `{$table}` ORDER BY id DESC");
        $users = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = 'Datenbankfehler: ' . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>User Management</title>
</head>

<body class="bg-light"> <!-- Bootstrap admin Panel nav bar -->

<?php include 'parts/navbar.php';?> 

  <div class="container py-4">
    <h1 class="mb-4">Admin Panel</h1>

    <nav class="mb-4">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link" href="userManagment.php">User Management</a>
          
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Post Management</a>
        </li>
      </ul>
    </nav>
  </div>
<body>
<div class="container py-4">
    <h2>Benutzerverwaltung</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php foreach ($messages as $m): ?>
        <div class="alert alert-<?php echo htmlspecialchars($m['type']); ?>"><?php echo htmlspecialchars($m['text']); ?></div>
    <?php endforeach; ?>

    <?php if (empty($users)): ?>
        <div class="alert alert-info">Keine Benutzer gefunden.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>E‑Mail</th>
                        <th>Vorname</th>
                        <th>Nachname</th>
                        <th>Rolle</th>
                        <th>Erstellt</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['id']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['firstname']); ?></td>
                        <td><?php echo htmlspecialchars($u['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($u['role']); ?></td>
                        <td><?php echo htmlspecialchars($u['created_at']); ?></td>
                        <td>
                            <?php if ($u['id'] == ($_SESSION['user']['id'] ?? 0)): ?>
                                <span class="text-muted">Eigenes Konto</span>
                            <?php else: ?>
                                <form method="post" style="display:inline" onsubmit="return confirm('Benutzer wirklich löschen?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($u['id']); ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Löschen</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>
</body>
</html>
