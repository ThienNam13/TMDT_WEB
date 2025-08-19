<?php
include '../php/database.php';

// Thiết lập header cho file Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="bao_cao_doanh_thu_' . date('Y-m-d') . '.xls"');

// Xử lý bộ lọc thời gian
$type = $_GET['type'] ?? 'this_month';

switch ($type) {
    case '7days':
        $startDate = date('Y-m-d', strtotime('-7 days'));
        $endDate = date('Y-m-d');
        break;
    case '30days':
        $startDate = date('Y-m-d', strtotime('-30 days'));
        $endDate = date('Y-m-d');
        break;
    case 'this_month':
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        break;
    case 'last_month':
        $startDate = date('Y-m-01', strtotime('-1 month'));
        $endDate = date('Y-m-t', strtotime('-1 month'));
        break;
    case 'this_year':
        $startDate = date('Y-01-01');
        $endDate = date('Y-12-31');
        break;
    case 'custom':
        $startDate = $_GET['start'] ?? date('Y-m-01');
        $endDate = $_GET['end'] ?? date('Y-m-t');
        break;
}

// Tạo nội dung file Excel
echo '<html><body>';
echo '<table border="1">';
echo '<tr><th colspan="4" style="text-align:center;font-size:16px;background:#f2f2f2;">BÁO CÁO DOANH THU ' . date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate)) . '</th></tr>';

// 1. Tổng quan
echo '<tr><th colspan="4" style="text-align:left;background:#e6e6e6;">TỔNG QUAN</th></tr>';
echo '<tr><th>Chỉ số</th><th>Giá trị</th><th>So với kỳ trước</th></tr>';

$query = "SELECT 
    SUM(tong_tien) as total_revenue,
    COUNT(*) as total_orders,
    SUM(tong_tien) / COUNT(*) as avg_order_value
    FROM orders
    WHERE thoi_gian_dat BETWEEN '$startDate' AND '$endDate 23:59:59'";
$result = $conn->query($query);
$kpi = $result->fetch_assoc();

// Lấy dữ liệu tháng trước để tính % thay đổi
$prevStart = date('Y-m-d', strtotime($startDate . ' -1 month'));
$prevEnd = date('Y-m-d', strtotime($endDate . ' -1 month'));

$query = "SELECT 
    SUM(tong_tien) as total_revenue,
    COUNT(*) as total_orders,
    SUM(tong_tien) / COUNT(*) as avg_order_value
    FROM orders
    WHERE thoi_gian_dat BETWEEN '$prevStart' AND '$prevEnd 23:59:59'";
$result = $conn->query($query);
$prevKpi = $result->fetch_assoc();

function calcChange($current, $previous) {
    if ($previous == 0) return $current > 0 ? '100% ↑' : '0%';
    $change = round((($current - $previous) / $previous) * 100, 1);
    return $change >= 0 ? $change . '% ↑' : abs($change) . '% ↓';
}

echo '<tr><td>Doanh thu</td><td>' . number_format($kpi['total_revenue'], 0, ',', '.') . ' ₫</td><td>' . calcChange($kpi['total_revenue'], $prevKpi['total_revenue']) . '</td></tr>';
echo '<tr><td>Tổng đơn hàng</td><td>' . number_format($kpi['total_orders'], 0, ',', '.') . '</td><td>' . calcChange($kpi['total_orders'], $prevKpi['total_orders']) . '</td></tr>';
echo '<tr><td>Giá trị đơn TB</td><td>' . number_format($kpi['avg_order_value'], 0, ',', '.') . ' ₫</td><td>' . calcChange($kpi['avg_order_value'], $prevKpi['avg_order_value']) . '</td></tr>';

// 2. Top sản phẩm bán chạy
echo '<tr><th colspan="4" style="text-align:left;background:#e6e6e6;">TOP SẢN PHẨM BÁN CHẠY</th></tr>';
echo '<tr><th>Sản phẩm</th><th>Số lượng</th><th>Doanh thu</th><th>Tỷ trọng</th></tr>';

$query = "SELECT 
    sp.ten_san_pham, 
    SUM(oi.so_luong) as total_sold, 
    SUM(oi.so_luong * oi.don_gia) as total_revenue
    FROM order_items oi
    JOIN san_pham sp ON oi.san_pham_id = sp.id
    JOIN orders o ON oi.order_id = o.id
    WHERE o.thoi_gian_dat BETWEEN '$startDate' AND '$endDate 23:59:59'
    GROUP BY sp.id
    ORDER BY total_sold DESC
    LIMIT 5";
$result = $conn->query($query);
$totalRevenue = $kpi['total_revenue'] > 0 ? $kpi['total_revenue'] : 1;

while($row = $result->fetch_assoc()) {
    $percentage = round(($row['total_revenue'] / $totalRevenue) * 100, 1);
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['ten_san_pham']) . '</td>';
    echo '<td>' . number_format($row['total_sold'], 0, ',', '.') . '</td>';
    echo '<td>' . number_format($row['total_revenue'], 0, ',', '.') . ' ₫</td>';
    echo '<td>' . $percentage . '%</td>';
    echo '</tr>';
}

// 3. Thống kê trạng thái đơn hàng
echo '<tr><th colspan="4" style="text-align:left;background:#e6e6e6;">TRẠNG THÁI ĐƠN HÀNG</th></tr>';
echo '<tr><th>Trạng thái</th><th>Số lượng</th><th>Tỷ lệ</th></tr>';

$query = "SELECT 
    trang_thai, 
    COUNT(*) as count,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM orders WHERE thoi_gian_dat BETWEEN '$startDate' AND '$endDate 23:59:59'), 1) as percentage
    FROM orders
    WHERE thoi_gian_dat BETWEEN '$startDate' AND '$endDate 23:59:59'
    GROUP BY trang_thai";
$result = $conn->query($query);

while($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['trang_thai'] . '</td>';
    echo '<td>' . number_format($row['count'], 0, ',', '.') . '</td>';
    echo '<td>' . $row['percentage'] . '%</td>';
    echo '</tr>';
}

echo '</table>';
echo '</body></html>';
?>