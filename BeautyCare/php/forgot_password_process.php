<?php
session_start();
include 'database.php';

header('Content-Type: application/json');

// Load PHPMailer
$autoload = __DIR__ . '/vendor/autoload.php';
$phpmailer_src = __DIR__ . '/PHPMailer/src/';
if (file_exists($autoload)) {
    require $autoload;
} elseif (file_exists($phpmailer_src . 'PHPMailer.php')) {
    require $phpmailer_src . 'Exception.php';
    require $phpmailer_src . 'PHPMailer.php';
    require $phpmailer_src . 'SMTP.php';
} else {
    echo json_encode(["status" => "error", "message" => "Không tìm thấy thư viện PHPMailer"]);
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Validate email
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
if (!$email) {
    echo json_encode(["status" => "error", "message" => "Email không hợp lệ."]);
    exit;
}

// Tìm user
$stmt = $conn->prepare("SELECT id, fullname FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id, $fullname);
if (!$stmt->fetch()) {
    echo json_encode(["status" => "error", "message" => "Email không tồn tại trong hệ thống."]);
    exit;
}
$stmt->close();

// Tạo token
$token = bin2hex(random_bytes(32));
$expires = date('Y-m-d H:i:s', strtotime('+30 minutes'));

$stmt = $conn->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE id=?");
$stmt->bind_param("ssi", $token, $expires, $id);
$stmt->execute();
$stmt->close();

// Cấu hình SMTP
$smtp_host = 'smtp.gmail.com';
$smtp_username = 'thiennamng308@gmail.com';
$smtp_password = 'jjtlltdmhfixaizt';
$smtp_port = 587;
$smtp_secure = 'tls';
$from_email = 'thiennamng308@gmail.com';
$from_name = 'BeautyCare';

// Gửi mail
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = $smtp_host;
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtp_username;
    $mail->Password   = $smtp_password;
    $mail->SMTPSecure = $smtp_secure;
    $mail->Port       = $smtp_port;

    $mail->setFrom($from_email, $from_name);
    $mail->addAddress($email, $fullname);

    $mail->isHTML(true);
    $mail->Subject = 'Đặt lại mật khẩu - BeautyCare';
    $resetLink = sprintf('http://%s/TMĐT/BeautyCare/reset_password.php?token=%s', $_SERVER['HTTP_HOST'], $token);
    $mail->Body    = "Xin chào $fullname,<br>Nhấn vào liên kết dưới đây để đặt lại mật khẩu:<br><a href='$resetLink'>$resetLink</a><br>Liên kết này hết hạn sau 30 phút.";
    $mail->send();

    echo json_encode(["status" => "success", "message" => "Liên kết đặt lại mật khẩu đã được gửi tới email của bạn."]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Không thể gửi email: " . $mail->ErrorInfo]);
}
