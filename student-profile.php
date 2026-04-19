<?php
include 'student-auth.php';
include 'config/db.php';

$user_id = $_SESSION['user_id'];

// Check if profile exists
$q = $conn->query("SELECT id FROM student_profiles WHERE user_id='$user_id'");
$profile_exists = ($q && $q->num_rows > 0);
?>
<?php if(isset($_GET['success'])): ?>

<div id="toast" class="toast-msg exam-card"
     style="background:#dcfce7;border-left:5px solid #16a34a;">

  <div style="color:#16a34a;font-weight:600;">
    Profile saved successfully.
  </div>

</div>

<?php endif; ?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Profile</title>
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="profile-page">

  <!-- HEADER -->
  <div class="profile-header">

<?php
$user_id = $_SESSION['user_id'];

$result = $conn->query("SELECT profile_photo FROM student_profiles WHERE user_id='$user_id'");
$profile = $result->fetch_assoc();

$photo = !empty($profile['profile_photo'])
    ? "uploads/profile/" . $profile['profile_photo']
    : "assets/img/profile.jpg";
?>

<img src="<?= $photo ?>" class="profile-pic">
<h3><?= $_SESSION['name'] ?? 'Student' ?></h3>
<p><?= $_SESSION['user_id'] ?></p>

</div>

  <!-- COMPLETE PROFILE BUTTON -->
  <?php if (!$profile_exists): ?>
    <div style="text-align:center; margin:15px;">
      <a href="profile-edit.php" class="primary-btn">
        Complete Profile Form
      </a>
    </div>
  <?php endif; ?>

  <!-- PROFILE OPTIONS -->
  <div class="profile-card">

    <?php if ($profile_exists): ?>

      <a href="profile-details.php">Profile Detail</a>

      <a href="contact-details.php">Contact Detail</a>
      <a href="postal-details.php">Postal Detail</a>

    <?php else: ?>

      <a href="#" onclick="showLocked()">Profile Detail 🔒</a>
      <a href="#" onclick="showLocked()">Contact Detail 🔒</a>
      <a href="#" onclick="showLocked()">Postal Detail 🔒</a>

    <?php endif; ?>

      <a href="change-password.php">Change Password</a>

  </div>

  <!-- OTHER OPTIONS -->
  <div class="profile-card">
    <a href="#">Privacy Policies</a>
  </div>

  <div class="profile-card">
    <a href="#">Share App</a>
    <a href="#">Rate App</a>
  </div>

  <!-- LOGOUT -->
  <a href="logout.php" class="logout-btn">Logout</a>

</div>

<?php include 'includes/bottom-nav.php'; ?>

<script>
function showLocked() {
  alert("Please fill the profile form to unlock this section.");
}
</script>

</body>
</html>
