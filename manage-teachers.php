<?php
include 'admin-auth.php';
include 'config/db.php';

/* DELETE USER */
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];

  $stmt = $conn->prepare("DELETE FROM users WHERE id=? AND role='teacher'");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  header("Location: manage-teachers.php");
  exit;
}

/* FETCH TEACHERS */
$teachers = $conn->query("SELECT id, name, email FROM users WHERE role='teacher' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Teachers</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">
</head>

<body class="page">

  <!-- HEADER -->
  <div class="page-header">
    <a href="admin-dashboard.php" class="back-btn">←</a>
    <h2>Manage Teachers</h2>
  </div>

  <?php if ($teachers && $teachers->num_rows > 0): ?>

    <?php while($t = $teachers->fetch_assoc()): ?>

      <div class="exam-card">

        <div style="font-weight:600;">
          <?= htmlspecialchars($t['name']) ?>
        </div>

        <div style="font-size:13px; color:#666;">
          <?= htmlspecialchars($t['email']) ?>
        </div>

        <a href="?delete=<?= $t['id'] ?>"
           onclick="return confirm('Delete this teacher?')"
           style="color:red; margin-top:8px; display:inline-block;">
          Delete
        </a>

      </div>

    <?php endwhile; ?>

  <?php else: ?>

    <div class="exam-card">
      No teachers found.
    </div>

  <?php endif; ?>

</body>
</html>