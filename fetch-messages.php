<?php
require_once "student-auth.php";
include "config/db.php";

$user_id = $_SESSION['user_id'];
$conversation_id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT m.*, u.name ,u.role 
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.conversation_id = ?
    ORDER BY m.created_at ASC
");
$stmt->bind_param("i", $conversation_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {

    $isMine = $row['sender_id'] == $user_id;
    ?>
    <div class="chat-bubble <?= $isMine ? 'mine' : 'other' ?>">
        <span class="sender-name">
    <?= htmlspecialchars($row['name']) ?>
    <span class="sender-role <?= $row['role'] ?>">
        (<?= ucfirst($row['role']) ?>)
    </span>
</span>
        <div><?= htmlspecialchars($row['message']) ?></div>
        <small><?= date("h:i A", strtotime($row['created_at'])) ?></small>
    </div>
    <?php
}