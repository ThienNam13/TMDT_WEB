<?php
include 'database.php';
session_start();

header('Content-Type: application/json');

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if ($password !== $confirm) {
    echo json_encode(["status" => "error", "message" => "Mật khẩu xác nhận không khớp."]);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(["status" => "error", "message" => "Mật khẩu phải từ 6 ký tự trở lên."]);
    exit;
}

// Kiểm tra token
$stmt = $conn->prepare("SELECT id FROM users WHERE reset_token=? AND reset_expires > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->bind_result($userId);
if (!$stmt->fetch()) {
    echo json_encode(["status" => "error", "message" => "Liên kết không hợp lệ hoặc đã hết hạn."]);
    exit;
}
$stmt->close();

// Cập nhật mật khẩu và xóa token
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE id=?");
$stmt->bind_param("si", $hashed_password, $userId);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Mật khẩu đã được cập nhật!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Có lỗi khi cập nhật mật khẩu."]);
}
