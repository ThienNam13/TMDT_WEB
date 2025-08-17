<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['quantity']));

    // Lấy sản phẩm từ DB
    $sql = "SELECT id, ten_san_pham, gia, hinh_anh FROM san_pham WHERE id = ? AND is_available = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['qty'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'ten_san_pham' => $product['ten_san_pham'],
                'gia' => $product['gia'],
                'hinh_anh' => $product['hinh_anh'],
                'qty' => $quantity
            ];
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Đã thêm sản phẩm vào giỏ hàng!'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Sản phẩm không tồn tại hoặc đã bị ẩn!'
        ]);
    }
}
