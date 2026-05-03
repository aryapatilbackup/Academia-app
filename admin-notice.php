<?php
include 'admin-auth.php';
include 'config/db.php';

$success = "";

/* ===============================
   HANDLE FORM SUBMIT
=================================*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $message = $_POST['message'];
  $link = $_POST['link'] ?: null;
  $target = $_POST['target'];

  $stmt = $conn->prepare(
    "INSERT INTO notices (title, message, link, target) VALUES (?, ?, ?, ?)"
  );
  $stmt->bind_param("ssss", $title, $message, $link, $target);
  $stmt->execute();

  $success = "Notice posted successfully";
}

/* ===============================
   FETCH ALL NOTICES
=================================*/
$notices = $conn->query("SELECT * FROM notices ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Post Notice</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">
</head>

<body class="page">

  <!-- HEADER -->
  <div class="page-header">
    <a href="admin-dashboard.php" class="back-btn">←</a>
    <h2>Upload Notice</h2>
  </div>

  <!-- SUCCESS MESSAGE -->
  <?php if (!empty($success)): ?>
  <div class="exam-card" style="background:#dcfce7; border-left:5px solid #16a34a;">
    <div style="color:#16a34a; font-weight:600;">
      <?= $success ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- FORM -->
  <div class="form-card">
    <form method="post">

      <input type="text" name="title" placeholder="Title" required>

      <textarea name="message" placeholder="Message" required></textarea>

      <input type="url" name="link" placeholder="Optional link">

      <select name="target" required>
        <option value="student">Student</option>
        <option value="teacher">Teacher</option>
        <option value="all">All</option>
      </select>

      <button class="primary-btn">Post Notice</button>

    </form>
  </div>

  <!-- ===============================
       ALL NOTICES SECTION
  =================================-->
  <div class="page-header" style="margin-top:20px;">
    <h2>All Notices</h2>
  </div>

  <?php if ($notices && $notices->num_rows > 0): ?>

    <?php while($n = $notices->fetch_assoc()): ?>

      <div class="exam-card" style="margin-top:10px;">

        <!-- TITLE -->
        <div style="font-weight:600; font-size:16px;">
          <?= htmlspecialchars($n['title']) ?>
        </div>

        <!-- MESSAGE -->
        <div style="margin-top:6px; font-size:14px; color:#555;">
          <?= nl2br(htmlspecialchars($n['message'])) ?>
        </div>

        <!-- LINK -->
        <?php if (!empty($n['link'])): ?>
          <a href="<?= htmlspecialchars($n['link']) ?>" target="_blank"
             style="display:inline-block; margin-top:8px; color:#6366f1;">
            Open Link
          </a>
        <?php endif; ?>

        <!-- TARGET -->
        <div style="margin-top:8px; font-size:12px; color:#888;">
          Target: <?= htmlspecialchars($n['target']) ?>
        </div>

      </div>

    <?php endwhile; ?>

  <?php else: ?>

    <div class="exam-card" style="margin-top:10px;">
      No notices found.
    </div>

  <?php endif; ?>

</body>
</html>