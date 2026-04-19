<?php
require_once "teacher-auth.php";
include "config/db.php";

/* Fetch all assignments */
$result = $conn->query("
  SELECT id, subject, title, due_date, upload_date
  FROM assignment_pdfs
  ORDER BY upload_date DESC
");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Assignments</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <!-- Header -->
  <div class="page-header">
    <a href="teacher-dashboard.php" class="back-btn">←</a>
    <h2>Assignments</h2>
  </div>

<?php if ($result && $result->num_rows > 0): ?>

  <?php while ($row = $result->fetch_assoc()): ?>

    <div class="exam-card">

      <div class="exam-title">
        <?= htmlspecialchars($row['subject']) ?> — <?= htmlspecialchars($row['title']) ?>
      </div>

      <div class="exam-date">
        Due: <?= date("d M Y", strtotime($row['due_date'])) ?>
      </div>

      <div class="exam-actions">
        <a href="teacher-assignment-submited.php?id=<?= $row['id'] ?>"
           class="primary-btn">
          View Submissions
        </a>
      </div>

    </div>

  <?php endwhile; ?>

<?php else: ?>

  <div class="empty-state">
    <div class="empty-icon">📝</div>
    <p>No assignments uploaded yet.</p>
  </div>

<?php endif; ?>

</div>

</body>
</html>
