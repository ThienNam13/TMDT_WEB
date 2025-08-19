<?php
session_start();
include 'php\database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : null;
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    $errors = [];
    
    if (empty($name)) $errors[] = 'Vui lòng nhập họ và tên';
    if (empty($email)) $errors[] = 'Vui lòng nhập email';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ';
    if (empty($subject)) $errors[] = 'Vui lòng nhập tiêu đề';
    if (empty($message)) $errors[] = 'Vui lòng nhập nội dung yêu cầu';

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO lien_he (user_id, ho_ten, email, so_dien_thoai, tieu_de, noi_dung, ngay_gui) 
                                   VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$userId, $name, $email, $phone, $subject, $message]);
            
            $_SESSION['support_submitted'] = true;
            header('Location: support.php?success=1');
            exit();
        } catch (PDOException $e) {
            error_log('Lỗi khi xử lý form hỗ trợ: ' . $e->getMessage());
            $_SESSION['form_errors'] = ['Đã có lỗi xảy ra, vui lòng thử lại sau'];
            header('Location: support.php');
            exit();
        }
    } else {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['old_input'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message
        ];
        header('Location: support.php');
        exit();
    }
} else {
    header('Location: support.php');
    exit();
}
