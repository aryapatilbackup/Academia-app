<?php
include 'admin-auth.php';
include 'config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Upload Hall Ticket</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:sans-serif;
}

/* PAGE */
.page {
  min-height: 100vh;
  background: linear-gradient(180deg, #7c83ff 0%, #8e84d8 100%);
  padding: 16px;
}

/* HEADER */
.page-header {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
}

.page-header h1 {
  font-size: 26px;
  font-weight: 700;
  color: #2d2d2d;
}

/* BACK BUTTON */
.back-btn {
  background: transparent;
  border: none;
  font-size: 22px;
  cursor: pointer;
}

/* CARD */
.form-card {
  background: #f3f4f6;
  padding: 20px;
  border-radius: 20px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* FORM */
.form-group {
  margin-bottom: 16px;
}

label {
  font-size: 14px;
  color: #444;
  margin-bottom: 6px;
  display: block;
}

input, select {
  width: 100%;
  padding: 12px;
  border-radius: 10px;
  border: 1px solid #ccc;
  font-size: 14px;
  background: #fff;
}

/* FILE INPUT */
input[type="file"] {
  padding: 8px;
}

/* BUTTON */
.primary-btn {
  width: 100%;
  padding: 14px;
  border: none;
  border-radius: 18px;
  background: #2f7f6f;
  color: #fff;
  font-size: 18px;
  font-weight: bold;
  margin-top: 10px;
  cursor: pointer;
}

/* SUCCESS */
.success-msg {
  color: green;
  margin-bottom: 10px;
  font-size: 14px;
}
</style>
</head>

<body>

<div class="page">

  <!-- HEADER -->
  <div class="page-header">
   <a href="admin-dashboard.php" class="back-btn">←</a>
    <h1>Upload Hall Ticket</h1>
  </div>

  <!-- CARD -->
  <div class="form-card">

    <?php if (isset($_GET['success'])): ?>
      <div class="success-msg">Hall ticket uploaded successfully.</div>
    <?php endif; ?>

    <form action="admin-hallticket-save.php" method="post" enctype="multipart/form-data">

      <!-- STUDENT -->
      <div class="form-group">
        <label>Student</label>
        <select name="student_id" required>
          <option value="">Select student</option>
          <?php
          $q = mysqli_query($conn, "SELECT id, name FROM users WHERE role='student'");
          while ($r = mysqli_fetch_assoc($q)) {
              echo "<option value='{$r['id']}'>{$r['name']}</option>";
          }
          ?>
        </select>
      </div>

      <!-- SESSION -->
      <div class="form-group">
        <label>Exam Session</label>
        <select name="exam_session" required>
          <option value="">Select Exam Session</option>
          <option value="Sem 1 Hall Ticket">Sem 1 Hall Ticket</option>
          <option value="Sem 2 Hall Ticket">Sem 2 Hall Ticket</option>
          <option value="Sem 3 Hall Ticket">Sem 3 Hall Ticket</option>
          <option value="Sem 4 Hall Ticket">Sem 4 Hall Ticket</option>
          <option value="Sem 5 Hall Ticket">Sem 5 Hall Ticket</option>
          <option value="Sem 6 Hall Ticket">Sem 6 Hall Ticket</option>
        </select>
      </div>

      <!-- FILE -->
      <div class="form-group">
        <label>Upload PDF</label>
        <input type="file" name="pdf" accept="application/pdf" required>
      </div>

      <!-- BUTTON -->
      <button class="primary-btn">Upload PDF</button>

    </form>

  </div>

</div>

</body>
</html>