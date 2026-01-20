<!-- User Story number 6-->


<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

// init flash-messages
$_SESSION['messages'] = $_SESSION['messages'] ?? [];

// DB connection (use config/db.php if it provides $pdo, else build PDO from variables)
require_once __DIR__ . '/../config/db.php';

if (!isset($pdo)) {
    if (isset($dbHost, $dbUser, $dbPass, $dbName)) {
        try {
            $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            // DEBUG: konkrete Fehlermeldung lokal anzeigen
            $_SESSION['messages'][] = ['type' => 'danger', 'text' => 'Datenbankverbindung fehlgeschlagen: ' . $e->getMessage()];
            header('Location: ../login.php');
            exit;
        }
    } else {
        $_SESSION['messages'][] = ['type' => 'danger', 'text' => 'Datenbankkonfiguration nicht gefunden.'];
        header('Location: ../login.php');
        exit;
    }
}

// table name fallback
$table = $table ?? 'accounts';

// form values
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// validation
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['messages'][] = ['type' => 'danger', 'text' => 'Bitte eine gültige E‑Mail-Adresse angeben.'];
    $_SESSION['email'] = $email;
    header('Location: ../login.php');
    exit;
}
if ($password === '') {
    $_SESSION['messages'][] = ['type' => 'danger', 'text' => 'Bitte das Passwort eingeben.'];
    $_SESSION['email'] = $email;
    header('Location: ../login.php');
    exit;
}

// fetch user
try {
    $stmt = $pdo->prepare("SELECT id, email, username, password, firstname, lastname, role, created_at FROM `{$table}` WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

} catch (PDOException $e) {
    // show real error for local debugging
    $_SESSION['messages'][] = ['type' => 'danger', 'text' => 'Fehler beim Zugriff auf die Benutzerdaten: ' . $e->getMessage()];
    $_SESSION['email'] = $email;
    header('Location: ../login.php');
    exit;
}

// verify
if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['messages'][] = ['type' => 'danger', 'text' => 'Ungültige Zugangsdaten.'];
    $_SESSION['email'] = $email;
    header('Location: ../login.php');
    exit;
}

// set session user
$_SESSION['user'] = [
    'id'        => $user['id'],
    'username'  => $user['username'] ?? null,
    'email'     => $user['email'] ?? null,
    'firstname' => $user['firstname'] ?? null,
    'lastname'  => $user['lastname'] ?? null,
    'role'      => $user['role'] ?? 'user',
    'created_at'=> $user['created_at'] ?? null,
];

header('Location: ../profile.php');
exit;
?>