<?php
include '../php/database.php';
include 'header.php';

function calcGrowth($current, $previous) {
    if ($previous == 0) return $current > 0 ? 100 : 0;
    return round((($current - $previous) / $previous) * 100, 1);
}

// Lấy thời gian hiện tại và tháng trước
$currentMonth = date('Y-m');
$prevMonth = date('Y-m', strtotime('-1 month'));

// Xử lý bộ lọc thời gian nếu có
$filterYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$filterMonth = isset($_GET['month']) ? intval($_GET['month']) : null;

/* ========== 1. DOANH THU THEO THÁNG ========== */
$monthlyRevenue = [];
$whereClause = "";
if ($filterMonth) {
    $whereClause = "WHERE DATE_FORMAT(thoi_gian_dat, '%Y-%m') = '{$filterYear}-".str_pad($filterMonth, 2, '0', STR_PAD_LEFT)."'";
}

$res = $conn->query("SELECT 
    DATE_FORMAT(thoi_gian_dat, '%Y-%m') as month, 
    SUM(tong_tien) as revenue 
    FROM orders 
    {$whereClause}
    GROUP BY DATE_FORMAT(thoi_gian_dat, '%Y-%m') 
    ORDER BY month DESC 
    LIMIT 12");
while($row = $res->fetch_assoc()) {
    $monthlyRevenue[] = $row;
}

// Tính toán tăng trưởng doanh thu
$revenueGrowth = 0;
if (count($monthlyRevenue) >= 2) {
    $currentRevenue = $monthlyRevenue[0]['revenue'] ?? 0;
    $previousRevenue = $monthlyRevenue[1]['revenue'] ?? 0;
    $revenueGrowth = calcGrowth($currentRevenue, $previousRevenue);
}

/* ========== 2. TOP SẢN PHẨM BÁN CHẠY ========== */
$topProducts = [];
$res = $conn->query("SELECT 
    sp.id, sp.ten_san_pham, sp.hinh_anh,
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

/* ========== 5. DOANH THU THEO DANH MỤC ========== */
$categoryRevenue = [];
$res = $conn->query("SELECT 
    dm.ten_danh_muc,
    SUM(oi.so_luong * oi.don_gia) as revenue
    FROM order_items oi
    JOIN san_pham sp ON oi.san_pham_id = sp.id
    JOIN danh_muc dm ON sp.ma_danh_muc = dm.ma_danh_muc
    GROUP BY dm.ma_danh_muc
    ORDER BY revenue DESC");
while($row = $res->fetch_assoc()) {
    $categoryRevenue[] = $row;
}

/* ========== 6. TOP KHÁCH HÀNG THÂN THIẾT ========== */
$topCustomers = [];
$res = $conn->query("SELECT 
    u.id, u.fullname as ho_ten, u.email,
    COUNT(o.id) as order_count,
    SUM(o.tong_tien) as total_spent
    FROM orders o
    JOIN users u ON o.user_id = u.id
    GROUP BY u.id
    ORDER BY total_spent DESC
    LIMIT 5");
while($row = $res->fetch_assoc()) {
    $topCustomers[] = $row;
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
<main class="container">
    <section class="reports">
        <div class="dashboard-header">
            <h2>Báo cáo thống kê - Web bán mỹ phẩm</h2>
            <div class="report-filters">
                <form method="GET" class="filter-form">
                    <select name="year" onchange="this.form.submit()">
                        <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                        <option value="<?= $y ?>" <?= $y == $filterYear ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="month" onchange="this.form.submit()">
                        <option value="">Tất cả tháng</option>
                        <?php for($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == $filterMonth ? 'selected' : '' ?>><?= $m ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="button" class="export-btn" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Xuất Excel
                    </button>
                </form>
            </div>
        </div>

       <!-- Summary Cards -->
<div class="summary-cards">
    <div class="summary-card">
        <div class="summary-icon revenue">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="summary-content">
            <h4>Doanh thu tháng</h4>
            <p><?= isset($monthlyRevenue[0]) ? number_format($monthlyRevenue[0]['revenue'], 0, ',', '.').' ₫' : '0 ₫' ?></p>
            <span class="growth <?= $revenueGrowth >= 0 ? 'positive' : 'negative' ?>">
                <?= $revenueGrowth >= 0 ? '+' : '' ?><?= $revenueGrowth ?>%
                <i class="fas fa-arrow-<?= $revenueGrowth >= 0 ? 'up' : 'down' ?>"></i>
            </span>
        </div>
    </div>
    
    <div class="summary-card">
        <div class="summary-icon orders">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="summary-content">
            <h4>Đơn hàng</h4>
            <p><?= array_sum(array_column($orderStatusStats, 'count')) ?></p>
            <span class="growth">
                <i class="fas fa-info-circle" title="Tổng số đơn hàng"></i>
            </span>
        </div>
    </div>
    
    <div class="summary-card">
        <div class="summary-icon customers">
            <i class="fas fa-user-plus"></i>
        </div>
        <div class="summary-content">
            <h4>Khách hàng mới</h4>
            <p><?= isset($customerGrowth[0]) ? $customerGrowth[0]['new_customers'] : 0 ?></p>
            <?php if (count($customerGrowth) >= 2): ?>
            <span class="growth <?= ($customerGrowth[0]['new_customers'] >= $customerGrowth[1]['new_customers']) ? 'positive' : 'negative' ?>">
                <?= $customerGrowth[0]['new_customers'] >= $customerGrowth[1]['new_customers'] ? '+' : '' ?>
                <?= abs($customerGrowth[0]['new_customers'] - $customerGrowth[1]['new_customers']) ?>
                <i class="fas fa-arrow-<?= ($customerGrowth[0]['new_customers'] >= $customerGrowth[1]['new_customers']) ? 'up' : 'down' ?>"></i>
            </span>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="summary-card">
        <div class="summary-icon products">
            <i class="fas fa-star"></i>
        </div>
        <div class="summary-content">
            <h4>Sản phẩm bán chạy</h4>
            <p><?= isset($topProducts[0]) ? htmlspecialchars($topProducts[0]['ten_san_pham']) : 'N/A' ?></p>
            <span class="growth">
                <?= isset($topProducts[0]) ? number_format($topProducts[0]['total_sold'], 0, ',', '.').' đã bán' : '' ?>
            </span>
        </div>
    </div>
</div>

        <!-- Biểu đồ doanh thu theo tháng -->
        <div class="report-card">
            <div class="report-header">
                <h3>Doanh thu theo tháng</h3>
                <div class="chart-actions">
                    <button class="chart-action-btn" onclick="toggleChartType('revenueChart')">
                        <i class="fas fa-exchange-alt"></i> Đổi loại biểu đồ
                    </button>
                </div>
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
                            <th>Hình ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Số lượng bán</th>
                            <th>Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($topProducts as $product): ?>
                        <tr>
                            <td><img src="../<?= htmlspecialchars($product['hinh_anh']) ?>" alt="<?= htmlspecialchars($product['ten_san_pham']) ?>" class="product-thumbnail"></td>
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
            <div class="status-details">
                <?php foreach($orderStatusStats as $status): ?>
                <div class="status-item">
                    <span class="status-badge" style="background-color: <?= getStatusColor($status['trang_thai']) ?>"></span>
                    <span><?= $status['trang_thai'] ?></span>
                    <span class="status-count"><?= $status['count'] ?> (<?= $status['percentage'] ?>%)</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Doanh thu theo danh mục -->
        <div class="report-card">
            <div class="report-header">
                <h3>Doanh thu theo danh mục</h3>
            </div>
            <div class="chart-container">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Top khách hàng thân thiết -->
        <div class="report-card">
            <div class="report-header">
                <h3>Top khách hàng thân thiết</h3>
            </div>
            <div class="table-responsive">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Khách hàng</th>
                            <th>Email</th>
                            <th>Số đơn hàng</th>
                            <th>Tổng chi tiêu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($topCustomers as $customer): ?>
                        <tr>
                            <td><?= htmlspecialchars($customer['ho_ten']) ?></td>
                            <td><?= htmlspecialchars($customer['email']) ?></td>
                            <td><?= $customer['order_count'] ?></td>
                            <td><?= number_format($customer['total_spent'], 0, ',', '.') ?> ₫</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
const charts = {};

function toggleChartType(chartId) {
    const chart = charts[chartId];
    const newType = chart.config.type === 'bar' ? 'line' : 'bar';
    chart.config.type = newType;
    chart.update();
}

function exportToExcel() {
    // Tạo workbook
    const wb = XLSX.utils.book_new();
    
    // Thêm sheet doanh thu theo tháng
    const revenueData = [
        ['Tháng', 'Doanh thu (VND)'],
        ...<?= json_encode(array_map(function($item) {
            return [date('m/Y', strtotime($item['month'])), $item['revenue']];
        }, $monthlyRevenue)) ?>
    ];
    const revenueWS = XLSX.utils.aoa_to_sheet(revenueData);
    XLSX.utils.book_append_sheet(wb, revenueWS, "Doanh thu");
    
    // Thêm sheet sản phẩm bán chạy
    const productsData = [
        ['Sản phẩm', 'Số lượng bán', 'Doanh thu (VND)'],
        ...<?= json_encode(array_map(function($item) {
            return [$item['ten_san_pham'], $item['total_sold'], $item['total_revenue']];
        }, $topProducts)) ?>
    ];
    const productsWS = XLSX.utils.aoa_to_sheet(productsData);
    XLSX.utils.book_append_sheet(wb, productsWS, "Sản phẩm bán chạy");
    
    // Xuất file
    XLSX.writeFile(wb, `BaoCao_${new Date().toISOString().slice(0,10)}.xlsx`);
}

// Biểu đồ doanh thu
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
charts.revenueChart = new Chart(revenueCtx, {
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
charts.statusChart = new Chart(statusCtx, {
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
        responsive: true,
        plugins: {
            legend: {
                position: 'right',
            }
        }
    }
});

// Biểu đồ doanh thu theo danh mục
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
charts.categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: [<?php foreach($categoryRevenue as $cat): ?>"<?= $cat['ten_danh_muc'] ?>", <?php endforeach; ?>],
        datasets: [{
            data: [<?php foreach($categoryRevenue as $cat): ?><?= $cat['revenue'] ?>, <?php endforeach; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'right',
            }
        }
    }
});

// Biểu đồ khách hàng
const customerCtx = document.getElementById('customerChart').getContext('2d');
charts.customerChart = new Chart(customerCtx, {
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

flatpickr(".date-picker", {
    mode: "range",
    dateFormat: "Y-m-d",
    maxDate: "today"
});
// Cập nhật options chung cho biểu đồ
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
            labels: {
                boxWidth: 12,
                padding: 20,
                font: {
                    family: 'Roboto, sans-serif'
                }
            }
        },
        tooltip: {
            backgroundColor: 'rgba(0,0,0,0.8)',
            titleFont: {
                size: 14,
                weight: 'bold'
            },
            bodyFont: {
                size: 12
            },
            padding: 12,
            usePointStyle: true,
            callbacks: {
                label: function(context) {
                    let label = context.dataset.label || '';
                    if (label) label += ': ';
                    if (context.parsed.y !== null) {
                        label += context.parsed.y.toLocaleString('vi-VN') + ' ₫';
                    }
                    return label;
                }
            }
        }
    },
    scales: {
        x: {
            grid: {
                display: false
            },
            ticks: {
                font: {
                    family: 'Roboto, sans-serif'
                }
            }
        },
        y: {
            beginAtZero: true,
            grid: {
                color: 'rgba(0,0,0,0.05)'
            },
            ticks: {
                font: {
                    family: 'Roboto, sans-serif'
                },
                callback: function(value) {
                    return value.toLocaleString('vi-VN');
                }
            }
        }
    }
};

charts.revenueChart.options = {...charts.revenueChart.options, ...chartOptions};
charts.statusChart.options = {...charts.statusChart.options, ...chartOptions};
charts.categoryChart.options = {...charts.categoryChart.options, ...chartOptions};
charts.customerChart.options = {...charts.customerChart.options, ...chartOptions};
</script>
</body>
</html>

<?php 
function getStatusColor($status) {
    $colors = [
        'Đã đặt hàng' => '#FF6384',
        'Đang xử lý' => '#36A2EB',
        'Đang giao hàng' => '#FFCE56',
        'Đã giao hàng' => '#4BC0C0',
        'Đã hủy' => '#9966FF'
    ];
    return $colors[$status] ?? '#CCCCCC';
}

include 'footer.php'; 
?>