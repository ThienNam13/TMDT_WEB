<?php
include '../php/database.php';
include 'header.php';

// Xử lý xóa phản hồi
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM danh_gia WHERE id = $delete_id");
    $_SESSION['message'] = "Đã xóa phản hồi thành công!";
    header("Location: review-management.php");
    exit();
}

// Lấy danh sách phản hồi
$reviewsQuery = "
    SELECT d.*, u.fullname, u.email, s.ten_san_pham 
    FROM danh_gia d
    JOIN users u ON d.user_id = u.id
    JOIN san_pham s ON d.san_pham_id = s.id
    ORDER BY d.ngay_danh_gia DESC
";
$reviewsResult = $conn->query($reviewsQuery);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Phản hồi Khách hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        .small-header {
            padding: 10px 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .small-header .page-title {
            font-size: 18px;
            margin: 0;
            color: #333;
        }
        
        
        
        .review-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            padding: 20px;
            position: relative;
        }
        
        
        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .review-user {
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            color: #666;
        }
        
        .user-info h4 {
            margin: 0;
            font-size: 16px;
        }
        
        .user-info p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        
        .review-rating {
            display: flex;
            align-items: center;
        }
        
        .stars {
            color: #FFD700;
            margin-right: 10px;
        }
        
        .review-date {
            font-size: 13px;
            color: #888;
        }
        
        .review-product {
            font-size: 15px;
            color: #555;
            margin-bottom: 10px;
        }
        
        .review-product strong {
            color: #333;
        }
        
        .review-content {
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .review-image {
            max-width: 200px;
            max-height: 200px;
            border-radius: 5px;
            margin-top: 10px;
        }
        
        .review-actions {
            display: flex;
            justify-content: flex-end;
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            border: none;
            transition: all 0.3s;
        }
        
        .btn-delete {
            background-color: #ff4444;
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #cc0000;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #666;
        }
        
        .empty-state i {
            font-size: 50px;
            margin-bottom: 20px;
            color: #ddd;
        }
        
        .filter-section {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        
        .filter-section select, .filter-section input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .filter-section button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<main class="container">
    <div class="page-header">
        <h1 class="page-title">Quản lý Phản hồi Khách hàng</h1>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <div class="filter-section">
    <select name="rating_filter" id="rating_filter">
        <option value="">Tất cả đánh giá</option>
        <option value="5">5 sao</option>
        <option value="4">4 sao</option>
        <option value="3">3 sao</option>
        <option value="2">2 sao</option>
        <option value="1">1 sao</option>
    </select>
    <button type="button" id="apply-rating">Lọc</button>

    <input type="text" name="search" id="search" placeholder="Tìm kiếm...">
    <button type="button" id="apply-search">Tìm kiếm</button>
</div>


    <?php if ($reviewsResult && $reviewsResult->num_rows > 0): ?>
        <?php while ($review = $reviewsResult->fetch_assoc()): ?>
            <div class="review-card">
                <div class="review-header">
                    <div class="review-user">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-info">
                            <h4><?= htmlspecialchars($review['fullname']) ?></h4>
                            <p><?= htmlspecialchars($review['email']) ?></p>
                        </div>
                    </div>
                    <div class="review-rating">
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star<?= $i > $review['so_sao'] ? '-empty' : '' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <div class="review-date">
                            <?= date('d/m/Y H:i', strtotime($review['ngay_danh_gia'])) ?>
                        </div>
                    </div>
                </div>
                
                <div class="review-product">
                    <strong>Sản phẩm:</strong> <?= htmlspecialchars($review['ten_san_pham']) ?>
                </div>
                
                <div class="review-content">
                    <?= nl2br(htmlspecialchars($review['binh_luan'])) ?>
                </div>
                
                <?php if (!empty($review['anh_minh_chung'])): ?>
                    <img src="../<?= htmlspecialchars($review['anh_minh_chung']) ?>" alt="Ảnh minh chứng" class="review-image">
                <?php endif; ?>
                
                <div class="review-actions">
                    <button class="btn btn-delete" onclick="confirmDelete(<?= $review['id'] ?>)">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-comment-slash"></i>
            <h3>Chưa có phản hồi nào</h3>
            <p>Khách hàng chưa để lại đánh giá nào về sản phẩm.</p>
        </div>
    <?php endif; ?>
</main>

<script>
// lọc đánh giá
document.getElementById('apply-rating').addEventListener('click', function() {
    const rating = document.getElementById('rating_filter').value;
    window.location.href = `review-management.php?rating=${rating}`;
});

document.getElementById('apply-search').addEventListener('click', function() {
    const search = document.getElementById('search').value;
    window.location.href = `review-management.php?search=${encodeURIComponent(search)}`;
});
</script>

</script>
</body>
</html>

<?php include 'footer.php'; ?>