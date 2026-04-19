<?php
require_once "student-auth.php";
include "config/db.php";

$student_id = $_SESSION['user_id'];

// Fetch latest academic fees
$stmt = $conn->prepare("
    SELECT * FROM fees 
    WHERE student_id = ? 
    ORDER BY id DESC 
    LIMIT 1
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$fee = $result->fetch_assoc();

$fee_name = $fee['course_name'] ?? '-';
$total_amount = $fee['total_amount'] ?? 0;
$paid_amount = $fee['paid_amount'] ?? 0;
$balance_amount = $total_amount - $paid_amount;
$academic_year = $fee['academic_year'] ?? '-';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Fees</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <link rel="stylesheet" href="student.css">

  <style>
    .tab-content { display: none; }
    .tab-content.active { display: block; }
  </style>
</head>
<body>

<div class="page">

  <!-- Header -->
  <div class="page-header">
    <a href="student-dashboard.php" class="back-btn">←</a>
    <h2>Fees</h2>
  </div>

  <!-- Course Select -->
  <div class="filter-card">
    <label>Select Course</label>
    <select>
      <option><?= htmlspecialchars($fee_name) ?></option>
    </select>
  </div>

  <!-- Tabs -->
  <div class="tabs">
    <button class="tab active" onclick="showTab('academic', this)">Academic Fees</button>
    <button class="tab" onclick="showTab('scholarship', this)">Scholarship</button>
    <button class="tab" onclick="showTab('other', this)">Other Fees</button>
  </div>

  <!-- ================= Academic Tab ================= -->
  <div id="academic" class="tab-content active">

    <h3 class="fees-title">Academic Fees (<?= htmlspecialchars($academic_year) ?>)</h3>

    <?php if ($fee): ?>

    <div class="fees-card">

      <div class="fees-row">
        <span class="label">Course</span>
        <span class="value"><?= htmlspecialchars($fee_name) ?></span>
      </div>

      <div class="fees-row">
        <span class="label">Total Amount</span>
        <span class="value"><?= number_format($total_amount, 2) ?></span>
      </div>

      <div class="fees-row">
        <span class="label">Paid Amount</span>
        <span class="value paid"><?= number_format($paid_amount, 2) ?></span>
      </div>

      <div class="fees-row">
        <span class="label">Balance Amount</span>
        <span class="value balance"><?= number_format($balance_amount, 2) ?></span>
      </div>

      <div class="fees-row">
        <span class="label">Status</span>
        <span class="value <?= $balance_amount == 0 ? 'paid' : 'balance' ?>">
          <?= $balance_amount == 0 ? 'Paid' : 'Pending' ?>
        </span>
      </div>

    </div>

    <?php else: ?>

      <div class="empty-state">
        <div class="empty-icon">💰</div>
        <p>No academic fee record available.</p>
      </div>

    <?php endif; ?>

  </div>

  <!-- ================= Scholarship Tab ================= -->
  <div id="scholarship" class="tab-content">

    <div class="empty-state">
      <div class="empty-icon">🎓</div>
      <p>No scholarship records available.</p>
    </div>

  </div>

  <!-- ================= Other Fees Tab ================= -->
  <div id="other" class="tab-content">

    <div class="empty-state">
      <div class="empty-icon">📑</div>
      <p>No other fee records available.</p>
    </div>

  </div>

</div>

<?php include 'includes/bottom-nav.php'; ?>

<script>
function showTab(tabId, btn) {

  document.querySelectorAll('.tab-content').forEach(tab => {
    tab.classList.remove('active');
  });

  document.querySelectorAll('.tab').forEach(tabBtn => {
    tabBtn.classList.remove('active');
  });

  document.getElementById(tabId).classList.add('active');
  btn.classList.add('active');
}
</script>

</body>
</html>