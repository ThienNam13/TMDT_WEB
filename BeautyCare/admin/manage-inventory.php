<?php
include '../php/database.php';
include 'header.php';

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = $search ? "WHERE sp.ten_san_pham LIKE ?" : "";

// Truy vấn dữ liệu
$sql = "SELECT w.id, sp.ten_san_pham, w.vi_tri_kho, w.so_luong_ton, sp.hinh_anh
        FROM kho_hang w
        LEFT JOIN san_pham sp ON w.san_pham_id = sp.id
        $where
        ORDER BY w.id DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Lỗi truy vấn: " . $conn->error);
}

if ($search) {
    $param = "%$search%";
    $stmt->bind_param("s", $param);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý kho hàng | BeautyCare Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/inventory.css">
</head>
<body>
<main class="container">
    <section class="dashboard">
        <div class="dashboard-header">
            <h2><i class="fas fa-warehouse"></i> Quản lý kho hàng</h2>
        </div>

        <div class="inventory-toolbar">
            <form method="GET" class="search-form">
                <div class="search-box">
                    <input type="text" name="search" placeholder="Tìm theo tên sản phẩm..." 
                           value="<?= htmlspecialchars($search) ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <div class="action-buttons">
                <a href="them_manage-inventory.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm kho
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="inventory-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Vị trí kho</th>
                                    <th>Số lượng tồn</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td>
                                            <div class="product-info">
                                                <?php if (!empty($row['hinh_anh'])): ?>
                                                    <img src="<?= htmlspecialchars($row['hinh_anh']) ?>" 
                                                         class="product-thumb" 
                                                         alt="<?= htmlspecialchars($row['ten_san_pham']) ?>">
                                                <?php endif; ?>
                                                <span><?= htmlspecialchars($row['ten_san_pham']) ?></span>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($row['vi_tri_kho']) ?></td>
                                        <td class="<?= $row['so_luong_ton'] <= 10 ? 'text-danger' : '' ?>">
                                            <?= $row['so_luong_ton'] ?>
                                            <?php if ($row['so_luong_ton'] <= 10): ?>
                                                <span class="badge badge-warning">Sắp hết</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="sua_manage-inventory.php?id=<?= $row['id'] ?>" 
                                                   class="btn-icon" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="xoa_manage-inventory.php?id=<?= $row['id'] ?>" 
                                                   class="btn-icon btn-danger" 
                                                   title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-box-open fa-4x"></i>
                        <h3>Không tìm thấy sản phẩm nào trong kho</h3>
                        <a href="them_manage-inventory.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm sản phẩm vào kho
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>

<script>
    document.querySelectorAll('.btn-danger').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi kho?')) {
                e.preventDefault();
            }
        });
    });
</script>
</body>
</html>