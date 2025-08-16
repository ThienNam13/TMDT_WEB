<?php
include '../php/database.php';
include 'header.php';

if (!isset($_GET['id']) || !isset($_GET['status']) || !is_numeric($_GET['id'])) {
    header("Location: manage-orders.php");
    exit();
}

$order_id = (int)$_GET['id'];
$new_status = trim($_GET['status']);

// Kiểm tra quyền và hợp lệ của trạng thái mới
$allowed_statuses = ['Chờ xác nhận', 'Đang xử lý', 'Đang giao hàng', 'Đã giao', 'Đã hủy'];
if (!in_array($new_status, $allowed_statuses)) {
    $_SESSION['error'] = "Trạng thái không hợp lệ";
    header("Location: order_detail.php?id=$order_id");
    exit();
}

// Cập nhật trạng thái
$sql = "UPDATE orders SET trang_thai = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $new_status, $order_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Cập nhật trạng thái đơn hàng thành công";
} else {
    $_SESSION['error'] = "Lỗi khi cập nhật trạng thái: " . $conn->error;
}

header("Location: order_detail.php?id=$order_id");
exit();
?>
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

$stmt->close();
$conn->close();
