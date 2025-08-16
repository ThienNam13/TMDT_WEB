<?php
include 'database.php';
session_start();
header('Content-Type: application/json');
date_default_timezone_set('Asia/Ho_Chi_Minh');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn chưa đăng nhập']);
    exit;
}

$orderId = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;
$reason  = trim($_POST['reason'] ?? '');

if ($orderId <= 0 || $reason === '') {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin']);
    exit;
}

// Kiểm tra quyền sở hữu đơn
$stmt = $conn->prepare("SELECT id, user_id, trang_thai, thoi_gian_dat, hinh_thuc_thanh_toan FROM orders WHERE id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();
$order  = $result->fetch_assoc();
$stmt->close();

if (!$order || $order['user_id'] != $_SESSION['user_id']) {
    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy đơn hàng']);
    exit;
}

// Kiểm tra trạng thái + thời gian <= 30 phút
$timeDiff = time() - strtotime($order['thoi_gian_dat']);
if ($order['trang_thai'] !== 'Chờ xác nhận' || $timeDiff > 60) {
    echo json_encode(['status' => 'error', 'message' => 'Không thể hủy đơn hàng này']);
    exit;
}

// Cập nhật trạng thái
$stmt = $conn->prepare("UPDATE orders SET trang_thai = 'Đã hủy', cancel_reason = ?, cancel_time = NOW() WHERE id = ?");
$stmt->bind_param("si", $reason, $orderId);
if ($stmt->execute()) {
    $stmt->close();

    $extraMsg = '';
    if (in_array($order['hinh_thuc_thanh_toan'], ['Chuyển khoản', 'MOMO'])) {
        $extraMsg = ' Admin sẽ xử lý số tiền của bạn sớm nhất.';
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Đơn hàng đã được hủy thành công.' . $extraMsg
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi khi hủy đơn']);
}