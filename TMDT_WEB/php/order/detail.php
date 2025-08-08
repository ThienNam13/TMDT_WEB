<?php
require_once('../db.php');

// Nhận mã đơn hàng
$ma_don = $_GET['order_id'] ?? '';

if (empty($ma_don)) {
    http_response_code(400);
    echo json_encode(['error' => 'Thiếu mã đơn hàng']);
    exit;
}

// Truy vấn đơn hàng
$sql_order = "SELECT id, ho_ten, tong_tien FROM orders WHERE ma_don = ?";
$stmt = $link->prepare($sql_order);
$stmt->bind_param("s", $ma_don);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);

    echo json_encode(['error' => 'Không tìm thấy đơn hàng']);
    exit;
}

$order = $result->fetch_assoc();
$order_id = $order['id'];

// Truy vấn các món trong đơn hàng
$sql_items = "
    SELECT f.ten_mon AS name, oi.so_luong AS quantity, oi.don_gia AS price
    FROM order_items oi
    JOIN foods f ON oi.food_id = f.id
    WHERE oi.order_id = ?
";
$stmt_items = $link->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$res_items = $stmt_items->get_result();

$items = [];
while ($row = $res_items->fetch_assoc()) {
    $items[] = $row;
}

header('Content-Type: application/json');
echo json_encode([
    'order_id' => $ma_don,
    'tong_tien' => $order['tong_tien'],
    "trang_thai": "Đang giao",
    'items' => $items
]);

error_log("Mã đơn nhận được: " . $ma_don);
error_log("Kết quả đơn hàng: " . json_encode($order));