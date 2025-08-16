<?php
include '../php/database.php';
include 'header.php';

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($product_id <= 0) {
    echo "<p style='color:red;'>ID sản phẩm không hợp lệ!</p>";
    exit();
}

// Lấy dữ liệu sản phẩm hiện tại
$stmt = $conn->prepare("SELECT ten_san_pham, thuong_hieu, phan_loai, mo_ta, thanh_phan, hinh_anh, han_su_dung, gia, is_available, ma_danh_muc 
                        FROM san_pham WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<p style='color:red;'>Sản phẩm không tồn tại!</p>";
    exit();
}

// Xử lý khi submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_san_pham  = trim($_POST['ten_san_pham']);
    $thuong_hieu   = trim($_POST['thuong_hieu']);
    $phan_loai     = trim($_POST['phan_loai']);
    $mo_ta         = trim($_POST['mo_ta']);
    $thanh_phan    = trim($_POST['thanh_phan']);
    $han_su_dung   = $_POST['han_su_dung'] ?: null;
    $gia           = floatval($_POST['gia']);
    $is_available  = isset($_POST['is_available']) ? 1 : 0;
    $ma_danh_muc   = intval($_POST['ma_danh_muc']);
    $hinh_anh      = $product['hinh_anh'];

    // Upload ảnh mới
    if (!empty($_FILES['hinh_anh']['name'])) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_ext = strtolower(pathinfo($_FILES['hinh_anh']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg','jpeg','png','gif'];
        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = uniqid("sp_", true) . '.' . $file_ext;
            if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target_dir . $new_file_name)) {
                $hinh_anh = "uploads/" . $new_file_name;
            }
        }
    }

    // Cập nhật sản phẩm
    $stmt = $conn->prepare("UPDATE san_pham 
                             SET ten_san_pham=?, thuong_hieu=?, phan_loai=?, mo_ta=?, thanh_phan=?, hinh_anh=?, han_su_dung=?, gia=?, is_available=?, ma_danh_muc=? 
                             WHERE id=?");
    $stmt->bind_param("sssssssdisi", $ten_san_pham, $thuong_hieu, $phan_loai, $mo_ta, $thanh_phan, $hinh_anh, $han_su_dung, $gia, $is_available, $ma_danh_muc, $product_id);

    if ($stmt->execute()) {
        header("Location: manage-products.php");
        exit();
    } else {
        echo "<p style='color:red;'>Lỗi: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa sản phẩm | BeautyCare Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        .form-wrapper {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .form-group { margin-bottom: 15px; }
        .form-group label { font-weight: 600; display: block; margin-bottom: 5px; }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;
        }
        .toggle-switch {
            position: relative; display: inline-block; width: 50px; height: 24px;
        }
        .toggle-switch input { display: none; }
        .slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ccc; transition: .4s; border-radius: 24px;
        }
        .slider:before {
            position: absolute; content: "";
            height: 18px; width: 18px;
            left: 3px; bottom: 3px;
            background-color: white; transition: .4s; border-radius: 50%;
        }
        input:checked + .slider { background-color: #4CAF50; }
        input:checked + .slider:before { transform: translateX(26px); }
        .form-actions { margin-top: 20px; }
        .btn {
            display: inline-block; padding: 8px 15px;
            border-radius: 5px; text-decoration: none; cursor: pointer;
        }
        .btn-primary { background: #4CAF50; color: white; }
        .btn-secondary { background: #ccc; color: black; }
    </style>
</head>
<body>



    <div class="form-wrapper">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Tên sản phẩm <span style="color:red">*</span></label>
                <input type="text" name="ten_san_pham" value="<?= htmlspecialchars($product['ten_san_pham']) ?>" required>
            </div>

            <div class="form-group">
                <label>Thương hiệu</label>
                <input type="text" name="thuong_hieu" value="<?= htmlspecialchars($product['thuong_hieu']) ?>">
            </div>

            <div class="form-group">
                <label>Phân loại</label>
                <input type="text" name="phan_loai" value="<?= htmlspecialchars($product['phan_loai']) ?>">
            </div>

            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="mo_ta" rows="3"><?= htmlspecialchars($product['mo_ta']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Thành phần</label>
                <textarea name="thanh_phan" rows="3"><?= htmlspecialchars($product['thanh_phan']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Hạn sử dụng</label>
                <input type="date" name="han_su_dung" value="<?= $product['han_su_dung'] ?>">
            </div>

            <div class="form-group">
                <label>Giá (VNĐ) <span style="color:red">*</span></label>
                <input type="number" name="gia" step="1000" value="<?= $product['gia'] ?>" required>
            </div>

            <div class="form-group">
                <label>Còn hàng</label><br>
                <label class="toggle-switch">
                    <input type="checkbox" name="is_available" <?= $product['is_available'] ? 'checked' : '' ?>>
                    <span class="slider"></span>
                </label>
            </div>

            <div class="form-group">
                <label>Danh mục <span style="color:red">*</span></label>
                <select name="ma_danh_muc" required>
                    <?php
                    $cat_result = $conn->query("SELECT ma_danh_muc, ten_danh_muc FROM danh_muc");
                    while ($cat = $cat_result->fetch_assoc()) {
                        $selected = ($cat['ma_danh_muc'] == $product['ma_danh_muc']) ? 'selected' : '';
                        echo "<option value='{$cat['ma_danh_muc']}' $selected>{$cat['ten_danh_muc']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Ảnh sản phẩm</label>
                <?php if ($product['hinh_anh']): ?>
                    <div style="margin-bottom:5px;">
                        <img src="../<?= $product['hinh_anh'] ?>" alt="Ảnh sản phẩm" style="max-width:150px;">
                    </div>
                <?php endif; ?>
                <input type="file" name="hinh_anh" accept="image/*">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu thay đổi</button>
                <a href="manage-products.php" class="btn btn-secondary"><i class="fas fa-times"></i> Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
