<?php
require_once "admin-auth.php";
include "config/db.php";

/* ===============================
   ISSUE PASS
=================================*/
if(isset($_POST['issue_pass'])) {

  $request_id = $_POST['request_id'];

  $file = $_FILES['pdf_file']['name'];
  $tmp = $_FILES['pdf_file']['tmp_name'];

  $upload_path = "uploads/railway/" . $file;

  move_uploaded_file($tmp, $upload_path);

  $stmt = $conn->prepare("UPDATE railway_requests 
      SET status='approved', pdf_file=? 
      WHERE id=?");

  $stmt->bind_param("si", $file, $request_id);
  $stmt->execute();

  header("Location: ".$_SERVER['PHP_SELF']."?issued=1");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Railway Requests</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">
  <style>
/* Railway Admin Page Specific Compact Spacing */

.railway-page .exam-card {
  padding: 16px;
}

.railway-page .exam-title {
  margin-bottom: 4px;
}

.railway-page .exam-date {
  margin-bottom: 4px;
}

.railway-page form {
  margin-top: 10px;
}

.railway-page label {
  margin-bottom: 4px;
  display: block;
}

.railway-page .primary-btn {
  margin-top: 8px !important;
}
</style>
</head>
<body>

<div class="page railway-page">

  <div class="page-header">
    <a href="admin-dashboard.php" class="back-btn">←</a>
    <h2>Railway Concession Requests</h2>
  </div>

  <?php if(isset($_GET['issued'])): ?>
    <div class="exam-card" style="background:#dcfce7; border-left:5px solid #16a34a;">
      <div style="color:#16a34a; font-weight:600;">
        Concession Pass Issued Successfully.
      </div>
    </div>
  <?php endif; ?>

  <?php
  $result = $conn->query("SELECT rr.*, u.name 
                          FROM railway_requests rr
                          JOIN users u ON rr.student_id = u.id
                          ORDER BY rr.applied_at DESC");

  if($result->num_rows > 0):
    while($row = $result->fetch_assoc()):
  ?>

  <div class="exam-card">

    <!-- Status Badge -->
    <?php if($row['status'] == 'approved'): ?>
      <div class="status-badge status-approved">Approved</div>
    <?php else: ?>
      <div class="status-badge status-pending">In Process</div>
    <?php endif; ?>

    <!-- Student + Pass -->
    <div class="exam-title">
      <?= htmlspecialchars($row['name']) ?> - <?= ucfirst($row['pass_type']) ?> Pass
    </div>

    <!-- Route -->
    <div class="exam-date">
      <?= htmlspecialchars($row['from_station']) ?> → <?= htmlspecialchars($row['to_station']) ?>
    </div>

    <!-- Date -->
    <div class="exam-date">
      Applied on: <?= date("d M Y", strtotime($row['applied_at'])) ?>
    </div>

    <?php if($row['status'] == 'in_process'): ?>

      <form method="POST" enctype="multipart/form-data" style="margin-top:15px;">
        <input type="hidden" name="request_id" value="<?= $row['id'] ?>">

        <label style="font-weight:500; display:block; margin-bottom:6px;">
          Upload Concession PDF
        </label>

        <input type="file" name="pdf_file" class="form-input" required>

        <button type="submit" name="issue_pass" class="primary-btn" style="margin-top:12px;">
          Issue Pass
        </button>
      </form>

    <?php endif; ?>

    <?php if($row['status'] == 'approved'): ?>
      <div class="exam-actions" style="margin-top:15px;">
        <a href="uploads/railway/<?= $row['pdf_file'] ?>" 
           class="view-btn" target="_blank">View PDF</a>
      </div>
    <?php endif; ?>

  </div>

  <?php
    endwhile;
  else:
  ?>

  <div class="empty-state">
    No Railway Requests Found
  </div>

  <?php endif; ?>

</div>

</body>
</html>