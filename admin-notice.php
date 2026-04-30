<?php
include 'admin-auth.php';
include 'config/db.php';

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
?>
<!DOCTYPE html>
<html>
<head>
  <title>Post Notice</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="student.css">

</head>

<body class="page">

  <div class="page-header">
    <a href="admin-dashboard.php" class="back-btn">←</a>
    <h2>Upload Notice</h2>
  </div>

  <?php if (!empty($success)): ?>
  <div class="exam-card" style="background:#dcfce7; border-left:5px solid #16a34a;">
    <div style="color:#16a34a; font-weight:600;">
      <?php echo $success; ?>
    </div>
  </div>
<?php endif; ?>
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

</body>
</html>