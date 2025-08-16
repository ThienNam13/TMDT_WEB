<?php
// Kết nối CSDL
include '../php/database.php';

// Kiểm tra có id không
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Thiếu ID sản phẩm.");
}

$id = intval($_GET['id']);

// Lấy thông tin sản phẩm trước khi xóa (để xóa cả ảnh nếu có)
$sql_get = "SELECT hinh_anh FROM san_pham WHERE id = ?";
$stmt_get = $conn->prepare($sql_get);
$stmt_get->bind_param("i", $id);
$stmt_get->execute();
$result = $stmt_get->get_result();

if ($result->num_rows == 0) {
    die("Sản phẩm không tồn tại.");
}

$product = $result->fetch_assoc();

// Xóa sản phẩm trong DB
$sql = "DELETE FROM san_pham WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Nếu sản phẩm có ảnh thì xóa file ảnh
    if (!empty($product['hinh_anh']) && file_exists("../" . $product['hinh_anh'])) {
        unlink("../" . $product['hinh_anh']);
    }

    // Quay lại trang quản lý sản phẩm
    header("Location: manage-products.php?msg=deleted");
    exit;
} else {
    echo "Lỗi khi xóa sản phẩm: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
