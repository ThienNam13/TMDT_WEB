<?php
include '../php/database.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$customerId = $data['id'] ?? 0;
$action = $data['action'] ?? 'block'; // 'block' or 'unblock'

// Check if customer exists
$check = $conn->prepare("SELECT id FROM users WHERE id = ? AND role='user'");
$check->bind_param("i", $customerId);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Khách hàng không tồn tại']);
    exit;
}

// Update blocked status
$blocked = $action === 'block' ? 1 : 0;
$stmt = $conn->prepare("UPDATE users SET blocked = ? WHERE id = ?");
$stmt->bind_param("ii", $blocked, $customerId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật trạng thái']);
}
?>