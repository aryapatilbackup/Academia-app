<?php
require_once "chat-auth.php";
include "config/db.php";

$user_id = $_SESSION['user_id'];
$conversation_id = intval($_GET['id'] ?? 0);

/* MARK AS READ */
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

if ($check->get_result()->num_rows == 0) {
    die("Access denied.");
}

/* GET CHAT NAME */
$conv = $conn->prepare("SELECT * FROM conversations WHERE id=?");
$conv->bind_param("i", $conversation_id);
$conv->execute();
$conversation = $conv->get_result()->fetch_assoc();

if ($conversation['type'] === 'private') {
    $stmt2 = $conn->prepare("
        SELECT u.name 
        FROM conversation_participants cp
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
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="student.css">

<style>
.chat-bubble {
  max-width: 70%;
  padding: 10px;
  margin: 6px;
  border-radius: 12px;
  font-size: 14px;
}
.mine {
  background: #6366f1;
  color: white;
  margin-left: auto;
}
.other {
  background: #e5e7eb;
}
</style>
</head>

<body>

<div class="chat-page">

<div class="chat-header">
<?php
$role = $_SESSION['role'] ?? 'student';

$back = match($role) {
    'admin' => 'admin-chatbox.php',
    'teacher' => 'teacher-chatbox.php',
    default => 'student-chatbox.php'
};
?>

<a href="<?= $back ?>" class="back-btn">←</a>
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

let lastTime = "1970-01-01 00:00:00";
let isFetching = false;

/* FETCH NEW MESSAGES */
function fetchMessages() {
  if (isFetching) return;
  isFetching = true;

  fetch(`fetch-messages.php?id=${conversationId}&last_time=${encodeURIComponent(lastTime)}`)
    .then(res => res.text())
    .then(data => {

      if (data.trim() !== "") {
        const box = document.getElementById('chat-messages');
        box.innerHTML += data;
        box.scrollTop = box.scrollHeight;

        // UPDATE LAST TIME FROM LAST MESSAGE
        const lastMsg = box.querySelector('.chat-bubble:last-child small');
        if (lastMsg) {
          lastTime = lastMsg.getAttribute('data-time');
        }
      }

      isFetching = false;
    })
    .catch(() => isFetching = false);
}

/* SEND MESSAGE */
document.getElementById('chat-form').addEventListener('submit', function(e){
  e.preventDefault();

  const input = document.getElementById('message');
  const message = input.value;

  fetch('send-message.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'conversation_id=' + conversationId + '&message=' + encodeURIComponent(message)
  }).then(() => {
    input.value = '';
    fetchMessages(); // instant update
  });
});

/* INITIAL LOAD */
fetchMessages();

/* SMART REAL-TIME */
setInterval(fetchMessages, 1500);
</script>

</body>
</html>