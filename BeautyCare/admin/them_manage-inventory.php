<?php
include '../php/database.php';
include 'header.php';

// Lấy danh sách sản phẩm để chọn
$products = $conn->query("SELECT id, ten_san_pham FROM san_pham ORDER BY ten_san_pham");

// Xử lý form thêm mới
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $san_pham_id = (int)$_POST['san_pham_id'];
    $vi_tri_kho = trim($_POST['vi_tri_kho']);
    $so_luong_ton = (int)$_POST['so_luong_ton'];
    
    // Kiểm tra sản phẩm đã tồn tại trong kho chưa
    $check = $conn->prepare("SELECT id FROM kho_hang WHERE san_pham_id = ?");
    $check->bind_param("i", $san_pham_id);
    $check->execute();
    
    if ($check->get_result()->num_rows > 0) {
        $error = "Sản phẩm này đã có trong kho";
    } else {
        $stmt = $conn->prepare("INSERT INTO kho_hang (san_pham_id, vi_tri_kho, so_luong_ton) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $san_pham_id, $vi_tri_kho, $so_luong_ton);
        
        if ($stmt->execute()) {
            header("Location: manage-inventory.php?success=1");
            exit();
        } else {
            $error = "Lỗi khi thêm vào kho: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm vào kho | BeautyCare Admin</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
<link rel="stylesheet" href="assets/css/inventory.css">
</head>
<body>
<main class="container">
    <section class="dashboard">
        <div class="dashboard-header">
            <h2><i class="fas fa-plus-circle"></i> Thêm sản phẩm vào kho</h2>
            <a href="manage-inventory.php" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Sản phẩm</label>
                        <select name="san_pham_id" class="form-control" required>
                            <option value="">-- Chọn sản phẩm --</option>
                            <?php while ($product = $products->fetch_assoc()): ?>
                                <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['ten_san_pham']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Vị trí kho</label>
                        <input type="text" name="vi_tri_kho" class="form-control" required 
                               placeholder="VD: Kệ A1, Tầng 2...">
                    </div>

                    <div class="form-group">
                        <label>Số lượng tồn</label>
                        <input type="number" name="so_luong_ton" class="form-control" 
                               min="0" value="0" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu lại
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>