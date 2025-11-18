<?php
session_start();

// MySQL / MAMP Einstellungen (Setup erfolgt nun via setup_db.php)
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = 'root';
$dbName = 'clips_accounts';
$table  = 'accounts';

$messages = [];

try {
    // direkte Verbindung zur existierenden DB (keine DB/Tabelle mehr erstellen)
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
            <input name="firstname" type="text" class="form-control" placeholder="Maxine" aria-label="firstname" value="<?php echo isset($firstname) ? htmlspecialchars($firstname) : ''; ?>" required>
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text">Nachname</span>
            <input name="lastname" type="text" class="form-control" placeholder="Musterfrau" aria-label="lastname" value="<?php echo isset($lastname) ? htmlspecialchars($lastname) : ''; ?>" required>
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