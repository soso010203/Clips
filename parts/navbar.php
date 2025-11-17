<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Einfache Prüfung: Session['user']['id'] muss gesetzt sein
$logged = !empty($_SESSION['user']['id']);
$displayName = $logged ? htmlspecialchars($_SESSION['user']['firstname'] ?? $_SESSION['user']['email'] ?? 'Profil') : '';
$isAdmin = $logged && (($_SESSION['user']['role'] ?? '') === 'admin');
?>
<!--Navigation-->
<nav class="navbar navbar-expand">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">CLIPS</a>

      <div class="navbar-nav ">
        <a class="nav-link active" aria-current="page" href="index.php">home</a>
        <a class="nav-link" href="search.php">search</a>
        <a class="nav-link" href="upload.php">upload</a>

        <?php if ($isAdmin): ?>
            <a class="nav-link" href="admin.php">admin</a>
        <?php endif; ?>

        <?php if ($logged): ?>
            <a class="nav-link rounded-pill text-bg-dark mx-1" href="profile.php"><?php echo $displayName; ?></a>
            <a class="nav-link rounded-pill border border-dark text-bg-light mx-1" href="logout.php">logout</a>
        <?php else: ?>
            <a class="nav-link rounded-pill text-bg-dark mx-1" href="register.php">register</a>
            <a class="nav-link rounded-pill border border-dark text-bg-light mx-1" href="login.php">login</a>
        <?php endif; ?>

      </div>
    </div>
  </div>
</nav>

