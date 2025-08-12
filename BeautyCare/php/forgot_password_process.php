<?php
// php/forgot_password_process.php
session_start();
include 'database.php';

// cố gắng load PHPMailer (composer hoặc manual)
$autoload = __DIR__ . '/vendor/autoload.php';
$phpmailer_src = __DIR__ . '/PHPMailer/src/';

if (file_exists($autoload)) {
    require $autoload; // nếu bạn đã dùng composer
} elseif (file_exists($phpmailer_src . 'PHPMailer.php')) {
    require $phpmailer_src . 'Exception.php';
    require $phpmailer_src . 'PHPMailer.php';
    require $phpmailer_src . 'SMTP.php';
} else {
    $_SESSION['error'] = 'Thư viện PHPMailer không tìm thấy. Cài bằng composer (recommended) hoặc copy thư mục PHPMailer vào php/PHPMailer/';
    header('Location: ../forgot_password.php');
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// validate email
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
if (!$email) {
    $_SESSION['error'] = 'Email không hợp lệ.';
    header('Location: ../forgot_password.php');
    exit;
}

// tìm user
$stmt = $conn->prepare("SELECT id, fullname FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id, $fullname);
if (!$stmt->fetch()) {
    $stmt->close();
    $_SESSION['error'] = 'Email không tồn tại trong hệ thống.';
    header('Location: ../forgot_password.php');
    exit;
}
$stmt->close();

// tạo token và expiry
$token = bin2hex(random_bytes(32));
$expires = date('Y-m-d H:i:s', strtotime('+30 minutes'));

// cập nhật DB (cột reset_token, reset_expires phải có trong bảng users)
$stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
$stmt->bind_param("ssi", $token, $expires, $id);
$stmt->execute();
$stmt->close();

// ----- Cấu hình SMTP: sửa thông tin bên dưới -----
/*
 * Nếu dùng Gmail: cần bật 2FA + tạo App Password (không dùng mật khẩu thường).
 * Nếu test local: dùng Mailtrap để dễ debug.
 */
$smtp_host = 'smtp.gmail.com';            // SMTP server
$smtp_username = 'your_email@gmail.com'; // email gửi
$smtp_password = 'your_app_password';    // app password (Gmail) hoặc mật khẩu SMTP
$smtp_port = 587;
$smtp_secure = 'tls';
$from_email = 'your_email@gmail.com';
$from_name = 'BeautyCare';
// -----------------------------------------------

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = $smtp_host;
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtp_username;
    $mail->Password   = $smtp_password;
    if (!empty($smtp_secure)) $mail->SMTPSecure = $smtp_secure;
    $mail->Port       = $smtp_port;

    $mail->setFrom($from_email, $from_name);
    $mail->addAddress($email, $fullname);

    $mail->isHTML(true);
    $mail->Subject = 'Đặt lại mật khẩu - BeautyCare';
    $resetLink = 'http://localhost/BeautyCare/reset_password.php?token=' . $token;
    $mail->Body    = "Xin chào " . htmlspecialchars($fullname) . ",<br><br>"
                   . "Bạn (hoặc ai đó) đã yêu cầu đặt lại mật khẩu. Nhấn vào liên kết dưới đây để đặt lại:<br>"
                   . "<a href=\"$resetLink\">$resetLink</a><br><br>"
                   . "Liên kết này sẽ hết hạn trong 30 phút. Nếu bạn không yêu cầu, hãy bỏ qua email này.";
    $mail->AltBody = "Link reset: $resetLink";

    $mail->send();
    $_SESSION['success'] = 'Liên kết đặt lại mật khẩu đã được gửi tới email của bạn.';
} catch (Exception $e) {
    // PHPMailer lưu lỗi chi tiết ở $mail->ErrorInfo
    $_SESSION['error'] = 'Không thể gửi email. Lỗi: ' . ($mail->ErrorInfo ?? $e->getMessage());
}

// quay về form
header('Location: ../forgot_password.php');
exit;
