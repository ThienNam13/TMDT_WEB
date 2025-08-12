<?php
session_start();
include 'database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Chưa đăng nhập"]);
    exit;
}

$fullname = trim($_POST['fullname']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);

$stmt = $conn->prepare("UPDATE users SET fullname=?, email=?, phone=? WHERE id=?");
$stmt->bind_param("sssi", $fullname, $email, $phone, $_SESSION['user_id']);
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Cập nhật thành công"]);
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi khi cập nhật"]);
}
