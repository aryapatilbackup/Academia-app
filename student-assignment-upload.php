<?php
require_once "student-auth.php";
include "config/db.php";

$student_id = $_SESSION['user_id'];
$assignment_id = $_GET['id'] ?? null;

if (!$assignment_id) {
    die("Invalid assignment");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Upload Assignment</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <link rel="stylesheet" href="student.css">

  <style>
    .pdf-preview {
      margin-top: 12px;
      width: 100%;
      height: 400px;
      border: 1px solid #ddd;
      display: none;
    }
  </style>
</head>
<body>

<div class="page">

  <div class="page-header">
    <a href="student-assignment.php" class="back-btn">←</a>
    <h2>Upload Assignment</h2>
  </div>

  <form action="student-assignment-save.php" method="post" enctype="multipart/form-data" class="filter-card">

    <input type="hidden" name="assignment_id" value="<?= $assignment_id ?>">

    <label>Select PDF</label>
    <input type="file" name="pdf" accept="application/pdf" required
           onchange="previewPDF(this)">

    <!-- PDF PREVIEW -->
    <iframe id="pdfPreview" class="pdf-preview"></iframe>

    <!-- CONFIRM -->
    <label style="margin-top:12px; display:block;">
      <input type="checkbox" required>
      I confirm this is the correct file
    </label>

    <button class="primary-btn" style="margin-top:12px;">
      Submit Assignment
    </button>

  </form>

</div>

<script>
function previewPDF(input) {

  const file = input.files[0];
  if (!file) return;

  const preview = document.getElementById("pdfPreview");
  const url = URL.createObjectURL(file);

  // detect mobile
  const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

  if (isMobile) {

    preview.style.display = "block";
    preview.style.height = "120px";
    preview.src = "";

    preview.outerHTML = `
      <div id="pdfPreview" class="pdf-preview" style="display:flex;align-items:center;justify-content:center;flex-direction:column;height:120px;">
        <div style="font-size:40px;">📄</div>
        <div style="font-size:14px;margin-top:5px;">${file.name}</div>
        <small>Preview not supported on mobile</small>
      </div>
    `;

  } else {

    preview.src = url;
    preview.style.display = "block";

  }
}
</script>
</body>
</html>
