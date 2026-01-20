<?php
session_start();

// free session variables
$_SESSION = [];

// delete session-cookie 
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

// destroy session
session_destroy();

// new session to store logout message
session_start();
$_SESSION['messages'][] = ['type' => 'success', 'text' => 'Erfolgreich ausgeloggt.'];

header('Location: index.php');
exit;
?>