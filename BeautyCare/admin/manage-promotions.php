<?php
include '../php/database.php';
include 'header.php';


// Xử lý thêm/sửa/xóa khuyến mãi
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['add_promo'])) {
        // Xử lý thêm khuyến mãi mới
        $ten = $conn->real_escape_string($_POST['ten_chuong_trinh']);
        $mota = $conn->real_escape_string($_POST['mo_ta']);
        $batdau = $conn->real_escape_string($_POST['ngay_bat_dau']);
        $ketthuc = $conn->real_escape_string($_POST['ngay_ket_thuc']);
        $giamgia = floatval($_POST['muc_giam_gia']);
        $trangthai = isset($_POST['trang_thai']) ? 1 : 0;
        
        $sql = "INSERT INTO khuyen_mai (ten_chuong_trinh, mo_ta, ngay_bat_dau, ngay_ket_thuc, muc_giam_gia, trang_thai) 
                VALUES ('$ten', '$mota', '$batdau', '$ketthuc', $giamgia, $trangthai)";
        
        if($conn->query($sql)) {
            $promo_id = $conn->insert_id;
            
            // Xử lý thêm sản phẩm khuyến mãi
            if(isset($_POST['products']) && is_array($_POST['products'])) {
                foreach($_POST['products'] as $product_id) {
                    $product_id = intval($product_id);
                    $conn->query("INSERT INTO san_pham_khuyen_mai (khuyen_mai_id, san_pham_id) VALUES ($promo_id, $product_id)");
                }
            }
            
            $_SESSION['success'] = "Thêm chương trình khuyến mãi thành công!";
            header('Location: manage-promotions.php');
            exit;
        } else {
            $error = "Lỗi khi thêm khuyến mãi: " . $conn->error;
        }
    }
    elseif(isset($_POST['update_promo'])) {
        // Xử lý cập nhật khuyến mãi
        $id = intval($_POST['id']);
        $ten = $conn->real_escape_string($_POST['ten_chuong_trinh']);
        $mota = $conn->real_escape_string($_POST['mo_ta']);
        $batdau = $conn->real_escape_string($_POST['ngay_bat_dau']);
        $ketthuc = $conn->real_escape_string($_POST['ngay_ket_thuc']);
        $giamgia = floatval($_POST['muc_giam_gia']);
        $trangthai = isset($_POST['trang_thai']) ? 1 : 0;
        
        $sql = "UPDATE khuyen_mai SET 
                ten_chuong_trinh = '$ten',
                mo_ta = '$mota',
                ngay_bat_dau = '$batdau',
                ngay_ket_thuc = '$ketthuc',
                muc_giam_gia = $giamgia,
                trang_thai = $trangthai
                WHERE id = $id";
        
        if($conn->query($sql)) {
            // Xóa các sản phẩm khuyến mãi cũ
            $conn->query("DELETE FROM san_pham_khuyen_mai WHERE khuyen_mai_id = $id");
            
            // Thêm lại sản phẩm khuyến mãi mới
            if(isset($_POST['products']) && is_array($_POST['products'])) {
                foreach($_POST['products'] as $product_id) {
                    $product_id = intval($product_id);
                    $conn->query("INSERT INTO san_pham_khuyen_mai (khuyen_mai_id, san_pham_id) VALUES ($id, $product_id)");
                }
            }
            
            $_SESSION['success'] = "Cập nhật chương trình khuyến mãi thành công!";
            header('Location: manage-promotions.php');
            exit;
        } else {
            $error = "Lỗi khi cập nhật khuyến mãi: " . $conn->error;
        }
    }
    elseif(isset($_POST['delete_promo'])) {
        // Xử lý xóa khuyến mãi
        $id = intval($_POST['id']);
        
        // Xóa các sản phẩm khuyến mãi trước
        $conn->query("DELETE FROM san_pham_khuyen_mai WHERE khuyen_mai_id = $id");
        
        // Sau đó xóa khuyến mãi
        if($conn->query("DELETE FROM khuyen_mai WHERE id = $id")) {
            $_SESSION['success'] = "Xóa chương trình khuyến mãi thành công!";
            header('Location: manage-promotions.php');
            exit;
        } else {
            $error = "Lỗi khi xóa khuyến mãi: " . $conn->error;
        }
    }
}

// Lấy danh sách khuyến mãi
$promotions = $conn->query("SELECT * FROM khuyen_mai ORDER BY ngay_bat_dau DESC");

// Lấy danh sách sản phẩm cho select box
$products = $conn->query("SELECT id, ten_san_pham FROM san_pham WHERE is_available = 1 ORDER BY ten_san_pham");

