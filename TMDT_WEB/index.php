<?php include("header.php"); ?>

<!-- CONTENT -->
<div id="content">
  <!-- Slide Show -->
  <div class="slideshow">
    <div class="slide"><img src="./assets/img/food/home1.jpg" alt="advertise1"></div>
    <div class="slide"><img src="./assets/img/food/home2.jpg" alt="advertise2"></div>
    <div class="slide"><img src="./assets/img/food/home3.jpg" alt="advertise3"></div>
  </div>

  <!-- Danh mục món ăn -->
  <div class="menu section-content">
    <div class="menu-name section-name"><b>---------- MENU ----------</b></div>
    <div class="container-menu">
      <div class="type-food" id="type-food">
        <a href="menu.php#mon-moi"><img src="./assets/img/food/monmoi.jpg" alt=""><div class="type-name"><b>Món Mới</b></div></a>
      </div>
      <div class="type-food">
        <a href="menu.php#combo"><img src="./assets/img/food/combo.jpg" alt=""><div class="type-name"><b>Combo</b></div></a>
      </div>
      <div class="type-food">
        <a href="menu.php#ga-ran"><img src="./assets/img/food/garan.jpg" alt=""><div class="type-name"><b>Gà Rán</b></div></a>
      </div>
      <div class="type-food">
        <a href="menu.php#burger-com-my-y"><img src="./assets/img/food/burger-com-myy.jpg" alt=""><div class="type-name"><b>Burger - Cơm - Mỳ Ý</b></div></a>
      </div>
      <div class="type-food">
        <a href="menu.php#khuyen-mai"><img src="./assets/img/food/muc_ran.jpg" alt=""><div class="type-name"><b>Khuyến Mãi</b></div></a>
      </div>
      <div class="type-food">
        <a href="menu.php#trang-mieng"><img src="./assets/img/food/trangmieng.jpg" alt=""><div class="type-name"><b>Tráng Miệng</b></div></a>
      </div>
    </div>
  </div>

  <!-- Best-seller -->
  <div class="best-seller section-content">
    <div class="clear"></div>
    <div class="section-name"><b>---------- Khách Hàng <a style="color: #000;">Mê FOOD</a> Ê Hề ---------</b></div>
  <?php
require_once "php/db.php";

function getFoodById($id, $link) {
  $stmt = mysqli_prepare($link, "SELECT * FROM foods WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return mysqli_fetch_assoc($result);
}

$recentViewed = isset($_COOKIE['recentViewedItems']) ? explode(",", $_COOKIE['recentViewedItems']) : [];

// Loại bỏ ID trùng nhau
$recentViewed = array_unique($recentViewed);

if (!empty($recentViewed)) {
  echo "<div class='food-title'><h2>Đã xem gần đây</h2><div class='food-container'>";
  foreach ($recentViewed as $id) {
    $food = getFoodById($id, $link);
    if ($food) {
      echo "
      <div class='food-card'>
        <img src='./{$food['hinh_anh']}' alt='{$food['ten_mon']}'>
        <h3>{$food['ten_mon']}</h3>
        <p>{$food['mo_ta']}</p>
        <p class='price'>".number_format($food['gia'])."₫</p>
      </div>";
    }
  }
  echo "</div></div>";
}
?>

  </div>
  <!-- Giới thiệu -->
  <div class="introduction">
    <div class="container-intro">
      <div class="intro-name">KFJoli, say hi</div>
      <p>Dành cho những tín đồ yêu thích các món gà ngon, chất lượng và tiện lợi, KFJoli là lựa chọn hàng đầu với khẩu hiệu “Bạn ăn gì có KFJoli lo”.</p>
      <p>Với các món gà chế biến đa dạng như gà rán giòn tan, gà sốt cay Hàn Quốc, gà nướng mật ong, cánh gà chiên nước mắm, cùng nhiều món ăn kèm hấp dẫn như khoai tây chiên, salad, nước uống tươi mát... chúng tôi cam kết sẽ đem đến một trải nghiệm tuyệt vời từ đồ ăn đến dịch vụ dành cho khách hàng một cách trọn vẹn nhất.</p>
      <p>Hiện tại, cửa hàng hỗ trợ giao hàng nhanh trong khoảng 15 phút đến các quận lân cận như Bình Thạnh, Gò Vấp, Thủ Đức, Quận 1, Quận 2.</p>
    </div>
  </div>


<!-- Popup đăng nhập / đăng ký -->
<div id="popupFrame" style="display:none">
  <div class="popup-box">
    <iframe id="authFrame" src=""></iframe>
    <button class="popup-close" onclick="document.getElementById('popupFrame').style.display='none'">
      &times;
    </button>
  </div>
</div>

<script src="assets/js/auth.js"></script>

<?php include("footer.php"); ?>