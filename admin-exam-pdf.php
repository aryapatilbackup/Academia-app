<?php
require_once "admin-auth.php";
include "config/db.php";
?>

<!DOCTYPE html>
<html>
<head>
  <title>Upload Exam Timetable</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <div class="page-header">
    
    <a href="admin-dashboard.php" class="back-btn">←</a>
    <h2>Upload Exam Timetable</h2>
  </div>

  <form method="post" action="admin-exam-pdf-save.php" enctype="multipart/form-data" class="filter-card">

    <label>Title</label>
    <input type="text" name="title" placeholder="FY BCA End Sem Exam Timetable" required>

    <label>Upload PDF</label>
    <input type="file" name="pdf" accept="application/pdf" required>

    <button class="primary-btn">Upload PDF</button>

  </form>
<?php if (isset($_GET['success'])): ?>
  <div id="toast" class="toast">
    Exam timetable PDF uploaded successfully
  </div>
<?php endif; ?>
<?php include 'includes/toast.php'; ?>
</div>

</body>
</html>
