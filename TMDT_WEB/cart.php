<?php
  $pageTitle = "Giỏ hàng";
  include("header.php");
?>

<link rel="stylesheet" href="./assets/css/cart.css">
<div class="cart-container">
  <h2>Giỏ hàng của bạn</h2>
  <table>
    <thead>
      <tr>
        <th>Món</th>
        <th>Số lượng</th>
        <th>Giá</th>
        <th>Thành tiền</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody id="cart-body"></tbody>
  </table>

  <div class="cart-total">
    Tổng cộng: <span id="total-price">0₫</span>
  </div>

  <div class="checkout-button">
    <button class="pay-btn" id="checkout-btn">Thanh toán</button>
  </div>

  <!-- Popup yêu cầu đăng nhập -->
  <div class="popup-overlay" id="login-popup" style="display: none;">
    <div class="popup-box">
      <h3>⚠️ Vui lòng đăng nhập để đặt hàng</h3>
      <div class="popup-buttons">
        <button class="btn-login" onclick="openLoginPopup()">Đăng nhập ngay</button>
        <button onclick="closeLoginPopup()" class="btn-close">Đóng</button>
      </div>
    </div>
  </div>


  <!-- Modal sửa số lượng -->
  <div id="edit-modal" class="modal" style="display:none;">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3>Sửa số lượng</h3>
      <p id="modal-item-name"></p>
      <input type="number" id="new-quantity" min="1" />
      <button id="save-edit">Lưu</button>
    </div>
  </div>
</div>

<script>
  window.isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
</script>
<script src="assets/js/cart.js"></script>

<?php include("footer.php"); ?>
