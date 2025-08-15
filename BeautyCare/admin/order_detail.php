<?php
include '../php/database.php';
include 'header.php';
$sql = "SELECT o.*, p.phuong_thuc, p.trang_thai, p.ngay_thanh_toan
        FROM orders o
        LEFT JOIN payment p ON o.id = p.order_id
        WHERE o.id = ?";


// Lấy order_id từ URL
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($order_id <= 0) {
    die("ID đơn hàng không hợp lệ!");
}

// Lấy thông tin đơn hàng
$order_sql = "SELECT o.*, pm.ten_phuong_thuc AS payment_method
              FROM orders o
              LEFT JOIN payment_methods pm ON o.payment_method_id = pm.id
              WHERE o.id = ?";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Không tìm thấy đơn hàng!");
}

// Lấy danh sách sản phẩm trong đơn hàng
// Thay đổi câu truy vấn items để lấy cả hình ảnh
$item_sql = "SELECT oi.*, p.ma_sp, p.ten_sp, p.gia, p.hinh_anh
             FROM order_items oi
             LEFT JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = ?";
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng #<?= $order['ma_don'] ?> | BeautyCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        .order-detail-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .order-title {
            font-size: 24px;
            color: #333;
        }
        
        .order-status {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            background: #f1f1f1;
        }
        
        .order-status.completed {
            background: #4caf50;
            color: white;
        }
        
        .order-status.pending {
            background: #ff9800;
            color: white;
        }
        
        .order-status.cancelled {
            background: #f44336;
            color: white;
        }
        
        .order-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .order-info-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .order-info-card h3 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            color: #6c63ff;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: bold;
            min-width: 120px;
            color: #666;
        }
        
        .info-value {
            flex: 1;
        }
        
        .order-items {
            margin-bottom: 30px;
        }
        
        .order-items table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .order-items th {
            background: #f5f5f5;
            padding: 12px 15px;
            text-align: left;
        }
        
        .order-items td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .product-info {
            display: flex;
            align-items: center;
        }
        
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }
        
        .order-summary {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .summary-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .summary-total {
            font-size: 18px;
            font-weight: bold;
            border-top: 1px solid #eee;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .order-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 30px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
        
        .btn-primary {
            background: #6c63ff;
            color: white;
        }
        
        .btn-secondary {
            background: #f1f1f1;
            color: #333;
        }
        
        .btn-danger {
            background: #f44336;
            color: white;
        }
        
        @media (max-width: 768px) {
            .order-info-grid, .order-summary {
                grid-template-columns: 1fr;
            }
            
            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
<main class="container">
    <section class="dashboard">
        <div class="order-detail-container">
            <div class="order-header">
                <h1 class="order-title">Đơn hàng #<?= htmlspecialchars($order['ma_don']) ?></h1>
                <span class="order-status <?= strtolower($order['trang_thai']) ?>">
                    <?= htmlspecialchars($order['trang_thai']) ?>
                </span>
            </div>
            
            <div class="order-info-grid">
                <div class="order-info-card">
                    <h3>Thông tin khách hàng</h3>
                    <div class="info-row">
                        <div class="info-label">Họ tên:</div>
                        <div class="info-value"><?= htmlspecialchars($order['ho_ten']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Số điện thoại:</div>
                        <div class="info-value"><?= htmlspecialchars($order['sdt']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value"><?= htmlspecialchars($order['email'] ?? 'N/A') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Địa chỉ:</div>
                        <div class="info-value"><?= htmlspecialchars($order['dia_chi'] ?? 'N/A') ?></div>
                    </div>
                </div>
                
                <div class="order-info-card">
                    <h3>Thông tin đơn hàng</h3>
                    <div class="info-row">
                        <div class="info-label">Ngày đặt:</div>
                        <div class="info-value"><?= date('d/m/Y H:i', strtotime($order['thoi_gian_dat'])) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phương thức TT:</div>
                        <div class="info-value"><?= htmlspecialchars($order['payment_method'] ?? 'N/A') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Ghi chú:</div>
                        <div class="info-value"><?= htmlspecialchars($order['ghi_chu'] ?? 'Không có ghi chú') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nhân viên:</div>
                        <div class="info-value"><?= htmlspecialchars($order['staff_name'] ?? 'N/A') ?></div>
                    </div>
                </div>
            </div>
            
            <div class="order-items">
                <h3>Danh sách sản phẩm</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Mã SP</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $items->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <img src="../uploads/<?= htmlspecialchars($item['hinh_anh']) ?>" 
                                             alt="<?= htmlspecialchars($item['ten_san_pham']) ?>" 
                                             class="product-image">
                                        <div><?= htmlspecialchars($item['ten_san_pham']) ?></div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($item['ma_san_pham']) ?></td>
                                <td><?= number_format($item['don_gia'], 0, ',', '.') ?>₫</td>
                                <td><?= $item['so_luong'] ?></td>
                                <td><?= number_format($item['thanh_tien'], 0, ',', '.') ?>₫</td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="order-summary">
                <div class="summary-card">
                    <h3>Tóm tắt thanh toán</h3>
                    <div class="summary-row">
                        <span>Tạm tính:</span>
                        <span><?= number_format($order['tong_tien'] - $order['giam_gia'], 0, ',', '.') ?>₫</span>
                    </div>
                    <div class="summary-row">
                        <span>Giảm giá:</span>
                        <span>-<?= number_format($order['giam_gia'], 0, ',', '.') ?>₫</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Tổng cộng:</span>
                        <span><?= number_format($order['tong_tien'], 0, ',', '.') ?>₫</span>
                    </div>
                </div>
                
                <div class="summary-card">
                    <h3>Hành động</h3>
                    <p>Thay đổi trạng thái đơn hàng hoặc thực hiện các hành động khác</p>
                    
                    <div class="order-actions">
                        <a href="manage-orders.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <a href="edit_order.php?id=<?= $order_id ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        <?php if ($order['trang_thai'] != 'Đã hủy'): ?>
                            <a href="update_order_status.php?id=<?= $order_id ?>&status=Đã hủy" 
                               class="btn btn-danger" 
                               onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                <i class="fas fa-times"></i> Hủy đơn
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
</body>
</html>