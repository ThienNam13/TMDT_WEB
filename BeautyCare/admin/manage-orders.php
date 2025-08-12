<?php
include '../php/database.php';
include 'header.php';

// Lấy danh sách đơn hàng
$sql = "SELECT 
            o.id, 
            o.ma_don, 
            o.ho_ten AS customer_name, 
            o.sdt AS customer_phone, 
            o.thoi_gian_dat AS order_date, 
            o.tong_tien AS total_amount, 
            o.trang_thai AS status
        FROM orders o
        ORDER BY o.thoi_gian_dat DESC 
        LIMIT 50";

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
    <title>Quản lý đơn hàng | BeautyCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/orders.css">
</head>
<body>
<main class="container">
    <section class="dashboard">
        <div class="dashboard-header">
            <h2><i class="fas fa-shopping-cart"></i> Quản lý đơn hàng</h2>
        </div>

        <div class="orders-toolbar">
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm đơn hàng...">
                <button><i class="fas fa-search"></i></button>
            </div>
            <div class="action-buttons">
                <a href="#" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm đơn hàng
                </a>
                <a href="export-orders.php" class="btn btn-secondary">
                    <i class="fas fa-file-export"></i> Xuất Excel
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['ma_don']) ?></td>
                                        <td>
                                            <div class="customer-info">
                                                <strong><?= htmlspecialchars($row['customer_name']) ?></strong>
                                                <small><?= htmlspecialchars($row['customer_phone']) ?></small>
                                            </div>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($row['order_date'])) ?></td>
                                        <td><?= number_format($row['total_amount'], 0, ',', '.') ?>₫</td>
                                        <td>
                                            <span class="status-badge <?= strtolower(str_replace(' ', '-', $row['status'])) ?>">
                                                <?= htmlspecialchars($row['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="order_detail.php?id=<?= $row['id'] ?>" class="btn-icon" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="edit_order.php?id=<?= $row['id'] ?>" class="btn-icon" title="Sửa">
                                                    <i class="fas fa-edit"></i>
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
                        <i class="fas fa-shopping-cart fa-4x"></i>
                        <h3>Không có đơn hàng nào</h3>
                        <p>Hiện tại hệ thống chưa có đơn hàng nào được tạo</p>
                        <a href="#" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tạo đơn hàng mới
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<script>
    // Xác nhận trước khi xóa (nếu có chức năng xóa)
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')) {
                e.preventDefault();
            }
        });
    });
</script>
</body>
</html>

<?php include 'footer.php'; ?>