<?php
// Kết nối CSDL
include '../php/database.php';
include 'header.php';

// Phân trang
$page    = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 10;
$start   = ($page - 1) * $perPage;

// Đếm tổng số khách hàng (đúng bảng: users)
$totalRes = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='user'");
$total    = (int)$totalRes->fetch_assoc()['total'];
$pages    = (int)ceil($total / $perPage);

// Lấy danh sách khách hàng (đổi customers -> users, đổi LIMIT dùng $start, $perPage)
$query = "
    SELECT id, email, fullname, COALESCE(blocked, 0) AS blocked
    FROM users
    WHERE role='user'
    ORDER BY id DESC
    LIMIT $start, $perPage
";
$customers = $conn->query($query);

// Thống kê khách hàng (không dùng created_at nữa)
$stats = [
    'total'   => $total,
    'active'  => (int)$conn->query("SELECT COUNT(*) FROM users WHERE role='user' AND COALESCE(blocked,0)=0")->fetch_row()[0],
    'blocked' => (int)$conn->query("SELECT COUNT(*) FROM users WHERE role='user' AND COALESCE(blocked,0)=1")->fetch_row()[0],
    // Không có cột created_at => tạm tính tất cả là mới (hoặc bạn để 0 nếu muốn)
    'new_this_month' => (int)$conn->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetch_row()[0]
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
<main class="container">
    <section class="customer-management">
        <div class="dashboard-header">
            <h2><i class="fas fa-users"></i> Quản lý Khách hàng</h2>
            <div class="header-actions">
                <a href="export_customers.php" class="btn-export">
                    <i class="fas fa-file-export"></i> Xuất Excel
                </a>
            </div>
        </div>
<!-- Thêm thông báo AJAX -->
<div id="ajaxMessage" style="display:none;"></div> <!-- để thêm khách hàng không chuyển hướng -->

        <!-- Thống kê nhanh -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-blue"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <h3>Tổng khách hàng</h3>
                    <p><?= number_format($stats['total']) ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-green"><i class="fas fa-user-check"></i></div>
                <div class="stat-info">
                    <h3>Đang hoạt động</h3>
                    <p><?= number_format($stats['active']) ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-red"><i class="fas fa-user-lock"></i></div>
                <div class="stat-info">
                    <h3>Đã khóa</h3>
                    <p><?= number_format($stats['blocked']) ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-orange"><i class="fas fa-user-plus"></i></div>
                <div class="stat-info">
                    <h3>Mới tháng này</h3>
                    <p><?= number_format($stats['new_this_month']) ?></p>
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
                                <tr data-id="<?= $customer['id'] ?>">
                                    <td><?= $customer['id'] ?></td>
                                    <td><?= htmlspecialchars($customer['fullname'] ?? 'Chưa cập nhật') ?></td>
                                    <td><?= htmlspecialchars($customer['email']) ?></td>
                                    <td>
                                        <span class="status-badge <?= $customer['blocked'] ? 'blocked' : 'active' ?>">
                                            <?= $customer['blocked'] ? 'Đã khóa' : 'Hoạt động' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="action-btn view-btn" title="Xem chi tiết" data-id="<?= $customer['id'] ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn edit-btn" title="Sửa khách hàng" data-id="<?= $customer['id'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Xóa khách hàng" data-id="<?= $customer['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
                                <td colspan="5" class="no-data">
                                    <i class="fas fa-user-slash"></i>
                                    <p>Không có khách hàng nào</p>
                                </td>
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

<!-- Modal -->
<div id="customerModal" class="modal">
    <div class="modal-content" id="modalContent"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Search functionality
document.getElementById('customerSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.customer-table tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// View customer details
document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const customerId = this.getAttribute('data-id');
        fetch(`get_customers.php?id=${customerId}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('modalContent').innerHTML = data;
                document.getElementById('customerModal').style.display = 'block';
            });
    });
});

// Edit customer
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const customerId = this.getAttribute('data-id');
        window.location.href = `edit_customers.php?id=${customerId}`;
    });
});

// Delete customer
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const customerId = this.getAttribute('data-id');
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Bạn có chắc chắn muốn xóa khách hàng này?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`delete_customers.php?id=${customerId}`, { method: 'POST' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Đã xóa!', 'Khách hàng đã được xóa.', 'success')
                                .then(() => window.location.reload());
                        } else {
                            Swal.fire('Lỗi!', data.message || 'Không thể xóa khách hàng.', 'error');
                        }
                    });
            }
        });
    });
});

// Block/Unblock customer
document.querySelectorAll('.block-btn, .unblock-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const customerId = this.getAttribute('data-id');
        const isBlocked = this.classList.contains('unblock-btn');
        const action = isBlocked ? 'unblock' : 'block';
        
        Swal.fire({
            title: isBlocked ? 'Mở khóa tài khoản?' : 'Khóa tài khoản?',
            text: isBlocked ? 'Bạn có chắc muốn mở khóa tài khoản này?' : 'Bạn có chắc muốn khóa tài khoản này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: isBlocked ? 'Mở khóa' : 'Khóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`block_customer.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: customerId,
                        action: action
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            isBlocked ? 'Đã mở khóa!' : 'Đã khóa!',
                            isBlocked ? 'Tài khoản đã được mở khóa.' : 'Tài khoản đã được khóa.',
                            'success'
                        ).then(() => window.location.reload());
                    } else {
                        Swal.fire('Lỗi!', data.message || 'Không thể thực hiện thao tác.', 'error');
                    }
                });
            }
        });
    });
});

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('customerModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});
fetch(`get_customer.php?id=${customerId}`)
</script>
z\
</body>
</html>

<?php include 'footer.php'; ?>