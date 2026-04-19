<?php
require_once "admin-auth.php";
include "config/db.php";

if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] !== 0) {
  die("PDF upload failed");
}

$title = trim($_POST['title']);
$uploaded_by = $_SESSION['user_id'];

// allow only PDF
$ext = strtolower(pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION));
if ($ext !== 'pdf') {
  die("Only PDF files are allowed");
}

// upload folder
$uploadDir = "uploads/exams/";
if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0777, true);
}

/* ================= FIX START ================= */

// generate UNIQUE filename (no overwrite)
$originalName = pathinfo($_FILES['pdf']['name'], PATHINFO_FILENAME);
$originalName = preg_replace("/[^a-zA-Z0-9_-]/", "", $originalName);

$fileName = "exam_" . time() . "_" . rand(1000,9999) . "_" . $originalName . ".pdf";
$targetPath = $uploadDir . $fileName;

/* ================= FIX END ================= */

// move file
if (!move_uploaded_file($_FILES['pdf']['tmp_name'], $targetPath)) {
  die("Failed to save file");
}

// save to DB
$stmt = $conn->prepare("
  INSERT INTO exam_pdfs (title, pdf_file, uploaded_by)
  VALUES (?, ?, ?)
");
$stmt->bind_param("ssi", $title, $fileName, $uploaded_by);
$stmt->execute();

header("Location: admin-exam-pdf.php?success=1");
exit;

