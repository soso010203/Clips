<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<nav class="mb-4">
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link text-body-tertiary" href="admin/userManagment.php">User Management</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-body-tertiary" href="admin/postManagement.php">Post Management</a>
    </li>
  </ul>
</nav>