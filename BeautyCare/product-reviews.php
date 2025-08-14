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

// Lấy tên sản phẩm để hiển thị trên tiêu đề trang
$sql_product_name = "SELECT ten_san_pham FROM san_pham WHERE id = ? LIMIT 1";
$stmt_product_name = $conn->prepare($sql_product_name);
$stmt_product_name->bind_param("i", $product_id);
$stmt_product_name->execute();
$product_name_result = $stmt_product_name->get_result();
$product_name = $product_name_result->fetch_assoc()['ten_san_pham'] ?? 'Sản phẩm không tồn tại';
$stmt_product_name->close();

// Chuẩn bị và thực thi câu lệnh SQL để lấy TẤT CẢ danh sách đánh giá
$sql_reviews = "SELECT dg.*, u.fullname 
                FROM danh_gia dg
                LEFT JOIN users u ON dg.user_id = u.id
                WHERE dg.san_pham_id = ?
                ORDER BY dg.ngay_danh_gia DESC";
$stmt_reviews = $conn->prepare($sql_reviews);

if ($stmt_reviews === false) {
    die("Lỗi chuẩn bị câu lệnh SQL đánh giá: " . $conn->error);
}

$stmt_reviews->bind_param("i", $product_id);
$stmt_reviews->execute();
$reviews = $stmt_reviews->get_result();
?>

<style>
.reviews-full-page {
    margin-top: 20px;
    background: #fff;
    padding: 20px;
    border-radius: 6px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}
.review-item {
    border-bottom: 1px solid #eee;
    padding: 15px 0;
}
.review-item:last-child {
    border-bottom: none;
}
.review-item strong {
    font-size: 1.1em;
}
.review-item .rating {
    color: orange;
}
.review-item small {
    display: block;
    color: #888;
    margin-top: 5px;
}
.back-btn {
    display: inline-block;
    margin-bottom: 15px;
    padding: 8px 15px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}
.back-btn:hover {
    background-color: #0056b3;
}
</style>

<div class="container">
    <div class="reviews-full-page">
        <a href="product-detail.php?id=<?php echo $product_id; ?>" class="back-btn">← Quay lại</a>
        <h2>Tất cả đánh giá cho sản phẩm: "<?php echo htmlspecialchars($product_name); ?>"</h2>
        <hr>
        <?php
        if ($reviews->num_rows > 0) {
            while ($rev = $reviews->fetch_assoc()) {
                echo '<div class="review-item">';
                echo '<strong>' . htmlspecialchars($rev['fullname'] ?? 'Người dùng ẩn danh') . '</strong> ';
                echo '<span class="rating">' . str_repeat('⭐', intval($rev['so_sao']));
                echo '</span>';
                echo '<br>' . nl2br(htmlspecialchars($rev['binh_luan']));
                echo '<br><small>Đăng vào: ' . $rev['ngay_danh_gia'] . '</small>';
                echo '</div>';
            }
        } else {
            echo "<p>Sản phẩm này chưa có đánh giá nào.</p>";
        }
        ?>
    </div>
</div>

<?php 
$stmt_reviews->close();
$conn->close();
include 'includes/footer.php'; 
?>