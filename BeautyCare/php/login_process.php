<?php
include 'database.php';
session_start();

$email = trim($_POST['email']);
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, fullname, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id, $fullname, $hashed_password);
if ($stmt->fetch()) {
    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['fullname'] = $fullname;
        $_SESSION['success'] = "Đăng nhập thành công!";
        header("Location: ../index.php");
        exit;
    } else {
        $_SESSION['error'] = "Sai mật khẩu";
        header("Location: ../login.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Email không tồn tại";
    header("Location: ../login.php");
    exit;
}
