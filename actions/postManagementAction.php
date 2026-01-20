<!-- User Story number 12 and 13 -->

<?php
if(!isset($_SESSION)){
    session_start();
}

// CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

require_once __DIR__ . '/../config/db.php'; 

//tables
$postsTable = 'posts';
$usersTable = 'accounts';


$messages = [];

//User Story Number 12

// delete post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_post') 
{
    $id = intval($_POST['delete_id'] ?? 0);

    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $messages[] = ['type' => 'danger', 'text' => 'Ungültiger Request (CSRF).'];

    } elseif ($id <= 0) {
        $messages[] = ['type' => 'danger', 'text' => 'Ungültige Post‑ID.'];
    } else {

        
        try {
            // save  file_path 
            $stmt = $pdo->prepare("SELECT file_path FROM `{$postsTable}` WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();

            // delete post
            $del = $pdo->prepare("DELETE FROM `{$postsTable}` WHERE id = :id");
            $del->execute(['id' => $id]);

            if ($del->rowCount() > 0) {
                
                // delete file 
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

//User Story Number 12
// shows all posts
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