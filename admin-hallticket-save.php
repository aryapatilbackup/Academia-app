<?php
include 'admin-auth.php';
include 'config/db.php';

$student_id   = $_POST['student_id'];
$exam_session = $_POST['exam_session'];

if ($_FILES['pdf']['type'] !== 'application/pdf') {
    die('Only PDF allowed');
}

$dir = 'uploads/halltickets/';
if (!is_dir($dir)) mkdir($dir, 0777, true);

$filename = 'hall_' . time() . '_' . rand(1000,9999) . '.pdf';
move_uploaded_file($_FILES['pdf']['tmp_name'], $dir . $filename);

mysqli_query($conn, "
    INSERT INTO hall_tickets (student_id, exam_session, pdf_file)
    VALUES ('$student_id', '$exam_session', '$filename')
");

header("Location: admin-hallticket.php?success=1");
exit;