// Lấy khuyến mãi đang chỉnh sửa
$edit_promo = null;
if(isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_promo = $conn->query("SELECT * FROM khuyen_mai WHERE id = $edit_id")->fetch_assoc();
    
    // Lấy sản phẩm của khuyến mãi này
    if($edit_promo) {
        $promo_products = $conn->query("SELECT san_pham_id FROM san_pham_khuyen_mai WHERE khuyen_mai_id = $edit_id");
        $selected_products = [];
        while($row = $promo_products->fetch_assoc()) {
            $selected_products[] = $row['san_pham_id'];
        }
        $edit_promo['products'] = $selected_products;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khuyến mãi - BeautyCare Admin</title>
    <link rel="stylesheet" href="assets/css/promo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .promo-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .promo-form h3 {
            margin-top: 0;
            color: var(--primary);
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
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .product-select {
            height: 200px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            overflow-y: auto;
        }
        .product-select option {
            padding: 8px;
            margin-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .promo-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .promo-table th, .promo-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .promo-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .promo-table tr:hover {
            background-color: #f5f5f5;
        }
        .badge {
            display: inline-block;
            padding: 3px 7px;
            font-size: 12px;
            font-weight: 600;
            line-height: 1;
            color: white;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            border-radius: 10px;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-secondary {
            background-color: #6c757d;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .action-btns a {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <main class="container">
        <section class="dashboard">
            <div class="dashboard-header">
                <h2>Quản lý chương trình khuyến mãi</h2>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <!-- Form thêm/sửa khuyến mãi -->
            <div class="promo-form">
                <h3><?php echo $edit_promo ? 'Chỉnh sửa' : 'Thêm mới'; ?> chương trình khuyến mãi</h3>
                <form method="POST">
                    <?php if($edit_promo): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_promo['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="ten_chuong_trinh">Tên chương trình</label>
                        <input type="text" class="form-control" id="ten_chuong_trinh" name="ten_chuong_trinh" 
                               value="<?php echo $edit_promo ? htmlspecialchars($edit_promo['ten_chuong_trinh']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="mo_ta">Mô tả</label>
                        <textarea class="form-control" id="mo_ta" name="mo_ta" rows="3"><?php 
                            echo $edit_promo ? htmlspecialchars($edit_promo['mo_ta']) : ''; 
                        ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ngay_bat_dau">Ngày bắt đầu</label>
                                <input type="datetime-local" class="form-control" id="ngay_bat_dau" name="ngay_bat_dau" 
                                       value="<?php echo $edit_promo ? date('Y-m-d\TH:i', strtotime($edit_promo['ngay_bat_dau'])) : ''; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ngay_ket_thuc">Ngày kết thúc</label>
                                <input type="datetime-local" class="form-control" id="ngay_ket_thuc" name="ngay_ket_thuc" 
                                       value="<?php echo $edit_promo ? date('Y-m-d\TH:i', strtotime($edit_promo['ngay_ket_thuc'])) : ''; ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="muc_giam_gia">Mức giảm giá (%)</label>
                        <input type="number" class="form-control" id="muc_giam_gia" name="muc_giam_gia" min="1" max="100" step="0.1"
                               value="<?php echo $edit_promo ? $edit_promo['muc_giam_gia'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="trang_thai" value="1" <?php 
                                echo ($edit_promo && $edit_promo['trang_thai']) ? 'checked' : ''; 
                            ?>> Kích hoạt chương trình
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label>Sản phẩm áp dụng</label>
                        <select multiple class="product-select" name="products[]">
                            <?php while($product = $products->fetch_assoc()): ?>
                                <option value="<?php echo $product['id']; ?>" <?php 
                                    echo ($edit_promo && in_array($product['id'], $edit_promo['products'])) ? 'selected' : '';
                                ?>><?php echo htmlspecialchars($product['ten_san_pham']); ?></option>
                            <?php endwhile; ?>
                        </select>
                        <small class="text-muted">Nhấn Ctrl/Cmd để chọn nhiều sản phẩm</small>
                    </div>
                    
                    <div class="form-group">
                        <?php if($edit_promo): ?>
                            <button type="submit" name="update_promo" class="btn btn-primary">Cập nhật</button>
                            <a href="manage-promotions.php" class="btn btn-secondary">Hủy</a>
                        <?php else: ?>
                            <button type="submit" name="add_promo" class="btn btn-primary">Thêm khuyến mãi</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Danh sách khuyến mãi -->
            <h3>Danh sách chương trình khuyến mãi</h3>
            <table class="promo-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên chương trình</th>
                        <th>Thời gian</th>
                        <th>Giảm giá</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($promo = $promotions->fetch_assoc()): 
                        $current_time = time();
                        $start_time = strtotime($promo['ngay_bat_dau']);
                        $end_time = strtotime($promo['ngay_ket_thuc']);
                        $is_active = ($current_time >= $start_time && $current_time <= $end_time && $promo['trang_thai']);
                    ?>
                        <tr>
                            <td><?php echo $promo['id']; ?></td>
                            <td><?php echo htmlspecialchars($promo['ten_chuong_trinh']); ?></td>
                            <td>
                                <?php echo date('d/m/Y H:i', $start_time); ?> - 
                                <?php echo date('d/m/Y H:i', $end_time); ?>
                            </td>
                            <td><?php echo $promo['muc_giam_gia']; ?>%</td>
                            <td>
                                <?php if($is_active): ?>
                                    <span class="badge badge-success">Đang hoạt động</span>
                                <?php elseif($current_time < $start_time): ?>
                                    <span class="badge badge-warning">Sắp diễn ra</span>
                                <?php elseif($current_time > $end_time): ?>
                                    <span class="badge badge-secondary">Đã kết thúc</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Tạm ngưng</span>
                                <?php endif; ?>
                            </td>
                            <td class="action-btns">
                                <a href="manage-promotions.php?edit=<?php echo $promo['id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $promo['id']; ?>">
                                    <button type="submit" name="delete_promo" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa chương trình này?');">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>

<?php include 'footer.php'; ?>