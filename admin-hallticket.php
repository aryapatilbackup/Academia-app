<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Upload Hall Ticket</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:sans-serif}

body{
  background: linear-gradient(135deg,#7c83ff,#8f7bdb);
  min-height:100vh;
}

/* HEADER */
.header{
  padding:20px;
  color:#fff;
  font-size:22px;
  font-weight:bold;
  display:flex;
  align-items:center;
  gap:10px;
}

.header a{
  text-decoration:none;
  color:#fff;
  font-size:20px;
}

/* CARD */
.card{
  background:#fff;
  margin:20px;
  padding:20px;
  border-radius:20px;
  box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

/* FORM */
label{
  display:block;
  margin-top:10px;
  margin-bottom:5px;
  font-weight:600;
  color:#444;
}

input, select{
  width:100%;
  padding:12px;
  border-radius:10px;
  border:1px solid #ddd;
  font-size:14px;
}

/* FILE INPUT FIX */
input[type="file"]{
  padding:8px;
}

/* BUTTON */
.btn{
  width:100%;
  margin-top:20px;
  padding:14px;
  background:#2f7f6f;
  color:#fff;
  border:none;
  border-radius:15px;
  font-size:18px;
  font-weight:bold;
  cursor:pointer;
}

/* SUCCESS MESSAGE */
.success{
  color:green;
  margin-bottom:10px;
  font-size:14px;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
  <a href="admin-dashboard.php">←</a>
  Upload Hall Ticket
</div>

<!-- CARD -->
<div class="card">

<?php if (isset($_GET['success'])): ?>
  <div class="success">Hall ticket uploaded successfully.</div>
<?php endif; ?>

<form action="admin-hallticket-save.php" method="post" enctype="multipart/form-data">

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

  <label>Upload PDF</label>
  <input type="file" name="pdf" accept="application/pdf" required>

  <button type="submit" class="btn">Upload PDF</button>

</form>

</div>

</body>
</html>
