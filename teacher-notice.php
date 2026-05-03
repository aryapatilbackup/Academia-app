<?php
include 'teacher-auth.php';
include 'config/db.php';

$student_id = $_SESSION['user_id'];

$result = $conn->query("
  SELECT n.*, 
         nr.id AS read_id
  FROM notices n
  LEFT JOIN notice_reads nr
    ON n.id = nr.notice_id
    AND nr.student_id = '$student_id'
  WHERE n.target='teacher' OR n.target='all'
  ORDER BY n.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Notice</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <div class="page-header">
    <a href="student-dashboard.php" class="back-btn">←</a>
    <h2>Notice</h2>
  </div>

  <?php if ($result && $result->num_rows > 0): ?>

    <?php while ($row = $result->fetch_assoc()): 
      $isUnread = empty($row['read_id']);
    ?>

    <div class="notice-card <?= $isUnread ? 'unread' : '' ?>">

      <div class="notice-header">

        <div class="notice-title">
          <?= htmlspecialchars($row['title']) ?>
        </div>

        <div class="notice-date">
          📅 <?= date("d/m/Y H:i:s", strtotime($row['created_at'])) ?>
        </div>

      </div>

      <div class="notice-message">
        <?= nl2br(htmlspecialchars($row['message'])) ?>
      </div>

      <?php if (!empty($row['link'])): ?>
        <a href="<?= $row['link'] ?>" target="_blank" class="notice-link">
          Open Link
        </a>
      <?php endif; ?>

      <?php if ($isUnread): ?>
        <span class="notice-badge">NEW</span>
      <?php endif; ?>

      <?php
      // Mark as read automatically
      if ($isUnread) {
        $notice_id = $row['id'];
        $conn->query("
          INSERT IGNORE INTO notice_reads (notice_id, student_id)
          VALUES ('$notice_id', '$student_id')
        ");
      }
      ?>

    </div>

    <?php endwhile; ?>

  <?php else: ?>

    <div class="empty-state">
      <div class="empty-icon">📢</div>
      <p>No notices available.</p>
    </div>

  <?php endif; ?>

</div>

<?php include 'includes/bottom-nav.php'; ?>

</body>
</html>