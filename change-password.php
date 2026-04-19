<?php
require_once "student-auth.php";
include "config/db.php";
include "includes/toast.php";
$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($current, $user['password'])) {
        $error = "Current password is incorrect.";
    } 
    elseif ($new !== $confirm) {
        $error = "New passwords do not match.";
    } 
    elseif (strlen($new) < 6) {
        $error = "Password must be at least 6 characters.";
    } 
    else {

        $hashed = password_hash($new, PASSWORD_DEFAULT);

        $update = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $update->bind_param("si", $hashed, $user_id);
        $update->execute();

        $success = "Password changed successfully.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Change Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="student.css">
</head>

<body>

<div class="page">

<div class="page-header">
<a href="student-profile.php" class="back-btn">←</a>
<h2>Change Password</h2>
</div>

<?php if ($error || $success): ?>
<?php
$isError = $error ? true : false;
$message = $error ?: $success;
?>
<div id="toast" class="toast-msg exam-card"
     style="background:<?= $isError ? '#fee2e2' : '#dcfce7' ?>;
            border-left:5px solid <?= $isError ? '#dc2626' : '#16a34a' ?>;">
<div style="color:<?= $isError ? '#dc2626' : '#16a34a' ?>;font-weight:600;">
<?= htmlspecialchars($message) ?>
</div>
</div>
<?php endif; ?>

<div class="login-card">

<form method="post">

<div class="input-group">
<label>Current Password</label>
<input type="password" name="current_password" id="currentPass" required>
<span class="toggle-pass">👁</span>
</div>

<div class="input-group">
<label>New Password</label>
<input type="password" name="new_password" id="newPass" required oninput="checkStrength()">
<span class="toggle-pass">👁</span>

<div class="strength">
<div class="strength-bar" id="strengthBar"></div>
</div>

</div>

<div class="input-group">
<label>Confirm Password</label>
<input type="password" name="confirm_password" id="confirmPass" required oninput="checkMatch()">
<span class="toggle-pass">👁</span>

<div id="matchMsg" class="match"></div>

</div>

<button class="primary-btn" style="margin-top:10px;">
Change Password
</button>

</form>

</div>

</div>



<script>

/* password toggle (same as login page) */

document.querySelectorAll(".toggle-pass").forEach((toggle, index) => {

const inputs = ["currentPass","newPass","confirmPass"];
const passwordInput = document.getElementById(inputs[index]);

toggle.addEventListener("click", function () {

const hidden = passwordInput.type === "password";
passwordInput.type = hidden ? "text" : "password";
this.textContent = hidden ? "🙈" : "👁";

});

});


/* password strength */

function checkStrength(){

const pass=document.getElementById("newPass").value;
const bar=document.getElementById("strengthBar");

let strength=0;

if(pass.length>=6) strength+=25;
if(pass.match(/[A-Z]/)) strength+=25;
if(pass.match(/[0-9]/)) strength+=25;
if(pass.match(/[^A-Za-z0-9]/)) strength+=25;

bar.style.width=strength+"%";

if(strength<=25){
bar.style.background="red";
}else if(strength<=50){
bar.style.background="orange";
}else if(strength<=75){
bar.style.background="#eab308";
}else{
bar.style.background="green";
}

}


/* password match */

function checkMatch(){

const pass=document.getElementById("newPass").value;
const confirm=document.getElementById("confirmPass").value;
const msg=document.getElementById("matchMsg");

if(confirm.length===0){
msg.innerHTML="";
return;
}

if(pass===confirm){
msg.innerHTML="✔ Passwords match";
msg.style.color="green";
}else{
msg.innerHTML="✖ Passwords do not match";
msg.style.color="red";
}

}

</script>

</body>
</html>