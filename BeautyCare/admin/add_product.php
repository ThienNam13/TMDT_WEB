<?php
// Kết nối CSDL
include '../php/database.php';
include 'header.php';

// Lấy danh sách kho
$query = "
    SELECT 
      p.*, 
      IFNULL(w.so_luong_ton, 0) AS so_luong_ton,
      w.vi_tri_kho
    FROM san_pham p
    LEFT JOIN kho_san_pham w ON p.id = w.san_pham_id
    ORDER BY p.id DESC
";


?>



<head>
    <meta charset="UTF-8">
    <title>Danh sách kho mỹ phẩm</title>
    <link rel="stylesheet" href="products.css">
    <link rel="stylesheet" href="dashboard.css">
</head>

<div class="main-content">
    <div class="container-fluid px-4 py-4">
        <h2>Danh sách kho mỹ phẩm</h2>
        <a href="dashboard.php" class="btn btn-primary mb-3">Thêm kho mới</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID kho</th>
                    <th>Tên kho</th>
                    <th>Địa chỉ</th>
                    <th>Sức chứa</th>
                    <th>Showroom</th>
                    <th>Quản lý</th>
                    <th>Hiện tại trong kho</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['warehouse_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo $row['capacity']; ?></td>
                            <td><?php echo htmlspecialchars($row['showroom_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['manager_name']); ?></td>
                            <td><?php echo $row['current_count']; ?></td>
                            <td>
                                <a href="edit_warehouse.php?id=<?php echo $row['warehouse_id']; ?>" class="btn btn-sm btn-warning">Sửa</a>
                                <a href="delete_warehouse.php?id=<?php echo $row['warehouse_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa kho này?');">Xóa</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center">Chưa có kho mỹ phẩm nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
