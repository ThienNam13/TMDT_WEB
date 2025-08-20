<?php
include '../php/database.php';

header('Content-Type: application/json');

// Khởi tạo session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    
    // Validate dữ liệu
    if (empty($fullname) || empty($email) || empty($_POST['password'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin bắt buộc']);
        exit;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
        exit;
    }

    try {
        // Kiểm tra email đã tồn tại chưa
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Email đã được sử dụng']);
            exit;
        }

        // Thêm khách hàng mới
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, phone, address, password, role) VALUES (?, ?, ?, ?, ?, 'user')");
        $stmt->bind_param("sssss", $fullname, $email, $phone, $address, $password);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Thêm khách hàng thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm khách hàng: ' . $conn->error]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
}
?>