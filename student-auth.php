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

// OPTIONAL: role check (safe, after session exists)
if (isset($_SESSION['role']) && $_SESSION['role'] !== "student") {
    header("Location: login.php");
    exit;
}
?>