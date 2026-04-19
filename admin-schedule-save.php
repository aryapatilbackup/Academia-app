<?php
require_once "admin-auth.php";
include "config/db.php";

$day        = $_POST['day'];
$subject_id = $_POST['subject_id'];
$start      = $_POST['start_time'];
$end        = $_POST['end_time'];
$room       = $_POST['room'];

$conn->query("
  INSERT INTO class_schedule (day, subject_id, start_time, end_time, room)
  VALUES ('$day', '$subject_id', '$start', '$end', '$room')
");

header("Location: admin-schedule.php?day=$day");
exit;

