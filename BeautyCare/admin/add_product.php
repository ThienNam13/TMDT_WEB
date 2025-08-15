<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");


// Kết nối CSDL
require_once '../php/database.php';

// Nhận dữ liệu từ client
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra dữ liệu đầu vào
if (!isset($data['ten_san_pham']) || empty($data['ten_san_pham']) ||
    !isset($data['gia']) || empty($data['gia']) ||
    !isset($data['ma_danh_muc']) || empty($data['ma_danh_muc'])) {
    http_response_code(400);
    echo json_encode(["message" => "Thiếu thông tin bắt buộc"]);
    exit;
}

// Xử lý ảnh sản phẩm
$hinh_anh = null;
if (isset($_FILES['hinh_anh'])) {
    $uploadDir = '../uploads/';
    $fileName = uniqid() . '_' . basename($_FILES['hinh_anh']['name']);
    $targetFilePath = $uploadDir . $fileName;
    
    // Kiểm tra và upload file
    if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $targetFilePath)) {
        $hinh_anh = $fileName;
    }
}

// Chuẩn bị dữ liệu
$ten_san_pham = $conn->real_escape_string($data['ten_san_pham']);
$thuong_hieu = isset($data['thuong_hieu']) ? $conn->real_escape_string($data['thuong_hieu']) : null;
$phan_loai = isset($data['phan_loai']) ? $conn->real_escape_string($data['phan_loai']) : null;
$mo_ta = isset($data['mo_ta']) ? $conn->real_escape_string($data['mo_ta']) : null;
$thanh_phan = isset($data['thanh_phan']) ? $conn->real_escape_string($data['thanh_phan']) : null;
$gia = floatval($data['gia']);
$han_su_dung = isset($data['han_su_dung']) ? $conn->real_escape_string($data['han_su_dung']) : null;
$ma_danh_muc = intval($data['ma_danh_muc']);
$is_available = isset($data['is_available']) ? intval($data['is_available']) : 1;

// Tạo câu lệnh SQL
$sql = "INSERT INTO san_pham (
    ten_san_pham, 
    thuong_hieu, 
    phan_loai, 
    mo_ta, 
    thanh_phan, 
    hinh_anh, 
    han_su_dung, 
    gia, 
    is_available, 
    ma_danh_muc
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssssdii", 
    $ten_san_pham, 
    $thuong_hieu, 
    $phan_loai, 
    $mo_ta, 
    $thanh_phan, 
    $hinh_anh, 
    $han_su_dung, 
    $gia, 
    $is_available, 
    $ma_danh_muc
);

// Thực thi và trả về kết quả
if ($stmt->execute()) {
    $product_id = $stmt->insert_id;
    
    // Thêm vào kho hàng
    $sql_kho = "INSERT INTO kho_hang (san_pham_id, so_luong_ton) VALUES (?, 0)";
    $stmt_kho = $conn->prepare($sql_kho);
    $stmt_kho->bind_param("i", $product_id);
    $stmt_kho->execute();
    
    http_response_code(201);
    echo json_encode([
        "message" => "Thêm sản phẩm thành công",
        "product_id" => $product_id
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "message" => "Lỗi khi thêm sản phẩm",
        "error" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>