<?php
include 'php/database.php';
include 'includes/header.php';
include 'includes/navbar.php';
?>
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
            <img src="assets/img/banner3.png" alt="Mỹ phẩm cao cấp">
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

<?php
// --- Lấy thông tin khuyến mãi từ CSDL ---
$currentDateTime = date('Y-m-d H:i:s');
$sqlPromo = "SELECT * FROM khuyen_mai 
             WHERE trang_thai = 1 
             AND ngay_bat_dau <= '$currentDateTime' 
             AND ngay_ket_thuc >= '$currentDateTime' 
             ORDER BY id DESC LIMIT 1";
$promoData = $conn->query($sqlPromo)->fetch_assoc();

// --- Kiểm tra khuyến mãi ---
$isPromoActive = false;
$promoMessage = "";
$promoProducts = [];
$nextPromoTime = null;

if ($promoData) {
    $dayOfWeek = date('N'); // 1=Thứ 2, 7=CN
    $hour = date('H');
    
    // Kiểm tra nếu là khuyến mãi giờ vàng cuối tuần
    if ($promoData['id'] == 1) {
        if (in_array($dayOfWeek, [6,7,8])) {
            $isPromoActive = ($hour >= 19 && $hour <= 23);
            $promoMessage = $isPromoActive 
                ? "🔥 Giờ vàng khuyến mãi! Giảm ngay {$promoData['muc_giam_gia']}%" 
                : "Khuyến mãi giờ vàng sắp diễn ra!";
            
            // Tính thời gian đến khuyến mãi tiếp theo
            if (!$isPromoActive) {
                if ($dayOfWeek < 5) {
                    $nextPromoDay = 5; // Thứ 6
                } elseif ($dayOfWeek == 5 && $hour < 19) {
                    $nextPromoDay = 5; // Cùng ngày nhưng chưa đến giờ
                } else {
                    $nextPromoDay = 5 + (7 - $dayOfWeek); // Thứ 6 tuần sau
                }
                $nextPromoTime = strtotime("next Friday 19:00:00");
            }
        }
    } else {
        // Xử lý các loại khuyến mãi khác
        $isPromoActive = true;
        $promoMessage = "🔥 {$promoData['ten_chuong_trinh']} - Giảm {$promoData['muc_giam_gia']}%";
    }
    
    // Lấy sản phẩm khuyến mãi
    if ($isPromoActive || $promoData['id'] == 1) {
        $sqlProducts = "SELECT sp.* FROM san_pham sp
                        JOIN san_pham_khuyen_mai spkm ON sp.id = spkm.san_pham_id
                        WHERE spkm.khuyen_mai_id = {$promoData['id']}
                        ORDER BY RAND() LIMIT 8";
        
        $result = $conn->query($sqlProducts);
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $promoProducts[] = $row;
            }
        } else {
            // Nếu không có sản phẩm được chọn riêng, lấy ngẫu nhiên
            $sqlRandom = "SELECT * FROM san_pham ORDER BY RAND() LIMIT 6";
            $resultRandom = $conn->query($sqlRandom);
            while($row = $resultRandom->fetch_assoc()) {
                $promoProducts[] = $row;
            }
        }
    }
}

// Nếu không có khuyến mãi nào, kiểm tra khuyến mãi sắp tới
if (!$promoData) {
    $sqlNextPromo = "SELECT * FROM khuyen_mai 
                    WHERE trang_thai = 1 
                    AND ngay_bat_dau > '$currentDateTime'
                    ORDER BY ngay_bat_dau ASC LIMIT 1";
    $nextPromo = $conn->query($sqlNextPromo)->fetch_assoc();
    
    if ($nextPromo) {
        $promoMessage = "Sắp diễn ra: {$nextPromo['ten_chuong_trinh']}";
        $nextPromoTime = strtotime($nextPromo['ngay_bat_dau']);
    }
}
?>

<!-- Thêm CSS khuyến mãi -->
<link rel="stylesheet" href="assets/css/promo.css">

<!-- Section khuyến mãi -->
<?php if ($promoData || $nextPromoTime): ?>
<section class="promo-section">
    <h2><?php echo htmlspecialchars($promoMessage); ?></h2>
    
    <?php if (!empty($promoProducts)): ?>
    <div class="promo-products">
        <?php foreach($promoProducts as $sp): ?>
            <div class="promo-product-card">
                <img src="uploads/<?php echo htmlspecialchars($sp['hinh_anh']); ?>" alt="<?php echo htmlspecialchars($sp['ten_san_pham']); ?>">
                <h3><?php echo htmlspecialchars($sp['ten_san_pham']); ?></h3>
                
                <?php if ($isPromoActive): ?>
                    <?php $newPrice = $sp['gia'] * (1 - $promoData['muc_giam_gia']/100); ?>
                    <p class="original-price"><?php echo number_format($sp['gia']); ?>đ</p>
                    <p class="promo-price"><?php echo number_format($newPrice); ?>đ</p>
                    <span class="badge">-<?php echo $promoData['muc_giam_gia']; ?>%</span>
                <?php else: ?>
                    <p class="promo-price"><?php echo number_format($sp['gia']); ?>đ</p>
                    <span class="badge coming-soon-badge">Sắp giảm giá</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if(!$isPromoActive && $nextPromoTime): ?>
        <div id="countdown"></div>
        <script>
            // Countdown tới giờ khuyến mãi
            var countDownDate = new Date(<?php echo $nextPromoTime*1000; ?>).getTime();
            var x = setInterval(function() {
                var now = new Date().getTime();
                var distance = countDownDate - now;
                
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("countdown").innerHTML = "🔥 Khuyến mãi đang diễn ra!";
                    location.reload(); // Tải lại trang khi đến giờ khuyến mãi
                } else {
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    var countdownText = "⏰ Khuyến mãi bắt đầu sau: ";
                    if (days > 0) countdownText += days + " ngày ";
                    countdownText += hours + " giờ " + minutes + " phút " + seconds + " giây";
                    
                    document.getElementById("countdown").innerHTML = countdownText;
                }
            }, 1000);
        </script>
    <?php endif; ?>
</section>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert success">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>


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
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.opacity = '0';
        alert.style.transition = 'opacity 0.5s ease';
        setTimeout(() => alert.remove(), 500); // Xóa khỏi DOM sau khi mờ dần
    });
}, 3000); // 3 giây
</script>
<?php include 'includes/footer.php'; ?>
