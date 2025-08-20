<?php
include '../php/database.php';
include 'header.php';

// Xử lý ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage-inventory.php?error=invalid_id");
    exit();
}
$id = (int)$_GET['id'];

// Lấy thông tin hiện tại với prepared statement để tránh SQL injection
$stmt = $conn->prepare("SELECT * FROM kho_hang WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage-inventory.php?error=not_found");
    exit();
}
$current = $result->fetch_assoc();
$san_pham_id = $current['san_pham_id'];

// Xử lý form cập nhật
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vi_tri_kho = trim($_POST['vi_tri_kho']);
    $so_luong_ton = (int)$_POST['so_luong_ton'];
    
    if (empty($vi_tri_kho)) {
        $error = "Vị trí kho không được để trống";
    } elseif ($so_luong_ton < 0) {
        $error = "Số lượng tồn không được âm";
    } else {
        $stmt = $conn->prepare("UPDATE kho_hang SET vi_tri_kho = ?, so_luong_ton = ? WHERE id = ?");
        $stmt->bind_param("sii", $vi_tri_kho, $so_luong_ton, $id);
        if ($so_luong_ton <= 0) {
            $conn->query("UPDATE san_pham SET is_available = 0 WHERE id = $san_pham_id");
        } else {
            $conn->query("UPDATE san_pham SET is_available = 1 WHERE id = $san_pham_id");
        }

        if ($stmt->execute()) {
            header("Location: manage-inventory.php?success=1");
            exit();
        } else {
            $error = "Lỗi khi cập nhật: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin kho | BeautyCare Admin</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/inventory.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .edit-form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: #6c63ff;
            outline: none;
            box-shadow: 0 0 0 2px rgba(108, 99, 255, 0.2);
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: #6c63ff;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #5a52d6;
        }
        
        .btn-back {
            background-color: #f1f1f1;
            color: #333;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-back:hover {
            background-color: #e1e1e1;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-danger {
            background-color: #ffebee;
            color: #d32f2f;
            border-left: 4px solid #d32f2f;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 30px;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .dashboard-header h2 {
            color: #333;
            font-size: 24px;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        @media (max-width: 768px) {
            .edit-form-container {
                padding: 15px;
            }
            
            .dashboard-header {
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
        <div class="dashboard-header">
            <h2><i class="fas fa-edit"></i> Sửa thông tin kho</h2>
            <a href="manage-inventory.php" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="edit-form-container">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="vi_tri_kho">Vị trí kho</label>
                    <input type="text" id="vi_tri_kho" name="vi_tri_kho" class="form-control" required 
                           value="<?= htmlspecialchars($current['vi_tri_kho']) ?>"
                           placeholder="VD: Kệ A1, Tầng 2...">
                </div>

                <div class="form-group">
                    <label for="so_luong_ton">Số lượng tồn</label>
                    <input type="number" id="so_luong_ton" name="so_luong_ton" class="form-control" 
                           min="0" value="<?= htmlspecialchars($current['so_luong_ton']) ?>" required>
                    <small class="text-muted">Nhập số lượng hiện có trong kho</small>
                </div>

                <div class="form-actions">
                    <a href="manage-inventory.php" class="btn btn-back">Hủy bỏ</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>