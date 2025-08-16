<?php
include '../php/database.php';
include 'header.php';

// Initialize variables
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

// Items per page
$items_per_page = 10;
$offset = ($page - 1) * $items_per_page;

// Build base query
$sql = "SELECT 
            o.id, 
            o.ma_don, 
            o.ho_ten AS customer_name, 
            o.sdt AS customer_phone, 
            o.thoi_gian_dat AS order_date, 
            o.tong_tien AS total_amount, 
            o.trang_thai AS status,
            o.hinh_thuc_thanh_toan AS payment_method
        FROM orders o
        WHERE 1=1";

// Add search conditions
if (!empty($search)) {
    $sql .= " AND (o.ma_don LIKE '%" . $conn->real_escape_string($search) . "%' 
              OR o.ho_ten LIKE '%" . $conn->real_escape_string($search) . "%'
              OR o.sdt LIKE '%" . $conn->real_escape_string($search) . "%')";
}

// Add status filter
if (!empty($status) && $status != 'all') {
    $sql .= " AND o.trang_thai = '" . $conn->real_escape_string($status) . "'";
}

// Add date range filter
if (!empty($date_from)) {
    $sql .= " AND DATE(o.thoi_gian_dat) >= '" . $conn->real_escape_string($date_from) . "'";
}
if (!empty($date_to)) {
    $sql .= " AND DATE(o.thoi_gian_dat) <= '" . $conn->real_escape_string($date_to) . "'";
}

// Count total records for pagination
$count_sql = "SELECT COUNT(*) AS total FROM orders o WHERE 1=1";

if (!empty($search)) {
    $count_sql .= " AND (o.ma_don LIKE '%" . $conn->real_escape_string($search) . "%' 
                  OR o.ho_ten LIKE '%" . $conn->real_escape_string($search) . "%'
                  OR o.sdt LIKE '%" . $conn->real_escape_string($search) . "%')";
}

if (!empty($status) && $status != 'all') {
    $count_sql .= " AND o.trang_thai = '" . $conn->real_escape_string($status) . "'";
}

if (!empty($date_from)) {
    $count_sql .= " AND DATE(o.thoi_gian_dat) >= '" . $conn->real_escape_string($date_from) . "'";
}
if (!empty($date_to)) {
    $count_sql .= " AND DATE(o.thoi_gian_dat) <= '" . $conn->real_escape_string($date_to) . "'";
}

$count_result = $conn->query($count_sql);
if (!$count_result) {
    die("Lỗi truy vấn COUNT: " . $conn->error);
}

$total_records = (int)$count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $items_per_page);


// Add sorting and pagination
$sql .= " ORDER BY o.thoi_gian_dat DESC 
          LIMIT $offset, $items_per_page";

$result = $conn->query($sql);

if (!$result) {
    die("Lỗi truy vấn: " . $conn->error);
}

// Get distinct statuses for filter dropdown
$statuses_sql = "SELECT DISTINCT trang_thai FROM orders";
$statuses_result = $conn->query($statuses_sql);
$statuses = [];
while ($row = $statuses_result->fetch_assoc()) {
    $statuses[] = $row['trang_thai'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng | BeautyCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/orders.css">
    <style>
        .filter-section {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #555;
        }
        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .filter-actions {
            display: flex;
            gap: 10px;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 5px;
        }
        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .pagination a:hover {
            background: #f5f5f5;
        }
        .pagination .active {
            background: #6c63ff;
            color: white;
            border-color: #6c63ff;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-badge.chờ-xác-nhận { background: #fff3cd; color: #856404; }
        .status-badge.đang-xử-lý { background: #cce5ff; color: #004085; }
        .status-badge.đang-giao-hàng { background: #e2e3e5; color: #383d41; }
        .status-badge.đã-giao { background: #d4edda; color: #155724; }
        .status-badge.đã-hủy { background: #f8d7da; color: #721c24; }
        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
                gap: 10px;
            }
            .filter-group {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
<main class="container">
    <section class="dashboard">
        <div class="dashboard-header">
            <h2><i class="fas fa-shopping-cart"></i> Quản lý đơn hàng</h2>
        </div>

        <div class="filter-section">
            <form method="GET" action="">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="search">Tìm kiếm</label>
                        <input type="text" id="search" name="search" placeholder="Mã đơn, tên KH, SĐT..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="filter-group">
                        <label for="status">Trạng thái</label>
                        <select id="status" name="status">
                            <option value="all">Tất cả trạng thái</option>
                            <?php foreach ($statuses as $s): ?>
                                <option value="<?= htmlspecialchars($s) ?>" <?= $status == $s ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="date_from">Từ ngày</label>
                        <input type="date" id="date_from" name="date_from" value="<?= htmlspecialchars($date_from) ?>">
                    </div>
                    <div class="filter-group">
                        <label for="date_to">Đến ngày</label>
                        <input type="date" id="date_to" name="date_to" value="<?= htmlspecialchars($date_to) ?>">
                    </div>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                    <a href="manage-orders.php" class="btn btn-secondary">
                        <i class="fas fa-sync-alt"></i> Xóa bộ lọc
                    </a>
                </div>
            </form>
        </div>

        <div class="orders-toolbar">
            <div class="search-box">
                <input type="text" id="quick-search" placeholder="Tìm kiếm nhanh..." value="<?= htmlspecialchars($search) ?>">
                <button id="quick-search-btn"><i class="fas fa-search"></i></button>
            </div>
            <div class="action-buttons">
                <a href="add_order.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm đơn hàng
                </a>
                <a href="export-orders.php?<?= http_build_query($_GET) ?>" class="btn btn-secondary">
                    <i class="fas fa-file-export"></i> Xuất Excel
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>PTTT</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
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
                                                <a href="edit_order.php?id=<?= $row['id'] ?>" class="btn-icon" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="delete_order.php?id=<?= $row['id'] ?>" class="btn-icon btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>">
                                    <i class="fas fa-angle-double-left"></i>
                                </a>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            <?php endif; ?>

                            <?php 
                            $start = max(1, $page - 2);
                            $end = min($total_pages, $page + 2);
                            for ($i = $start; $i <= $end; $i++): ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" <?= $i == $page ? 'class="active"' : '' ?>>
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                                    <i class="fas fa-angle-right"></i>
                                </a>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $total_pages])) ?>">
                                    <i class="fas fa-angle-double-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-shopping-cart fa-4x"></i>
                        <h3>Không tìm thấy đơn hàng</h3>
                        <p>Không có đơn hàng nào phù hợp với tiêu chí tìm kiếm của bạn</p>
                        <a href="manage-orders.php" class="btn btn-primary">
                            <i class="fas fa-sync-alt"></i> Xóa bộ lọc
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<script>
    // Quick search functionality
    document.getElementById('quick-search-btn').addEventListener('click', function() {
        const searchValue = document.getElementById('quick-search').value;
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchValue);
        url.searchParams.delete('page'); // Reset to first page
        window.location.href = url.toString();
    });

    // Quick search on Enter key
    document.getElementById('quick-search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('quick-search-btn').click();
        }
    });

    // Delete confirmation
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')) {
                e.preventDefault();
            }
        });
    });
</script>
</body>
</html>

<?php include 'footer.php'; ?>