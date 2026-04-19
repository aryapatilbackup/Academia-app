<?php
require_once "student-auth.php";
include "config/db.php";
 include 'includes/toast.php';
$student_id = $_SESSION['user_id'];

/* SORT */
$sort  = $_GET['sort'] ?? 'new';
$order = ($sort === 'old') ? 'ASC' : 'DESC';

/* FETCH ASSIGNMENTS */
$result = $conn->query("
  SELECT a.id, a.subject, a.title, a.due_date, a.pdf_file, a.upload_date,
         s.id AS submission_id,
         s.submitted_at
  FROM assignment_pdfs a
  LEFT JOIN assignment_submissions s
       ON a.id = s.assignment_id
       AND s.student_id = '$student_id'
  ORDER BY a.upload_date $order
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
    <a href="student-dashboard.php" class="back-btn">←</a>
    <h2>Assignments</h2>
  </div>

  <!-- FILTER -->
  <form method="get" class="filter-card">
    <label>Sort by date</label>
    <select name="sort">
      <option value="new" <?= $sort==='new'?'selected':'' ?>>Newest First</option>
      <option value="old" <?= $sort==='old'?'selected':'' ?>>Oldest First</option>
    </select>
    <button class="primary-btn" style="margin-top:12px;">Apply</button>
  </form>

<?php if ($result && $result->num_rows > 0): ?>

  <?php while ($row = $result->fetch_assoc()):
    $pdfPath = "uploads/assignments/" . $row['pdf_file'];
  ?>
<?php 
$isSubmitted = !empty($row['submission_id']); 
?>
  <!-- ASSIGNMENT CARD -->
<div class="exam-card">

  <!-- TOP RIGHT BADGE -->
  <div class="assignment-badge <?= $isSubmitted ? 'submitted' : 'pending' ?>">
    <?php if ($isSubmitted): ?>
      Submitted
    <?php else: ?>
      Pending
    <?php endif; ?>
  </div>

  <div class="exam-title">
    <?= htmlspecialchars($row['subject']) ?> — <?= htmlspecialchars($row['title']) ?>
  </div>

  <div class="exam-date">
    Due: <?= date("d M Y", strtotime($row['due_date'])) ?>
  </div>

  <?php if ($isSubmitted): ?>
    <div class="submitted-date">
      Submitted on <?= date("d M Y", strtotime($row['submitted_at'])) ?>
    </div>
  <?php endif; ?>

  <div class="exam-actions">
    <a href="<?= $pdfPath ?>" target="_blank" class="view-btn">View</a>
    <a href="<?= $pdfPath ?>" download class="download-btn">Download</a>
    <a href="student-assignment-upload.php?id=<?= $row['id'] ?>" class="upload-btn">
      <?= $isSubmitted ? 'Submit Again' : 'Upload' ?>
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
<?php if (isset($_GET['submitted'])): ?>
<div id="toast" class="toast-msg exam-card" style="background:#dcfce7;border-left:5px solid #16a34a;">
  <div style="color:#16a34a;font-weight:600;">
    Assignment submitted successfully.
  </div>
</div>
<?php endif; ?>

</div>

</body>
</html>
