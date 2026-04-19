<?php
require_once "teacher-auth.php";
include "config/db.php";

$assignment_id = $_GET['id'] ?? null;
if (!$assignment_id) {
    die("Invalid assignment");
}

/* Assignment info */
$assign = $conn->query("
  SELECT subject, title
  FROM assignment_pdfs
  WHERE id = '$assignment_id'
")->fetch_assoc();

/* Submissions */
$result = $conn->query("
  SELECT u.name, s.pdf_file, s.submitted_at
  FROM assignment_submissions s
  JOIN users u ON u.id = s.student_id
  WHERE s.assignment_id = '$assignment_id'
  ORDER BY s.submitted_at DESC
");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Submitted Assignments</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <!-- Header -->
  <div class="page-header">
    <a href="teacher-assignment-list.php" class="back-btn">←</a>
    <h2>Submissions</h2>
  </div>

  <!-- Assignment info -->
  <div class="filter-card">
    <strong><?= htmlspecialchars($assign['subject']) ?></strong><br>
    <?= htmlspecialchars($assign['title']) ?>
  </div>

<?php if ($result && $result->num_rows > 0): ?>

  <?php while ($row = $result->fetch_assoc()):
    $filePath = "uploads/assignment-submissions/" . $row['pdf_file'];
  ?>

  <div class="exam-card">
    <div class="exam-title">
      <?= htmlspecialchars($row['name']) ?>
    </div>

    <div class="exam-date">
      Submitted on <?= date("d M Y, h:i A", strtotime($row['submitted_at'])) ?>
    </div>

    <div class="exam-actions">
      <a href="<?= $filePath ?>" target="_blank" class="view-btn">View</a>
      <a href="<?= $filePath ?>" download class="download-btn">Download</a>
    </div>
  </div>

  <?php endwhile; ?>

<?php else: ?>

  <div class="empty-state">
    <div class="empty-icon">📂</div>
    <p>No submissions yet.</p>
  </div>

<?php endif; ?>

</div>

</body>
</html>
