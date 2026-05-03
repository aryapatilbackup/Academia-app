<?php
require_once "student-auth.php";
include "config/db.php";

$current_user = $_SESSION['user_id'];
$other_user = intval($_GET['user_id']);

$stmt = $conn->prepare("
SELECT c.id
FROM conversations c
JOIN conversation_participants cp1 ON c.id = cp1.conversation_id
JOIN conversation_participants cp2 ON c.id = cp2.conversation_id
WHERE c.type = 'private'
AND cp1.user_id = ?
AND cp2.user_id = ?
LIMIT 1
");
$stmt->bind_param("ii", $current_user, $other_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $conversation_id = $result->fetch_assoc()['id'];
} else {
    $conn->query("INSERT INTO conversations (type) VALUES ('private')");
    $conversation_id = $conn->insert_id;

    $add = $conn->prepare("INSERT INTO conversation_participants (conversation_id, user_id) VALUES (?, ?)");
    $add->bind_param("ii", $conversation_id, $current_user);
    $add->execute();

    $add->bind_param("ii", $conversation_id, $other_user);
    $add->execute();
}

header("Location: chat.php?id=" . $conversation_id);
exit;