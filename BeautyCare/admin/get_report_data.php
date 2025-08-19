<?php
include '../php/database.php';

header('Content-Type: application/json');

// Xử lý bộ lọc thời gian
$timeRange = $_POST['timeRange'] ?? 'this_month';
$startDate = $endDate = null;

switch ($timeRange) {
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
        $startDate = $_POST['startDate'] ?? date('Y-m-01');
        $endDate = $_POST['endDate'] ?? date('Y-m-t');
        break;
}

// Lấy dữ liệu báo cáo
$data = [];

// 1. Doanh thu theo tháng
$query = "SELECT 
    DATE_FORMAT(thoi_gian_dat, '%Y-%m') as month, 
    SUM(tong_tien) as revenue 
    FROM orders 
    WHERE thoi_gian_dat BETWEEN '$startDate' AND '$endDate 23:59:59'
    GROUP BY DATE_FORMAT(thoi_gian_dat, '%Y-%m') 
    ORDER BY month DESC";
$result = $conn->query($query);
$data['monthlyRevenue'] = [];
while($row = $result->fetch_assoc()) {
    $data['monthlyRevenue'][] = $row;
}

// 2. Top sản phẩm bán chạy
$query = "SELECT 
    sp.id, sp.ten_san_pham, 
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
$data['topProducts'] = [];
while($row = $result->fetch_assoc()) {
    $data['topProducts'][] = $row;
}

// 3. Thống kê trạng thái đơn hàng
$query = "SELECT 
    trang_thai, 
    COUNT(*) as count,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM orders WHERE thoi_gian_dat BETWEEN '$startDate' AND '$endDate 23:59:59'), 1) as percentage
    FROM orders
    WHERE thoi_gian_dat BETWEEN '$startDate' AND '$endDate 23:59:59'
    GROUP BY trang_thai";
$result = $conn->query($query);
$data['orderStatusStats'] = [];
while($row = $result->fetch_assoc()) {
    $data['orderStatusStats'][] = $row;
}

// 4. Đơn hàng gần đây
$query = "SELECT 
    o.id, 
    CONCAT(u.first_name, ' ', u.last_name) as customer_name,
    o.thoi_gian_dat as order_date,
    o.tong_tien as total_amount,
    o.trang_thai as status
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.thoi_gian_dat BETWEEN '$startDate' AND '$endDate 23:59:59'
    ORDER BY o.thoi_gian_dat DESC
    LIMIT 5";
$result = $conn->query($query);
$data['recentOrders'] = [];
while($row = $result->fetch_assoc()) {
    $data['recentOrders'][] = $row;
}

// 5. Tính toán các KPI
$query = "SELECT 
    SUM(tong_tien) as total_revenue,
    COUNT(*) as total_orders,
    SUM(tong_tien) / COUNT(*) as avg_order_value
    FROM orders
    WHERE thoi_gian_dat BETWEEN '$startDate' AND '$endDate 23:59:59'";
$result = $conn->query($query);
$data['kpi'] = $result->fetch_assoc();

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

// Tính % thay đổi
function calcChange($current, $previous) {
    if ($previous == 0) return $current > 0 ? 100 : 0;
    return round((($current - $previous) / $previous) * 100, 1);
}

$data['changes'] = [
    'revenue' => calcChange($data['kpi']['total_revenue'] ?? 0, $prevKpi['total_revenue'] ?? 0),
    'orders' => calcChange($data['kpi']['total_orders'] ?? 0, $prevKpi['total_orders'] ?? 0),
    'aov' => calcChange($data['kpi']['avg_order_value'] ?? 0, $prevKpi['avg_order_value'] ?? 0)
];

// Lấy số khách hàng mới
$query = "SELECT COUNT(*) as new_customers FROM users 
    WHERE role = 'user' 
    AND created_at BETWEEN '$startDate' AND '$endDate 23:59:59'";
$result = $conn->query($query);
$data['kpi']['new_customers'] = $result->fetch_assoc()['new_customers'] ?? 0;

// Số khách hàng tháng trước
$query = "SELECT COUNT(*) as new_customers FROM users 
    WHERE role = 'user' 
    AND created_at BETWEEN '$prevStart' AND '$prevEnd 23:59:59'";
$result = $conn->query($query);
$prevCustomers = $result->fetch_assoc()['new_customers'] ?? 0;

$data['changes']['customers'] = calcChange($data['kpi']['new_customers'], $prevCustomers);

echo json_encode($data);
?>