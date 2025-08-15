<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myphamdb";

// Kết nối
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->query("SET time_zone = '+07:00'");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>