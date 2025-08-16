<?php
include '../php/database.php';
include 'header.php';

// Lấy danh sách sản phẩm mới nhất
$sql = "SELECT * FROM san_pham ORDER BY id DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Lỗi truy vấn: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm | BeautyCare Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/products.css">
    <style>
        .status-available {
            color: green;
            font-weight: bold;
        }
        .status-unavailable {
            color: red;
            font-weight: bold;
        }
        .product-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

    <div class="products-container">
        <div class="products-header">
            <h2><i class="fas fa-box-open"></i> Danh sách sản phẩm</h2>
            <div class="action-buttons">
                <a href="add_product.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm sản phẩm
                </a>
            </div>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Mô tả</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php if (!empty($row['hinh_anh'])): ?>
                                    <img src="../assets/img/products/<?php echo htmlspecialchars($row['hinh_anh']); ?>?v=<?php echo time(); ?>" 
                                         class="product-thumbnail"
                                         alt="<?php echo htmlspecialchars($row['ten_san_pham']); ?>">
                                <?php else: ?>
                                    <div class="no-image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['ten_san_pham']); ?></strong>
                                <div class="product-desc">
                                    <?php echo htmlspecialchars(substr($row['mo_ta'], 0, 100)); ?>...
                                </div>
                            </td>
                            <td class="product-price">
                                <?php echo number_format($row['gia'], 0, ',', '.'); ?>₫
                            </td>
                            <td>
                                <?php 
                                if ((int)$row['is_available'] === 1) {
                                    echo '<span class="status-available">Còn hàng</span>';
                                } else {
                                    echo '<span class="status-unavailable">Hết hàng</span>';
                                }
                                ?>
                            </td>
                            <td class="action-buttons">
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" 
                                   class="btn btn-sm btn-edit" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_product.php?id=<?php echo $row['id']; ?>" 
                                   class="btn btn-sm btn-delete" 
                                   title="Xóa"
                                   onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-box-open fa-3x"></i>
                <h3>Không có sản phẩm nào</h3>
                <a href="add_product.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm sản phẩm mới
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
