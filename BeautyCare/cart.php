<?php
session_start();
include 'php/database.php';
include 'includes/header.php';
include 'includes/navbar.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Thêm sản phẩm vào giỏ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['quantity']));

    $sql = "SELECT id, ten_san_pham, gia, hinh_anh FROM san_pham WHERE id = $product_id AND is_available = 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['qty'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'ten_san_pham' => $product['ten_san_pham'],
                'gia' => $product['gia'],
                'hinh_anh' => $product['hinh_anh'],
                'qty' => $quantity
            ];
        }
    }
}

// Cập nhật số lượng
if (isset($_POST['update_qty'])) {
    $product_id = intval($_POST['product_id']);
    $new_qty = max(1, intval($_POST['quantity']));
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['qty'] = $new_qty;
    }
}

// Xóa sản phẩm
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$remove_id]);
}
?>

<div class="container" style="margin-top: 20px;">
    <h2 class="section-title">Giỏ hàng của bạn</h2>
    <p style="text-align:center;">Hướng dẫn: Bạn có thể thay đổi số lượng hoặc xóa sản phẩm khỏi giỏ hàng.</p>

    <?php if (!empty($_SESSION['cart'])): ?>
    <table class="cart-table" style="width:100%; border-collapse: collapse; margin-top:20px;">
        <thead>
            <tr style="background:#cdb4db; color:#fff;">
                <th>Hình ảnh</th>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $tong_tien = 0;
        foreach ($_SESSION['cart'] as $item):
            $thanh_tien = $item['gia'] * $item['qty'];
            $tong_tien += $thanh_tien;
        ?>
        <tr style="text-align:center; border-bottom:1px solid #ddd;">
            <td>
                <img src="assets/img/products/<?php echo htmlspecialchars($item['hinh_anh']); ?>" alt="" style="width:80px; border-radius:8px;">
            </td>
            <td>
                <a href="product-detail.php?id=<?php echo $item['id']; ?>" style="color:#6a4c93; font-weight:500;">
                    <?php echo htmlspecialchars($item['ten_san_pham']); ?>
                </a>
            </td>
            <td><?php echo number_format($item['gia'], 0, ',', '.'); ?> VND</td>
            <td>
                <form method="POST" style="display:inline-block;">
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <input type="number" name="quantity" value="<?php echo $item['qty']; ?>" min="1" style="width:60px; padding:4px;">
                    <button type="submit" name="update_qty" class="btn-primary" style="border:none; cursor:pointer;">Cập nhật</button>
                </form>
            </td>
            <td><?php echo number_format($thanh_tien, 0, ',', '.'); ?> VND</td>
            <td>
                <a href="cart.php?remove=<?php echo $item['id']; ?>" onclick="return confirm('Xóa sản phẩm này?')" class="btn-primary" style="background:#ffafcc;">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr style="text-align:right; font-weight:bold;">
            <td colspan="4">Tổng tiền:</td>
            <td colspan="2"><?php echo number_format($tong_tien, 0, ',', '.'); ?> VND</td>
        </tr>
        </tbody>
    </table>

    <div style="margin-top:20px; text-align:center;">
        <a href="index.php" class="btn-primary">Tiếp tục mua sắm</a>
        <a href="payment.php" class="btn-primary" style="background:#b5838d;">Thanh toán</a>
    </div>

    <?php else: ?>
        <p style="text-align:center;">Giỏ hàng của bạn đang trống. <a href="index.php" style="color:#6a4c93;">Mua sắm ngay</a></p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
