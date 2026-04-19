<?php
include 'admin-auth.php';
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Result</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<h3>Upload Result</h3>

<?php if (isset($_GET['success'])): ?>
  <p style="color:green;">Result uploaded successfully.</p>
<?php endif; ?>

<form action="admin-result-save.php" method="post" enctype="multipart/form-data">

  <label>Student</label><br>
  <select name="student_id" required>
    <option value="">Select student</option>
    <?php
    $q = mysqli_query($conn, "SELECT id, name FROM users WHERE role='student'");
    while ($r = mysqli_fetch_assoc($q)) {
        echo "<option value='{$r['id']}'>{$r['name']}</option>";
    }
    ?>
  </select><br><br>

  <label>Exam Session</label><br>
  <select name="exam_session" required>
    <option value="">Select session</option>
    <option>Sem 1 Result</option>
    <option>Sem 2 Result</option>
    <option>Sem 3 Result</option>
    <option>Sem 4 Result</option>
    <option>Sem 5 Result</option>
    <option>Sem 6 Result</option>
  </select><br><br>

  <label>Result PDF</label><br>
  <input type="file" name="pdf" accept="application/pdf" required><br><br>

  <button type="submit">Upload</button>
</form>

</body>
</html>
