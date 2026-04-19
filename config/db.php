<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "auth_app";   // ✅ MUST be auth_app

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database connection failed");
}
mysqli_set_charset($conn, "utf8mb4");