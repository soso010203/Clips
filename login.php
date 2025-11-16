<?php
session_start();

// DB Einstellungen (angepasst an clips_accounts)
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = 'root';
$dbName = 'clips_accounts';
$table  = 'accounts';

$messages = [];

try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Datenbankfehler: ' . htmlspecialchars($e->getMessage()));
}

// Login-Verarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $messages[] = ['type' => 'danger', 'text' => 'Bitte eine gültige E‑Mail-Adresse angeben.'];
    } elseif ($password === '') {
        $messages[] = ['type' => 'danger', 'text' => 'Bitte das Passwort eingeben.'];
    } else {
        // Benutzer suchen
        $stmt = $pdo->prepare("SELECT id, email, password, firstname, lastname, role FROM `{$table}` WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $messages[] = ['type' => 'danger', 'text' => 'Ungültige Zugangsdaten.'];
        } else {
            // Login erfolgreich: Session setzen
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'role' => $user['role'],
            ];
            // Redirect (z.B. zur Startseite)
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
</head>

<header>

<!-- Navbar -->
<?php include 'parts/navbar.php';?> 


</header>


<body>

<div class="container py-4" style="max-width:600px;">
    <h2 class="mb-3">Bitte einloggen</h2>

    <?php foreach ($messages as $m): ?>
        <div class="alert alert-<?php echo htmlspecialchars($m['type']); ?>"><?php echo htmlspecialchars($m['text']); ?></div>
    <?php endforeach; ?>

    <form method="post" class="m-3" novalidate>
        <div class="mb-3">
            <label for="LoginEmail" class="form-label">E‑Mail</label>
            <input name="email" type="email" class="form-control" id="LoginEmail" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
        </div>

        <div class="mb-3">
            <label for="LoginPassword" class="form-label">Passwort</label>
            <input name="password" type="password" class="form-control" id="LoginPassword" required>
        </div>

        <button type="submit" class="btn btn-dark">Einloggen</button>
    </form>
</div>