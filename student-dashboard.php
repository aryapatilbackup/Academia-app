<?php
session_start();
include 'student-auth.php';
include 'config/db.php';
include 'includes/bottom-nav.php';

$student_id = $_SESSION['user_id'];

/* ===== USER ===== */
$q = $conn->query("SELECT name FROM users WHERE id='$student_id'");
$user = $q->fetch_assoc();
$name = $user['name'];


/* ===== ATTENDANCE ===== */
function getAttendancePercent($conn, $student_id, $type = null) {
    $where = "a.student_id='$student_id'";
    if ($type) {
        $where .= " AND s.type='$type'";
    }

    $q = $conn->query("
        SELECT 
            SUM(a.status='present') AS present,
            COUNT(*) AS total
        FROM attendance a
        JOIN subjects s ON s.id = a.subject_id
        WHERE $where
    ");

    $r = $q->fetch_assoc();
    if ($r['total'] == 0) return 0;

    return round(($r['present'] / $r['total']) * 100);
}

$theory     = getAttendancePercent($conn, $student_id, 'theory');
$practical  = getAttendancePercent($conn, $student_id, 'practical');
$overall    = getAttendancePercent($conn, $student_id);


/* ===== TODAY ===== */
$today = date('l');


/* ===== TODAY'S SCHEDULE ===== */
$scheduleQuery = $conn->query("
  SELECT
    cs.start_time,
    cs.end_time,
    cs.room,
    s.name AS subject_name,
    s.code,
    s.teacher_name
  FROM class_schedule cs
  JOIN subjects s ON cs.subject_id = s.id
  WHERE cs.day = '$today'
  ORDER BY cs.start_time ASC
  LIMIT 1
");

$schedule = $scheduleQuery->fetch_assoc();


/* ===== PENDING ASSIGNMENT ===== */
$assignmentQuery = $conn->query("
  SELECT a.id, a.subject, a.title, a.due_date
  FROM assignment_pdfs a
  LEFT JOIN assignment_submissions s
    ON a.id = s.assignment_id
    AND s.student_id = '$student_id'
  WHERE s.id IS NULL
  ORDER BY a.due_date ASC
  LIMIT 1
");

$assignment = $assignmentQuery->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="dashboard-container">

  <!-- ===== HEADER ===== -->
  <header class="app-header">
    <div class="header-content">

      <div class="header-left">
        <div class="header-avatar">👤</div>

        <div class="header-info">
          <div class="header-greeting">Good Afternoon</div>
          <div class="header-name"><?= htmlspecialchars($name) ?></div>
        </div>
      </div>

      <div class="header-badge">🎓</div>

    </div>
  </header>


  <!-- ===== MAIN DASHBOARD ===== -->
  <section class="section dashboard-main">

    <h4>Dashboard</h4>

    <div class="dash-top">

      <!-- ===== SCHEDULE ===== -->
      <div class="dash-col">
        <div class="dash-header">
          <h5>Today's Schedule</h5>
          <a href="student-schedule.php">See All</a>
        </div>

        <?php if ($schedule): ?>
          <div class="dash-card schedule-card">

            <div class="time-badge">
              <?= date("g:iA", strtotime($schedule['start_time'])) ?>
              -
              <?= date("g:iA", strtotime($schedule['end_time'])) ?>
            </div>

            <h3>
              <?= htmlspecialchars($schedule['code']) ?> -
              <?= htmlspecialchars($schedule['subject_name']) ?>
            </h3>

            <div class="dash-meta">
              <span>📍Room<?= htmlspecialchars($schedule['room']) ?></span>
              <span>👤 <?= htmlspecialchars($schedule['teacher_name']) ?></span>
            </div>

          </div>
        <?php else: ?>
          <div class="dash-card empty-card">
            <div class="empty-icon">📅</div>
            <p>No classes today</p>
          </div>
        <?php endif; ?>
      </div>


      <!-- ===== ASSIGNMENT ===== -->
      <div class="dash-col">
        <div class="dash-header">
          <h5>Pending Assignment</h5>
          <a href="student-assignment.php">See All</a>
        </div>

        <?php if ($assignment): ?>
          <div class="dash-card schedule-card">

            <h3>
              <?= htmlspecialchars($assignment['subject']) ?> —
              <?= htmlspecialchars($assignment['title']) ?>
            </h3>

            <div class="dash-meta">
              <span>📅 Due:
                <?= date("d M", strtotime($assignment['due_date'])) ?>
              </span>
            </div>

          </div>
        <?php else: ?>
          <div class="dash-card empty-card">
            <div class="empty-icon">📋</div>
            <p>No pending assignments</p>
          </div>
        <?php endif; ?>
      </div>

    </div>


    <!-- ===== ATTENDANCE ===== -->
    <div class="dash-attendance">

      <h5>Attendance summary</h5>

      <div class="att-grid">
        <div class="att-box">
          <div class="att-circle"><?= $theory ?>%</div>
          <p>Theory</p>
        </div>

        <div class="att-box">
          <div class="att-circle"><?= $practical ?>%</div>
          <p>Practical</p>
        </div>

        <div class="att-box">
          <div class="att-circle"><?= $overall ?>%</div>
          <p>Overall</p>
        </div>
      </div>

    </div>

  </section>


  <!-- ===== ACADEMIC ===== -->
  <section class="content-section">

    <h2 class="section-title">📚 Academic</h2><br>

    <div class="menu-list">
      <a href="student-attendance.php" class="menu-item">📊 Attendance</a>
      <a href="student-assignment.php" class="menu-item">📘 Assignments</a>
      <a href="student-schedule.php" class="menu-item">📅 Class Schedule</a>
      <a href="student-online.php" class="menu-item">💻 Online Class</a>
      <a href="student-internalmark.php" class="menu-item">📘 Internal Mark</a>
      <a href="student-registersubject.php" class="menu-item">➕ Register Subject</a>
    </div>

  </section>


  <!-- ===== SERVICES ===== -->
  <section class="content-section">

    <h2 class="section-title">🛠 Services</h2><br>

    <div class="service-list">
      <a href="student-certificates.php" class="service-item">📜 Certificate</a>
      <a href="student-railconcession.php" class="service-item">🚆 Railway Concession</a>
      <a href="student-fees.php" class="service-item">💰 Fees</a>
       <a href="student-exams.php" class="menu-item">📝 Exam Timetable</a>
      <a href="student-hallticket.php" class="menu-item">🎫 Hall Ticket</a>
      <a href="student-results.php" class="menu-item">🏆 Result</a>
    </div>

  </section>

</div>

</body>
</html>