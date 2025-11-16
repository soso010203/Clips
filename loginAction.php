<?php
<?php
session_start();

// DB Einstellungen
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = 'root';
$dbName = 'clips_accounts';
$table  = 'accounts';

$_SESSION['messages'] = []; // init

try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    $_SESSION['messages'][] = ['type' => 'danger', 'text' => 'Datenbankfehler.'];
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['messages'][] = ['type' => 'danger', 'text' => 'Bitte eine gültige E‑Mail-Adresse angeben.'];
    $_SESSION['email'] = $email;
    header('Location: login.php');
    exit;
}

if ($password === '') {
    $_SESSION['messages'][] = ['type' => 'danger', 'text' => 'Bitte das Passwort eingeben.'];
    $_SESSION['email'] = $email;
    header('Location: login.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, email, password, firstname, lastname, role FROM `{$table}` WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    $_SESSION['messages'][] = ['type' => 'danger', 'text' => 'Fehler beim Zugriff auf die Benutzerdaten.'];
    $_SESSION['email'] = $email;
    header('Location: login.php');
    exit;
}

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['messages'][] = ['type' => 'danger', 'text' => 'Ungültige Zugangsdaten.'];
    $_SESSION['email'] = $email;
    header('Location: login.php');
    exit;
}

// Login erfolgreich: Session setzen
$_SESSION['user'] = [
    'id' => $user['id'],
    'email' => $user['email'],
    'firstname' => $user['firstname'],
    'lastname' => $user['lastname'],
    'role' => $user['role'],
];

header('Location: index.php');
exit;