<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// auto login from cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
}

// must be logged in (ANY role)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>