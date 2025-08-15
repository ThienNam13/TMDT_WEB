<?php
include '../php/database.php';
include 'header.php';

// 1. Lấy thống kê kho hàng trước khi xử lý tìm kiếm
$stats = [
    'total_products' => 0,
    'low_stock' => 0,
    'out_of_stock' => 0
];

try {
    $result = $conn->query("SELECT COUNT(*) as total FROM kho_hang");
    if ($result) $stats['total_products'] = $result->fetch_assoc()['total'];
    
    $result = $conn->query("SELECT COUNT(*) as total FROM kho_hang WHERE so_luong_ton <= 10");
    if ($result) $stats['low_stock'] = $result->fetch_assoc()['total'];
    
    $result = $conn->query("SELECT COUNT(*) as total FROM kho_hang WHERE so_luong_ton = 0");
    if ($result) $stats['out_of_stock'] = $result->fetch_assoc()['total'];
} catch (Exception $e) {
    error_log("Lỗi truy vấn thống kê: " . $e->getMessage());
}

// 2. Phân trang
$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

// 3. Xử lý tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = $search ? "WHERE sp.ten_san_pham LIKE ?" : "";

// 4. Truy vấn dữ liệu với phân trang
$sql = "SELECT w.id, sp.ten_san_pham, w.vi_tri_kho, w.so_luong_ton, sp.hinh_anh
        FROM kho_hang w
        LEFT JOIN san_pham sp ON w.san_pham_id = sp.id
        $where
        ORDER BY w.id DESC
        LIMIT ?, ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Lỗi truy vấn: " . $conn->error);
}

if ($search) {
    $param = "%$search%";
    $stmt->bind_param("sii", $param, $offset, $perPage);
} else {
    $stmt->bind_param("ii", $offset, $perPage);
}

$stmt->execute();
$result = $stmt->get_result();

// 5. Đếm tổng số bản ghi cho phân trang
$countSql = "SELECT COUNT(*) as total FROM kho_hang w LEFT JOIN san_pham sp ON w.san_pham_id = sp.id $where";
$countStmt = $conn->prepare($countSql);

if ($search) {
    $param = "%$search%";
    $countStmt->bind_param("s", $param);
}
$countStmt->execute();
$totalResult = $countStmt->get_result();
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $perPage);
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
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 8px 16px;
            margin: 0 4px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
        }
        .pagination a.active {
            background-color: #6b21a8;
            color: white;
            border: 1px solid #6b21a8;
        }
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>
<body>
<main class="container">
    <section class="dashboard">
        <div class="dashboard-header">
            <h2><i class="fas fa-warehouse"></i> Quản lý kho hàng</h2>
        </div>

        <!-- Thống kê nhanh -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-blue"><i class="fas fa-boxes"></i></div>
                <div class="stat-info">
                    <h3>Tổng sản phẩm</h3>
                    <p><?= htmlspecialchars($stats['total_products']) ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-orange"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-info">
                    <h3>Sắp hết hàng</h3>
                    <p><?= htmlspecialchars($stats['low_stock']) ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-red"><i class="fas fa-times-circle"></i></div>
                <div class="stat-info">
                    <h3>Hết hàng</h3>
                    <p><?= htmlspecialchars($stats['out_of_stock']) ?></p>
                </div>
            </div>
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
                                        <td><?= $i + $offset ?></td>
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
                                            <?= htmlspecialchars($row['so_luong_ton']) ?>
                                            <?php if ($row['so_luong_ton'] <= 10): ?>
                                                <span class="badge badge-warning">Sắp hết</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="sua_manage-inventory.php?id=<?= (int)$row['id'] ?>" 
                                                   class="btn-icon" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="xoa_manage-inventory.php?id=<?= (int)$row['id'] ?>" 
                                                   class="btn-icon btn-danger" 
                                                   title="Xóa"
                                                   onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi kho?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <a href="nhap_xuat_kho.php?id=<?= (int)$row['id'] ?>" 
                                                   class="btn-icon btn-success" 
                                                   title="Nhập/Xuất kho">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">&laquo;</a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" 
                               class="<?= $i == $page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">&raquo;</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

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
</body>
</html>