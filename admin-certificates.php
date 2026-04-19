<?php
require_once "admin-auth.php";
include "config/db.php";

/* ===============================
   APPROVE CERTIFICATE
=================================*/
if(isset($_POST['approve_certificate'])) {

  $id = $_POST['certificate_id'];
  $issue_date = $_POST['issue_date'];

  $file = $_FILES['pdf_file']['name'];
  $tmp  = $_FILES['pdf_file']['tmp_name'];

  $upload_path = "uploads/certificates/" . $file;
  move_uploaded_file($tmp, $upload_path);

  $stmt = $conn->prepare("
    UPDATE certificates
    SET status='approved',
        pdf_file=?,
        issue_date=?
    WHERE id=?
  ");

  $stmt->bind_param("ssi", $file, $issue_date, $id);
  $stmt->execute();

  header("Location: ".$_SERVER['PHP_SELF']."?success=1");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Certificate Requests</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">

  <style>
  .certificate-page .exam-card { padding:16px; }
  .certificate-page .exam-title { margin-bottom:4px; }
  .certificate-page .exam-date { margin-bottom:4px; }
  </style>

</head>
<body>

<div class="page certificate-page">

  <div class="page-header">
    <a href="admin-dashboard.php" class="back-btn">←</a>
    <h2>Certificate Requests</h2>
  </div>

  <?php if(isset($_GET['success'])): ?>
    <div class="exam-card" style="background:#dcfce7; border-left:5px solid #16a34a;">
      <div style="color:#16a34a; font-weight:600;">
        Certificate Issued Successfully.
      </div>
    </div>
  <?php endif; ?>

  <?php
  $result = $conn->query("
    SELECT c.*, u.name 
    FROM certificates c
    JOIN users u ON c.student_id = u.id
    ORDER BY c.created_at DESC
  ");

  if($result->num_rows > 0):
    while($row = $result->fetch_assoc()):
  ?>

  <div class="exam-card">

    <!-- STATUS -->
    <?php if($row['status'] == 'approved'): ?>
      <div class="status-badge status-approved">Approved</div>
    <?php elseif($row['status'] == 'in_process'): ?>
      <div class="status-badge status-pending">In Process</div>
    <?php else: ?>
      <div class="status-badge status-rejected">Rejected</div>
    <?php endif; ?>

    <div class="exam-title">
      <?= htmlspecialchars($row['name']) ?> - <?= htmlspecialchars($row['title']) ?>
    </div>

    <div class="exam-date">
      Applied on <?= date("d M Y", strtotime($row['created_at'])) ?>
    </div>

    <?php if(!empty($row['reason'])): ?>
      <div class="exam-date">
        Reason: <?= htmlspecialchars($row['reason']) ?>
      </div>
    <?php endif; ?>

    <!-- APPROVAL FORM -->
    <?php if($row['status'] == 'in_process'): ?>

      <form method="POST" enctype="multipart/form-data" style="margin-top:12px;">
        <input type="hidden" name="certificate_id" value="<?= $row['id'] ?>">

        <label>Issue Date</label>
        <input type="date" name="issue_date" class="form-input" required>

        <label style="margin-top:8px;">Upload Certificate PDF</label>
        <input type="file" name="pdf_file" class="form-input" accept="application/pdf" required>

        <button type="submit" name="approve_certificate" class="primary-btn" style="margin-top:10px;">
          Issue Certificate
        </button>
      </form>

    <?php endif; ?>

    <?php if($row['status'] == 'approved'): ?>
      <div class="exam-actions" style="margin-top:12px;">
        <a href="uploads/certificates/<?= $row['pdf_file'] ?>" 
           target="_blank" 
           class="view-btn">View PDF</a>
      </div>
    <?php endif; ?>

  </div>

  <?php endwhile; ?>
  <?php else: ?>

    <div class="empty-state">
      <div class="empty-icon">📄</div>
      <p>No certificate requests found.</p>
    </div>

  <?php endif; ?>

</div>

</body>
</html>