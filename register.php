<?php
require_once "config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $role = trim($_POST["role"] ?? "");

    // 1️⃣ Empty fields check
    if ($name === "" || $email === "" || $password === "" || $role === "") {
        $error = "All fields are required";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } else {

        // 2️⃣ Check if email already exists
        $check = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "Email already registered";
        } else {

            // 3️⃣ Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // 4️⃣ Insert user
            $insert = "INSERT INTO users (name, email, password, role)
                       VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert);
            mysqli_stmt_bind_param($stmt, "ssss",
                $name, $email, $hashedPassword, $role
            );

            if (mysqli_stmt_execute($stmt)) {

                // 🔥 NEW USER ID
                $new_user_id = mysqli_insert_id($conn);

                // 🔥 ADD USER TO GLOBAL GROUP CHAT (conversation_id = 1)
                $group_stmt = mysqli_prepare($conn, "
                    INSERT INTO conversation_participants (conversation_id, user_id)
                    VALUES (1, ?)
                ");
                mysqli_stmt_bind_param($group_stmt, "i", $new_user_id);
                mysqli_stmt_execute($group_stmt);
                mysqli_stmt_close($group_stmt);

                // ✅ SUCCESS → go to login
                header("Location: login.php?success=Registration successful. Please login.");
                exit;

            } else {
                $error = "Registration failed. Try again.";
            }
        }
    }
}
?>