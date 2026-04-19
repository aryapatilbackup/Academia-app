<?php
require_once "teacher-auth.php";
include "config/db.php";

$subject = $_POST['subject_id'];
$class_date = $_POST['class_date'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$meeting_link = $_POST['meeting_link'];
$teacher_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
  INSERT INTO online_classes 
  (subject_id, teacher_id, class_date, start_time, end_time, meeting_link)
  VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
  "sissss",
  $subject,
  $teacher_id,
  $class_date,
  $start_time,
  $end_time,
  $meeting_link
);

$stmt->execute();

header("Location: teacher-online.php?success=1");
exit;
