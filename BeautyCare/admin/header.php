
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
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
            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User">
            <div class="user-details">
                <h3>Nguyễn Thị Hoa</h3>
                <p>Quản lý hệ thống</p>
            </div>
        </div>
    </div>
</div>

<!-- Navigation -->
<nav>
    <div class="container">
        <ul class="nav-menu">
            <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Trang chủ</a></li>
            <li><a href="products.php"><i class="fas fa-box"></i> Sản phẩm</a></li>
            <li><a href="manage-inventory.php"><i class="fas fa-warehouse"></i> Kho hàng</a></li>
            <li><a href="manage-orders.php"><i class="fas fa-file-invoice-dollar"></i> Đơn hàng</a></li>
            <li><a href="customers.php"><i class="fas fa-users"></i> Khách hàng</a></li>
            <li><a href="reports.php"><i class="fas fa-chart-line"></i> Báo cáo</a></li>
            <li><a href="logs.php"><i class="fas fa-cog"></i> Lịch sử hệ thống</a></li>
        </ul>
    </div>
</nav>
