<?php
include '../php/database.php';

// Kiểm tra quyền
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Lấy dữ liệu đơn hàng với các điều kiện lọc tương tự trang chính
// ... (code tương tự phần xây dựng query trong orders.php)

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="danh_sach_don_hang_' . date('Y-m-d') . '.xls"');

echo "<table border='1'>";
echo "<tr>
        <th>Mã đơn</th>
        <th>Khách hàng</th>
        <th>SĐT</th>
        <th>Email</th>
        <th>Ngày đặt</th>
        <th>Tổng tiền</th>
        <th>Số SP</th>
        <th>PTTT</th>
        <th>Trạng thái</th>
      </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>" . $row['ma_don'] . "</td>
            <td>" . $row['customer_name'] . "</td>
            <td>" . $row['customer_phone'] . "</td>
            <td>" . ($row['customer_email_account'] ?: $row['customer_email']) . "</td>
            <td>" . date('d/m/Y H:i', strtotime($row['order_date'])) . "</td>
            <td>" . number_format($row['total_amount'], 0, ',', '.') . "</td>
            <td>" . $row['item_count'] . "</td>
            <td>" . $row['payment_method'] . "</td>
            <td>" . $row['status'] . "</td>
          </tr>";
}

echo "</table>";
exit();
?>