<!-- User Story number 5 -->

<?php

session_start();

// only accept post requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../register.php');
    exit;
}

require_once __DIR__ . '/../config/db.php';
$table = 'accounts';

// form values 
$firstname = trim($_POST['firstname'] ?? '');
$lastname  = trim($_POST['lastname'] ?? '');
$email     = trim($_POST['email'] ?? '');
$password  = $_POST['password'] ?? '';
$username  = $_POST['username'] ?? '';

// save form values in session
$_SESSION['firstname'] = $firstname;
$_SESSION['lastname']  = $lastname;
$_SESSION['email']     = $email;

$messages = [];

// validation
if ($firstname === '' || mb_strlen($firstname) < 2) {
    $messages[] = ['type' => 'danger', 'text' => 'Bitte einen gültigen Vornamen angeben.'];
}
if ($lastname === '' || mb_strlen($lastname) < 2) {
    $messages[] = ['type' => 'danger', 'text' => 'Bitte einen gültigen Nachnamen angeben.'];
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $messages[] = ['type' => 'danger', 'text' => 'Bitte eine gültige E‑Mail-Adresse angeben.'];
}
if (strlen($password) < 6) {
    $messages[] = ['type' => 'danger', 'text' => 'Passwort muss mindestens 6 Zeichen lang sein.'];
}

if ($username === '' || mb_strlen($username) < 3) {
    $messages[] = ['type' => 'danger', 'text' => 'Bitte einen gültigen Usernamen angeben (mindestens 3 Zeichen).'];
}


if (!empty($messages)) {
    $_SESSION['messages'] = $messages;
    header('Location: ../register.php');
    exit;
}

// database connection and insertion
try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // check for existing email
    $stmt = $pdo->prepare("SELECT id FROM `{$table}` WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        $_SESSION['messages'] = [['type' => 'warning', 'text' => 'Diese E‑Mail ist bereits registriert.']];
        header('Location: ../register.php');
        exit;
    }

    // insert
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $ins = $pdo->prepare("INSERT INTO `{$table}` (username, email, password, firstname, lastname, role, created_at) VALUES (:username, :email, :password, :firstname, :lastname, :role, NOW())");
    $ins->execute([
        'username'  => $username,
        'email'     => $email,
        'password'  => $hash,
        'firstname' => $firstname,
        'lastname'  => $lastname,
        'role'      => 'user',
    ]);

    // success
    unset($_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['email']);
    $_SESSION['messages'] = [['type' => 'success', 'text' => 'Registrierung erfolgreich. Sie können sich jetzt einloggen.']];

    header('Location: ../login.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['messages'] = [['type' => 'danger', 'text' => 'Datenbankfehler: ' . htmlspecialchars($e->getMessage())]];
    header('Location: ../register.php');
    exit;
}
?>