<?php
// Kurze Anleitung zur DB-Tabelle (einmalig ausführen, falls nicht vorhanden):
// CREATE TABLE users (id INT AUTO_INCREMENT PRIMARY KEY, email VARCHAR(255) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL);

session_start();

$dsn = 'mysql:host=localhost;dbname=Accounts;charset=utf8';
$user = 'root';
$pass = 'root';

// Name der vorhandenen Tabelle festlegen (z.B. 'Accounts' oder 'users')
$table = 'Accounts';

$messages = [];
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // Bei Verbindungfehlern kurz melden und beenden
    die('Datenbankverbindung fehlgeschlagen: ' . htmlspecialchars($e->getMessage()));
}

// CSRF-Token erzeugen
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF prüfen
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $messages[] = ['type' => 'danger', 'text' => 'Ungültiges Formular (CSRF).'];
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validierung
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $messages[] = ['type' => 'danger', 'text' => 'Bitte eine gültige E‑Mail-Adresse angeben.'];
        } elseif (strlen($password) < 6) {
            $messages[] = ['type' => 'danger', 'text' => 'Passwort muss mindestens 6 Zeichen lang sein.'];
        } else {
            // Prüfen ob Email schon existiert
            $stmt = $pdo->prepare("SELECT id FROM `{$table}` WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                $messages[] = ['type' => 'warning', 'text' => 'Diese E‑Mail ist bereits registriert.'];
            } else {
                // Passwort hashen und speichern
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $ins = $pdo->prepare("INSERT INTO `{$table}` (email, password) VALUES (:email, :password)");
                $ins->execute(['email' => $email, 'password' => $hash]);
                $messages[] = ['type' => 'success', 'text' => 'Registrierung erfolgreich. Sie können sich jetzt einloggen.'];
                // Optional: Formular leeren
                $email = '';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Registrieren</title>
</head>
<body>
<div class="container py-4" style="max-width:600px;">
    <h2 class="mb-3">Registrieren</h2>

    <?php foreach ($messages as $m): ?>
        <div class="alert alert-<?php echo htmlspecialchars($m['type']); ?>"><?php echo htmlspecialchars($m['text']); ?></div>
    <?php endforeach; ?>

    <form method="post" class="m-3">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
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