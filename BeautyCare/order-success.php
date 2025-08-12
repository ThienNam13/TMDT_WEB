<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'php/database.php';

// Determine which order to show
$orderId = null;
$orderCode = isset($_GET['ma_don']) ? trim($_GET['ma_don']) : null;
if (isset($_GET['order_id'])) {
    $orderId = (int) $_GET['order_id'];
}

// If order code provided, resolve to id
if (!$orderId && $orderCode) {
    $stmt = $conn->prepare("SELECT id FROM orders WHERE ma_don = ?");
    $stmt->bind_param("s", $orderCode);
    $stmt->execute();
    $stmt->bind_result($resolvedId);
    if ($stmt->fetch()) {
        $orderId = (int)$resolvedId;
    }
    $stmt->close();
}

// If still no order id, try last order of logged-in user
if (!$orderId && isset($_SESSION['user_id'])) {
    $userId = (int) $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ? ORDER BY thoi_gian_dat DESC LIMIT 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($latestId);
    if ($stmt->fetch()) {
        $orderId = (int)$latestId;
    }
    $stmt->close();
}

$order = null;
$orderItems = [];

if ($orderId) {
    // Load order
    $stmt = $conn->prepare("SELECT id, ma_don, ho_ten, sdt, dia_chi, phuong_xa, khu_vuc, tong_tien, thoi_gian_dat, trang_thai, hinh_thuc_thanh_toan FROM orders WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $order = $result->fetch_assoc();
    }
    $stmt->close();

    if ($order) {
        // Load items
        $stmt = $conn->prepare("SELECT oi.san_pham_id, oi.so_luong, oi.don_gia, sp.ten_san_pham, sp.hinh_anh FROM order_items oi JOIN san_pham sp ON sp.id = oi.san_pham_id WHERE oi.order_id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $itemsRes = $stmt->get_result();
        while ($row = $itemsRes->fetch_assoc()) {
            $orderItems[] = $row;
        }
        $stmt->close();
    }
}
?>

<style>
.success-container { max-width: 1000px; margin: 20px auto 40px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
.success-header { text-align: center; margin-bottom: 20px; }
.success-header h1 { color: #6a4c93; margin: 10px 0; }
.order-meta { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin: 15px 0; }
.order-meta .box { background: #f9f5ff; border: 1px solid #eee; border-radius: 6px; padding: 12px; }
.items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
.items-table th, .items-table td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
.items-table th { background: #faf5ff; color: #6a4c93; }
.product-cell { display: flex; align-items: center; gap: 10px; }
.product-cell img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; }
.total-row td { font-weight: bold; color: #6a4c93; }
.actions { text-align: center; margin-top: 20px; }
.actions a { margin: 0 6px; }
.hint { color: #666; font-size: 13px; text-align: center; margin-top: 8px; }
</style>

<div class="success-container">
    <div class="success-header">
        <div style="font-size: 42px;">✅</div>
        <h1>Đặt hàng thành công!</h1>
        <p>Cảm ơn bạn đã mua sắm tại BeautyCare.</p>
    </div>

    <?php if (!$order): ?>
        <p style="text-align:center; color:#b30000;">Không tìm thấy đơn hàng để hiển thị.</p>
        <div class="actions">
            <a class="btn-primary" href="products.php">Tiếp tục mua sắm</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="btn-primary" href="order-history.php">Xem lịch sử đơn hàng</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="order-meta">
            <div class="box">
                <strong>Mã đơn:</strong><br>
                <?php echo htmlspecialchars($order['ma_don'] ?: ('#' . $order['id'])); ?>
            </div>
            <div class="box">
                <strong>Trạng thái:</strong><br>
                <?php echo htmlspecialchars($order['trang_thai']); ?>
            </div>
            <div class="box">
                <strong>Thời gian đặt:</strong><br>
                <?php echo htmlspecialchars($order['thoi_gian_dat']); ?>
            </div>
            <div class="box">
                <strong>Thanh toán:</strong><br>
                <?php echo htmlspecialchars($order['hinh_thuc_thanh_toan'] ?: 'Chưa xác định'); ?>
            </div>
        </div>

        <div class="order-meta">
            <div class="box">
                <strong>Người nhận</strong><br>
                <?php echo htmlspecialchars($order['ho_ten']); ?> - <?php echo htmlspecialchars($order['sdt']); ?><br>
                <?php echo htmlspecialchars($order['dia_chi']); ?><?php echo $order['phuong_xa'] ? ', ' . htmlspecialchars($order['phuong_xa']) : ''; ?>, <?php echo htmlspecialchars($order['khu_vuc']); ?>
            </div>
            <div class="box">
                <strong>Tổng tiền</strong><br>
                <span style="color:#e63c5a; font-weight:bold;"><?php echo number_format((float)$order['tong_tien'], 0, ',', '.'); ?> VND</span>
            </div>
        </div>

        <h3>Chi tiết sản phẩm</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php $sum = 0; foreach ($orderItems as $item): $line = (float)$item['don_gia'] * (int)$item['so_luong']; $sum += $line; ?>
                    <tr>
                        <td>
                            <div class="product-cell">
                                <img src="assets/img/products/<?php echo htmlspecialchars($item['hinh_anh']); ?>" alt="<?php echo htmlspecialchars($item['ten_san_pham']); ?>">
                                <div>
                                    <?php echo htmlspecialchars($item['ten_san_pham']); ?>
                                </div>
                            </div>
                        </td>
                        <td><?php echo (int)$item['so_luong']; ?></td>
                        <td><?php echo number_format((float)$item['don_gia'], 0, ',', '.'); ?> VND</td>
                        <td><?php echo number_format($line, 0, ',', '.'); ?> VND</td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="3" style="text-align:right;">Tạm tính</td>
                    <td><?php echo number_format($sum, 0, ',', '.'); ?> VND</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align:right;">Tổng thanh toán</td>
                    <td><?php echo number_format((float)$order['tong_tien'], 0, ',', '.'); ?> VND</td>
                </tr>
            </tbody>
        </table>

        <div class="actions">
            <a class="btn-primary" href="products.php">Tiếp tục mua sắm</a>
            <a class="btn-primary" href="order-history.php">Xem lịch sử đơn hàng</a>
        </div>
        <div class="hint">Lưu ý: Mã đơn được gửi về email/SMS của bạn. Vui lòng giữ lại để tra cứu.</div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>


