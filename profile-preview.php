<?php
require_once "student-auth.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: profile-edit.php");
    exit;
}

$data = $_POST;

/* ===============================
   HANDLE PROFILE PHOTO TEMP UPLOAD
=================================*/
$photoName = null;

if(isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0){

    if(!is_dir("uploads/profile")){
        mkdir("uploads/profile", 0777, true);
    }

    $photoName = time() . "_" . basename($_FILES['profile_photo']['name']);
    move_uploaded_file($_FILES['profile_photo']['tmp_name'], "uploads/profile/" . $photoName);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile Preview</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

    <div class="page-header">
        <a href="profile-edit.php" class="back-btn">←</a>
        <h2>Profile Preview</h2>
    </div>

    <div class="form-card">

        <h3>Academic Details</h3>
        <p><strong>Roll No:</strong> <?= htmlspecialchars($data['roll_no']) ?></p>
        <p><strong>Enrollment No:</strong> <?= htmlspecialchars($data['enrollment_no']) ?></p>
        <p><strong>Registration No:</strong> <?= htmlspecialchars($data['registration_no']) ?></p>
        <p><strong>Student ID:</strong> <?= htmlspecialchars($data['student_id']) ?></p>
        <p><strong>Degree:</strong> <?= htmlspecialchars($data['degree']) ?></p>
        <p><strong>Branch:</strong> <?= htmlspecialchars($data['branch']) ?></p>
        <p><strong>Semester:</strong> <?= htmlspecialchars($data['semester']) ?></p>
        <p><strong>Session:</strong> <?= htmlspecialchars($data['session']) ?></p>
        <p><strong>Section:</strong> <?= htmlspecialchars($data['section_name']) ?></p>
        <p><strong>Medium:</strong> <?= htmlspecialchars($data['medium']) ?></p>
        <p><strong>Admission Date:</strong> <?= htmlspecialchars($data['admission_date']) ?></p>

        <h3>Personal Details</h3>
        <p><strong>Father Name:</strong> <?= htmlspecialchars($data['father_name']) ?></p>
        <p><strong>Mother Name:</strong> <?= htmlspecialchars($data['mother_name']) ?></p>
        <p><strong>Guardian Name:</strong> <?= htmlspecialchars($data['guardian_name']) ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($data['gender']) ?></p>
        <p><strong>DOB:</strong> <?= htmlspecialchars($data['dob']) ?></p>
        <p><strong>Blood Group:</strong> <?= htmlspecialchars($data['blood_group']) ?></p>
        <p><strong>Caste:</strong> <?= htmlspecialchars($data['caste_name']) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($data['category']) ?></p>
        <p><strong>Religion:</strong> <?= htmlspecialchars($data['religion']) ?></p>

        <h3>Profile Photo</h3>
        <?php if($photoName): ?>
            <img src="uploads/profile/<?= $photoName ?>" style="width:120px; border-radius:8px;">
        <?php endif; ?>

        <h3>Contact</h3>
        <p><strong>Student Mobile:</strong> <?= htmlspecialchars($data['student_mobile']) ?></p>
        <p><strong>Parent Mobile:</strong> <?= htmlspecialchars($data['parent_mobile']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>

        <h3>Address</h3>
        <p><strong>Address:</strong> <?= htmlspecialchars($data['address']) ?></p>
        <p><strong>City:</strong> <?= htmlspecialchars($data['city']) ?></p>
        <p><strong>State:</strong> <?= htmlspecialchars($data['state']) ?></p>
        <p><strong>Country:</strong> <?= htmlspecialchars($data['country']) ?></p>
        <p><strong>Pincode:</strong> <?= htmlspecialchars($data['pincode']) ?></p>

        <h3>Bank Details</h3>
        <p><strong>Bank Name:</strong> <?= htmlspecialchars($data['bank_name']) ?></p>
        <p><strong>Account No:</strong> <?= htmlspecialchars($data['bank_acc_no']) ?></p>
        <p><strong>IFSC:</strong> <?= htmlspecialchars($data['ifsc_code']) ?></p>

        <h3>Government IDs</h3>
        <p><strong>Aadhar:</strong> <?= htmlspecialchars($data['aadhar_number']) ?></p>
        <p><strong>PRN:</strong> <?= htmlspecialchars($data['prn_number']) ?></p>

        <form action="profile-save.php" method="post">
            <?php foreach ($data as $key => $value): ?>
                <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
            <?php endforeach; ?>

            <?php if($photoName): ?>
                <input type="hidden" name="profile_photo" value="<?= $photoName ?>">
            <?php endif; ?>

            <button class="primary-btn" style="margin-top:20px;">
                Confirm & Submit
            </button>
        </form>

    </div>

</div>

</body>
</html>