<?php
// fetch_orders.php
include '../php/database.php';

// Lấy các tham số filter/pagination từ query string
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

// Số dòng mỗi trang
$items_per_page = 10;
$offset = ($page - 1) * $items_per_page;

// Query chính
$sql = "SELECT 
            o.id, 
            o.ma_don, 
            o.ho_ten AS customer_name, 
            o.sdt AS customer_phone, 
            o.thoi_gian_dat AS order_date, 
            o.tong_tien AS total_amount, 
            o.trang_thai AS status,
            o.cancel_reason,
            o.hinh_thuc_thanh_toan AS payment_method
        FROM orders o
        WHERE 1=1";

// Search
if (!empty($search)) {
    $sql .= " AND (o.ma_don LIKE '%" . $conn->real_escape_string($search) . "%' 
              OR o.ho_ten LIKE '%" . $conn->real_escape_string($search) . "%'
              OR o.sdt LIKE '%" . $conn->real_escape_string($search) . "%')";
}

// Trạng thái
if (!empty($status) && $status != 'all') {
    $sql .= " AND o.trang_thai = '" . $conn->real_escape_string($status) . "'";
}

// Khoảng ngày
if (!empty($date_from)) {
    $sql .= " AND DATE(o.thoi_gian_dat) >= '" . $conn->real_escape_string($date_from) . "'";
}
if (!empty($date_to)) {
    $sql .= " AND DATE(o.thoi_gian_dat) <= '" . $conn->real_escape_string($date_to) . "'";
}

// Sort + phân trang
$sql .= " ORDER BY o.thoi_gian_dat DESC 
          LIMIT $offset, $items_per_page";

$result = $conn->query($sql);
if (!$result) {
    die("Lỗi truy vấn: " . $conn->error);
}

while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['ma_don']) ?></td>
        <td>
            <div class="customer-info">
                <strong><?= htmlspecialchars($row['customer_name']) ?></strong>
                <small><?= htmlspecialchars($row['customer_phone']) ?></small>
            </div>
        </td>
        <td><?= date('d/m/Y H:i', strtotime($row['order_date'])) ?></td>
        <td><?= number_format($row['total_amount'], 0, ',', '.') ?>₫</td>
        <td><?= htmlspecialchars($row['payment_method']) ?></td>
        <td>
            <span class="status-badge <?= strtolower(str_replace(' ', '-', $row['status'])) ?>">
                <?= htmlspecialchars($row['status']) ?>
            </span>
        </td>
        <td>
            <div class="action-buttons">
                <a href="order_detail.php?id=<?= $row['id'] ?>" class="btn-icon" title="Xem chi tiết">
                    <i class="fas fa-eye"></i>
                </a>

                <?php if ($row['status'] === 'Chờ xác nhận'): ?>
                    <button class="btn-icon btn-update-status" 
                            data-id="<?= $row['id'] ?>" 
                            data-action="confirm"
                            title="Chuyển sang Đang xử lý">
                       <i class="fas fa-check-circle"></i>
                    </button>

                <?php elseif ($row['status'] === 'Đang xử lý'): ?>
                    <button class="btn-icon btn-update-status" 
                            data-id="<?= $row['id'] ?>" 
                            data-action="processing"
                            title="Chuyển sang Đang giao">
                        <i class="fas fa-truck"></i>
                    </button>

                <?php elseif ($row['status'] === 'Đang giao'): ?>
                    <button class="btn-icon btn-update-status" 
                            data-id="<?= $row['id'] ?>" 
                            data-action="complete"
                            title="Xác nhận Hoàn tất">
                        <i class="fas fa-check"></i>
                    </button>

                <?php elseif ($row['status'] === 'Yêu cầu trả hàng'): ?>
                    <button class="btn-icon btn-update-status" 
                            data-id="<?= $row['id'] ?>" 
                            data-action="return" 
                            data-reason="<?= htmlspecialchars($row['cancel_reason']) ?>"
                            title="Xử lý yêu cầu trả hàng">
                        <i class="fas fa-undo-alt"></i>
                    </button>

                <?php elseif ($row['status'] === 'Đã hủy' && !empty($row['cancel_reason'])): ?>
                    <button class="btn-icon btn-view-reason" 
                            data-reason="<?= htmlspecialchars($row['cancel_reason']) ?>"
                            title="Xem lý do hủy">
                        <i class="fas fa-ban"></i>
                    </button>
                <?php endif; ?>
            </div>
        </td>
    </tr>
<?php endwhile; ?>
