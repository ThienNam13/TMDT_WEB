<?php
// BẮT BUỘC: không echo gì trước phần PHP này
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../php/database.php';

// Nhận bộ lọc giống trang manage-orders.php
$search    = isset($_GET['search']) ? trim($_GET['search']) : '';
$status    = isset($_GET['status']) ? $_GET['status'] : '';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to   = isset($_GET['date_to']) ? $_GET['date_to'] : '';

// Xây dựng query (không LIMIT để xuất hết)
$sql = "SELECT
            o.id,
            o.ma_don,
            o.ho_ten AS customer_name,
            o.sdt AS customer_phone,
            o.thoi_gian_dat AS order_date,
            o.tong_tien AS total_amount,
            o.hinh_thuc_thanh_toan AS payment_method,
            o.trang_thai AS status,
            (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) AS item_count
        FROM orders o
        WHERE 1=1";

if ($search !== '') {
    $s = $conn->real_escape_string($search);
    $sql .= " AND (o.ma_don LIKE '%$s%' OR o.ho_ten LIKE '%$s%' OR o.sdt LIKE '%$s%')";
}

if ($status !== '' && $status !== 'all') {
    $st = $conn->real_escape_string($status);
    $sql .= " AND o.trang_thai = '$st'";
}

if ($date_from !== '') {
    $df = $conn->real_escape_string($date_from);
    $sql .= " AND DATE(o.thoi_gian_dat) >= '$df'";
}
if ($date_to !== '') {
    $dt = $conn->real_escape_string($date_to);
    $sql .= " AND DATE(o.thoi_gian_dat) <= '$dt'";
}

$sql .= " ORDER BY o.thoi_gian_dat DESC";

$result = $conn->query($sql);
if (!$result) {
    die("Lỗi truy vấn: " . $conn->error);
}

// Gửi header tải Excel (KHÔNG được có output nào trước đó)
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="danh_sach_don_hang_' . date('Y-m-d') . '.xls"');
header('Pragma: no-cache');
header('Expires: 0');

// BOM để Excel đọc đúng UTF-8
echo "\xEF\xBB\xBF";

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
    $email = isset($row['customer_email']) ? $row['customer_email'] : '';
    echo "<tr>
            <td>" . htmlspecialchars($row['ma_don']) . "</td>
            <td>" . htmlspecialchars($row['customer_name']) . "</td>
            <td>" . htmlspecialchars($row['customer_phone']) . "</td>
            <td>" . htmlspecialchars($email) . "</td>
            <td>" . date('d/m/Y H:i', strtotime($row['order_date'])) . "</td>
            <td>" . number_format((float)$row['total_amount'], 0, ',', '.') . "</td>
            <td>" . (int)$row['item_count'] . "</td>
            <td>" . htmlspecialchars($row['payment_method']) . "</td>
            <td>" . htmlspecialchars($row['status']) . "</td>
          </tr>";
}

echo "</table>";
exit;
