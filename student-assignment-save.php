<?php
require_once "student-auth.php";
include "config/db.php";

$student_id    = $_SESSION['user_id'];
$assignment_id = $_POST['assignment_id'];

if ($_FILES['pdf']['type'] !== 'application/pdf') {
    die("Only PDF allowed");
}

$dir = "uploads/assignment-submissions/";
if (!is_dir($dir)) mkdir($dir, 0777, true);

$filename = "sub_{$assignment_id}_{$student_id}.pdf";
move_uploaded_file($_FILES['pdf']['tmp_name'], $dir . $filename);

/* CHECK if already submitted */
$check = $conn->query("
  SELECT id FROM assignment_submissions
  WHERE assignment_id = '$assignment_id'
  AND student_id = '$student_id'
");

if ($check && $check->num_rows > 0) {

  // UPDATE existing submission
  $conn->query("
    UPDATE assignment_submissions
    SET pdf_file = '$filename',
        submitted_at = NOW()
    WHERE assignment_id = '$assignment_id'
    AND student_id = '$student_id'
  ");

} else {

  // INSERT new submission
  $conn->query("
    INSERT INTO assignment_submissions (assignment_id, student_id, pdf_file)
    VALUES ('$assignment_id', '$student_id', '$filename')
  ");
}

header("Location: student-assignment.php?submitted=1");
exit;

