<?php
include '../php/database.php';
include 'header.php';

// Xử lý khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_don = $conn->real_escape_string(trim($_POST['ma_don']));
    $ho_ten = $conn->real_escape_string(trim($_POST['ho_ten']));
    $sdt = $conn->real_escape_string(trim($_POST['sdt']));
    $tong_tien = (float)$_POST['tong_tien'];
    $payment_method = $conn->real_escape_string(trim($_POST['payment_method']));
    $status = $conn->real_escape_string(trim($_POST['status']));
    $thoi_gian_dat = date('Y-m-d H:i:s');

    if (!empty($ma_don) && !empty($ho_ten) && !empty($sdt) && $tong_tien > 0) {
        $sql = "INSERT INTO orders (ma_don, ho_ten, sdt, tong_tien, hinh_thuc_thanh_toan, trang_thai, thoi_gian_dat) 
                VALUES ('$ma_don', '$ho_ten', '$sdt', $tong_tien, '$payment_method', '$status', '$thoi_gian_dat')";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: manage-orders.php?success=1");
            exit;
        } else {
            $error = "Lỗi khi thêm đơn hàng: " . $conn->error;
        }
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm đơn hàng | BeautyCare</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            max-width: 600px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: 500;
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-primary {
            background: #6c63ff;
            color: #fff;
        }
        .btn-secondary {
            background: #ddd;
        }
        .error {
            background: #f8d7da;
            padding: 10px;
            border-radius: 4px;
            color: #721c24;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<main class="container">
    <section class="dashboard">
        <div class="dashboard-header">
            <h2><i class="fas fa-plus"></i> Thêm đơn hàng</h2>
        </div>

        <div class="form-container">
            <?php if (!empty($error)): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="ma_don">Mã đơn</label>
                    <input type="text" id="ma_don" name="ma_don" required>
                </div>
                <div class="form-group">
                    <label for="ho_ten">Họ tên khách hàng</label>
                    <input type="text" id="ho_ten" name="ho_ten" required>
                </div>
                <div class="form-group">
                    <label for="sdt">Số điện thoại</label>
                    <input type="text" id="sdt" name="sdt" required>
                </div>
                <div class="form-group">
                    <label for="tong_tien">Tổng tiền (VNĐ)</label>
                    <input type="number" id="tong_tien" name="tong_tien" min="0" required>
                </div>
                <div class="form-group">
                    <label for="payment_method">Phương thức thanh toán</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="Tiền mặt">Tiền mặt</option>
                        <option value="Chuyển khoản">Chuyển khoản</option>
                        <option value="Thẻ">Thẻ</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Trạng thái</label>
                    <select id="status" name="status" required>
                        <option value="Chờ xác nhận">Chờ xác nhận</option>
                        <option value="Đang xử lý">Đang xử lý</option>
                        <option value="Đang giao hàng">Đang giao hàng</option>
                        <option value="Đã giao">Đã giao</option>
                        <option value="Đã hủy">Đã hủy</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Lưu đơn hàng</button>
                <a href="manage-orders.php" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </section>
</main>
</body>
</html>

<?php include 'footer.php'; ?>
