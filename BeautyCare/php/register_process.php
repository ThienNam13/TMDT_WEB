<?php
include 'database.php';
session_start();

header('Content-Type: application/json');

$fullname = trim($_POST['fullname']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if ($password !== $confirm_password) {
    echo json_encode(["status" => "error", "message" => "Mật khẩu xác nhận không khớp"]);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email đã tồn tại"]);
    exit;
}
$stmt->close();

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $fullname, $email, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Đăng ký thành công! Vui lòng đăng nhập."]);
    exit;
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi đăng ký: " . $conn->error]);
    exit;
}
