<?php
// để navbar hoạt động độc lập hoặc bạn có thể chắc chắn nó
// đã được định nghĩa trong header.php
$base_url = '../BeautyCare/';
?>
<nav class="main-navbar">
    <ul>
        <li><a href="<?php echo $base_url; ?>index.php">Trang chủ</a></li>
        <li class="dropdown">
            <a href="<?php echo $base_url; ?>products.php">Sản phẩm</a>
            <ul class="dropdown-content">
                <li><a href="<?php echo $base_url; ?>products.php?category=Dưỡng da">Dưỡng da</a></li>
                <li><a href="<?php echo $base_url; ?>products.php?category=Trang điểm">Trang điểm</a></li>
                <li><a href="<?php echo $base_url; ?>products.php?category=Chăm sóc tóc">Chăm sóc tóc</a></li>
                <li><a href="<?php echo $base_url; ?>products.php?category=Mặt nạ">Mặt nạ</a></li>
            </ul>
        </li>
        <li><a href="<?php echo $base_url; ?>about.php">Giới thiệu</a></li>
        <li><a href="<?php echo $base_url; ?>contact.php">Liên hệ</a></li>
        <li><a href="<?php echo $base_url; ?>order-history.php">Lịch sử đơn hàng</a></li>

    </ul>
</nav>