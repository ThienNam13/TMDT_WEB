<?php
include 'database.php';
session_start();

$fullname = trim($_POST['fullname']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Kiểm tra mật khẩu nhập lại
if ($password !== $confirm_password) {
    $_SESSION['error'] = "Mật khẩu xác nhận không khớp";
    header("Location: ../login.php");
    exit;
}

// Kiểm tra email tồn tại
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $_SESSION['error'] = "Email đã tồn tại";
    header("Location: ../login.php");
    exit;
}
$stmt->close();

// Mã hóa mật khẩu
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Lưu vào DB
$stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $fullname, $email, $hashed_password);
if ($stmt->execute()) {
    $_SESSION['success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
    header("Location: ../login.php");
    exit;
} else {
    $_SESSION['error'] = "Lỗi đăng ký: " . $conn->error;
    header("Location: ../login.php");
    exit;
}
