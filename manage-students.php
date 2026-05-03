<?php
include 'admin-auth.php';
include 'config/db.php';

/* DELETE USER */
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];

  $stmt = $conn->prepare("DELETE FROM users WHERE id=? AND role='student'");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  header("Location: manage-students.php");
  exit;
}

/* FETCH STUDENTS */
$students = $conn->query("SELECT id, name, email FROM users WHERE role='student' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Students</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">
</head>

<body class="page">

  <!-- HEADER -->
  <div class="page-header">
    <a href="admin-dashboard.php" class="back-btn">←</a>
    <h2>Manage Students</h2>
  </div>

  <?php if ($students && $students->num_rows > 0): ?>

    <?php while($s = $students->fetch_assoc()): ?>

      <div class="exam-card">

        <div style="font-weight:600;">
          <?= htmlspecialchars($s['name']) ?>
        </div>

        <div style="font-size:13px; color:#666;">
          <?= htmlspecialchars($s['email']) ?>
        </div>

        <a href="?delete=<?= $s['id'] ?>"
           onclick="return confirm('Delete this student?')"
           style="color:red; margin-top:8px; display:inline-block;">
          Delete
        </a>

      </div>

    <?php endwhile; ?>

  <?php else: ?>

    <div class="exam-card">
      No students found.
    </div>

  <?php endif; ?>

</body>
</html>