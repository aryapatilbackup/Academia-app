<?php
require_once "teacher-auth.php";
include "config/db.php";

$subject  = $_POST['subject'];
$title    = $_POST['title'];
$due_date = $_POST['due_date'];

if ($_FILES['pdf']['type'] !== 'application/pdf') {
    die("Only PDF allowed");
}
$dir = "uploads/assignments/";
if (!is_dir($dir)) mkdir($dir, 0777, true);

$filename = "assign_" . time() . "_" . rand(1000,9999) . ".pdf";
move_uploaded_file($_FILES['pdf']['tmp_name'], $dir . $filename);

$conn->query("
  INSERT INTO assignment_pdfs (subject, title, due_date, pdf_file)
  VALUES ('$subject', '$title', '$due_date', '$filename')
");

header("Location: teacher-assignment.php?success=1");
exit;
