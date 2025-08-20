<?php
header('Content-Type: application/json; charset=utf-8');

// Bật báo lỗi chi tiết
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../php/database.php';

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập'], JSON_UNESCAPED_UNICODE);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerId = (int)($_POST['id'] ?? 0);
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $blocked = isset($_POST['blocked']) ? 1 : 0;

    $errors = [];
    if (empty($fullname)) $errors[] = "Vui lòng nhập họ tên";
    if (empty($email)) $errors[] = "Vui lòng nhập email";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email không hợp lệ";

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'message' => implode("\n", $errors)], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Kiểm tra email trùng
    $check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $check->bind_param("si", $email, $customerId);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email đã tồn tại'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Cập nhật
    $stmt = $conn->prepare("UPDATE users SET 
        fullname = ?, 
        email = ?, 
        phone = ?, 
        address = ?, 
        blocked = ? 
        WHERE id = ?");
    
    $stmt->bind_param("ssssii", $fullname, $email, $phone, $address, $blocked, $customerId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cập nhật thành công'], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi database: ' . $conn->error], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ'], JSON_UNESCAPED_UNICODE);
}
?>