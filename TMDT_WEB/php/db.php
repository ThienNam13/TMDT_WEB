<?php
$host = 'localhost';
$dbname = 'myphamdb';
$username = 'root';
$password = '';

$link = @new mysqli($host, $username, $password, $dbname);

if ($link->connect_error) {
    die("Kết nối thất bại.");
}
?>
