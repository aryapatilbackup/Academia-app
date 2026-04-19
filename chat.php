<?php
require_once "student-auth.php";
include "config/db.php";

$user_id = $_SESSION['user_id'];
$conversation_id = intval($_GET['id'] ?? 0);



/* MARK MESSAGES AS READ */
$update = $conn->prepare("
UPDATE messages
SET is_read = 1
WHERE conversation_id = ?
AND sender_id != ?
");

$update->bind_param("ii", $conversation_id, $user_id);
$update->execute();

/* Check if user belongs to conversation */
$check = $conn->prepare("
SELECT * FROM conversation_participants
WHERE conversation_id = ? AND user_id = ?
");

$check->bind_param("ii", $conversation_id, $user_id);
$check->execute();
$resultCheck = $check->get_result();

if ($resultCheck->num_rows == 0) {
    die("Access denied.");
}

// Fetch conversation info
$conv = $conn->prepare("SELECT * FROM conversations WHERE id = ?");
$conv->bind_param("i", $conversation_id);
$conv->execute();
$conversation = $conv->get_result()->fetch_assoc();

// 🔥 FIX: Determine proper chat name
if ($conversation['type'] === 'private') {

    $stmt2 = $conn->prepare("
        SELECT u.name 
        FROM conversation_participants cp
        JOIN users u ON cp.user_id = u.id
        WHERE cp.conversation_id = ?
        AND cp.user_id != ?
        LIMIT 1
    ");
    $stmt2->bind_param("ii", $conversation_id, $user_id);
    $stmt2->execute();
    $other = $stmt2->get_result()->fetch_assoc();

    $chatName = $other['name'] ?? 'Private Chat';

} else {
    $chatName = $conversation['name'];
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Chat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="chat-page">

  <div class="chat-header">
    <a href="student-chatbox.php"  class="back-btn">←</a>
    <h3><?= htmlspecialchars($chatName) ?></h3>
  </div>

  <div id="chat-messages" class="chat-messages"></div>

  <form id="chat-form" class="chat-input">
    <input type="text" id="message" placeholder="Type a message..." required>
    <button type="submit">➤</button>
  </form>

</div>

<script>
const conversationId = <?= $conversation_id ?>;
const currentUserId = <?= $user_id ?>;

function fetchMessages() {
    fetch('fetch-messages.php?id=' + conversationId)
        .then(res => res.text())
        .then(data => {
            document.getElementById('chat-messages').innerHTML = data;
            document.getElementById('chat-messages').scrollTop =
                document.getElementById('chat-messages').scrollHeight;
        });
}

document.getElementById('chat-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const messageInput = document.getElementById('message');
    const message = messageInput.value;

    fetch('send-message.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'conversation_id=' + conversationId + '&message=' + encodeURIComponent(message)
    }).then(() => {
        messageInput.value = '';
        fetchMessages();
    });
});

setInterval(fetchMessages, 2000);
fetchMessages();
</script>

</body>
</html>