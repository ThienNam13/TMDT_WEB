<?php
session_start();
require_once("php/db.php");

$user_id = $_SESSION['user_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lịch sử đơn hàng</title>
  <link rel="stylesheet" href="assets/css/history.css" />
  <link rel="stylesheet" href="assets/css/warning.css" />
  <link rel="stylesheet" href="./assets/themify-icons-font/themify-icons/themify-icons.css">
</head>
<body>
<div id="app">
  <?php include("header.php"); ?>

  <?php if (!$user_id): ?>
    <!-- Nếu chưa đăng nhập -->
    <div class="login-warning-box">
      <h2>Bạn chưa đăng nhập</h2>
      <p>Vui lòng đăng nhập để xem lịch sử đơn hàng của bạn.</p>
      <button class="login-warning-btn" onclick="openLoginPopup()">Đăng nhập ngay</button>
    </div>

  <?php else: ?>
    <!-- Nếu đã đăng nhập -->
    <?php
      $stmt = $link->prepare("SELECT ma_don, thoi_gian_dat, tong_tien FROM orders WHERE user_id = ? ORDER BY thoi_gian_dat DESC");
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();
    ?>

    <div class="history-container">
      <h2>Lịch sử đơn hàng</h2>

      <div class="order-history-list">
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <div class="order-summary">
              <p><strong>Mã đơn:</strong> #<?php echo htmlspecialchars($row['ma_don']); ?></p>
              <p><strong>Ngày đặt:</strong> <?php echo date("d/m/Y", strtotime($row['thoi_gian_dat'])); ?></p>
              <p><strong>Tổng tiền:</strong> <?php echo number_format($row['tong_tien'], 0, ',', '.'); ?>₫</p>
              <button class="btn" onclick="window.location.href='order.php?order_id=<?php echo urlencode($row['ma_don']); ?>'">Xem chi tiết</button>
              <!-- <button class="btn" onclick="window.location.href='order.php?review_id=<php echo urlencode($row['ma_don']); ?>'">Đánh giá</button> -->
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p style="text-align:center; color: #777;">Bạn chưa có đơn hàng nào.</p>
        <?php endif; ?>
      </div>

      <div class="history-buttons">
        <button class="btn" onclick="window.location.href='index.php'">Quay về trang chủ</button>
      </div>
    </div>
  <?php endif; ?>

  <?php include("footer.php"); ?>
</div>
<script src="assets/js/auth.js"></script>
</body>
</html>
