<?php
require_once "student-auth.php";
include "config/db.php";

$sql = "
SELECT
    oc.class_date,
    oc.start_time,
    oc.end_time,
    oc.meeting_link,
    s.name AS subject_name,
    s.code,
    s.teacher_name
FROM online_classes oc
JOIN subjects s ON oc.subject_id = s.id
WHERE oc.class_date >= CURDATE()
ORDER BY oc.class_date ASC, oc.start_time ASC
";


$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Online Class</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <!-- Header -->
  <div class="page-header">
    <a href="student-dashboard.php" class="back-btn">←</a>
    <h2>Online Class</h2>
  </div>

  <?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="online-card">

        <h4>
  <?= htmlspecialchars($row['subject_name']) ?>
  (<?= htmlspecialchars($row['code']) ?>)
</h4>


        <div class="online-time">
  <span><strong>Date:</strong> 
    <?= date("d M Y", strtotime($row['class_date'])) ?>
  </span>

  <span style="margin-left:10px;">
    <strong>Time:</strong> 
    <?= substr($row['start_time'], 0, 5) ?>
    -
    <?= substr($row['end_time'], 0, 5) ?>
  </span>
</div>

        <div class="online-meta">
          <?= htmlspecialchars($row['teacher_name']) ?>
        </div>

        <a href="<?= htmlspecialchars($row['meeting_link']) ?>"
           target="_blank"
           class="join-btn">
          Join Class
        </a>

      </div>
    <?php endwhile; ?>

  <?php else: ?>
    <!-- EMPTY STATE -->
    <div class="empty-state">
      <div class="empty-icon">🎥</div>
      <p>No online classes scheduled.</p>
    </div>
  <?php endif; ?>

</div>

</body>
</html>
