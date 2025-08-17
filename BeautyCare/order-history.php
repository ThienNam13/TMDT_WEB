<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'php/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = (int) $_SESSION['user_id'];

// ===== Lấy danh sách đơn hàng =====
$sql = "SELECT id, ma_don, tong_tien, thoi_gian_dat, trang_thai 
        FROM orders 
        WHERE user_id = ? 
        ORDER BY thoi_gian_dat DESC";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Lỗi prepare đơn hàng: " . $conn->error);
}
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
$orders = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ===== Lấy danh sách sản phẩm đã đánh giá theo từng đơn =====
$reviewedItems = [];
$sql = "SELECT order_id, san_pham_id 
        FROM danh_gia 
        WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Lỗi prepare đánh giá: " . $conn->error);
}
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $reviewedItems[(int)$row['order_id']][(int)$row['san_pham_id']] = true;
}
$stmt->close();

// ===== Preload items cho tất cả đơn hàng =====
$orderIdList = array_map(fn($o) => (int)$o['id'], $orders);
$itemsByOrderId = [];
if (!empty($orderIdList)) {
    $in = implode(',', array_fill(0, count($orderIdList), '?'));
    $types = str_repeat('i', count($orderIdList));
    $sql = "SELECT oi.order_id, oi.san_pham_id, oi.so_luong, oi.don_gia, sp.ten_san_pham, sp.hinh_anh 
            FROM order_items oi 
            JOIN san_pham sp ON sp.id = oi.san_pham_id 
            WHERE oi.order_id IN ($in) 
            ORDER BY oi.order_id DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Lỗi prepare items: " . $conn->error);
    }
    $bindParams = [$types];
    foreach ($orderIdList as $oid) { $bindParams[] = $oid; }
    $refs = [];
    foreach ($bindParams as $k => $v) { $refs[$k] = &$bindParams[$k]; }
    call_user_func_array([$stmt, 'bind_param'], $refs);

    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $oid = (int)$row['order_id'];
        $itemsByOrderId[$oid][] = $row;
    }
    $stmt->close();
}
?>

<style>
/* Giữ nguyên CSS của bạn */
.history-container { max-width: 1100px; margin: 20px auto 40px; }
.history-title { text-align: center; color: #6a4c93; margin: 20px 0; }
.order-card { background: #fff; border: 1px solid #eee; border-radius: 8px; margin-bottom: 14px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
.order-header { display: grid; grid-template-columns: 1.2fr 1fr 1fr 1fr 250px; gap: 10px; padding: 12px 14px; align-items: center; background: #faf5ff; }
.order-header div { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.order-body { display: none; padding: 12px 14px; }
.order-body table { width: 100%; border-collapse: collapse; }
.order-body th, .order-body td { padding: 8px 10px; border-bottom: 1px solid #eee; text-align: left; }
.product-cell { display: flex; align-items: center; gap: 10px; }
.product-cell img { width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; }
.status-pill { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; background: #eae2f8; color: #6a4c93; }
.toggle-btn { cursor: pointer; background: #cdb4db; color: #fff; border: none; border-radius: 6px; padding: 8px 10px; }
.actions a { margin-left: 6px; }
.empty { text-align:center; padding: 30px 10px; color: #666; }
.review-btn { background: #ffb703; color: #fff; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 13px; }
.review-btn:hover { background: #fb8500; }
.reviewed-badge { background: #4caf50; color: #fff; padding: 6px 10px; border-radius: 6px; font-size: 13px; display: inline-block; }
@media (max-width: 820px) {
  .order-header { grid-template-columns: 1fr 1fr; grid-auto-rows: minmax(24px,auto); }
}
</style>

<div class="history-container">
    <h2 class="history-title">Lịch sử đơn hàng</h2>

    <?php if (empty($orders)): ?>
        <div class="order-card">
            <div class="empty">Bạn chưa có đơn hàng nào. <a href="products.php" class="btn-primary">Mua sắm ngay</a></div>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <?php $oid = (int)$order['id']; ?>
            <div class="order-card" id="order-<?php echo $oid; ?>">
                <div class="order-header">
                    <div><strong>Mã đơn:</strong> <?php echo htmlspecialchars($order['ma_don'] ?: ('#'.$oid)); ?></div>
                    <div><strong>Ngày đặt:</strong> <?php echo htmlspecialchars($order['thoi_gian_dat']); ?></div>
                    <div><strong>Trạng thái:</strong> <span class="status-pill"><?php echo htmlspecialchars($order['trang_thai']); ?></span></div>
                    <div><strong>Tổng:</strong> <span style="color:#e63c5a; font-weight:600;"><?php echo number_format((float)$order['tong_tien'], 0, ',', '.'); ?> VND</span></div>
                    <div class="actions" style="text-align:right;">
                        <button class="toggle-btn" data-target="body-<?php echo $oid; ?>">Xem chi tiết</button>
                        <a class="btn-primary" href="order-success.php?order_id=<?php echo $oid; ?>">Hóa đơn</a>
                    </div>
                </div>
                <div class="order-body" id="body-<?php echo $oid; ?>">
                    <table>
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                                <th>Đánh giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $sum=0; foreach (($itemsByOrderId[$oid] ?? []) as $item): 
                                $line=(float)$item['don_gia']*(int)$item['so_luong']; $sum+=$line;
                                $pid = (int)$item['san_pham_id'];
                            ?>
                                <tr>
                                    <td>
                                        <div class="product-cell">
                                            <img src="assets/img/products/<?php echo htmlspecialchars($item['hinh_anh']); ?>" alt="<?php echo htmlspecialchars($item['ten_san_pham']); ?>">
                                            <div><?php echo htmlspecialchars($item['ten_san_pham']); ?></div>
                                        </div>
                                    </td>
                                    <td><?php echo (int)$item['so_luong']; ?></td>
                                    <td><?php echo number_format((float)$item['don_gia'], 0, ',', '.'); ?> VND</td>
                                    <td><?php echo number_format($line, 0, ',', '.'); ?> VND</td>
                                    <td>
                                        <?php if (!empty($reviewedItems[$oid][$pid])): ?>
                                            <span class="reviewed-badge">✅ Đã đánh giá</span>
                                        <?php else: ?>
                                            <a class="review-btn" href="review.php?order_id=<?php echo $oid; ?>&id=<?php echo $pid; ?>">Đánh giá</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="4" style="text-align:right; font-weight:600;">Tổng tiền</td>
                                <td style="font-weight:600; color:#6a4c93;"><?php echo number_format($sum, 0, ',', '.'); ?> VND</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.toggle-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var id = this.getAttribute('data-target');
      var el = document.getElementById(id);
      if (!el) return;
      var isShown = el.style.display === 'block';
      el.style.display = isShown ? 'none' : 'block';
      this.textContent = isShown ? 'Xem chi tiết' : 'Ẩn chi tiết';
    });
  });
});
</script>

<?php include 'includes/footer.php'; ?>
