<?php
require_once "chat-auth.php";
include "config/db.php";

$user_id = $_SESSION['user_id'];

// Fetch all other users
$stmt = $conn->prepare("SELECT id, name, role FROM users WHERE id != ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>New Chat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <div class="page-header">
    <a href="student-chatbox.php" class="back-btn">←</a>
    <h2>Select Contact</h2>
  </div>

  <?php while ($row = $result->fetch_assoc()): ?>

    <a href="start-private-chat.php?user_id=<?= $row['id'] ?>" class="chat-item">

      <div class="chat-avatar">
        <?= strtoupper(substr($row['name'], 0, 1)) ?>
      </div>

      <div class="chat-content">
        <div class="chat-name">
          <?= htmlspecialchars($row['name']) ?>
          <small>(<?= ucfirst($row['role']) ?>)</small>
        </div>
      </div>

    </a>

  <?php endwhile; ?>

</div>

</body>
</html>