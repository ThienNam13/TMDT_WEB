<?php
// Kết nối CSDL
include '../php/database.php';
include 'header.php';

// Hàm tính % tăng trưởng
function calcGrowth($current, $previous) {
    if ($previous == 0) return $current > 0 ? 100 : 0;
    return round((($current - $previous) / $previous) * 100, 1);
}

// Lấy thời gian hiện tại và tháng trước
$currentMonth = date('Y-m');
$prevMonth = date('Y-m', strtotime('-1 month'));

/* ========== 1. DOANH THU THEO THÁNG ========== */
$monthlyRevenue = [];
$res = $conn->query("SELECT 
    DATE_FORMAT(thoi_gian_dat, '%Y-%m') as month, 
    SUM(tong_tien) as revenue 
    FROM orders 
    GROUP BY DATE_FORMAT(thoi_gian_dat, '%Y-%m') 
    ORDER BY month DESC 
    LIMIT 12");
while($row = $res->fetch_assoc()) {
    $monthlyRevenue[] = $row;
}

/* ========== 2. TOP SẢN PHẨM BÁN CHẠY ========== */
$topProducts = [];
$res = $conn->query("SELECT 
    sp.id, sp.ten_san_pham, 
    SUM(oi.so_luong) as total_sold, 
    SUM(oi.so_luong * oi.don_gia) as total_revenue
    FROM order_items oi
    JOIN san_pham sp ON oi.san_pham_id = sp.id
    GROUP BY sp.id
    ORDER BY total_sold DESC
    LIMIT 5");
while($row = $res->fetch_assoc()) {
    $topProducts[] = $row;
}

/* ========== 3. THỐNG KÊ TRẠNG THÁI ĐƠN HÀNG ========== */
$orderStatusStats = [];
$res = $conn->query("SELECT 
    trang_thai, 
    COUNT(*) as count,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM orders), 1) as percentage
    FROM orders
    GROUP BY trang_thai");
while($row = $res->fetch_assoc()) {
    $orderStatusStats[] = $row;
}

/* ========== 4. KHÁCH HÀNG THEO THÁNG ========== */
$customerGrowth = [];
// Kiểm tra xem bảng users có cột ngày tạo không (vì trong schema không thấy created_at)
$checkCol = $conn->query("SHOW COLUMNS FROM users LIKE 'created_at'");
if ($checkCol->num_rows > 0) {
    $res = $conn->query("SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as month, 
        COUNT(*) as new_customers
        FROM users
        WHERE role = 'user'
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month DESC
        LIMIT 12");
} else {
    // Nếu không có cột created_at, sử dụng id làm xấp xỉ (không chính xác bằng)
    $res = $conn->query("SELECT 
        DATE_FORMAT(NOW() - INTERVAL (id-1) MONTH, '%Y-%m') as month, 
        COUNT(*) as new_customers
        FROM users
        WHERE role = 'user'
        GROUP BY month
        ORDER BY month DESC
        LIMIT 12");
}
while($row = $res->fetch_assoc()) {
    $customerGrowth[] = $row;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo thống kê - BeautyCare Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/reports.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
<main class="container">
    <section class="reports">
        <div class="dashboard-header">
            <h2>Báo cáo thống kê - Web bán mỹ phẩm</h2>
        </div>

        <!-- Biểu đồ doanh thu theo tháng -->
        <div class="report-card">
            <div class="report-header">
                <h3>Doanh thu theo tháng</h3>
            </div>
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Top sản phẩm bán chạy -->
        <div class="report-card">
            <div class="report-header">
                <h3>Top sản phẩm bán chạy</h3>
            </div>
            <div class="table-responsive">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Số lượng bán</th>
                            <th>Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($topProducts as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['ten_san_pham']) ?></td>
                            <td><?= $product['total_sold'] ?></td>
                            <td><?= number_format($product['total_revenue'], 0, ',', '.') ?> ₫</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Thống kê trạng thái đơn hàng -->
        <div class="report-card">
            <div class="report-header">
                <h3>Trạng thái đơn hàng</h3>
            </div>
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Tăng trưởng khách hàng -->
        <div class="report-card">
            <div class="report-header">
                <h3>Tăng trưởng khách hàng</h3>
            </div>
            <div class="chart-container">
                <canvas id="customerChart"></canvas>
            </div>
        </div>
    </section>
</main>

<!-- Thêm thư viện Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Biểu đồ doanh thu
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: [<?php foreach(array_reverse($monthlyRevenue) as $data): ?>"<?= date('m/Y', strtotime($data['month'])) ?>", <?php endforeach; ?>],
        datasets: [{
            label: 'Doanh thu (₫)',
            data: [<?php foreach(array_reverse($monthlyRevenue) as $data): ?><?= $data['revenue'] ?>, <?php endforeach; ?>],
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + ' ₫';
                    }
                }
            }
        }
    }
});

// Biểu đồ trạng thái đơn hàng
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'pie',
    data: {
        labels: [<?php foreach($orderStatusStats as $status): ?>"<?= $status['trang_thai'] ?>", <?php endforeach; ?>],
        datasets: [{
            data: [<?php foreach($orderStatusStats as $status): ?><?= $status['count'] ?>, <?php endforeach; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true
    }
});

// Biểu đồ khách hàng
const customerCtx = document.getElementById('customerChart').getContext('2d');
const customerChart = new Chart(customerCtx, {
    type: 'line',
    data: {
        labels: [<?php foreach(array_reverse($customerGrowth) as $data): ?>"<?= date('m/Y', strtotime($data['month'])) ?>", <?php endforeach; ?>],
        datasets: [{
            label: 'Khách hàng mới',
            data: [<?php foreach(array_reverse($customerGrowth) as $data): ?><?= $data['new_customers'] ?>, <?php endforeach; ?>],
            fill: false,
            backgroundColor: 'rgba(75, 192, 192, 0.5)',
            borderColor: 'rgba(75, 192, 192, 1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
</body>
</html>

<?php include 'footer.php'; ?>