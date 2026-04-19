<?php
require_once "student-auth.php";
include "config/db.php";
?>

<!DOCTYPE html>
<html>
<head>
  <title>Complete Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <div class="page-header">
    <a href="student-profile.php" class="back-btn">←</a>
    <h2>Complete Profile</h2>
  </div>

  <!-- IMPORTANT: enctype added -->
  <form action="student-profile-preview.php" method="post" enctype="multipart/form-data" class="form-card">

  <!-- ================= ACADEMIC ================= -->
  <h3 class="section-title">Academic Details</h3>

  <input type="text" name="roll_no" placeholder="Roll No *" required>
  <input type="text" name="enrollment_no" placeholder="Enrollment No *" required>
  <input type="text" name="registration_no" placeholder="Registration No">
  <input type="text" name="student_id" placeholder="Student ID">

  <input type="text" name="degree" placeholder="Degree *" required>
  <input type="text" name="branch" placeholder="Branch *" required>
  <input type="text" name="semester" placeholder="Semester *" required>

  <!-- NEW SESSION FIELD -->
  <input type="text" name="session" placeholder="Session (e.g. 2025-2026) *" required>

  <input type="text" name="section_name" placeholder="Section">
  <input type="text" name="medium" placeholder="Medium">

  <label>Admission Date *</label>
  <input type="date" name="admission_date" required>

  <!-- ================= PERSONAL ================= -->
  <h3 class="section-title">Personal Details</h3>

  <input type="text" name="father_name" placeholder="Father Name *" required>
  <input type="text" name="mother_name" placeholder="Mother Name *" required>
  <input type="text" name="guardian_name" placeholder="Guardian Name">

  <select name="gender" required>
    <option value="">Select Gender *</option>
    <option>Male</option>
    <option>Female</option>
    <option>Other</option>
  </select>

  <label>Date of Birth *</label>
  <input type="date" name="dob" required>

  <input type="text" name="blood_group" placeholder="Blood Group">
  <input type="text" name="caste_name" placeholder="Caste">
  <input type="text" name="category" placeholder="Category">
  <input type="text" name="religion" placeholder="Religion">

  <!-- ================= CONTACT ================= -->
  <h3 class="section-title">Contact Details</h3>

  <input type="text" name="student_mobile" placeholder="Student Mobile *" required>
  <input type="text" name="parent_mobile" placeholder="Parent Mobile">
  <input type="email" name="email" placeholder="Email *" required>

  <!-- ================= ADDRESS ================= -->
  <h3 class="section-title">Address Details</h3>

  <textarea name="address" placeholder="Full Address *" required></textarea>
  <input type="text" name="city" placeholder="City *" required>
  <input type="text" name="state" placeholder="State *" required>
  <input type="text" name="country" placeholder="Country *" required>
  <input type="text" name="pincode" placeholder="Pincode *" required>

  <!-- ================= BANK ================= -->
  <h3 class="section-title">Bank Details</h3>

  <input type="text" name="bank_name" placeholder="Bank Name">
  <input type="text" name="bank_acc_no" placeholder="Bank Account No">
  <input type="text" name="ifsc_code" placeholder="IFSC Code">

  <!-- ================= IDs ================= -->
  <h3 class="section-title">Government IDs</h3>

  <input type="text" name="aadhar_number" placeholder="Aadhar Number">
  <input type="text" name="prn_number" placeholder="PRN Number">

  <!-- ================= PROFILE PHOTO ================= -->
  <h3 class="section-title">Profile Photo</h3>

  <label>Upload Profile Photo *</label>
  <input type="file" name="profile_photo" accept="image/*" required>

  <button type="submit" class="primary-btn" style="margin-top:20px;">
    Preview & Confirm
  </button>

  </form>

</div>

</body>
</html>