<?php
session_start();
include 'database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Chưa đăng nhập"]);
    exit;
}

$current = $_POST['current_password'];
$new = $_POST['new_password'];
$confirm = $_POST['confirm_password'];

// Lấy mật khẩu hiện tại
$stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($hash);
$stmt->fetch();
$stmt->close();

if (!password_verify($current, $hash)) {
    echo json_encode(["status" => "error", "message" => "Mật khẩu hiện tại không đúng"]);
    exit;
}
if ($new !== $confirm) {
    echo json_encode(["status" => "error", "message" => "Mật khẩu mới không khớp"]);
    exit;
}

$newHash = password_hash($new, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
$stmt->bind_param("si", $newHash, $_SESSION['user_id']);
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Đổi mật khẩu thành công"]);
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi khi đổi mật khẩu"]);
}
