<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// auto login from cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
}

// if not logged in → redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// role check for teacher
if (isset($_SESSION['role']) && $_SESSION['role'] !== "teacher") {
    header("Location: login.php");
    exit;
}
?>
