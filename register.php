<?php
session_start();

// MySQL / MAMP Einstellungen
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = 'root';

// Neue DB und Tabelle-Name
$dbName = 'clips_accounts';
$table  = 'accounts';

$messages = [];

try {
    // Zuerst mit Server verbinden (ohne DB), DB erstellen falls nötig
    $pdo = new PDO("mysql:host={$dbHost};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS clips_accounts CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $pdo->exec("USE clips_accounts;");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `accounts` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `email` VARCHAR(255) NOT NULL UNIQUE,
      `password` VARCHAR(255) NOT NULL,
      `firstname` VARCHAR(100) NOT NULL,
      `lastname` VARCHAR(100) NOT NULL,
      `role` ENUM('user','admin') NOT NULL DEFAULT 'user',
      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    // Verbindung zur neu angelegten DB
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Datenbankfehler: ' . htmlspecialchars($e->getMessage()));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hinweis: CSRF-Prüfung ist hier nicht enthalten (unsicher für öffentliche Seiten)
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname  = trim($_POST['lastname'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';

    // Validierung
    if ($firstname === '' || mb_strlen($firstname) < 2) {
        $messages[] = ['type' => 'danger', 'text' => 'Bitte einen gültigen Vornamen angeben.'];
    } elseif ($lastname === '' || mb_strlen($lastname) < 2) {
        $messages[] = ['type' => 'danger', 'text' => 'Bitte einen gültigen Nachnamen angeben.'];
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $messages[] = ['type' => 'danger', 'text' => 'Bitte eine gültige E‑Mail-Adresse angeben.'];
    } elseif (strlen($password) < 6) {
        $messages[] = ['type' => 'danger', 'text' => 'Passwort muss mindestens 6 Zeichen lang sein.'];
    } else {
        // Existenz prüfen
        $stmt = $pdo->prepare("SELECT id FROM `{$table}` WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $messages[] = ['type' => 'warning', 'text' => 'Diese E‑Mail ist bereits registriert.'];
        } else {
            // Passwort hashen und Benutzer anlegen, Rolle = 'user' standardmäßig
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $pdo->prepare("INSERT INTO `{$table}` (email, password, firstname, lastname, role, created_at) VALUES (:email, :password, :firstname, :lastname, :role, NOW())");
            $ins->execute([
                'email'     => $email,
                'password'  => $hash,
                'firstname' => $firstname,
                'lastname'  => $lastname,
                'role'      => 'user',
            ]);
            $messages[] = ['type' => 'success', 'text' => 'Registrierung erfolgreich. Sie können sich jetzt einloggen.'];
            $firstname = $lastname = $email = '';
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
    <title>Registrieren</title>
</head>

<header>
<!-- Navbar -->
<?php include 'parts/navbar.php';?> 
</header>

<body>
<div class="container py-4" style="max-width:600px;">
    <h2 class="mb-3">Registrieren</h2>

    <?php foreach ($messages as $m): ?>
        <div class="alert alert-<?php echo htmlspecialchars($m['type']); ?>"><?php echo htmlspecialchars($m['text']); ?></div>
    <?php endforeach; ?>

    <form method="post" class="m-3">
        <div class="input-group mb-3">
            <span class="input-group-text">Vorname</span>
            <input name="firstname" type="text" class="form-control" placeholder="Max" aria-label="firstname" value="<?php echo isset($firstname) ? htmlspecialchars($firstname) : ''; ?>" required>
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text">Nachname</span>
            <input name="lastname" type="text" class="form-control" placeholder="Mustermann" aria-label="lastname" value="<?php echo isset($lastname) ? htmlspecialchars($lastname) : ''; ?>" required>
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="SignUpEmail">E‑Mail</span>
            <input name="email" type="email" class="form-control" placeholder="example@gmail.com" aria-label="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="SignUpPassword">Passwort</span>
            <input name="password" type="password" class="form-control" placeholder="********" aria-label="password" required>
        </div>

        <button type="submit" class="btn btn-dark m-3">Registrieren</button>
    </form>
</div>
</body>
</html>