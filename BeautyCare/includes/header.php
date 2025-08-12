<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeautyCare - Mỹ phẩm chính hãng</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header class="site-header">
        <div class="header-container">
            <div class="logo">
                <a href="index.php">
                    <img src="assets/img/logo.png" alt="BeautyCare Logo">
                </a>
            </div>
            <form class="search-bar" action="products.php" method="get">
                <input type="text" name="search" placeholder="Tìm kiếm sản phẩm...">
                <button type="submit">🔍</button>
            </form>
            <div class="header-icons">
                <a href="cart.php" class="icon-btn">🛒</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-dropdown">
                        <button class="icon-btn">👤</button>
                        <div class="user-dropdown-content">
                            <a href="account.php">Thông tin tài khoản</a>
                            <a href="order-history.php">Lịch sử đơn hàng</a>
                            <a href="#" id="logoutBtn">Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="icon-btn">🔑</a>
                 <?php endif; ?>
            </div>
        </div>
    </header>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const logoutBtn = document.getElementById("logoutBtn");
    if (logoutBtn) {
        logoutBtn.addEventListener("click", function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Bạn có chắc muốn đăng xuất?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b5838d',
                cancelButtonColor: '#ccc',
                confirmButtonText: 'Đăng xuất',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('php/logout.php', { method: 'POST' })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: data.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                // Đổi icon 👤 thành 🔑 ngay tại chỗ
                                const userDropdown = document.querySelector('.user-dropdown');
                                if (userDropdown) {
                                    userDropdown.outerHTML = `<a href="login.php" class="icon-btn">🔑</a>`;
                                }
                            }
                        });
                }
            });
        });
    }
});
</script>
