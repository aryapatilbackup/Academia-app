<?php
include 'teacher-auth.php';
include 'config/db.php';
date_default_timezone_set('Asia/Kolkata');

/* FETCH SUBJECTS */
$subjects = $conn->query("SELECT id, name FROM subjects");

/* FETCH STUDENTS */
$students = $conn->query("SELECT id, name FROM users WHERE role='student'");

$otpMessage = "";

/* ================= GENERATE OTP ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_otp'])) {

    $subject_id = intval($_POST['otp_subject_id']);

    if ($subject_id > 0) {

        $otp = rand(100000, 999999);
        $expires = date("Y-m-d H:i:s", strtotime("+5 minutes"));
        $today = date("Y-m-d");

        /* Get next lecture number */
        $lec = $conn->prepare("
        SELECT IFNULL(MAX(lecture_no),0)+1 AS next_lecture
        FROM attendance_sessions
        WHERE subject_id = ?
        AND DATE(created_at) = ?
        ");
        $lec->bind_param("is",$subject_id,$today);
        $lec->execute();
        $lecture = $lec->get_result()->fetch_assoc()['next_lecture'];

        /* Deactivate old sessions */
        $deactivate = $conn->prepare("
            UPDATE attendance_sessions
            SET is_active = 0
            WHERE subject_id = ?
        ");
        $deactivate->bind_param("i", $subject_id);
        $deactivate->execute();

        /* Insert new session */
        $stmt = $conn->prepare("
            INSERT INTO attendance_sessions
            (subject_id, lecture_no, otp_code, expires_at, is_active, created_at)
            VALUES (?, ?, ?, ?, 1, NOW())
        ");
        $stmt->bind_param("iiss", $subject_id, $lecture, $otp, $expires);
        $stmt->execute();

        $otpMessage = "OTP: <strong>$otp</strong> (Lecture $lecture - Valid 5 minutes)";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Mark Attendance</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="student.css">
</head>

<body class="page">

<h3>Mark Attendance</h3>

<!-- ================= OTP SECTION ================= -->

<div class="filter-card" style="margin-bottom:20px;">

<h4>Generate Attendance OTP</h4>

<?php if ($otpMessage): ?>
<div class="success-card" style="margin-bottom:10px;">
<?= $otpMessage ?>
</div>
<?php endif; ?>

<form method="post">

<label>Select Subject</label>

<select name="otp_subject_id" required>
<option value="">Select Subject</option>

<?php
$subjects2 = $conn->query("SELECT id, name FROM subjects");
while ($s2 = $subjects2->fetch_assoc()):
?>

<option value="<?= $s2['id'] ?>">
<?= htmlspecialchars($s2['name']) ?>
</option>

<?php endwhile; ?>
</select>

<button type="submit" name="generate_otp" class="primary-btn" style="margin-top:10px;">
Generate OTP
</button>

</form>

</div>

<hr>

<!-- ================= MANUAL ATTENDANCE ================= -->

<form method="post" action="teacher-attendance-save.php">

<label>Subject</label>

<select name="subject_id" required>
<option value="">Select Subject</option>

<?php
$subjects3 = $conn->query("SELECT id, name FROM subjects");
while ($s3 = $subjects3->fetch_assoc()):
?>

<option value="<?= $s3['id'] ?>">
<?= htmlspecialchars($s3['name']) ?>
</option>

<?php endwhile; ?>

</select>
<label>Lecture</label>
<select name="lecture_no" required>
<option value="1">Lecture 1</option>
<option value="2">Lecture 2</option>
<option value="3">Lecture 3</option>
<option value="4">Lecture 4</option>
</select>
<label>Date</label>
<input type="date" name="date" required>

<hr>

<?php while ($st = $students->fetch_assoc()): ?>

<div class="attendance-card">

<strong><?= htmlspecialchars($st['name']) ?></strong>

<div style="margin-top:8px;">

<label>
<input type="radio" name="attendance[<?= $st['id'] ?>]" value="present" required>
Present
</label>

<label style="margin-left:20px;">
<input type="radio" name="attendance[<?= $st['id'] ?>]" value="absent" required>
Absent
</label>

</div>

</div>

<?php endwhile; ?>

<button class="primary-btn">Save Attendance</button>

</form>

</body>
</html>