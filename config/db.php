<?php
$host = "monorail.proxy.rlwy.net";
$user = "root";
$password = "YJKJKjXhOdKHyLLGcFhfcJ1InnosvSv";
$database = "railway";
$port = 19660;

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

mysqli_real_connect(
    $conn,
    $host,
    $user,
    $password,
    $database,
    $port,
    NULL,
    MYSQLI_CLIENT_SSL
);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>