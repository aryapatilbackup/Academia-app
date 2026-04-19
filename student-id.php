<?php
require_once 'student-auth.php';
include 'config/db.php';

$user_id = $_SESSION['user_id'];

/* ===============================
   CHECK IF PROFILE EXISTS
=================================*/
$check = $conn->query("SELECT id FROM student_profiles WHERE user_id='$user_id'");
$profile_exists = ($check && $check->num_rows > 0);

/* ===============================
   FETCH STUDENT DATA (Only if profile exists)
=================================*/
if ($profile_exists) {

    $stmt = $conn->prepare("
        SELECT u.name,
               sp.roll_no,
               sp.student_id,
               sp.branch,
               sp.semester,
               sp.session,
               sp.section_name,
               sp.profile_photo
        FROM users u
        LEFT JOIN student_profiles sp ON u.id = sp.user_id
        WHERE u.id = ?
    ");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    $name       = $data['name'] ?? 'Student Name';
    $studentId  = $data['student_id'] ?? '-';
    $branch     = $data['branch'] ?? '-';
    $semester   = $data['semester'] ?? '-';
    $session    = $data['session'] ?? '-';
    $roll       = $data['roll_no'] ?? '-';

    $photo = !empty($data['profile_photo'])
        ? "uploads/profile/" . $data['profile_photo']
        : "assets/img/profile.jpg";
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>ID Card</title>
  <link rel="stylesheet" href="student.css">
</head>
<body>

<?php if (!$profile_exists): ?>

<!-- ===============================
     LOCKED VIEW
=================================-->
<div class="page id-page">

  <div class="id-header">
    <h3>ID Card Locked</h3>
  </div>

  <div class="id-profile" style="text-align:center;">
    <h2>Complete your profile to unlock ID Card</h2>
    <a href="profile-edit.php" class="primary-btn" style="margin-top:15px; display:inline-block;">
      Complete Profile
    </a>
  </div>

</div>

<?php else: ?>

<!-- ===============================
     NORMAL ID VIEW
=================================-->
<div class="page id-page">

  <!-- HEADER -->
  <div class="id-header">
    <h3>
      CHANDRABHAN SHARMA COLLEGE OF<br>
      ARTS, COMMERCE & SCIENCE<br>
      <small>(AUTONOMOUS)</small>
    </h3>
  </div>

  <!-- PROFILE -->
  <div class="id-profile">
    <img src="<?= $photo ?>" class="id-pic">
    <h2><?= htmlspecialchars($name) ?></h2>
    <p class="sid">
      Student ID<br>
      <strong><?= htmlspecialchars($studentId) ?></strong>
    </p>
  </div>

  <!-- DETAILS -->
  <div class="id-details">

    <p>
      <span>Branch</span>
      <span><?= htmlspecialchars($branch) ?></span>
    </p>

    <p>
      <span>Semester</span>
      <span><?= htmlspecialchars($semester) ?></span>
    </p>

    <p>
      <span>Session</span>
      <span><?= htmlspecialchars($session) ?></span>
    </p>

    <p>
      <span>Roll No.</span>
      <span><?= htmlspecialchars($roll) ?></span>
    </p>

  </div>

  <!-- BARCODE -->
  <div class="barcode">
    <img src="assets/img/barcode.png">
    <p><?= htmlspecialchars($studentId) ?></p>
  </div>

  <p class="sign">Principal</p>

</div>

<?php endif; ?>

<?php include 'includes/bottom-nav.php'; ?>

</body>
</html>