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
</head>
<body>

<h3>Upload Hall Ticket</h3>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green; margin-bottom: 10px;">
        Hall ticket uploaded successfully.
    </p>
<?php endif; ?>

<form action="admin-hallticket-save.php" method="post" enctype="multipart/form-data">

    <label>Student</label><br>
    <select name="student_id" required>
        <option value="">Select student</option>
        <?php
        $q = mysqli_query($conn, "SELECT id, name FROM users WHERE role='student'");
        while ($r = mysqli_fetch_assoc($q)) {
            echo "<option value='{$r['id']}'>{$r['name']}</option>";
        }
        ?>
    </select><br>

    <label>Exam Session</label><br>
   <select name="exam_session" required>
    <option value="">Select Exam Session</option>
    <option value="Sem 1 Hall Ticket">Sem 1 Hall Ticket</option>
    <option value="Sem 2 Hall Ticket">Sem 2 Hall Ticket</option>
    <option value="Sem 3 Hall Ticket">Sem 3 Hall Ticket</option>
    <option value="Sem 4 Hall Ticket">Sem 4 Hall Ticket</option>
    <option value="Sem 5 Hall Ticket">Sem 5 Hall Ticket</option>
    <option value="Sem 6 Hall Ticket">Sem 6 Hall Ticket</option>
</select><br>


<label>Hall Ticket (PDF)</label><br>
    <input type="file" name="pdf" accept="application/pdf" required><br><br>

    <button type="submit">Upload</button>

</form>

</body>
</html>
