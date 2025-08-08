<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="assets/js/cookie.js"></script>
<script src="assets/js/menu.js"></script>   
<script src="assets/js/add-to-cart.js"></script> 
<script src="assets/js/search.js"></script> 
<?php
$title = "Thực đơn | KFJoli"; $page = 'menu'; 
include("header.php"); 
if (!empty($_GET['msg']) && $_GET['msg'] === 'no_cart') {
    echo "<script>alert('Bạn chưa chọn món nào!');</script>";
}
?>
<!-- Tabs danh mục -->
<div id="extra-nav">
  <div class="container-extra">
    <div class="menu-tabs">
      <a href="#khuyen-mai" class="tab active">Khuyến mãi</a>
      <a href="#mon-moi" class="tab">Món mới</a>
      <a href="#combo" class="tab">Combo</a>
      <a href="#ga-ran" class="tab">Gà rán</a>
      <a href="#burger-com-my-y" class="tab">Burger - Cơm - Mỳ ý</a>
      <a href="#trang-mieng" class="tab">Tráng Miệng</a>  
      <?php if (isset($page) && $page === 'menu'): ?>
              <form class="search tab">
                  <input type="text" id="searchInput" class="search-box" placeholder="Nhập món ăn cần tìm ...">
                  <button type="button" onclick="searchFood()">Tìm kiếm</button>
              </form>
              <div class="shopping-cart tab">
                  <a href="cart.php"><i class="icon-cart ti-shopping-cart"></i></a>
              </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Danh mục: Khuyến mãi -->
<div class="product-title">
  <h2 id="khuyen-mai">Khuyến mãi</h2>
  <div class="product-container"></div>
</div>

<!-- Danh mục: Món mới -->
<div class="product-title">
  <h2 id="mon-moi">Món mới</h2>
  <div class="product-container"></div>
</div>

<!-- Danh mục: Combo -->
<div class="product-title">
  <h2 id="combo">Combo</h2>
  <div class="product-container"></div>
</div>

<!-- Danh mục: Gà rán -->
<div class="product-title">
  <h2 id="ga-ran">Gà rán</h2>
  <div class="product-container"></div>
</div>

<!-- Danh mục: Burger - Cơm - Mỳ ý -->
<div class="product-title">
  <h2 id="burger-com-my-y">Burger - Cơm - Mỳ ý</h2>
  <div class="product-container"></div>
</div>

<!-- Danh mục: Tráng miệng -->
  <div class="product-title">
  <h2 id="trang-mieng">Tráng miệng</h2>
  <div class="product-container"></div>
</div>

<?php include("footer.php"); ?>
