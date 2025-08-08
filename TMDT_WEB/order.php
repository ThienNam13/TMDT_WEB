<?php
require_once("php/db.php");
include("header.php");
if (empty($_SESSION['user_id'])) {
    // Chưa đăng nhập → chuyển về login
    header('Location: history.php');
    exit;
}

$ma_don = $_GET['order_id'] ?? '';

if (!$ma_don) {
  header("Location: history.php");
  exit;
}

// Truy vấn đơn hàng
$stmt = $link->prepare("SELECT ma_don, thoi_gian_dat, trang_thai, dia_chi, phuong_xa, khu_vuc, hinh_thuc_thanh_toan, user_id, ghi_chu FROM orders WHERE ma_don = ?");
$stmt->bind_param("s", $ma_don);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$paymentMethods = [
    'bank' => 'Chuyển khoản',
    'cod'  => 'Tiền mặt'
];
$paymentDisplay = $paymentMethods[$order['hinh_thuc_thanh_toan']] ?? 'Không xác định';
$stmt->close();

// Kiểm tra đúng tài khoản
if (!$order || $order['user_id'] != $_SESSION['user_id']) {
    // Nếu không tìm thấy đơn hoặc không phải của user hiện tại
    header('Location: history.php');
    exit;
}

// Truy vấn các món ăn trong đơn hàng
$stmt2 = $link->prepare("
  SELECT f.ten_mon, oi.so_luong, oi.don_gia 
  FROM order_items oi
  JOIN foods f ON oi.food_id = f.id
  WHERE oi.order_id = (
    SELECT id FROM orders WHERE ma_don = ?
  )
");
$stmt2->bind_param("s", $ma_don);
$stmt2->execute();
$items = $stmt2->get_result();
$stmt2->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đặt hàng thành công</title>
  <link rel="stylesheet" href="assets/css/order.css" />
  <link rel="stylesheet" href="./assets/themify-icons-font/themify-icons/themify-icons.css">
  <script src="assets/js/order.js"></script>
</head>
<body>
  <div id="app">
    <!-- ORDER CONTENT -->
    <div class="order-container">
      <h2>Cảm ơn bạn đã đặt hàng</h2>

      <div class="order-info">
        <p><strong>Mã đơn hàng:</strong> #<?= htmlspecialchars($order['ma_don']) ?></p>
        <p><strong>Thời gian giao hàng dự kiến:</strong> 30 - 45 phút</p>
        <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['thoi_gian_dat'])) ?></p>
        <p><strong>Phương thức thanh toán:</strong> <?= htmlspecialchars($paymentDisplay) ?></p>
        <p><strong>Ghi chú đơn hàng:</strong> <?= htmlspecialchars($order['ghi_chu']) ?></p>
        <p><strong>Địa chỉ giao hàng:</strong> 
          <?= htmlspecialchars($order['dia_chi']) ?>, <?= htmlspecialchars($order['phuong_xa']) ?>, <?= htmlspecialchars($order['khu_vuc']) ?>
        </p>
        <?php
        function getStatusClass($status) {
          return match ($status) {
            'Đang xử lý' => 'status-pending',
            'Đang giao' => 'status-shipping',
            'Hoàn thành' => 'status-completed',
            'Đã hủy' => 'status-canceled',
            default => 'status-unknown',
          };
        }
        ?>
        <p><strong>Trạng thái hiện tại:</strong>
          <span class="status <?= getStatusClass($order['trang_thai']) ?>">
            <?= htmlspecialchars($order['trang_thai']) ?>
          </span>
        </p>
      </div>

      <div class="order-details">
        <table>
          <thead>
            <tr>
              <th>Món ăn</th>
              <th>Số lượng</th>
              <th>Đơn giá</th>
              <th>Thành tiền</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $tong = 0;
            while ($item = $items->fetch_assoc()): 
              $thanh_tien = $item['so_luong'] * $item['don_gia'];
              $tong += $thanh_tien;
            ?>
            <tr>
              <td><?= htmlspecialchars($item['ten_mon']) ?></td>
              <td><?= $item['so_luong'] ?></td>
              <td><?= number_format($item['don_gia'], 0, ',', '.') ?>₫</td>
              <td><?= number_format($thanh_tien, 0, ',', '.') ?>₫</td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <div class="total">
          Tổng cộng: <span><?= number_format($tong, 0, ',', '.') ?>₫</span>
        </div>
      </div>

      <div class="buttons">
        <button class="btn" onclick="window.location.href='index.php'">Tiếp tục mua hàng</button>
        <button class="btn" onclick="window.location.href='history.php'">Xem lịch sử đơn hàng</button>
      </div>
    </div>
  </div>

  <?php include("footer.php"); ?>
</body>
</html>
