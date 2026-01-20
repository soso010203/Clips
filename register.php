<?php

//User Story Number 5

session_start();

//flash messages 
$messages  = $_SESSION['messages'] ?? [];
$firstname = $_SESSION['firstname'] ?? '';
$lastname  = $_SESSION['lastname'] ?? '';
$email     = $_SESSION['email'] ?? '';
$username  = $_SESSION['username'] ?? '';
unset($_SESSION['messages'], $_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['email'], $_SESSION['username']);

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/clips/stylesheet.css">

    <title>Registrieren</title>
</head>

<header>
<?php include 'parts/navbar.php';?> 
</header>

<body>
<div class="container py-4" style="max-width:600px;">
    <h2 class="mb-3">Registrieren</h2>

    <?php foreach ($messages as $m): ?>
        <div class="alert alert-<?php echo htmlspecialchars($m['type']); ?>"><?php echo htmlspecialchars($m['text']); ?></div>
    <?php endforeach; ?>

    <form method="post" action="actions/registerAction.php" class="m-3">
        <div class="input-group mb-3">
            <span class="input-group-text">Username</span>
            <input name="username" type="text" class="form-control" placeholder="user_03" aria-label="username" value="<?php echo htmlspecialchars($username); ?>" required>
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text">Vorname</span>
            <input name="firstname" type="text" class="form-control" placeholder="Maxine" aria-label="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required>
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text">Nachname</span>
            <input name="lastname" type="text" class="form-control" placeholder="Musterfrau" aria-label="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required>
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="SignUpEmail">E‑Mail</span>
            <input name="email" type="email" class="form-control" placeholder="example@gmail.com" aria-label="email" value="<?php echo htmlspecialchars($email); ?>" required>
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