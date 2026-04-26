<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// remove session
session_unset();
session_destroy();

// ❌ remove remember-me cookie
if (isset($_COOKIE['user_id'])) {
    setcookie("user_id", "", time() - 3600, "/");
}

header("Location: login.php");
exit;
?>