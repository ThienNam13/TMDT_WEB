<?php
// Kết nối CSDL
include '../php/database.php';
include 'header.php';

// Hàm tính % tăng trưởng
function calcGrowth($current, $previous) {
    if ($previous == 0) return $current > 0 ? 100 : 0;
    return round((($current - $previous) / $previous) * 100, 1);
}

$currentMonth = date('Y-m');
$prevMonth = date('Y-m', strtotime('-1 month'));

/* ========== 1. CỬA HÀNG ========== */
if ($conn->query("SHOW TABLES LIKE 'stores'")->num_rows > 0) {
    $res = $conn->query("SELECT COUNT(*) as total FROM stores WHERE DATE_FORMAT(created_at, '%Y-%m') = '$currentMonth'");
    $storeCurrent = $res->fetch_assoc()['total'] ?? 0;

    $res = $conn->query("SELECT COUNT(*) as total FROM stores WHERE DATE_FORMAT(created_at, '%Y-%m') = '$prevMonth'");
    $storePrev = $res->fetch_assoc()['total'] ?? 0;

    $storeGrowth = calcGrowth($storeCurrent, $storePrev);

    $res = $conn->query("SELECT COUNT(*) as total FROM stores");
    $totalStores = $res->fetch_assoc()['total'] ?? 0;
} else {
    $storeCurrent = $storePrev = $storeGrowth = $totalStores = 0;
}

/* ========== 2. SẢN PHẨM ========== */
$checkCol = $conn->query("SHOW COLUMNS FROM san_pham LIKE 'created_at'");
$dateCol = ($checkCol->num_rows > 0) ? 'created_at' : null;

if ($dateCol) {
    $res = $conn->query("SELECT COUNT(*) as total FROM san_pham WHERE DATE_FORMAT($dateCol, '%Y-%m') = '$currentMonth'");
    $productCurrent = $res->fetch_assoc()['total'] ?? 0;

    $res = $conn->query("SELECT COUNT(*) as total FROM san_pham WHERE DATE_FORMAT($dateCol, '%Y-%m') = '$prevMonth'");
    $productPrev = $res->fetch_assoc()['total'] ?? 0;
} else {
    $productCurrent = $productPrev = 0;
}

$productGrowth = calcGrowth($productCurrent, $productPrev);

$res = $conn->query("SELECT COUNT(*) as total FROM san_pham");
$totalProducts = $res->fetch_assoc()['total'] ?? 0;

/* ========== 3. DOANH THU ========== */
$res = $conn->query("SELECT SUM(tong_tien) as revenue FROM orders WHERE DATE_FORMAT(thoi_gian_dat, '%Y-%m') = '$currentMonth'");
$revenueCurrent = $res->fetch_assoc()['revenue'] ?? 0;

$res = $conn->query("SELECT SUM(tong_tien) as revenue FROM orders WHERE DATE_FORMAT(thoi_gian_dat, '%Y-%m') = '$prevMonth'");
$revenuePrev = $res->fetch_assoc()['revenue'] ?? 0;

$revenueGrowth = calcGrowth($revenueCurrent, $revenuePrev);

/* ========== 4. KHÁCH HÀNG MỚI ========== */
$checkColUser = $conn->query("SHOW COLUMNS FROM users LIKE 'created_at'");
$dateColUser = ($checkColUser->num_rows > 0) ? 'created_at' : null;

if ($dateColUser) {
    $res = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='user' AND DATE_FORMAT($dateColUser, '%Y-%m') = '$currentMonth'");
    $customerCurrent = $res->fetch_assoc()['total'] ?? 0;

    $res = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='user' AND DATE_FORMAT($dateColUser, '%Y-%m') = '$prevMonth'");
    $customerPrev = $res->fetch_assoc()['total'] ?? 0;
} else {
    $customerCurrent = $customerPrev = 0;
}

$customerGrowth = calcGrowth($customerCurrent, $customerPrev);

/* ========== 5. ĐƠN HÀNG GẦN NHẤT ========== */
$recentOrdersRes = $conn->query("SELECT id, tong_tien, thoi_gian_dat, trang_thai FROM orders ORDER BY thoi_gian_dat DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeautyCare Admin - Quản trị Web Mỹ Phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        .stats-grid a.stat-card {
            display: flex;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        .stats-grid a.stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
<main class="container">
    <section class="dashboard">
        <div class="dashboard-header">
            <h2>Bảng điều khiển - Web bán mỹ phẩm</h2>
        </div>

        <div class="stats-grid">
            <a href="manage-inventory.php" class="stat-card">
                <div class="stat-icon bg-blue"><i class="fas fa-store"></i></div>
                <div class="stat-info">
                    <h3>Tổng cửa hàng</h3>
                    <p><?= $totalStores ?></p>
                    <span><?= $storeGrowth ?>% so với tháng trước</span>
                </div>
            </a>

            <a href="products.php" class="stat-card">
                <div class="stat-icon bg-purple"><i class="fas fa-box-open"></i></div>
                <div class="stat-info">
                    <h3>Tổng sản phẩm</h3>
                    <p><?= $totalProducts ?></p>
                    <span><?= $productGrowth ?>% sản phẩm mới</span>
                </div>
            </a>

            <div class="stat-card">
                <div class="stat-icon bg-orange"><i class="fas fa-file-invoice-dollar"></i></div>
                <div class="stat-info">
                    <h3>Doanh thu tháng</h3>
                    <p><?= number_format($revenueCurrent, 0, ',', '.') ?> ₫</p>
                    <span><?= $revenueGrowth ?>% tăng trưởng</span>
                </div>
            </div>

            <a href="customers.php" class="stat-card">
                <div class="stat-icon bg-red"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <h3>Khách hàng mới</h3>
                    <p><?= $customerCurrent ?></p>
                    <span><?= $customerGrowth ?>% so với tháng trước</span>
                </div>
            </a>
        </div>

        <div class="recent-container">
            <div class="section-header">
                <h3>Hoạt động gần đây</h3>
            </div>

            <ul class="activity-list">
                <?php if($recentOrdersRes && $recentOrdersRes->num_rows > 0): ?>
                    <?php while($row = $recentOrdersRes->fetch_assoc()): ?>
                        <li class="activity-item">
                            <div class="activity-icon" style="background-color: var(--secondary);">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <div class="activity-details">
                                <p>Đơn hàng #<?= htmlspecialchars($row['id']) ?> — <?= number_format($row['tong_tien'],0,',','.') ?> ₫</p>
                                <div class="activity-time"><?= date('d/m/Y H:i', strtotime($row['thoi_gian_dat'])) ?> — <?= htmlspecialchars($row['trang_thai']) ?></div>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li class="activity-item">
                        <div class="activity-icon" style="background-color: var(--secondary);"><i class="fas fa-info"></i></div>
                        <div class="activity-details">
                            <p>Chưa có hoạt động gần đây</p>
                            <div class="activity-time">—</div>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </section>
</main>
</body>
</html>

<?php include 'footer.php'; ?>
