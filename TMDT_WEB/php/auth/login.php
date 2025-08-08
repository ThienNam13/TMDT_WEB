<?php
session_start();
require_once __DIR__ . '/../db.php';

$isPopup = !empty($_POST['popup']);   // check form gửi lên
$email = trim($_POST['email']?? '');
$password = $_POST['password']?? '';

if ($email === '' || $password === '') {
    header('Location: ../../login.php?msg=empty');
    exit;
}

$sql  = "SELECT id, fullname, password, blocked FROM users WHERE email = ? LIMIT 1";
$stmt = $link->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    header('Location: ../../login.php?msg=fail&popup=1');
    exit;
}
if ($user['blocked']) {
    header('Location: ../../login.php?msg=blocked&popup=1');
    exit;
}
if (!password_verify($password, $user['password'])) {
    header('Location: ../../login.php?msg=fail&popup=1');
    exit;
}

// Đăng nhập thành công
$_SESSION['user_id']  = $user['id'];
$_SESSION['email']    = $email;
$_SESSION['fullname'] = $user['fullname'];

if ($isPopup) {
    header('Location: ../../login.php?msg=loginok&popup=1');
} else {
    header('Location: ../../index.php');
}
exit;