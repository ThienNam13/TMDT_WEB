<?php
include '../php/database.php';

// Kiểm tra ID khách hàng
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID khách hàng không hợp lệ');
}

$customerId = (int)$_GET['id'];

// Lấy thông tin khách hàng từ database
$query = "SELECT id, fullname, email, phone, address, COALESCE(blocked, 0) AS blocked 
          FROM users 
          WHERE id = ? AND role='user'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customerId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Không tìm thấy khách hàng');
}

$customer = $result->fetch_assoc();
?>

<div class="modal-header">
    <h3>Chi tiết khách hàng #<?= $customer['id'] ?></h3>
    <span class="close-modal">&times;</span>
</div>
<div class="modal-body">
    <div class="customer-details">
        <div class="detail-row">
            <span class="detail-label">Họ tên:</span>
            <span class="detail-value"><?= htmlspecialchars($customer['fullname']) ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Email:</span>
            <span class="detail-value"><?= htmlspecialchars($customer['email']) ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Số điện thoại:</span>
            <span class="detail-value"><?= htmlspecialchars($customer['phone'] ?? 'Chưa cập nhật') ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Địa chỉ:</span>
            <span class="detail-value"><?= htmlspecialchars($customer['address'] ?? 'Chưa cập nhật') ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Trạng thái:</span>
            <span class="status-badge <?= $customer['blocked'] ? 'blocked' : 'active' ?>">
                <?= $customer['blocked'] ? 'Đã khóa' : 'Hoạt động' ?>
            </span>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn-close" onclick="document.getElementById('customerModal').style.display='none'">Đóng</button>
</div>

<style>
.modal-header {
    padding: 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-body {
    padding: 20px;
}
.detail-row {
    margin-bottom: 15px;
    display: flex;
}
.detail-label {
    font-weight: bold;
    width: 120px;
}
.detail-value {
    flex: 1;
}
.status-badge {
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 14px;
}
.status-badge.active {
    background-color: #d4edda;
    color: #155724;
}
.status-badge.blocked {
    background-color: #f8d7da;
    color: #721c24;
}
.modal-footer {
    padding: 15px;
    border-top: 1px solid #eee;
    text-align: right;
}
.btn-close {
    padding: 8px 15px;
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.close-modal {
    font-size: 24px;
    cursor: pointer;
}
</style>

<script>
// Xử lý đóng modal khi click vào nút đóng
document.querySelector('.close-modal').addEventListener('click', function() {
    document.getElementById('customerModal').style.display = 'none';
});
</script>