<?php
include '../php/database.php';
header('Content-Type: application/json');

$orderId = $_POST['order_id'] ?? null;
$newStatus = $_POST['new_status'] ?? '';
$reason = $_POST['cancel_reason'] ?? '';

if (!$orderId || !$newStatus) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu dữ liệu']);
    exit;
}

$stmt = $conn->prepare("UPDATE orders SET trang_thai = ?, cancel_reason = ? WHERE id = ?");
$stmt->bind_param("ssi", $newStatus, $reason, $orderId);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Cập nhật trạng thái thành công']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi khi cập nhật']);
}

// if ($newStatus === 'Đang giao') {
//     $stmt = $conn->prepare("UPDATE orders SET trang_thai = ?, thoi_gian_giao = NOW() WHERE id = ?");
//     $stmt->bind_param("si", $newStatus, $orderId);
// } else {
//     $stmt = $conn->prepare("UPDATE orders SET trang_thai = ? WHERE id = ?");
//     $stmt->bind_param("si", $newStatus, $orderId);
// }
// $stmt->execute();

$stmt->close();
$conn->close();
