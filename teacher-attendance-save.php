<?php
include 'teacher-auth.php';
include 'config/db.php';

$subject_id = $_POST['subject_id'];
$date = $_POST['date'];
$lecture_no = $_POST['lecture_no']; // NEW
$attendance = $_POST['attendance']; // [student_id => present/absent]

foreach ($attendance as $student_id => $status) {

  // check if attendance already exists for same lecture
  $check = $conn->prepare("
    SELECT id FROM attendance 
    WHERE student_id=? 
    AND subject_id=? 
    AND lecture_no=? 
    AND date=?
  ");
  $check->bind_param("iiis", $student_id, $subject_id, $lecture_no, $date);
  $check->execute();
  $res = $check->get_result();

  if ($res->num_rows > 0) {

    // UPDATE
    $update = $conn->prepare("
      UPDATE attendance 
      SET status=? 
      WHERE student_id=? 
      AND subject_id=? 
      AND lecture_no=? 
      AND date=?
    ");
    $update->bind_param("siiis", $status, $student_id, $subject_id, $lecture_no, $date);
    $update->execute();

  } else {

    // INSERT
    $insert = $conn->prepare("
      INSERT INTO attendance (student_id, subject_id, lecture_no, date, status)
      VALUES (?, ?, ?, ?, ?)
    ");
    $insert->bind_param("iiiss", $student_id, $subject_id, $lecture_no, $date, $status);
    $insert->execute();
  }
}

header("Location: teacher-attendance.php?success=1");
exit;
?>
