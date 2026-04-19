<?php
require_once "student-auth.php";
include "config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: profile-edit.php");
    exit;
}

$student_id = $_SESSION['user_id'];

/* Check if profile already exists */
$check = $conn->query("
    SELECT id FROM student_profiles 
    WHERE user_id = '$student_id'
");

if ($check->num_rows > 0) {
    header("Location: student-profile.php");
    exit;
}

/* Get new fields */
$session = $_POST['session'];
$profile_photo = $_POST['profile_photo'] ?? null;

/* Insert full profile */
$stmt = $conn->prepare("
INSERT INTO student_profiles (
    user_id,
    roll_no,
    enrollment_no,
    registration_no,
    student_id,
    degree,
    branch,
    semester,
    session,
    section_name,
    medium,
    admission_date,
    father_name,
    mother_name,
    guardian_name,
    gender,
    dob,
    blood_group,
    caste_name,
    category,
    religion,
    bank_name,
    bank_acc_no,
    ifsc_code,
    aadhar_number,
    prn_number,
    student_mobile,
    parent_mobile,
    email,
    address,
    city,
    state,
    country,
    pincode,
    profile_photo,
    is_locked
) VALUES (
?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,1
)
");

$stmt->bind_param(
"isssssssssssssssssssssssssssssssssss",
$student_id,
$_POST['roll_no'],
$_POST['enrollment_no'],
$_POST['registration_no'],
$_POST['student_id'],
$_POST['degree'],
$_POST['branch'],
$_POST['semester'],
$session,
$_POST['section_name'],
$_POST['medium'],
$_POST['admission_date'],
$_POST['father_name'],
$_POST['mother_name'],
$_POST['guardian_name'],
$_POST['gender'],
$_POST['dob'],
$_POST['blood_group'],
$_POST['caste_name'],
$_POST['category'],
$_POST['religion'],
$_POST['bank_name'],
$_POST['bank_acc_no'],
$_POST['ifsc_code'],
$_POST['aadhar_number'],
$_POST['prn_number'],
$_POST['student_mobile'],
$_POST['parent_mobile'],
$_POST['email'],
$_POST['address'],
$_POST['city'],
$_POST['state'],
$_POST['country'],
$_POST['pincode'],
$profile_photo
);

$stmt->execute();
$stmt->close();

header("Location: student-profile.php?success=1");
exit;
?>