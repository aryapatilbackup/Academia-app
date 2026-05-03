<?php
include 'teacher-auth.php';
?>

<!DOCTYPE html>
<html>
<head>
<title>Teacher Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="student.css">
</head>

<body>

<div class="page">

<h1>Welcome Teacher</h1>

<div style="display:flex;flex-direction:column;gap:12px;margin-top:20px;">

<a class="primary-btn" href="teacher-attendance.php">
📋 Mark Attendance
</a>

<a class="primary-btn" href="teacher-assignment.php">
📝 Give Assignment
</a>

<a class="primary-btn" href="teacher-assignment-list.php">
📂 View Assignments
</a>

<a class="primary-btn" href="teacher-online.php">
🎥 Online Class
</a>

<a class="primary-btn" href="teacher-chatbox.php">
💬 Chat
</a>

<a class="primary-btn" href="teacher-notice.php">
📢 Notices
</a>



<a class="primary-btn" href="logout.php" style="background:#dc2626;">
🚪 Logout
</a>

</div>

</div>

</body>
</html>

