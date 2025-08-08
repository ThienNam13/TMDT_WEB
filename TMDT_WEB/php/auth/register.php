<?php
session_start();
require_once __DIR__ . '/../db.php';   // ../db.php vì db.php nằm ngay thư mục php/

// Lấy dữ liệu POST
$fullname         = trim($_POST['fullname'] ?? '');  // hiện chưa ghi xuống DB
$email         = trim($_POST['email']    ?? '');  // input email → cột email
$password         =       $_POST['password']        ?? '';
$confirm_password =       $_POST['confirm_password'] ?? '';

// Validate
$fullnameRegex = '/^(?=.*[\p{L}])(?=.*\s)[ \p{L}\'.-]{5,}$/u';// Ít nhất 5 ký tự, chỉ chữ cái và khoảng trắng
$emailRegex    = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
$passwordRegex = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&^_-])[A-Za-z\d@$!%*#?&^_-]{6,}$/';

if (!preg_match($fullnameRegex, $fullname)) {
    header('Location: ../../register.php?msg=invalid_fullname');
    exit;
}

if (!preg_match($emailRegex, $email)) {
    header('Location: ../../register.php?msg=invalid_email');
    exit;
}
if ($password !== $confirm_password) {
    header('Location: ../../register.php?msg=notmatch');
    exit;
}

// Kiểm tra trùng email
$sql  = "SELECT id FROM users WHERE email = ? LIMIT 1";
$stmt = $link->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows){
    $stmt->close();
    header('Location: ../../register.php?msg=exists');
    exit;
}
$stmt->close();

// Thêm user mới
$hash = password_hash($password, PASSWORD_DEFAULT);
$sql  = "INSERT INTO users(fullname, email, password) VALUES (?, ?, ?)";
$stmt = $link->prepare($sql);
$stmt->bind_param('sss', $fullname, $email, $hash);
$stmt->execute();
$user_id = $stmt->insert_id;
$stmt->close();

// Ghi session
$_SESSION['user_id']  = $user_id;
$_SESSION['email'] = $email;
$_SESSION['fullname'] = $fullname;

header('Location: ../../register.php?msg=welcome');
exit;

echo '<!DOCTYPE html><html><body><script>
 parent.postMessage("register-success","*");
 parent.postMessage("gotoLogin","*");
</script></body></html>';
exit;