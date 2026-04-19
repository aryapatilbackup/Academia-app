<?php
require_once "teacher-auth.php";

include "config/db.php";
$subjects = $conn->query("SELECT id, name FROM subjects");
if (isset($_GET['success'])): ?>
  <div class="filter-card">✅ Online class created successfully</div>
<?php endif; ?>


<!DOCTYPE html>
<html>
<head>
  <title>Create Online Class</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <!-- Header -->
  <div class="page-header">
    <a href="teacher-dashboard.php" class="back-btn">←</a>
    <h2>Create Online Class</h2>
  </div>

  <!-- FORM -->
  <form method="post" action="teacher-online-save.php" class="filter-card">

    <label>Subject</label>
<select name="subject_id" required>
  <option value="">Select Subject</option>

  <?php while ($s = $subjects->fetch_assoc()): ?>
    <option value="<?= $s['id'] ?>">
      <?= htmlspecialchars($s['name']) ?>
    </option>
  <?php endwhile; ?>

</select>


    <label>Date</label>
    <input type="date"  name="class_date" required>

    <div class="date-row">
      <div>
        <label>Start Time</label>
        <input type="time"name="start_time"  required>
      </div>
      <div>
        <label>End Time</label>
        <input type="time"name="end_time" required>
      </div>
    </div>

    <label>Meeting Link</label>
    <input type="url" name="meeting_link"placeholder="https://meet.google.com/..." required>

    <button class="primary-btn">Create Class</button>
  </form>

</div>

</body>
</html>
