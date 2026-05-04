<?php
require_once "student-auth.php";
include "config/db.php";

$user_id = $_SESSION['user_id'];
$conversation_id = intval($_GET['id']);
$last_time = $_GET['last_time'] ?? '1970-01-01 00:00:00';

/* FETCH ONLY NEW MESSAGES */
$stmt = $conn->prepare("
    SELECT m.*, u.name, u.role
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.conversation_id = ?
    AND m.created_at > ?
    ORDER BY m.created_at ASC
");

$stmt->bind_param("is", $conversation_id, $last_time);
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

    <small data-time="<?= $row['created_at'] ?>">
        <?= date("h:i A", strtotime($row['created_at'])) ?>
    </small>

</div>
<?php } ?>