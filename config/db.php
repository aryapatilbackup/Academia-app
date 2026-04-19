<?php
$host = "monorail.proxy.rlwy.net";
$user = "root";
$password = "YJKJKjXhOdKHyLLGcFhfcJ1InnosvSv";
$database = "railway";
$port = 19660;

$conn = mysqli_connect($host, $user, $password, $database, $port);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>