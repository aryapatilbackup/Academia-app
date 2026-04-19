<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

body{
font-family:Arial, sans-serif;
padding:20px;
background:#f9fafb;
}

.dashboard{
display:flex;
flex-direction:column;
gap:12px;
margin-top:20px;
}

.dashboard a{
display:block;
padding:12px;
background:#ffffff;
border-radius:8px;
text-decoration:none;
color:#111;
border:1px solid #eee;
font-weight:500;
}

.dashboard a:hover{
background:#f3f4f6;
}

.logout{
background:#dc2626;
color:#fff;
text-align:center;
}

</style>

</head>

<body>

<h1>🛠️ Admin Dashboard</h1>

<div class="dashboard">

<a href="admin-notice.php">📢 Upload Notice</a>

<a href="admin-exam-pdf.php">📄 Exam PDF</a>

<a href="admin-hallticket.php">🎫 Hall Ticket</a>

<a href="admin-result.php">📊 Result</a>

<a href="admin-schedule.php">📅 Timetable</a>

<a href="admin-certificates.php">📜 Upload Certificates</a>

<a href="admin-railway-requests.php">🚆 Railway Requests</a>

<a href="admin-students.php">👨‍🎓 Manage Students</a>

<a href="admin-teachers.php">👨‍🏫 Manage Teachers</a>

<a href="admin-subjects.php">📚 Manage Subjects</a>

<a href="admin-fees.php">💰 Fees Management</a>

<a href="admin-chatbox.php">💬 Chat</a>

<a class="logout" href="logout.php">🚪 Logout</a>

</div>

</body>
</html>