<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "config/db.php";

// auto login from cookie (SAFE)
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {

    $stmt = $conn->prepare("SELECT id, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $_COOKIE['user_id']);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
    }
}

// must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>