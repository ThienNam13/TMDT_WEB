<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "myphamdb";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
$conn->query("SET time_zone = '+07:00'");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
