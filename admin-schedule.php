<?php
require_once "admin-auth.php";
include "config/db.php";

/* Selected Day */
$day = $_GET['day'] ?? 'Monday';

/* Handle Delete */
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM class_schedule WHERE id='$id'");
    header("Location: admin-schedule.php?day=$day");
    exit;
}

/* Fetch Subjects */
$subjects = $conn->query("
    SELECT id, name, code 
    FROM subjects 
    ORDER BY name ASC
");

/* Fetch Schedule for Selected Day */
$schedule = $conn->query("
    SELECT cs.id, cs.day, cs.start_time, cs.end_time, cs.room,
           s.name, s.code
    FROM class_schedule cs
    JOIN subjects s ON cs.subject_id = s.id
    WHERE cs.day = '$day'
    ORDER BY cs.start_time ASC
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Class Schedule</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <!-- Header -->
  <div class="page-header">
    <a href="admin-dashboard.php" class="back-btn">←</a>
    <h2>Manage Class Schedule</h2>
  </div>

  <!-- Add Schedule Form -->
  <form action="admin-schedule-save.php" method="post" class="filter-card">

    <input type="hidden" name="current_day" value="<?= $day ?>">

    <label>Day</label>
    <select name="day" required>
      <option value="">Select Day</option>
      <?php
      $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
      foreach ($days as $d):
      ?>
        <option value="<?= $d ?>" <?= ($day == $d) ? 'selected' : '' ?>>
          <?= $d ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Subject</label>
    <select name="subject_id" required>
      <option value="">Select Subject</option>
      <?php while($sub = $subjects->fetch_assoc()): ?>
        <option value="<?= $sub['id'] ?>">
          <?= $sub['name'] ?> (<?= $sub['code'] ?>)
        </option>
      <?php endwhile; ?>
    </select>

    <label>Start Time</label>
    <input type="time" name="start_time" required>

    <label>End Time</label>
    <input type="time" name="end_time" required>

    <label>Room</label>
    <input type="text" name="room" required>

    <button class="primary-btn" style="margin-top:12px;">
      Add Schedule
    </button>

  </form>

  <!-- Existing Schedule -->
  <h4 style="margin-top:20px;">Existing Schedule</h4>

  <!-- Day Tabs -->
  <div class="day-selector">
    <?php foreach ($days as $d): ?>
      <a href="?day=<?= $d ?>" 
         class="day <?= ($day == $d) ? 'active' : '' ?>">
         <?= substr($d,0,3) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- Schedule Cards -->
  <?php if ($schedule && $schedule->num_rows > 0): ?>
    
    <?php while($row = $schedule->fetch_assoc()): ?>
      <div class="exam-card">

        <div class="exam-title">
          <?= $row['name'] ?> (<?= $row['code'] ?>)
        </div>

        <div class="exam-date">
          <?= substr($row['start_time'],0,5) ?> -
          <?= substr($row['end_time'],0,5) ?> •
          Room: <?= $row['room'] ?>
        </div>

        <div class="exam-actions">
          <a href="?day=<?= $day ?>&delete=<?= $row['id'] ?>" 
             class="download-btn">
             Delete
          </a>
        </div>

      </div>
    <?php endwhile; ?>

  <?php else: ?>

    <div class="empty-state">
      <div class="empty-icon">📅</div>
      <p>No schedule for <?= $day ?>.</p>
    </div>

  <?php endif; ?>

</div>

</body>
</html>
