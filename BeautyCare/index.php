<?php
include 'php/database.php'; // Kết nối CSDL
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- Slider Banner -->
<div class="banner-slider swiper">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <img src="assets/img/banner1.png" alt="Khuyến mãi mùa hè">
            <div class="banner-text">
                <h2>Làn da rạng rỡ</h2>
                <p>Giảm giá đến 30% cho dòng dưỡng da</p>
                <a href="products.php?category=Dưỡng da" class="btn-primary">Mua ngay</a>
            </div>
        </div>
        <div class="swiper-slide">
            <img src="assets/img/banner2.jpg" alt="Trang điểm tự nhiên">
            <div class="banner-text">
                <h2>Trang điểm tự tin</h2>
                <p>BST mới 2025 – Giảm 20%</p>
                <a href="products.php?category=Trang điểm" class="btn-primary">Khám phá</a>
            </div>
        </div>
        <div class="swiper-slide">
            <img src="assets/img/banner3.jpg" alt="Mỹ phẩm cao cấp">
            <div class="banner-text">
                <h2>Mỹ phẩm cao cấp</h2>
                <p>Miễn phí ship toàn quốc</p>
                <a href="products.php" class="btn-primary">Xem ngay</a>
            </div>
        </div>
    </div>
    <!-- Navigation -->
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>

<!-- Danh mục nổi bật -->
<section class="categories">
    <h2 class="section-title">Danh mục nổi bật</h2>
    <div class="category-grid">
        <a href="products.php?category=Dưỡng da" class="category-card">Dưỡng da</a>
        <a href="products.php?category=Trang điểm" class="category-card">Trang điểm</a>
        <a href="products.php?category=Chăm sóc tóc" class="category-card">Chăm sóc tóc</a>
        <a href="products.php?category=Mặt nạ" class="category-card">Mặt nạ</a>
    </div>
</section>

<!-- Sản phẩm nổi bật -->
<section class="featured-products">
    <h2 class="section-title">Sản phẩm nổi bật</h2>
    <div class="product-grid">
        <?php
        $sql = "SELECT * FROM san_pham LIMIT 6";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="product-card">';
                echo '<img src="assets/img/products/'.$row['hinh_anh'].'" alt="'.$row['ten_san_pham'].'">';
                echo '<h3>'.$row['ten_san_pham'].'</h3>';
                echo '<p class="price">'.number_format($row['gia'], 0, ',', '.').' VND</p>';
                echo '<a href="product-detail.php?id='.$row['id'].'" class="btn-primary">Xem chi tiết</a>';
                echo '</div>';
            }
        } else {
            echo "<p>Chưa có sản phẩm nào</p>";
        }
        ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<!-- SwiperJS -->
<link rel="stylesheet" href=" assets/css/style.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script>
var swiper = new Swiper('.swiper', {
    loop: true,
    pagination: { el: '.swiper-pagination', clickable: true },
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    autoplay: { delay: 3000 }
});
</script>