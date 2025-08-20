<?php
include '../php/database.php';

// Đếm số đơn hàng mới (Chờ xác nhận)
$sql = "SELECT COUNT(*) AS cnt FROM orders WHERE trang_thai = 'Chờ xác nhận'";
$res = $conn->query($sql);

$count = 0;
if ($res) {
    $row = $res->fetch_assoc();
    $count = (int)$row['cnt'];
}

echo json_encode([
    "count" => $count
]);
