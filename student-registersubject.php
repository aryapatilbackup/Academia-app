<?php
require_once "student-auth.php";
include "config/db.php";

$result = $conn->query("
  SELECT name, code, type
  FROM subjects
  ORDER BY id ASC
");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Registered Subjects</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <div class="page-header">
    <a href="student-dashboard.php" class="back-btn">←</a>
    <h2>Registered Subjects</h2>
  </div>

<?php if ($result && $result->num_rows > 0): ?>

  <?php while ($row = $result->fetch_assoc()): ?>

    <div class="exam-card">

      <div class="exam-title">
        <?= htmlspecialchars($row['name']) ?>
      </div>

      <div class="exam-date">
        Code: <?= htmlspecialchars($row['code']) ?>
        • <?= ucfirst($row['type']) ?>
      </div>

    </div>

  <?php endwhile; ?>

<?php else: ?>

  <div class="empty-state">
    <div class="empty-icon">📚</div>
    <p>No subjects assigned.</p>
  </div>

<?php endif; ?>

</div>

</body>
</html>
