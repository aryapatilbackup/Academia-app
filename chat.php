<?php
require_once "student-auth.php";
include "config/db.php";

$user_id = $_SESSION['user_id'];
$conversation_id = intval($_GET['id'] ?? 0);

/* MARK READ */
$update = $conn->prepare("
UPDATE messages SET is_read = 1
WHERE conversation_id = ? AND sender_id != ?
");
$update->bind_param("ii", $conversation_id, $user_id);
$update->execute();

/* SECURITY CHECK */
$check = $conn->prepare("
SELECT * FROM conversation_participants
WHERE conversation_id = ? AND user_id = ?
");
$check->bind_param("ii", $conversation_id, $user_id);
$check->execute();
if ($check->get_result()->num_rows == 0) die("Access denied.");

/* GET CHAT NAME */
$conv = $conn->prepare("SELECT * FROM conversations WHERE id=?");
$conv->bind_param("i", $conversation_id);
$conv->execute();
$conversation = $conv->get_result()->fetch_assoc();

if ($conversation['type'] === 'private') {
    $stmt2 = $conn->prepare("
        SELECT u.name FROM conversation_participants cp
        JOIN users u ON cp.user_id = u.id
        WHERE cp.conversation_id=? AND cp.user_id!=?
        LIMIT 1
    ");
    $stmt2->bind_param("ii", $conversation_id, $user_id);
    $stmt2->execute();
    $chatName = $stmt2->get_result()->fetch_assoc()['name'] ?? 'Chat';
} else {
    $chatName = $conversation['name'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat</title>
<link rel="stylesheet" href="student.css">
</head>
<body>

<div class="chat-page">

<div class="chat-header">
<a href="student-chatbox.php" class="back-btn">←</a>
<h3><?= htmlspecialchars($chatName) ?></h3>
<button onclick="fetchMessages()">🔄</button>
</div>

<div id="chat-messages" class="chat-messages"></div>

<form id="chat-form" class="chat-input">
<input type="text" id="message" placeholder="Type..." required>
<button>➤</button>
</form>

</div>

<script>
const conversationId = <?= $conversation_id ?>;

function fetchMessages() {
  fetch('fetch-messages.php?id=' + conversationId)
    .then(res => res.text())
    .then(data => {
      const box = document.getElementById('chat-messages');
      box.innerHTML = data;
      box.scrollTop = box.scrollHeight;
    });
}

// load once
fetchMessages();

// refresh when user returns to tab
window.addEventListener("focus", fetchMessages);

document.getElementById('chat-form').addEventListener('submit', function(e){
  e.preventDefault();

  const input = document.getElementById('message');

  fetch('send-message.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'conversation_id=' + conversationId + '&message=' + encodeURIComponent(input.value)
  }).then(() => {
    input.value = '';
    fetchMessages();
  });
});
</script>

</body>
</html>