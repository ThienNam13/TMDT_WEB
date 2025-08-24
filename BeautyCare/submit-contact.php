<?php
session_start();
include 'php\database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    $errors = [];
    
    if (empty($name)) $errors[] = 'Vui lòng nhập họ và tên';
    if (empty($email)) $errors[] = 'Vui lòng nhập email';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ';
    if (empty($message)) $errors[] = 'Vui lòng nhập nội dung liên hệ';

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO lien_he (user_id, ho_ten, email, noi_dung, ngay_gui) 
                                    VALUES (?, ?, ?, ?, NOW())");

            $stmt->bind_param("isss", $userId, $name, $email, $message);

            if ($stmt->execute()) {
                $_SESSION['form_submitted'] = true;
                header('Location: contact.php?success=1');
                exit();
            } else {
                error_log('Lỗi khi xử lý form liên hệ: ' . $stmt->error);
                $_SESSION['form_errors'] = ['Đã có lỗi xảy ra, vui lòng thử lại sau'];
                header('Location: contact.php');
                exit();
            }
            
            $_SESSION['form_submitted'] = true;
            header('Location: contact.php?success=1');
            exit();
        } catch (PDOException $e) {
            error_log('Lỗi khi xử lý form liên hệ: ' . $e->getMessage());
            $_SESSION['form_errors'] = ['Đã có lỗi xảy ra, vui lòng thử lại sau'];
            header('Location: contact.php');
            exit();
        }
    } else {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['old_input'] = [
            'name' => $name,
            'email' => $email,
            'message' => $message
        ];
        header('Location: contact.php');
        exit();
    }
} else {
    header('Location: contact.php');
    exit();
}
