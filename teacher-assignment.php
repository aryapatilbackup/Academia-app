<?php
require_once "teacher-auth.php";
include "config/db.php";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Upload Assignment</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <div class="page-header">
    <a href="teacher-dashboard.php" class="back-btn">←</a>
    <h2>Upload Assignment</h2>
  </div>

  <form action="teacher-assignment-save.php" method="post" enctype="multipart/form-data" class="filter-card">

    <label>Subject</label>
    <input type="text" name="subject" required>

    <label style="margin-top:10px;">Title</label>
    <input type="text" name="title" required>

    <label style="margin-top:10px;">Due Date</label>
    <input type="date" name="due_date" required>

    <label style="margin-top:10px;">Assignment PDF</label>
    <input type="file" name="pdf" accept="application/pdf" required>

    <button class="primary-btn" style="margin-top:12px;">
      Upload Assignment
    </button>

  </form>
<?php if (isset($_GET['success'])): ?>
  <div class="empty-state">
    <div class="empty-icon">✅</div>
    <p>Assignment uploaded successfully.</p>
  </div>
<?php endif; ?>


</div>

</body>
</html>
