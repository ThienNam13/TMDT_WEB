<?php
// Đường dẫn gốc của trang web, cần được định nghĩa ở đây
// để navbar hoạt động độc lập hoặc bạn có thể chắc chắn nó
// đã được định nghĩa trong header.php
$base_url = '/TMDT_WEB-main/BeautyCare/';
?>
<nav class="main-navbar">
    <ul>
        <li><a href="<?php echo $base_url; ?>index.php">Trang chủ</a></li>
        <li><a href="<?php echo $base_url; ?>about.php">Giới thiệu</a></li>
        <li class="dropdown">
            <a href="<?php echo $base_url; ?>products.php">Sản phẩm</a>
            <ul class="dropdown-content">
                <li><a href="<?php echo $base_url; ?>products.php?category=Dưỡng da">Dưỡng da</a></li>
                <li><a href="<?php echo $base_url; ?>products.php?category=Trang điểm">Trang điểm</a></li>
                <li><a href="<?php echo $base_url; ?>products.php?category=Chăm sóc tóc">Chăm sóc tóc</a></li>
                <li><a href="<?php echo $base_url; ?>products.php?category=Mặt nạ">Mặt nạ</a></li>
            </ul>
        </li>
        <li><a href="<?php echo $base_url; ?>contact.php">Liên hệ</a></li>
    </ul>
</nav>

<style>
/* CSS cho dropdown menu */
.main-navbar ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
}

.main-navbar li {
    margin: 0 15px;
    position: relative;
}

.main-navbar a {
    color: #fff;
    text-decoration: none;
    padding: 12px 0;
    display: block;
}

.main-navbar a:hover {
    text-decoration: underline;
}

.main-navbar .dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 250px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    list-style: none;
    padding: 0;
    margin: 0;
}

.main-navbar .dropdown-content li {
    margin: 0;
}

.main-navbar .dropdown-content a {
    color: #333;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    text-align: left;
}

.main-navbar .dropdown-content a:hover {
    background-color: #f1f1f1;
}

.main-navbar .dropdown:hover .dropdown-content {
    display: block;
}
</style>
