<?php
include '../php/database.php';
header('Content-Type: application/json');

$customerId = $_POST['id'] ?? $_GET['id'] ?? 0;

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối database']);
    exit;
}

// Kiểm tra khách hàng có tồn tại không
$check = $conn->prepare("SELECT id FROM users WHERE id = ? AND role='user'");
if (!$check) {
    echo json_encode(['success' => false, 'message' => 'Lỗi chuẩn bị truy vấn']);
    exit;
}

$check->bind_param("i", $customerId);
if (!$check->execute()) {
    echo json_encode(['success' => false, 'message' => 'Lỗi thực thi truy vấn']);
    exit;
}

$check->store_result();

if ($check->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Khách hàng không tồn tại']);
    exit;
}

// Thực hiện xóa
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Lỗi chuẩn bị lệnh xóa']);
    exit;
}

$stmt->bind_param("i", $customerId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa khách hàng: ' . $conn->error]);
}
?>