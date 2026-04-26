<?php
require_once "student-auth.php";
include "config/db.php";

$student_id = $_SESSION['user_id'];

/* ===============================
   APPLY CERTIFICATE
=================================*/
if(isset($_POST['apply_certificate'])) {

  $title  = $_POST['certificate_title'];
  $reason = $_POST['reason'];

  $stmt = $conn->prepare("
    INSERT INTO certificates 
    (student_id, title, reason, status) 
    VALUES (?, ?, ?, 'in_process')
  ");

  $stmt->bind_param("iss", $student_id, $title, $reason);
  $stmt->execute();

  header("Location: ".$_SERVER['PHP_SELF']."?success=1");
  exit();
}

/* ===============================
   FETCH CERTIFICATES
=================================*/
$result = $conn->query("
  SELECT * FROM certificates
  WHERE student_id='$student_id'
  ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Certificates</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="page">

  <div class="page-header">
    <a href="student-dashboard.php" class="back-btn">←</a>
    <h2>Certificates</h2>
  </div>

  <!-- Apply Button -->
  <div style="margin:20px 0;">
    <a href="#" onclick="openCertificateForm()" class="primary-btn" style="display:block; text-align:center;">
      Apply for Certificate
    </a>
  </div>

  <!-- Success Message -->
  <?php if(isset($_GET['success'])): ?>

<div id="toast" class="toast-msg exam-card"
     style="background:#dcfce7;border-left:5px solid #16a34a;">
<div style="color:#16a34a;font-weight:600;">
    Certificate Application Submitted Successfully.
  </div>
</div>
<?php endif; ?>

<?php if ($result && $result->num_rows > 0): ?>

  <?php while ($row = $result->fetch_assoc()): 
    $pdfPath = "uploads/certificates/" . $row['pdf_file'];
  ?>

  <div class="exam-card">

    <!-- STATUS BADGE -->
    <?php if($row['status'] == 'approved'): ?>
      <div class="status-badge status-approved">Approved</div>
    <?php elseif($row['status'] == 'in_process'): ?>
      <div class="status-badge status-pending">In Process</div>
    <?php else: ?>
      <div class="status-badge status-rejected">Rejected</div>
    <?php endif; ?>

    <div class="exam-title">
      <?= htmlspecialchars($row['title']) ?>
    </div>

    <?php if($row['status'] == 'approved'): ?>
      <div class="exam-date">
        Issued on <?= date("d M Y", strtotime($row['issue_date'])) ?>
      </div>
    <?php else: ?>
      <div class="exam-date">
        Applied on <?= date("d M Y", strtotime($row['created_at'])) ?>
      </div>
    <?php endif; ?>

    <!-- ACTIONS ONLY IF APPROVED -->
    <?php if($row['status'] == 'approved'): ?>
      <div class="exam-actions">
        <a href="<?= $pdfPath ?>" target="_blank" class="view-btn">View</a>
        <a href="<?= $pdfPath ?>" download class="download-btn">Download</a>
      </div>
    <?php endif; ?>

  </div>

  <?php endwhile; ?>

<?php else: ?>

  <div class="empty-state">
    <div class="empty-icon">📄</div>
    <p>No certificates available.</p>
  </div>

<?php endif; ?>

</div>

<!-- ===============================
     CERTIFICATE MODAL
=================================-->
<div id="certificateModal" class="modal-overlay">

  <div class="modal-box">

    <div class="modal-header">
      <h3>Certificate Application</h3>
      <span class="close-btn" onclick="closeCertificateForm()">✕</span>
    </div>

    <form method="POST">

      <input type="text" 
             name="certificate_title" 
             placeholder="Enter Certificate Title" 
             class="form-input" required>

      <textarea name="reason" 
                placeholder="Enter Reason" 
                class="form-input" 
                style="height:80px;" required></textarea>

      <button type="submit" 
              name="apply_certificate" 
              class="primary-btn" 
              style="margin-top:15px;">
        Submit
      </button>

    </form>

  </div>
</div>

<script>
function openCertificateForm() {
  document.getElementById("certificateModal").style.display = "flex";
}

function closeCertificateForm() {
  document.getElementById("certificateModal").style.display = "none";
}
</script>

<?php include 'includes/toast.php'; ?>
</body>
</html>