<?php
require_once "student-auth.php";
include "config/db.php";

$student_id = $_SESSION['user_id'];

/* ===============================
   APPLY RAILWAY REQUEST
=================================*/
if(isset($_POST['apply_railway'])) {

  $from = $_POST['from_station'];
  $to = $_POST['to_station'];
  $pass_type = $_POST['pass_type'];
  $class_type = $_POST['class_type'];
  $date = $_POST['application_date'];

  $stmt = $conn->prepare("INSERT INTO railway_requests 
      (student_id, from_station, to_station, pass_type, class_type, application_date) 
      VALUES (?, ?, ?, ?, ?, ?)");

  $stmt->bind_param("isssss", $student_id, $from, $to, $pass_type, $class_type, $date);
  $stmt->execute();

  header("Location:student-railconcession.php?success=1");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Railway Concession</title>
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<link rel="stylesheet" href="student.css">


</head>
<body>

<?php if(isset($_GET['success'])): ?>
<div id="toast" class="toast-msg exam-card" style="background:#dcfce7; border-left:5px solid #16a34a;">
  <div style="color:#16a34a; font-weight:600;">
    Railway Concession Application Submitted Successfully.
  </div>
</div>
<?php endif; ?>

<div class="page">

<!-- Header -->
<div class="page-header">
<a href="student-dashboard.php" class="back-btn">←</a>
<h2>Railway Concession</h2>
</div>

<!-- Apply Button -->
<div style="margin:20px 0;">
<a href="#" onclick="openRailwayForm()" class="primary-btn" style="display:block; text-align:center;">
Apply for Railway Concession
</a>
</div>

<?php
$result = $conn->query("SELECT * FROM railway_requests 
                        WHERE student_id = $student_id 
                        ORDER BY applied_at DESC");

if($result->num_rows > 0):
while($row = $result->fetch_assoc()):
?>

<div class="exam-card">

<?php if($row['status'] == 'approved'): ?>
<div class="status-badge status-approved">Approved</div>
<?php elseif($row['status'] == 'in_process'): ?>
<div class="status-badge status-pending">In Process</div>
<?php else: ?>
<div class="status-badge status-rejected">Rejected</div>
<?php endif; ?>

<div class="exam-title">
<?= ucfirst($row['pass_type']) ?> Pass Request
</div>

<div class="exam-date">
Applied on: <?= date("d M Y", strtotime($row['applied_at'])) ?>
</div>

<?php if($row['status'] == 'approved'): ?>
<div class="exam-actions">
<a href="uploads/railway/<?= $row['pdf_file'] ?>" class="view-btn" target="_blank">View</a>
<a href="uploads/railway/<?= $row['pdf_file'] ?>" class="download-btn" download>Download</a>
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

<!-- RAILWAY MODAL -->
<div id="railwayModal" class="modal-overlay">

<div class="modal-box">

<div class="modal-header">
<h3>Railway Concession Form</h3>
<span class="close-btn" onclick="closeRailwayForm()">✕</span>
</div>

<form method="POST">

<input type="text" name="from_station" placeholder="Enter From Station" class="form-input" required>

<input type="text" name="to_station" placeholder="Enter To Station" class="form-input" required>

<label>Pass Type</label>
<select name="pass_type" class="form-input" required>
<option value="">Select Pass Type</option>
<option value="Monthly">Monthly</option>
<option value="Quarterly">Quarterly</option>
</select>

<label>Class</label>
<select name="class_type" class="form-input" required>
<option value="First Class">First Class</option>
<option value="Second Class">Second Class</option>
</select>

<label>Application Date</label>
<input type="date" name="application_date" class="form-input" required>

<button type="submit" name="apply_railway" class="primary-btn" style="margin-top:15px;">
Submit
</button>

</form>

</div>
</div>

</div>

<script>

function openRailwayForm(){
document.getElementById("railwayModal").style.display="flex";
}

function closeRailwayForm(){
document.getElementById("railwayModal").style.display="none";
}

/* TOAST ANIMATION */
window.onload=function(){

const toast=document.getElementById("toast");

if(toast){
toast.classList.add("show");

setTimeout(()=>{
toast.classList.remove("show");
},3000);

if(window.history.replaceState){
const cleanURL = window.location.pathname;
window.history.replaceState({}, document.title, cleanURL);
}
}

}

</script>

<?php include 'includes/bottom-nav.php'; ?>

</body>
</html>