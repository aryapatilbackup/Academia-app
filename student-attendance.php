<?php
include 'student-auth.php';
include 'config/db.php';
include 'includes/toast.php'; 
date_default_timezone_set('Asia/Kolkata');

$student_id = $_SESSION['user_id'];
$otpMessage = "";

$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';

$whereDate = '';
$params = [$student_id];
$types = "i";

/* ================= AUTO MARK ABSENT AFTER OTP EXPIRES ================= */

$expired = $conn->query("
SELECT id, subject_id, lecture_no
FROM attendance_sessions
WHERE expires_at < NOW()
AND is_active = 1
");

while ($session = $expired->fetch_assoc()) {

    $subject_id = $session['subject_id'];
    $lecture_no = $session['lecture_no'];
    $today = date("Y-m-d");

    $students = $conn->query("
        SELECT id FROM users
        WHERE role='student'
        AND id NOT IN (
            SELECT student_id FROM attendance
            WHERE subject_id='$subject_id'
            AND lecture_no='$lecture_no'
            AND date='$today'
        )
    ");

    while ($st = $students->fetch_assoc()) {

        $sid = $st['id'];

        $conn->query("
        INSERT INTO attendance (student_id, subject_id, lecture_no, date, status)
        VALUES ('$sid','$subject_id','$lecture_no','$today','absent')
        ");
    }

    $conn->query("
    UPDATE attendance_sessions
    SET is_active = 0
    WHERE id='{$session['id']}'
    ");
}

/* ================= OTP PROCESS ================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {

    $otp = $_POST['otp'];

    $stmt = $conn->prepare("
        SELECT * FROM attendance_sessions
        WHERE otp_code = ?
        AND expires_at >= NOW()
        AND is_active = 1
        LIMIT 1
    ");
    $stmt->bind_param("s", $otp);
    $stmt->execute();
    $otpResult = $stmt->get_result();

    if ($otpResult->num_rows > 0) {

        $session = $otpResult->fetch_assoc();
        $subject_id = $session['subject_id'];
        $lecture_no = $session['lecture_no'];
        $today = date("Y-m-d");

        $check = $conn->prepare("
            SELECT id FROM attendance
            WHERE student_id = ?
            AND subject_id = ?
            AND lecture_no = ?
            AND date = ?
        ");
        $check->bind_param("iiis", $student_id, $subject_id, $lecture_no, $today);
        $check->execute();
        $exists = $check->get_result();

        if ($exists->num_rows == 0) {

            $insert = $conn->prepare("
                INSERT INTO attendance (student_id, subject_id, lecture_no, date, status)
                VALUES (?, ?, ?, ?, 'present')
            ");
            $insert->bind_param("iiis", $student_id, $subject_id, $lecture_no, $today);
            $insert->execute();

            $otpMessage = "Attendance marked successfully!";
        } else {
            $otpMessage = "Attendance already marked for this lecture.";
        }

    } else {
        $otpMessage = "Invalid or expired OTP!";
    }
}

/* ================= FILTER ================= */

if ($from && $to) {
  $whereDate = " AND attendance.date BETWEEN ? AND ?";
  $params[] = $from;
  $params[] = $to;
  $types .= "ss";
}

$sql = "
SELECT 
  subjects.id,
  subjects.name AS subject,
  subjects.code,
  subjects.teacher_name,
  COUNT(attendance.id) AS total,
  SUM(attendance.status='present') AS present
FROM attendance
JOIN subjects ON attendance.subject_id = subjects.id
WHERE attendance.student_id = ? $whereDate
GROUP BY subjects.id
";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Attendance</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="student.css">
</head>

<body class="page">

<div class="page-header">
<a href="student-dashboard.php" class="back-btn">←</a>
<h2>Attendance</h2>
</div>

<?php if ($otpMessage): ?>

<?php
$isError = ($otpMessage === "Invalid or expired OTP!");
?>

<div id="toast" class="toast-msg exam-card"
     style="background:<?= $isError ? '#fee2e2' : '#dcfce7' ?>;
            border-left:5px solid <?= $isError ? '#dc2626' : '#16a34a' ?>;">

  <div style="color:<?= $isError ? '#dc2626' : '#16a34a' ?>;font-weight:600;">
    <?= htmlspecialchars($otpMessage) ?>
  </div>

</div>

<?php endif; ?>

<form method="get" class="filter-card">

<div class="date-row">

<div>
<label>From Date</label>
<input type="date" name="from" value="<?= $from ?>">
</div>

<div>
<label>To Date</label>
<input type="date" name="to" value="<?= $to ?>">
</div>

</div>

<button class="primary-btn">Show Attendance</button>

</form>

<?php while ($row = $result->fetch_assoc()):

$total = $row['total'];
$present = $row['present'];
$absent = $total - $present;

$percentage = $total > 0 ? round(($present / $total) * 100) : 0;

?>

<div class="attendance-card">

<div class="subject-head">

<h4><?= htmlspecialchars($row['subject']) ?></h4>

<small>
<?= htmlspecialchars($row['code']) ?> •
<?= htmlspecialchars($row['teacher_name']) ?>
</small>

<a href="student-attendance-detail.php?subject_id=<?= $row['id'] ?>">
See All
</a>

</div>

<div class="percentage <?= $percentage < 40 ? 'red-dot' : 'green-dot' ?>">
<?= $percentage ?>%
</div>

<div class="progress">
<div class="present" style="width:<?= $percentage ?>%"></div>
<div class="absent" style="width:<?= 100-$percentage ?>%"></div>
</div>

<div class="stats">
<span class="green-dot">Present <?= $present ?></span>
<span>Total <?= $total ?></span>
<span class="red-dot">Absent <?= $absent ?></span>
</div>

</div>

<?php endwhile; ?>

<!-- FLOAT BUTTON -->

<div class="attendance-float">
<a href="javascript:void(0)" class="float-btn otp-btn" onclick="openOTP()">🔐</a>
</div>

<!-- OTP MODAL -->

<div id="otpModal" class="modal" style="display:none">

<div class="modal-content">

<h3>Enter OTP</h3>

<form method="post">
<input type="text" name="otp" placeholder="Enter OTP" required>
<button class="primary-btn">Submit</button>
</form>

<button class="close-btn" onclick="closeOTP()">Cancel</button>

</div>
</div>

<script>

function openOTP(){
document.getElementById("otpModal").style.display="flex";
}

function closeOTP(){
document.getElementById("otpModal").style.display="none";
}

</script>

</body>
</html>