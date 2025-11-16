<?php
<?php
session_start();

// Flash-Messages und letztes Email-Feld holen
$messages = $_SESSION['messages'] ?? [];
$email = $_SESSION['email'] ?? '';
unset($_SESSION['messages'], $_SESSION['email']);
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
<?php include 'parts/navbar.php';?>
</header>

<body>
<div class="container py-4" style="max-width:600px;">
    <h2 class="mb-3">Bitte einloggen</h2>

    <?php foreach ($messages as $m): ?>
        <div class="alert alert-<?php echo htmlspecialchars($m['type']); ?>"><?php echo htmlspecialchars($m['text']); ?></div>
    <?php endforeach; ?>

    <form method="post" action="loginAction.php" class="m-3" novalidate>
        <div class="mb-3">
            <label for="LoginEmail" class="form-label">E‑Mail</label>
            <input name="email" type="email" class="form-control" id="LoginEmail" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>

        <div class="mb-3">
            <label for="LoginPassword" class="form-label">Passwort</label>
            <input name="password" type="password" class="form-control" id="LoginPassword" required>
        </div>

        <button type="submit" class="btn btn-dark">Einloggen</button>
    </form>
</div>
</body>
</html>