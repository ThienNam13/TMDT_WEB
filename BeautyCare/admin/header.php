<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<!-- Header -->
<div class="container">
    <div class="header-top">
        <div class="logo">
            <div style="background-color: var(--primary); width: 50px; height: 50px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                <i class="fas fa-leaf" style="color: white; font-size: 24px;"></i>
            </div>
            <h1>Beauty<span>Care</span></h1>
        </div>
        <div class="user-info">
            <!-- Avatar mặc định -->
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['admin_name']) ?>&background=6c63ff&color=fff" alt="Admin">
            <div class="user-details">
                <h3><?= htmlspecialchars($_SESSION['admin_name']); ?></h3>
                <p>Quản trị viên</p>
                <a href="logout.php" style="font-size: 14px; color: red; text-decoration: none;">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Navigation -->
<nav>
    <div class="container">
        <ul class="nav-menu">
            <li><a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>"><i class="fas fa-home"></i> Trang chủ</a></li>
            <li><a href="manage-products.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-products.php' ? 'active' : '' ?>"><i class="fas fa-box"></i> Sản phẩm</a></li>
            <li><a href="manage-inventory.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-inventory.php' ? 'active' : '' ?>"><i class="fas fa-warehouse"></i> Kho hàng</a></li>
            <li><a href="manage-orders.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-orders.php' ? 'active' : '' ?>"><i class="fas fa-file-invoice-dollar"></i> Đơn hàng</a></li>
            <li><a href="manage-customers.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-customers.php' ? 'active' : '' ?>"><i class="fas fa-users"></i> Khách hàng</a></li>
            <li><a href="manage-reports.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-reports.php' ? 'active' : '' ?>"><i class="fas fa-chart-line"></i> Báo cáo</a></li>
            <li><a href="logs.php" class="<?= basename($_SERVER['PHP_SELF']) == 'logs.php' ? 'active' : '' ?>"><i class="fas fa-cog"></i> Lịch sử hệ thống</a></li>
        </ul>
    </div>
</nav>
