<?php
session_start();
include 'php/database.php';
include 'includes/header.php';
include 'includes/navbar.php';

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($product_id <= 0) {
    echo "<div class='container'><p>Sản phẩm không tồn tại.</p></div>";
    include 'includes/footer.php';
    exit;
}

// Lấy chi tiết sản phẩm
$sql = "SELECT * FROM san_pham WHERE id = $product_id AND is_available = 1 LIMIT 1";
$result = $conn->query($sql);
if (!$result || $result->num_rows == 0) {
    echo "<div class='container'><p>Sản phẩm không tồn tại.</p></div>";
    include 'includes/footer.php';
    exit;
}
$product = $result->fetch_assoc();

// Xử lý gửi đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $so_sao = intval($_POST['so_sao']);
    $binh_luan = trim($_POST['binh_luan']);
    $user_id = $_SESSION['user_id'] ?? 0;

    if ($user_id > 0 && $so_sao >= 1 && $so_sao <= 5 && $binh_luan !== '') {
        $binh_luan_esc = $conn->real_escape_string($binh_luan);
        $conn->query("INSERT INTO danh_gia (san_pham_id, user_id, so_sao, binh_luan) 
                      VALUES ($product_id, $user_id, $so_sao, '$binh_luan_esc')");
        header("Location: product-detail.php?id=$product_id");
        exit;
    } else {
        echo "<div class='container'><p style='color:red;'>Vui lòng đăng nhập và nhập thông tin hợp lệ.</p></div>";
    }
}

// Lấy danh sách đánh giá (đổi username -> HoTen)
$sql_reviews = "SELECT dg.*, u.HoTen 
                FROM danh_gia dg
                LEFT JOIN users u ON dg.user_id = u.id
                WHERE san_pham_id = $product_id
                ORDER BY ngay_danh_gia DESC";
$reviews = $conn->query($sql_reviews);
?>

<style>
.product-detail-page {
    display: flex;
    gap: 30px;
    margin-top: 20px;
    background: #fff;
    padding: 20px;
    border-radius: 6px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}
.product-detail-page img {
    width: 400px;
    border-radius: 6px;
}
.product-info h2 {
    margin-bottom: 10px;
}
.product-info p.price {
    color: red;
    font-size: 20px;
    font-weight: bold;
}
.table-thanh-phan {
    border-collapse: collapse;
    margin-top: 15px;
}
.table-thanh-phan td, .table-thanh-phan th {
    border: 1px solid #ddd;
    padding: 8px;
}
.review-section {
    margin-top: 40px;
    background: #fff;
    padding: 15px;
    border-radius: 6px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}
.review {
    border-bottom: 1px solid #eee;
    padding: 8px 0;
}
.review:last-child {
    border-bottom: none;
}
</style>

<div class="container">
    <div class="product-detail-page">
        <div class="product-image">
            <img src="assets/img/products/<?php echo htmlspecialchars($product['hinh_anh']); ?>" 
                 alt="<?php echo htmlspecialchars($product['ten_san_pham']); ?>">
        </div>
        <div class="product-info">
<h2><?php echo htmlspecialchars($product['ten_san_pham']); ?></h2>
            <p class="price"><?php echo number_format($product['gia'], 0, ',', '.'); ?> VND</p>
            <p><strong>Thương hiệu:</strong> <?php echo htmlspecialchars($product['thuong_hieu']); ?></p>
            <p><strong>Phân loại:</strong> <?php echo htmlspecialchars($product['phan_loai']); ?></p>
            <p><strong>Mô tả:</strong> <?php echo htmlspecialchars($product['mo_ta']); ?></p>
            
            <h4>Thành phần</h4>
            <table class="table-thanh-phan">
                <tr><td><?php echo nl2br(htmlspecialchars($product['thanh_phan'])); ?></td></tr>
            </table>

            <!-- Chuyển thẳng tới cart.php khi thêm vào giỏ -->
            <form method="POST" action="cart.php">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <label>Số lượng:</label>
                <input type="number" name="quantity" value="1" min="1" style="width:60px;">
                <button type="submit" class="btn-primary">Thêm vào giỏ hàng</button>
            </form>
        </div>
    </div>

    <!-- Đánh giá -->
    <div class="review-section">
        <h3>Đánh giá sản phẩm</h3>
        <?php if (isset($_SESSION['user_id'])): ?>
        <form method="POST">
            <label>Số sao:</label>
            <select name="so_sao" required>
                <option value="5">5 sao</option>
                <option value="4">4 sao</option>
                <option value="3">3 sao</option>
                <option value="2">2 sao</option>
                <option value="1">1 sao</option>
            </select>
            <br><br>
            <textarea name="binh_luan" rows="3" placeholder="Nhập đánh giá..." required></textarea>
            <br>
            <button type="submit" name="submit_review" class="btn-primary">Gửi đánh giá</button>
        </form>
        <?php else: ?>
            <p>Bạn cần <a href="php/login.php">đăng nhập</a> để đánh giá.</p>
        <?php endif; ?>

        <hr>
        <?php
        if ($reviews && $reviews->num_rows > 0) {
            while ($rev = $reviews->fetch_assoc()) {
                echo '<div class="review">';
                echo '<strong>' . htmlspecialchars($rev['HoTen'] ?? 'Người dùng ẩn danh') . '</strong> ';
                echo ' - ' . str_repeat('⭐', intval($rev['so_sao']));
                echo '<br>' . nl2br(htmlspecialchars($rev['binh_luan']));
                echo '<br><small>' . $rev['ngay_danh_gia'] . '</small>';
                echo '</div>';
            }
        } else {
            echo "<p>Chưa có đánh giá nào cho sản phẩm này.</p>";
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>