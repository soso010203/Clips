<?php

if(!isset($_SESSION)){
    session_start();
}



// CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

require_once __DIR__ . '/../config/db.php'; 


$postsTable = 'posts';
$usersTable = 'accounts';

$messages = [];

// Löschen verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_post') {
    $id = intval($_POST['delete_id'] ?? 0);
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $messages[] = ['type' => 'danger', 'text' => 'Ungültiger Request (CSRF).'];
    } elseif ($id <= 0) {
        $messages[] = ['type' => 'danger', 'text' => 'Ungültige Post‑ID.'];
    } else {
        try {
            // optional: hole file_path vor dem Löschen, um Datei zu entfernen
            $stmt = $pdo->prepare("SELECT file_path FROM `{$postsTable}` WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();

            $del = $pdo->prepare("DELETE FROM `{$postsTable}` WHERE id = :id");
            $del->execute(['id' => $id]);

            if ($del->rowCount() > 0) {
                // Datei löschen, falls local gespeichert
                if (!empty($row['file_path'])) {
                    $file = __DIR__ . '/' . ltrim($row['file_path'], '/');
                    if (is_file($file)) {
                        @unlink($file);
                    }
                }
                $messages[] = ['type' => 'success', 'text' => 'Post gelöscht.'];
            } else {
                $messages[] = ['type' => 'info', 'text' => 'Kein Post gefunden.'];
            }
        } catch (PDOException $e) {
            $messages[] = ['type' => 'danger', 'text' => 'Fehler beim Löschen: ' . htmlspecialchars($e->getMessage())];
        }
    }
}

// Posts laden (ohne Beschreibung anzeigen)
try {
    $stmt = $pdo->query("
        SELECT p.id, p.user_id, p.file_path, p.created_at, a.username
        FROM `{$postsTable}` p
        LEFT JOIN `{$usersTable}` a ON p.user_id = a.id
        ORDER BY p.created_at DESC
    ");
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $posts = [];
    $messages[] = ['type' => 'danger', 'text' => 'Datenbankfehler: ' . htmlspecialchars($e->getMessage())];
}
?>

<div class="container py-4">
    <h1 class="mb-4">Post Management</h1>

    <?php foreach ($messages as $m): ?>
        <div class="alert alert-<?php echo htmlspecialchars($m['type']); ?>"><?php echo htmlspecialchars($m['text']); ?></div>
    <?php endforeach; ?>

    <?php if (empty($posts)): ?>
        <div class="alert alert-info">Keine Posts gefunden.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Vorschau</th>
                        <th>Erstellt</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($posts as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['id']); ?></td>
                        <td><?php echo htmlspecialchars($p['username'] ?? ('#' . ($p['user_id'] ?? 'unknown'))); ?></td>
                        <td style="max-width:220px;">
                            <?php if (!empty($p['file_path'])): 
                                $ext = strtolower(pathinfo($p['file_path'], PATHINFO_EXTENSION));
                                if (in_array($ext, ['jpg','jpeg','png','gif'])): ?>
                                    <img src="<?php echo htmlspecialchars($p['file_path']); ?>" alt="" style="max-height:80px; max-width:200px; object-fit:cover;">
                                <?php elseif (in_array($ext, ['mp4','webm','ogg'])): ?>
                                    <video src="<?php echo htmlspecialchars($p['file_path']); ?>" style="max-height:80px; max-width:200px;" muted></video>
                                <?php else: ?>
                                    <small><?php echo htmlspecialchars(basename($p['file_path'])); ?></small>
                                <?php endif; 
                            else: ?>
                                <small class="text-muted">Keine Datei</small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($p['created_at']); ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('Post wirklich löschen?');" style="display:inline">
                                <input type="hidden" name="action" value="delete_post">
                                <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($p['id']); ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <button class="btn btn-sm btn-danger">Löschen</button>
                            </form>
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