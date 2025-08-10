<?php
session_start();
include 'database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Chưa đăng nhập"]);
    exit;
}

$address = trim($_POST['address']);

$stmt = $conn->prepare("UPDATE users SET address=? WHERE id=?");
$stmt->bind_param("si", $address, $_SESSION['user_id']);
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Địa chỉ đã được lưu"]);
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi khi lưu địa chỉ"]);
}
