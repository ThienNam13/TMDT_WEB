<?php
// Kết nối CSDL
include '../php/database.php';
include 'header.php';

// Phân trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$start = ($page > 1) ? ($page - 1) * $perPage : 0;

// Lấy tổng số khách hàng
$totalRes = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='user'");
$total = $totalRes->fetch_assoc()['total'];
$pages = ceil($total / $perPage);

// Lấy danh sách khách hàng
$query = "SELECT id, email, fullname, blocked FROM users WHERE role='user' ORDER BY id DESC LIMIT $start, $perPage";
$customers = $conn->query($query);

// Thống kê khách hàng
$stats = [
    'total' => $total,
    'active' => $conn->query("SELECT COUNT(*) FROM users WHERE role='user' AND blocked=0")->fetch_row()[0],
    'blocked' => $conn->query("SELECT COUNT(*) FROM users WHERE role='user' AND blocked=1")->fetch_row()[0],
    'new_this_month' => $conn->query("SELECT COUNT(*) FROM users WHERE role='user' AND DATE_FORMAT(NOW(), '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')")->fetch_row()[0]
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Khách hàng - BeautyCare Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/customers.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
<main class="container">
    <section class="customer-management">
        <div class="dashboard-header">
            <h2>Quản lý Khách hàng</h2>
            <div class="header-actions">
                <a href="export_customers.php" class="btn-export">
                    <i class="fas fa-file-export"></i> Xuất Excel
                </a>
            </div>
        </div>

        <!-- Thống kê nhanh -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-blue"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <h3>Tổng khách hàng</h3>
                    <p><?= $stats['total'] ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-green"><i class="fas fa-user-check"></i></div>
                <div class="stat-info">
                    <h3>Đang hoạt động</h3>
                    <p><?= $stats['active'] ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-red"><i class="fas fa-user-lock"></i></div>
                <div class="stat-info">
                    <h3>Đã khóa</h3>
                    <p><?= $stats['blocked'] ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-orange"><i class="fas fa-user-plus"></i></div>
                <div class="stat-info">
                    <h3>Mới tháng này</h3>
                    <p><?= $stats['new_this_month'] ?></p>
                </div>
            </div>
        </div>

        <!-- Bảng danh sách khách hàng -->
        <div class="customer-list">
            <div class="table-header">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="customerSearch" placeholder="Tìm kiếm khách hàng...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="customer-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($customers && $customers->num_rows > 0): ?>
                            <?php while($customer = $customers->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $customer['id'] ?></td>
                                    <td><?= htmlspecialchars($customer['fullname'] ?? 'Chưa cập nhật') ?></td>
                                    <td><?= htmlspecialchars($customer['email']) ?></td>
                                    <td>
                                        <span class="status-badge <?= $customer['blocked'] ? 'blocked' : 'active' ?>">
                                            <?= $customer['blocked'] ? 'Đã khóa' : 'Hoạt động' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="customer_detail.php?id=<?= $customer['id'] ?>" class="action-btn view-btn" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="action-btn <?= $customer['blocked'] ? 'unblock-btn' : 'block-btn' ?>" 
                                                title="<?= $customer['blocked'] ? 'Mở khóa' : 'Khóa tài khoản' ?>"
                                                data-id="<?= $customer['id'] ?>">
                                            <i class="fas <?= $customer['blocked'] ? 'fa-unlock' : 'fa-lock' ?>"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="no-data">Không có khách hàng nào</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            <?php if($pages > 1): ?>
                <div class="pagination">
                    <?php if($page > 1): ?>
                        <a href="?page=<?= $page-1 ?>" class="page-link"><i class="fas fa-chevron-left"></i></a>
                    <?php endif; ?>

                    <?php for($i = 1; $i <= $pages; $i++): ?>
                        <a href="?page=<?= $i ?>" class="page-link <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if($page < $pages): ?>
                        <a href="?page=<?= $page+1 ?>" class="page-link"><i class="fas fa-chevron-right"></i></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<script>
// Xử lý khóa/mở khóa tài khoản
document.querySelectorAll('.block-btn, .unblock-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const userId = this.getAttribute('data-id');
        const isBlock = this.classList.contains('block-btn');
        
        if(confirm(`Bạn chắc chắn muốn ${isBlock ? 'KHÓA' : 'MỞ KHÓA'} tài khoản này?`)) {
            fetch('block_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${userId}&block=${isBlock ? 1 : 0}`
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            });
        }
    });
});

// Tìm kiếm khách hàng
document.getElementById('customerSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.customer-table tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>
</body>
</html>

<?php include 'footer.php'; ?>