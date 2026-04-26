<?php
session_start();
require_once "config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // 1️⃣ Empty fields
    if ($email === "" || $password === "") {
        $error = "All fields are required";
    } else {

        // 2️⃣ Get user by email
        $sql = "SELECT id, name, email, password, role FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 0) {
            $error = "Invalid email or password";
        } else {
            $user = mysqli_fetch_assoc($result);

            // 3️⃣ Verify password
            if (!password_verify($password, $user["password"])) {
                $error = "Invalid email or password";
            } else {
                // ✅ LOGIN SUCCESS
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];

                

                // 4️⃣ Redirect by role
                if ($user["role"] === "admin") {
                    header("Location: admin-dashboard.php");
                } elseif ($user["role"] === "teacher") {
                    header("Location: teacher-dashboard.php");
                } else {
                    header("Location: student-dashboard.php");
                }
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login | My App</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="manifest" href="manifest.json">
<meta name="theme-color" content="#0f172a">

  <link rel="stylesheet" href="login.css">
</head>
<body>
   
<div id="splash">
  <img src="icon-192.png" alt="logo">
</div>

  <div class="login-container">
    <h2>Login</h2>

<?php if ($error): ?>
  <div class="server-error">
    <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>


<?php if (isset($_GET['success'])): ?>
  <div class="server-success">
    <?php echo htmlspecialchars($_GET['success']); ?>
  </div>
<?php endif; ?>

<form id="loginForm" action="login.php" method="POST">

      <div class="form-group">
        <label>Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter your email">
        <small class="error" id="emailError"></small>
      </div>

      <div class="form-group password-group">
        <label>Password</label>

        <div class="password-wrapper">
          <input type="password" id="password" name="password" placeholder="Enter password">
          <span id="togglePassword" class="toggle-password">👁</span>
        </div>

        <small class="error" id="passwordError"></small>
      </div>

      <button type="submit" class="login-btn">Login</button>
    </form>

    <p class="register-link">
      Don't have an account?
      <a href="register.html">Register</a>
    </p>
  </div>

  <script src="login.js"></script>

  <script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("sw.js");
}
</script>

<script id="splashfix2">
document.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    const splash = document.getElementById("splash");
    if (splash) splash.style.display = "none";
  }, 1000);
});
</script>
</body>
</html>
