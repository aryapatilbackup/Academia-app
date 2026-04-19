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
  <title>Profile Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <!-- Header -->
  <div class="page-header">
    <a href="student-profile.php" class="back-btn">←</a>
    <h2>Profile Details</h2>
  </div>

  <div class="profile-card">

    <!-- Academic -->
    <h4 style="padding:14px;">Academic Details</h4>

    <div class="detail-row"><span>Roll No</span><strong><?= $profile['roll_no'] ?></strong></div>
    <div class="detail-row"><span>Enrollment No</span><strong><?= $profile['enrollment_no'] ?></strong></div>
    <div class="detail-row"><span>Registration No</span><strong><?= $profile['registration_no'] ?></strong></div>
    <div class="detail-row"><span>Student ID</span><strong><?= $profile['student_id'] ?></strong></div>
    <div class="detail-row">
    <span>Degree</span>
    <span><?= htmlspecialchars($profile['degree']) ?></span>
</div>

<div class="detail-row">
    <span>Branch</span>
    <span><?= htmlspecialchars($profile['branch']) ?></span>
</div>
    <div class="detail-row"><span>Session</span><strong><?= $profile['session'] ?? '-' ?></strong></div>
    <div class="detail-row"><span>Semester</span><strong><?= $profile['semester'] ?></strong></div>
    <div class="detail-row"><span>Section</span><strong><?= $profile['section_name'] ?></strong></div>
    <div class="detail-row"><span>Medium</span><strong><?= $profile['medium'] ?></strong></div>
    <div class="detail-row"><span>Admission Date</span><strong><?= $profile['admission_date'] ?></strong></div>

    <!-- Personal -->
    <h4 style="padding:14px;">Personal Details</h4>

    <div class="detail-row"><span>Father Name</span><strong><?= $profile['father_name'] ?></strong></div>
    <div class="detail-row"><span>Mother Name</span><strong><?= $profile['mother_name'] ?></strong></div>
    <div class="detail-row"><span>Guardian Name</span><strong><?= $profile['guardian_name'] ?></strong></div>
    <div class="detail-row"><span>Gender</span><strong><?= $profile['gender'] ?></strong></div>
    <div class="detail-row"><span>Date of Birth</span><strong><?= $profile['dob'] ?></strong></div>
    <div class="detail-row"><span>Blood Group</span><strong><?= $profile['blood_group'] ?></strong></div>
    <div class="detail-row"><span>Caste</span><strong><?= $profile['caste_name'] ?></strong></div>
    <div class="detail-row"><span>Category</span><strong><?= $profile['category'] ?></strong></div>
    <div class="detail-row"><span>Religion</span><strong><?= $profile['religion'] ?></strong></div>

    <!-- IDs -->
    <h4 style="padding:14px;">Government IDs</h4>

    <div class="detail-row"><span>Aadhar Number</span><strong><?= $profile['aadhar_number'] ?></strong></div>
    <div class="detail-row"><span>PRN Number</span><strong><?= $profile['prn_number'] ?></strong></div>

    <!-- Bank -->
    <h4 style="padding:14px;">Bank Details</h4>

    <div class="detail-row"><span>Bank Name</span><strong><?= $profile['bank_name'] ?></strong></div>
    <div class="detail-row"><span>Account Number</span><strong><?= $profile['bank_acc_no'] ?></strong></div>
    <div class="detail-row"><span>IFSC Code</span><strong><?= $profile['ifsc_code'] ?></strong></div>

  </div>

</div>

<?php include 'includes/bottom-nav.php'; ?>

</body>
</html>
