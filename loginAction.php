<?php
ini_set("display_errors", "1");
session_start();

$error = "";
$adminUser = "admin@";
$adminPass = "123";

if( $_SERVER["REQUEST_METHOD"] == "POST"){
    echo "TSET 1";
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username == $adminUser && $password == $adminPass){

        $_SESSION['admin_logged_in'] = true;
        echo "TSET";
        // header("Location: profile.php");
        exit;

    } else {
        $error = "Invalid username or password";
    }
}
?>