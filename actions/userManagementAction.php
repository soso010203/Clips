<!-- User Story number 13 and 14 -->

<?php 

if(!isset($_SESSION)){
    session_start();
}

// CSRF-Token 
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

//Database connection
require_once __DIR__ . '/../config/db.php'; 
$table  = 'accounts';

// variables
$users = [];
$error = null;
$messages = [];


try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    $error = 'Datenbankfehler: ' . htmlspecialchars($e->getMessage());
}

//User Story Number 13
// see all users
if (!$error) {
    try {
        // username mit abfragen
        $stmt = $pdo->query("SELECT id, email, username, firstname, lastname, role, created_at FROM `{$table}` ORDER BY id DESC");
        $users = $stmt->fetchAll();

    } catch (PDOException $e) {
        $error = 'Datenbankfehler: ' . htmlspecialchars($e->getMessage());
    }
}



//User Story Number 14
// delete user

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    
    $deleteId = intval($_POST['delete_id'] ?? 0);
    $token = $_POST['csrf_token'] ?? '';

    //securty checks
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        $messages[] = ['type' => 'danger', 'text' => 'Ungültiger Request (CSRF).'];
    } elseif ($deleteId <= 0) {
        $messages[] = ['type' => 'danger', 'text' => 'Ungültige Benutzer-ID.'];
    } elseif ($deleteId == ($_SESSION['user']['id'] ?? 0)) {
        $messages[] = ['type' => 'warning', 'text' => 'Du kannst deinen eigenen Account nicht löschen.'];
    } else {

        //actual delete action
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

?> 