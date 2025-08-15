<?php
session_start();
include 'php/database.php';
include 'includes/header.php';
include 'includes/navbar.php';

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($product_id <= 0) {
    echo "<div class='container'><p>Sản phẩm không hợp lệ.</p></div>";
    include 'includes/footer.php';
    exit;
}

// Lấy tên sản phẩm
$sql_product_name = "SELECT ten_san_pham FROM san_pham WHERE id = ? LIMIT 1";
$stmt_product_name = $conn->prepare($sql_product_name);
$stmt_product_name->bind_param("i", $product_id);
$stmt_product_name->execute();
$result_product = $stmt_product_name->get_result();
$product_name = $result_product->fetch_assoc()['ten_san_pham'] ?? 'Sản phẩm không tồn tại';
$stmt_product_name->close();

// Lấy danh sách đánh giá
$sql_reviews = "SELECT dg.*, u.fullname 
                FROM danh_gia dg
                LEFT JOIN users u ON dg.user_id = u.id
                WHERE dg.san_pham_id = ?
                ORDER BY dg.ngay_danh_gia DESC";
$stmt_reviews = $conn->prepare($sql_reviews);
$stmt_reviews->bind_param("i", $product_id);
$stmt_reviews->execute();
$reviews = $stmt_reviews->get_result();
?>

<style>
.reviews-full-page {
    margin: 20px auto;
    max-width: 800px;
    font-family: 'Poppins', sans-serif;
}
.reviews-full-page .back-btn {
    display: inline-block;
    margin-bottom: 15px;
    padding: 8px 15px;
    background-color: #6a4c93;
    color: #fff;
    text-decoration: none;
    border-radius: 20px;
    transition: background 0.3s;
}
.reviews-full-page .back-btn:hover {
    background-color: #b5838d;
}
.reviews-full-page h2 {
    margin-bottom: 15px;
    color: #333;
}
.reviews-full-page .review-item {
    background: #fff;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.reviews-full-page .review-item strong {
    font-size: 1.1em;
    color: #444;
}
.reviews-full-page .rating {
    color: orange;
    margin-left: 5px;
}
.reviews-full-page small {
    display: block;
    color: #888;
    margin-top: 5px;
}
.reviews-full-page .review-image {
    margin-top: 10px;
    max-width: 150px;
    border-radius: 6px;
    border: 1px solid #ddd;
}
</style>

<div class="container reviews-full-page">
    <a href="product-detail.php?id=<?php echo $product_id; ?>" class="back-btn">← Quay lại</a>
    <h2>Tất cả đánh giá cho sản phẩm: "<?php echo htmlspecialchars($product_name); ?>"</h2>

    <?php if ($reviews->num_rows > 0): ?>
        <?php while ($rev = $reviews->fetch_assoc()): ?>
            <div class="review-item">
                <strong><?php echo htmlspecialchars($rev['fullname'] ?? 'Người dùng ẩn danh'); ?></strong>
                <span class="rating"><?php echo str_repeat('⭐', intval($rev['so_sao'])); ?></span>
                <p><?php echo nl2br(htmlspecialchars($rev['binh_luan'])); ?></p>
                <?php if (!empty($rev['anh_minh_chung'])): ?>
                    <img src="<?php echo htmlspecialchars($rev['anh_minh_chung']); ?>" alt="Ảnh minh chứng" class="review-image">
                <?php endif; ?>
                <small>Đăng vào: <?php echo $rev['ngay_danh_gia']; ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Sản phẩm này chưa có đánh giá nào.</p>
    <?php endif; ?>
</div>

<?php 
$stmt_reviews->close();
$conn->close();
include 'includes/footer.php';
?>
