<?php
include '../php/database.php';
include 'header.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
} else {
    header("Location: manage-inventory.php");
    exit();
}

// Lấy thông tin kho + tên sản phẩm
$current = $conn->query("
    SELECT kh.*, sp.ten_san_pham 
    FROM kho_hang kh
    LEFT JOIN san_pham sp ON kh.san_pham_id = sp.id
    WHERE kh.id = $id
");

if ($current->num_rows === 0) {
    header("Location: manage-inventory.php");
    exit();
}
$current = $current->fetch_assoc();

// Xử lý POST nhập/xuất kho
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $quantity = (int)$_POST['quantity'];
    $note = trim($_POST['note']);

    if ($quantity <= 0) {
        $error = "Số lượng phải lớn hơn 0";
    } else {
        $new_quantity = $type === 'import' 
            ? $current['so_luong_ton'] + $quantity 
            : $current['so_luong_ton'] - $quantity;

        if ($new_quantity < 0) {
            $error = "Số lượng xuất vượt quá tồn kho";
        } else {
            $conn->begin_transaction();
            try {
                $conn->query("UPDATE kho_hang SET so_luong_ton = $new_quantity WHERE id = $id");

                $conn->query("CREATE TABLE IF NOT EXISTS kho_log (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    kho_id INT NOT NULL,
                    loai ENUM('import', 'export') NOT NULL,
                    so_luong INT NOT NULL,
                    ghi_chu TEXT,
                    thoi_gian TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )");

                $stmt = $conn->prepare("INSERT INTO kho_log (kho_id, loai, so_luong, ghi_chu) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isis", $id, $type, $quantity, $note);
                $stmt->execute();

                $conn->commit();
                header("Location: manage-inventory.php?success=1");
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Lỗi hệ thống: " . $e->getMessage();
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Nhập/Xuất kho | BeautyCare Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard.css">
<link rel="stylesheet" href="assets/css/inventory.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-primary {
            background-color: #6b21a8;
            color: white;
        }
        .btn-back {
            background-color: #64748b;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-danger {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }
        .radio-group {
            display: flex;
            gap: 20px;
            margin: 10px 0;
        }
        .radio-option {
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>
<body>
<main class="container">
    <section class="dashboard">
        <div class="dashboard-header">
            <h2><i class="fas fa-exchange-alt"></i> Nhập/Xuất kho</h2>
            <a href="manage-inventory.php" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="form-container">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Sản phẩm</label>
                    <input type="text" class="form-control" 
                           value="<?= htmlspecialchars($current['ten_san_pham'] ?? '') ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Số lượng hiện tại</label>
                    <input type="text" class="form-control" 
                           value="<?= htmlspecialchars($current['so_luong_ton'] ?? 0) ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Loại giao dịch</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="type" value="import" checked> Nhập kho
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="type" value="export"> Xuất kho
                        </label>
                    </div>
                </div>
  <div class="form-container">
            <?php if ($error) ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php ?>

            <form method="POST">
                <div class="form-group">
                    <label>Sản phẩm</label>
                    <!-- Hiển thị tên sản phẩm từ kết quả truy vấn -->
                    <input type="text" class="form-control" 
                           value="<?= htmlspecialchars($current['ten_san_pham']) ?>" readonly>
                    <input type="hidden" name="san_pham_id" value="<?= $current['san_pham_id'] ?>">
                </div>

                <div class="form-group">
                    <label>Số lượng hiện tại</label>
                    <input type="text" class="form-control" 
                           value="<?= $current['so_luong_ton'] ?>" readonly>
                </div
                <div class="form-group">
                    <label>Số lượng</label>
                    <input type="number" name="quantity" min="1" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Ghi chú</label>
                    <textarea name="note" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Xác nhận
                </button>
            </form>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>