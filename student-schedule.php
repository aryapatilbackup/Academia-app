<?php
require_once "student-auth.php";
include "config/db.php";

/* Get selected day (default = today) */
$day = $_GET['day'] ?? date('l');

/* Fetch schedule */
$result = $conn->query("
  SELECT
    cs.start_time,
    cs.end_time,
    cs.room,
    s.name AS subject_name,
    s.code,
    s.teacher_name
  FROM class_schedule cs
  JOIN subjects s ON cs.subject_id = s.id
  WHERE cs.day = '$day'
  ORDER BY cs.start_time ASC
");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Class Schedule</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <!-- Header -->
  <div class="page-header">
    <a href="student-dashboard.php" class="back-btn">←</a>
    <h2>Class Schedule</h2>
  </div>

  <!-- Day Selector -->
  <div class="day-selector">
    <?php
    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    foreach ($days as $d):
    ?>
      <a href="?day=<?= $d ?>" 
         class="day <?= ($day == $d) ? 'active' : '' ?>">
         <?= substr($d,0,3) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- Schedule Content -->
   <?php if ($result && $result->num_rows > 0): ?>

  <?php while ($row = $result->fetch_assoc()): ?>

    <div class="dash-card schedule-card">

      <div class="time-badge">
        <?= date("g:i A", strtotime($row['start_time'])) ?>
        -
        <?= date("g:i A", strtotime($row['end_time'])) ?>
      </div>

      <h3>
        <?= htmlspecialchars($row['code']) ?> -
        <?= htmlspecialchars($row['subject_name']) ?>
      </h3>

      <div class="dash-meta">
        <span>📍 Room <?= htmlspecialchars($row['room']) ?></span>
        <span>👤 <?= htmlspecialchars($row['teacher_name']) ?></span>
      </div>

    </div>

  <?php endwhile; ?>

<?php else: ?>

  <div class="empty-state">
    <div class="empty-icon">📅</div>
    <p>No class schedule for <?= htmlspecialchars($day) ?>.</p>
  </div>

<?php endif; ?>
</div>

</body>
</html>
