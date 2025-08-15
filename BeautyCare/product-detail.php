<?php
session_start();
include 'php/database.php';
include 'includes/header.php';
include 'includes/navbar.php';

// Lấy ID sản phẩm từ URL và kiểm tra tính hợp lệ
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($product_id <= 0) {
    echo "<div class='container'><p>Sản phẩm không hợp lệ.</p></div>";
    include 'includes/footer.php';
    exit;
}

// Chuẩn bị và thực thi câu lệnh SQL để lấy chi tiết sản phẩm
$sql = "SELECT * FROM san_pham WHERE id = ? AND is_available = 1 LIMIT 1";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Lỗi chuẩn bị câu lệnh SQL sản phẩm: " . $conn->error);
}

$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows == 0) {
    echo "<div class='container'><p>Sản phẩm không tồn tại hoặc đã bị ẩn.</p></div>";
    include 'includes/footer.php';
    exit;
}
$product = $result->fetch_assoc();
$stmt->close();

// Chuẩn bị và thực thi câu lệnh SQL để lấy 2 đánh giá gần nhất
$sql_reviews = "SELECT dg.*, u.fullname 
                FROM danh_gia dg
                LEFT JOIN users u ON dg.user_id = u.id
                WHERE dg.san_pham_id = ?
                ORDER BY dg.ngay_danh_gia DESC
                LIMIT 2"; // Chỉ lấy 2 đánh giá
$stmt_reviews = $conn->prepare($sql_reviews);

if ($stmt_reviews === false) {
    die("Lỗi chuẩn bị câu lệnh SQL đánh giá: " . $conn->error);
}

$stmt_reviews->bind_param("i", $product_id);
$stmt_reviews->execute();
$reviews = $stmt_reviews->get_result();

// Lấy tổng số đánh giá để quyết định có hiển thị nút "Xem thêm" không
$sql_count = "SELECT COUNT(*) as total FROM danh_gia WHERE san_pham_id = ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("i", $product_id);
$stmt_count->execute();
$count_result = $stmt_count->get_result();
$total_reviews = $count_result->fetch_assoc()['total'];
$stmt_count->close();
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
.view-more-reviews-btn {
    display: block;
    width: 100%;
    text-align: center;
    margin-top: 10px;
    padding: 10px;
    cursor: pointer;
    background-color: #f0f0f0;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-weight: bold;
    color: #333;
    text-decoration: none;
}
.view-more-reviews-btn:hover {
    background-color: #e0e0e0;
}
#cart-message {
    margin-top: 10px;
    color: green;
    font-weight: bold;
}
.btn-primary{
    cursor: pointer;
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

            <!-- Form AJAX -->
            <form id="add-to-cart-form" onsubmit="return false;">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <label>Số lượng:</label>
                <input type="number" name="quantity" value="1" min="1" style="width:60px;">
                <button type="button" class="btn-primary" onclick="addToCart()">Thêm vào giỏ hàng</button>
            </form>
            <div id="cart-message"></div>
        </div>
    </div>

    <div class="review-section">
        <h3>Đánh giá sản phẩm</h3>
        <hr>
        <?php
        if ($reviews->num_rows > 0) {
            while ($rev = $reviews->fetch_assoc()) {
                echo '<div class="review">';
                echo '<strong>' . htmlspecialchars($rev['fullname'] ?? 'Người dùng ẩn danh') . '</strong> ';
                echo ' - ' . str_repeat('⭐', intval($rev['so_sao']));
                echo '<br>' . nl2br(htmlspecialchars($rev['binh_luan']));
                echo '<br><small>' . $rev['ngay_danh_gia'] . '</small>';
                echo '</div>';
            }
        } else {
            echo "<p>Chưa có đánh giá nào cho sản phẩm này.</p>";
        }
        
        if ($total_reviews > 2) {
            echo '<a href="product-reviews.php?id=' . $product_id . '" class="view-more-reviews-btn">Xem tất cả ' . $total_reviews . ' đánh giá</a>';
        }
        ?>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function addToCart() {
    const form = document.getElementById('add-to-cart-form');
    const formData = new FormData(form);

    Swal.fire({
        title: 'Đang xử lý...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('cart.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(() => {
        Swal.close();
        Swal.fire({
            icon: 'success',
            title: 'Đã thêm vào giỏ hàng!',
            showCancelButton: true,
            confirmButtonText: 'Xem giỏ hàng',
            cancelButtonText: 'Tiếp tục mua sắm'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'cart.php';
            }
        });
    })
    .catch(() => {
        Swal.close();
        Swal.fire('Lỗi', 'Không thể thêm vào giỏ hàng', 'error');
    });
}
</script>

<?php 
$stmt_reviews->close();
$conn->close();
include 'includes/footer.php'; 
?>
