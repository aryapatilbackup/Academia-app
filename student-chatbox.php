<?php
require_once "student-auth.php";
include "config/db.php";

$user_id = $_SESSION['user_id'];

// Fetch all conversations user is part of
$stmt = $conn->prepare("
SELECT 
    c.id, 
    c.type, 
    c.name,
    MAX(m.created_at) as last_time,
    (SELECT message FROM messages 
        WHERE conversation_id = c.id 
        ORDER BY created_at DESC LIMIT 1) as last_message
FROM conversations c
JOIN conversation_participants cp 
    ON c.id = cp.conversation_id
LEFT JOIN messages m 
    ON c.id = m.conversation_id
WHERE cp.user_id = ?
GROUP BY c.id
ORDER BY last_time DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="student.css">
</head>

<body>

<div class="page">

<div class="page-header">
<a href="student-dashboard.php" class="back-btn">←</a>
<h2>Chats</h2>
</div>

<?php if ($result->num_rows > 0): ?>
<?php while ($row = $result->fetch_assoc()): ?>

<?php
// Private chat → get other user's name
if ($row['type'] === 'private') {

    $stmt2 = $conn->prepare("
        SELECT u.name 
        FROM conversation_participants cp
        JOIN users u ON cp.user_id = u.id
        WHERE cp.conversation_id = ?
        AND cp.user_id != ?
        LIMIT 1
    ");

    $stmt2->bind_param("ii", $row['id'], $user_id);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $otherUser = $res2->fetch_assoc();

    $chatName = $otherUser['name'] ?? 'Private Chat';

} else {
    $chatName = $row['name'];
}

/* UNREAD COUNT */
$unreadStmt = $conn->prepare("
SELECT COUNT(*) as total
FROM messages
WHERE conversation_id = ?
AND sender_id != ?
AND is_read = 0
");

$unreadStmt->bind_param("ii", $row['id'], $user_id);
$unreadStmt->execute();
$unreadRes = $unreadStmt->get_result();
$unreadRow = $unreadRes->fetch_assoc();
$unreadCount = $unreadRow['total'];
?>

<a href="chat.php?id=<?= $row['id'] ?>" class="chat-item">

  <div class="chat-avatar">
    <?= strtoupper(substr($chatName, 0, 1)) ?>
  </div>

  <div class="chat-content">

    <div class="chat-top">
      <span class="chat-name"><?= htmlspecialchars($chatName) ?></span>

      <span class="chat-time">
        <?= $row['last_time'] ? date("h:i A", strtotime($row['last_time'])) : '' ?>
      </span>
    </div>

    <div class="chat-preview">
      <?= htmlspecialchars($row['last_message'] ?? 'No messages yet') ?>
    </div>

  </div>

  <?php if ($unreadCount > 0): ?>
    <span class="chat-badge"><?= $unreadCount ?></span>
  <?php endif; ?>

</a>

<?php endwhile; ?>

<?php else: ?>

<div class="empty-state">
<p>No chats available.</p>
</div>

<?php endif; ?>

</div>

<a href="new-chat.php" class="chat-float-btn">+</a>

<?php include 'includes/bottom-nav.php'; ?>

<script>
setInterval(function(){
  location.reload();
}, 2000);
</script>
</body>
</html>