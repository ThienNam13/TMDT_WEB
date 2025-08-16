<?php
include 'database.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn chưa đăng nhập']);
    exit;
}

$orderId = intval($_POST['order_id'] ?? 0);
$action = $_POST['action'] ?? '';
$reason = trim($_POST['reason'] ?? '');

$stmt = $conn->prepare("SELECT user_id, trang_thai FROM orders WHERE id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order || $order['user_id'] != $_SESSION['user_id']) {
    echo json_encode(['status' => 'error', 'message' => 'Đơn hàng không hợp lệ']);
    exit;
}

if ($order['trang_thai'] !== 'Đang giao') {
    echo json_encode(['status' => 'error', 'message' => 'Trạng thái đơn hàng không hợp lệ']);
    exit;
}

if ($action === 'complete') {
    $stmt = $conn->prepare("UPDATE orders SET trang_thai = 'Hoàn tất' WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['status' => 'success', 'message' => 'Đơn hàng đã được hoàn tất']);
} elseif ($action === 'return') {
    if (!$reason) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập lý do trả hàng']);
        exit;
    }
    $stmt = $conn->prepare("UPDATE orders SET trang_thai = 'Yêu cầu trả hàng', cancel_reason = ? WHERE id = ?");
    $stmt->bind_param("si", $reason, $orderId);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['status' => 'success', 'message' => 'Yêu cầu trả hàng đã được gửi']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ']);
}