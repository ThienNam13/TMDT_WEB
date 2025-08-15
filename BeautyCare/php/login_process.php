<?php
include 'database.php';
session_start();

header('Content-Type: application/json');

$email = trim($_POST['email']);
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, fullname, password, blocked FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id, $fullname, $hashed_password, $blocked);

if ($stmt->fetch()) {
    if ((int)$blocked === 1) {
        echo json_encode([
            "status" => "error",
            "message" => "Tài khoản của bạn đã bị khóa. Vui lòng liên hệ hỗ trợ."
        ]);
        exit;
    }
    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['fullname'] = $fullname;
        echo json_encode([
            "status" => "success",
            "message" => "Đăng nhập thành công!"
        ]);
        exit;
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Sai mật khẩu"
        ]);
        exit;
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Email không tồn tại"
    ]);
    exit;
}
