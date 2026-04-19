<?php
require_once "student-auth.php";
include "config/db.php";

$user_id = $_SESSION['user_id'];

$q = $conn->query("SELECT * FROM student_profiles WHERE user_id='$user_id'");
$profile = $q->fetch_assoc();

if (!$profile) {
    header("Location: profile-edit.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Postal Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <!-- Header -->
  <div class="page-header">
    <a href="student-profile.php" class="back-btn">←</a>
    <h2>Postal Details</h2>
  </div>

  <div class="profile-card">

    <div class="detail-row">
      <span>Address</span>
      <strong><?= $profile['address'] ?></strong>
    </div>

    <div class="detail-row">
      <span>City</span>
      <strong><?= $profile['city'] ?></strong>
    </div>

    <div class="detail-row">
      <span>State</span>
      <strong><?= $profile['state'] ?></strong>
    </div>

    <div class="detail-row">
      <span>Country</span>
      <strong><?= $profile['country'] ?></strong>
    </div>

    <div class="detail-row">
      <span>Pincode</span>
      <strong><?= $profile['pincode'] ?></strong>
    </div>

  </div>

</div>

<?php include 'includes/bottom-nav.php'; ?>

</body>
</html>
