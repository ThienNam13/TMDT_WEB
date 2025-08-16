<?php
include 'database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
date_default_timezone_set('Asia/Ho_Chi_Minh');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để đặt hàng']);
    exit;
}

function generateOrderCode(mysqli $conn): string {
    for ($i = 0; $i < 5; $i++) {
        $code = 'BC' . date('ymdHis') . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        $stmt = $conn->prepare('SELECT 1 FROM orders WHERE ma_don = ? LIMIT 1');
        $stmt->bind_param('s', $code);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        if (!$exists) return $code;
        usleep(100000);
    }
    return 'BC' . date('ymdHis') . rand(10000, 99999);
}

// Thu thập dữ liệu
$userId   = (int) $_SESSION['user_id'];
$hoTen    = trim($_POST['ho_ten'] ?? '');
$sdt      = trim($_POST['sdt'] ?? '');
$diaChi   = trim($_POST['dia_chi'] ?? '');
$phuongXa = trim($_POST['phuong_xa'] ?? '');
$khuVuc   = trim($_POST['khu_vuc'] ?? '');
$ghiChu   = trim($_POST['ghi_chu'] ?? '');
$hinhThucThanhToan = trim($_POST['hinh_thuc_thanh_toan'] ?? 'COD');

// Validate bắt buộc
if ($hoTen === '' || $sdt === '' || $diaChi === '' || $phuongXa === '' || $khuVuc === '') {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập đầy đủ thông tin người nhận']);
    exit;
}

if (!preg_match('/^[0-9]{10,11}$/', $sdt)) {
    echo json_encode(['status' => 'error', 'message' => 'Số điện thoại không hợp lệ']);
    exit;
}

// Xử lý giỏ hàng
$items = [];
if (!empty($_POST['items'])) {
    $decoded = json_decode($_POST['items'], true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        foreach ($decoded as $it) {
            $pid = isset($it['id']) ? (int)$it['id'] : 0;
            $qty = isset($it['quantity']) ? (int)$it['quantity'] : 0;
            if ($pid > 0 && $qty > 0) {
                $items[] = ['id' => $pid, 'quantity' => $qty];
            }
        }
    }
}
if (empty($items) && isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $pid = (int)$pid; $qty = (int)$qty;
        if ($pid > 0 && $qty > 0) {
            $items[] = ['id' => $pid, 'quantity' => $qty];
        }
    }
}

if (empty($items)) {
    echo json_encode(['status' => 'error', 'message' => 'Giỏ hàng trống']);
    exit;
}

// Lấy giá sản phẩm và kiểm tra
$total = 0.0;
$detailedItems = [];
foreach ($items as $it) {
    $pid = $it['id'];
    $qty = $it['quantity'];

    $stmt = $conn->prepare('SELECT id, ten_san_pham, gia, is_available FROM san_pham WHERE id = ?');
    $stmt->bind_param('i', $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    $prod = $result ? $result->fetch_assoc() : null;
    $stmt->close();

    if (!$prod || (int)$prod['is_available'] !== 1) {
        echo json_encode(['status' => 'error', 'message' => 'Sản phẩm không khả dụng (ID: ' . $pid . ')']);
        exit;
    }

    $price = (float)$prod['gia'];
    $total += $price * $qty;

    $detailedItems[] = [
        'san_pham_id' => (int)$prod['id'],
        'so_luong'    => $qty,
        'don_gia'     => $price,
    ];
}

// Phí ship
$shippingFee = 15000;
$grandTotal = $total + $shippingFee;

try {
    $conn->begin_transaction();

    $orderCode = generateOrderCode($conn);

    $stmt = $conn->prepare(
        'INSERT INTO orders (ma_don, user_id, ho_ten, sdt, dia_chi, phuong_xa, khu_vuc, tong_tien, ghi_chu, hinh_thuc_thanh_toan, trang_thai)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $trang_thai = 'Chờ xác nhận';
    $stmt->bind_param(
        'sisssssddss',
        $orderCode,
        $userId,
        $hoTen,
        $sdt,
        $diaChi,
        $phuongXa,
        $khuVuc,
        $grandTotal,
        $ghiChu,
        $hinhThucThanhToan,
        $trang_thai
    );
    $stmt->execute();
    $orderId = $stmt->insert_id;
    $stmt->close();

    // Thêm sản phẩm vào order_items
    $stmt = $conn->prepare('INSERT INTO order_items (order_id, san_pham_id, so_luong, don_gia) VALUES (?, ?, ?, ?)');
    foreach ($detailedItems as $di) {
        $stmt->bind_param('iiid', $orderId, $di['san_pham_id'], $di['so_luong'], $di['don_gia']);
        $stmt->execute();
    }
    $stmt->close();

    // Thêm bản ghi thanh toán
    $stmt = $conn->prepare('INSERT INTO thanh_toan (order_id, phuong_thuc, trang_thai) VALUES (?, ?, ?)');
    $trang_thai_tt = 'Chờ thanh toán';
    $stmt->bind_param('iss', $orderId, $hinhThucThanhToan, $trang_thai_tt);
    $stmt->execute();
    $stmt->close();

    $conn->commit();

    unset($_SESSION['cart']);

    echo json_encode([
        'status'   => 'success',
        'message'  => 'Đặt hàng thành công',
        'order_id' => $orderId,
        'ma_don'   => $orderCode,
        'redirect' => 'order-success.php?order_id=' . $orderId
    ]);
} catch (Throwable $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Lỗi xử lý đơn hàng', 'detail' => $e->getMessage()]);
}