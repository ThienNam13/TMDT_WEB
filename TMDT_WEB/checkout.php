<?php
  $pageTitle = "Thanh toán đơn hàng";
  session_start();
  require_once("php/db.php");
  if (empty($_SESSION['user_id'])) {
    // Chưa đăng nhập → chuyển về login
    header('Location: login.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $pageTitle ?></title>
  <link rel="stylesheet" href="assets/css/checkout.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

</head>
<body>

<div class="checkout-container">
  <h2>Thanh toán đơn hàng</h2>

  <form id="checkout-form" method="POST" action="php/order/create.php">
    <!-- Thông tin người nhận -->
    <div class="section">
      <div class="section-title">Thông tin người nhận</div>
      <div class="input-group">
        <label for="fullname">Họ và tên</label>
        <input type="text" id="fullname" name="fullname" required>
      </div>
      <div class="input-group">
        <label for="phone">Số điện thoại</label>
        <input type="tel" id="phone" name="phone" required>
      </div>
      <div class="input-group">
        <label for="address">Địa chỉ giao hàng</label>
        <input type="text" id="address" name="address" required>
      </div>
      <div class="input-group">
        <label for="ward">Phường / Xã</label>
        <select id="ward" name="ward" required>
          <option value="">-- Chọn phường --</option>
          <option value="Phường Bình Thạnh">Phường Bình Thạnh</option>
          <option value="Phường Tân Sơn Nhất">Phường Tân Sơn Nhất</option>
          <option value="Phường Cầu Kiệu">Phường Cầu Kiệu</option>
          <option value="Phường An Nhơn">Phường An Nhơn</option>
          <option value="Phường Hạnh Thông">Phường Hạnh Thông</option>
          <option value="Phường Sài Gòn">Phường Sài Gòn</option>
          <option value="Phường Minh Phụng">Phường Minh Phụng</option>
          <option value="Phường Tân Sơn Hòa">Phường Tân Sơn Hòa</option>
          <option value="Phường Phú Nhuận">Phường Phú Nhuận</option>
          <option value="Phường An Hội Đông">Phường An Hội Đông</option>
        </select>
        <div id="map" style="height: 300px;"></div>
        <input type="hidden" id="lat" name="lat">
        <input type="hidden" id="lng" name="lng">
      </div>
      <div class="input-group">
        <label for="region">Khu vực giao</label>
        <select id="region" name="region" required>
          <option value="">-- Chọn khu vực --</option>
          <option value="TP Hồ Chí Minh">TP Hồ Chí Minh</option>
        </select>
      </div>
    </div>

    <!-- Ghi chú -->
    <div class="section">
      <div class="section-title">Ghi chú cho đơn hàng</div>
      <textarea name="note" rows="3" placeholder="VD: Giao trước 12h, gọi trước khi đến..."></textarea>
    </div>

    <!-- Chi tiết đơn hàng -->
    <div class="section">
      <div class="section-title">Chi tiết đơn hàng</div>
      <div class="order-items-container">
        <div class="order-item"><span>Phí giao hàng</span><span>15.000₫</span></div>
      </div>
      <div class="checkout-total"><span>Tổng cộng</span><span id="total-amount">...</span></div>
    </div>

    <!-- Phương thức thanh toán -->
    <div class="section">
      <div class="section-title">Phương thức thanh toán</div>
      <select name="payment_method" required>
        <option value="cod">Tiền mặt</option>
        <option value="bank">Chuyển khoản</option>
      </select>
    </div>

    <input type="hidden" name="cart_data" id="cart-data">
    <button type="submit" class="btn-submit">Đặt hàng</button>
  </form>
</div>

<!-- ✅ Popup cảnh báo dùng chung -->
<div class="popup-overlay" id="popup-alert" style="display: none;">
  <div class="popup-box">
    <h3>❌ Cảnh báo</h3>
    <p id="popup-message">Nội dung cảnh báo sẽ được thay đổi bằng JS</p>
    <button onclick="closePopup()">Đóng</button>
  </div>
</div> 

<!-- Popup xác nhận đặt hàng -->
<div class="popup-overlay" id="popup-confirm">
  <div class="popup-box">
    <h3>Xác nhận thanh toán</h3>
    <p>Bạn có chắc muốn đặt đơn hàng này không?</p>
    <button class="btn-confirm" onclick="submitRealOrder()">Xác nhận</button>
    <button class="btn-cancel" onclick="closeConfirm()">Hủy</button>
  </div>
</div>
<script>
// Kiểm tra cart khi load trang
document.addEventListener("DOMContentLoaded", () => {
  const cart = JSON.parse(localStorage.getItem("cart") || "[]");
  if (!cart.length) {
    // alert("Bạn chưa chọn món nào!");
    window.location.href = "menu.php?msg=no_cart";
  }
});
</script>
<script src="assets/js/checkout.js"></script>
</body>
</html>