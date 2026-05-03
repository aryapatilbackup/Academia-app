<?php
require_once "admin-auth.php";
include "config/db.php";

/* ===============================
   FETCH ALL TIMETABLES
=================================*/
$timetables = $conn->query("SELECT * FROM exam_pdfs ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Upload Exam Timetable</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">
</head>

<body class="page">

  <!-- HEADER -->
  <div class="page-header">
    <a href="admin-dashboard.php" class="back-btn">←</a>
    <h2>Upload Exam Timetable</h2>
  </div>

  <!-- SUCCESS MESSAGE -->
  <?php if (isset($_GET['success'])): ?>
    <div class="exam-card" style="background:#dcfce7; border-left:5px solid #16a34a;">
      <div style="color:#16a34a; font-weight:600;">
        Exam timetable PDF uploaded successfully
      </div>
    </div>
  <?php endif; ?>

  <!-- FORM -->
  <form method="post" action="admin-exam-pdf-save.php" enctype="multipart/form-data" class="filter-card">

    <label>Title</label>
    <input type="text" name="title" placeholder="FY BCA End Sem Exam Timetable" required>

    <label>Upload PDF</label>
    <input type="file" name="pdf" accept="application/pdf" required>

    <button class="primary-btn">Upload PDF</button>

  </form>

  <!-- ===============================
       ALL TIMETABLES
  =================================-->
  <div class="page-header" style="margin-top:20px;">
    <h2>All Timetables</h2>
  </div>

  <?php if ($timetables && $timetables->num_rows > 0): ?>

    <?php while($t = $timetables->fetch_assoc()): ?>

      <div class="exam-card">

        <!-- TITLE -->
        <div style="font-weight:600; font-size:16px;">
          <?= htmlspecialchars($t['title']) ?>
        </div>

        <!-- FILE LINK -->
        <a href="<?= $pdfPath ?>" target="_blank" class="view-btn">
        View PDF
      </a>

      </div>

    <?php endwhile; ?>

  <?php else: ?>

    <div class="exam-card">
      No timetables uploaded yet.
    </div>

  <?php endif; ?>

</body>
</html>