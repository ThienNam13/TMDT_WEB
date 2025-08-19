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
    $so_luong_ton = $so_luong_ton ?? 0;
    // Lấy số lượng tồn kho từ DB
    $sql = "SELECT so_luong_ton FROM kho_hang WHERE san_pham_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($so_luong_ton);
    $stmt->fetch();
    $stmt->close();

    if (isset($_SESSION['cart'][$product_id])) {
        if ($new_qty > $so_luong_ton) {
            $_SESSION['cart'][$product_id]['qty'] = $so_luong_ton; // Giới hạn lại
            $ten_san_pham = $_SESSION['cart'][$product_id]['ten_san_pham'] ?? 'Sản phẩm';
            $notice = "Sản phẩm {$ten_san_pham} chỉ còn {$so_luong_ton} cái trong kho, số lượng đã được điều chỉnh.";
        } else {
            $_SESSION['cart'][$product_id]['qty'] = $new_qty;
        }
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
        <?php if (!empty($notice)): ?>
        <div style="color:red; font-size: 0.9rem; margin-bottom:10px; text-align:center;">
            <?= htmlspecialchars($notice) ?>
        </div>
        <?php endif; ?>
        <?php
        $tong_tien = 0;
        $cart_valid = true; // flag để check giỏ có hợp lệ không
        foreach ($_SESSION['cart'] as $item):
            // Lấy số lượng tồn kho từ DB
            $sql = "SELECT so_luong_ton FROM kho_hang WHERE san_pham_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $item['id']);
            $stmt->execute();
            $stmt->bind_result($so_luong_ton);
            $stmt->fetch();
            $stmt->close();
            $thanh_tien = $item['gia'] * $item['qty'];
            $tong_tien += $thanh_tien;

            // Nếu số lượng đặt lớn hơn tồn kho -> giỏ không hợp lệ
            if ($item['qty'] > $so_luong_ton || $so_luong_ton == 0) {
                $cart_valid = false;
            }
        ?>
        <tr style="text-align:center; border-bottom:1px solid #ddd;">
            <td>
                <img src="assets/img/products/<?php echo htmlspecialchars($item['hinh_anh']); ?>" alt="" style="width:80px; border-radius:8px;">
            </td>
            <td>
                <a href="product-detail.php?id=<?php echo $item['id']; ?>" style="color:#6a4c93; font-weight:500;">
                    <?php echo htmlspecialchars($item['ten_san_pham']); ?>
                </a>
                <br>
                <small style="color:#888;">Còn lại: <?php echo $so_luong_ton; ?> sản phẩm</small>
                <?php if ($item['qty'] > $so_luong_ton): ?>
                    <br><span style="color:red; font-size:0.9rem;">⚠ Số lượng vượt quá tồn kho!</span>
                <?php elseif ($so_luong_ton == 0): ?>
                    <br><span style="color:red; font-size:0.9rem;">⚠ Hết hàng</span>
                <?php endif; ?>
            </td>
            <td><?php echo number_format($item['gia'], 0, ',', '.'); ?> VND</td>
            <td>
                <form method="POST" style="display:inline-block;">
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <input type="number" 
                        name="quantity" 
                        value="<?php echo $item['qty']; ?>" 
                        min="1" 
                        max="<?php echo $so_luong_ton; ?>" 
                        style="width:60px; padding:4px;">
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
        <?php if ($cart_valid): ?>
            <a href="payment.php" class="btn-primary">Thanh toán</a>
        <?php else: ?>
            <button class="btn-primary" style="background:gray; cursor:not-allowed;" disabled>Không thể thanh toán</button>
            <p style="color:red; margin-top:5px;">⚠ Vui lòng điều chỉnh số lượng sản phẩm theo tồn kho trước khi thanh toán.</p>
        <?php endif; ?>
    </div>

    <?php elseif (empty($_SESSION['cart'])): ?>
        <div class="empty-state">
            <img src="assets/img/empty-cart.png" alt="Giỏ hàng trống">
            <h3>Giỏ hàng của bạn đang trống</h3>
            <p>Hãy thêm sản phẩm để bắt đầu mua sắm ngay!</p>
            <a href="products.php" class="btn-primary">Mua sắm ngay</a>
        </div>
    <?php else: ?>
        <!-- Bảng giỏ hàng -->
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
<script>
document.querySelectorAll('input[name="quantity"]').forEach(input => {
    function checkQuantity() {
        const max = parseInt(this.max);
        let current = parseInt(this.value);

        if (isNaN(current) || current < 1) {
            this.value = 1; // tránh nhập số âm hoặc null
            return;
        }

        if (current >= max) {
            // nếu vượt quá hoặc bằng tồn kho thì cảnh báo
            if (current > max) this.value = max;
            Swal.fire({
                icon: 'warning',
                title: 'Số lượng không đủ',
                text: 'Chỉ còn ' + max + ' sản phẩm trong kho',
                confirmButtonText: 'OK'
            });
        }
    }

    // Gõ trực tiếp
    input.addEventListener('input', checkQuantity);
    // Khi tăng giảm bằng nút spinner
    input.addEventListener('click', checkQuantity);
    // Khi rời khỏi input
    input.addEventListener('change', checkQuantity);
    // Khi bấm phím lên/xuống
    input.addEventListener('keyup', checkQuantity);
});
</script>