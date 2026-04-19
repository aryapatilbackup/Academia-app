<?php
require_once "student-auth.php";
include "config/db.php";

/* ✅ SORT LOGIC */
$sort = $_GET['sort'] ?? 'new';
$order = ($sort === 'old') ? 'ASC' : 'DESC';

/* ✅ FETCH ALL PDFs */
$result = $conn->query("
  SELECT title, pdf_file, created_at
  FROM exam_pdfs
  ORDER BY created_at $order
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Exam Timetable</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <!-- Header -->
  <div class="page-header">
    <a href="student-dashboard.php" class="back-btn">←</a>
    <h2>Exam Timetable</h2>
  </div>

  <!-- FILTER -->
  <form method="get" class="filter-card">
    <label>Sort by date</label>
    <select name="sort">
      <option value="new" <?= $sort === 'new' ? 'selected' : '' ?>>Newest First</option>
      <option value="old" <?= $sort === 'old' ? 'selected' : '' ?>>Oldest First</option>
    </select>

    <button class="primary-btn" style="margin-top:12px;">
      Apply
    </button>
  </form>

<?php if ($result && $result->num_rows > 0): ?>

  <?php while ($row = $result->fetch_assoc()):
    $pdfPath = "uploads/exams/" . $row['pdf_file'];
  ?>

  <!-- EXAM CARD -->
  <div class="exam-card">

    <div class="exam-title">
      <?= htmlspecialchars($row['title']) ?>
    </div>

    <div class="exam-date">
      Uploaded on <?= date("d M Y", strtotime($row['created_at'])) ?>
    </div>

    <div class="exam-actions">
      <a href="<?= $pdfPath ?>" target="_blank" class="view-btn">
        View PDF
      </a>

      <a href="<?= $pdfPath ?>" download class="download-btn">
        Download PDF
      </a>
    </div>

  </div>

  <?php endwhile; ?>

<?php else: ?>

  <!-- EMPTY STATE -->
  <div class="empty-state">
    <div class="empty-icon">📄</div>
    <p>Exam timetable not uploaded yet.</p>
  </div>

<?php endif; ?>

</div>

</body>
</html>
