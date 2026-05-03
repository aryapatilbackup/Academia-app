<?php
require_once "student-auth.php";
include "config/db.php";

$user_id = $_SESSION['user_id'];
$conversation_id = intval($_POST['conversation_id']);
$message = trim($_POST['message']);

if ($message !== "") {
    $stmt = $conn->prepare("
        INSERT INTO messages (conversation_id, sender_id, message)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("iis", $conversation_id, $user_id, $message);
    $stmt->execute();
}