<?php
include 'student-auth.php';
include 'config/db.php';

$student_id = $_SESSION['user_id'];
$subject_id = $_GET['subject_id'] ?? 0;

/* FETCH ATTENDANCE RECORDS */
$sql = "
SELECT 
  attendance.lecture_no,
  attendance.date,
  attendance.status,
  subjects.name AS subject
FROM attendance
JOIN subjects ON attendance.subject_id = subjects.id
WHERE attendance.student_id = ?
  AND attendance.subject_id = ?
ORDER BY attendance.date DESC, attendance.lecture_no ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $subject_id);
$stmt->execute();
$result = $stmt->get_result();

$subject = '';
$rows = [];

while ($row = $result->fetch_assoc()) {
  if ($subject === '') {
    $subject = $row['subject'];
  }
  $rows[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendance Detail</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="student.css">
</head>

<body class="page">

<!-- HEADER -->
<div class="page-header">
<a href="student-attendance.php" class="back-btn">←</a>
<h3>Attendance Detail</h3>
</div>

<h4 class="subject-title"><?= htmlspecialchars($subject) ?></h4>

<?php if (empty($rows)): ?>

<div class="empty-state">
<div class="empty-icon">📭</div>
<p>No attendance records found</p>
</div>

<?php else: ?>

<!-- TABLE WRAPPER -->
<div class="table-wrap">

<table class="attendance-table">

<tr>
<th>#</th>
<th>Lecture</th>
<th>Date</th>
<th>Status</th>
</tr>

<?php foreach ($rows as $i => $r): ?>

<tr>

<td><?= $i + 1 ?></td>

<td>
Lec <?= htmlspecialchars($r['lecture_no']) ?>
</td>

<td>
<?= date('d M Y', strtotime($r['date'])) ?>
</td>

<td>
<span class="status <?= $r['status'] === 'present' ? 'present-badge' : 'absent-badge' ?>">
<?= strtoupper($r['status'][0]) ?>
</span>
</td>

</tr>

<?php endforeach; ?>

</table>

</div>

<?php endif; ?>

</body>
</html>