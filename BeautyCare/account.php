<?php
include 'includes/header.php';
include 'includes/navbar.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'php/database.php';

// Lấy thông tin user
$stmt = $conn->prepare("SELECT fullname, email, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($fullname, $email, $phone, $address);
$stmt->fetch();
$stmt->close();
?>

<link rel="stylesheet" href="assets/css/account.css">

<div class="account-container">
    <div class="account-sidebar">
        <button class="tab-btn active" data-tab="profile">Thông tin cá nhân</button>
        <button class="tab-btn" data-tab="address">Địa chỉ giao hàng</button>
        <button class="tab-btn" data-tab="security">Bảo mật</button>
    </div>

    <div class="account-content">
        <!-- Tab Thông tin cá nhân -->
        <div class="tab-content active" id="profile">
            <h2>Thông tin cá nhân</h2>
            <form id="profileForm">
                <label>Họ và tên</label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($fullname) ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

                <label>Số điện thoại</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>">

                <button type="submit">Cập nhật</button>
            </form>
        </div>

        <!-- Tab Địa chỉ -->
        <div class="tab-content" id="address">
            <h2>Địa chỉ giao hàng</h2>
            <form id="addressForm">
                <label>Địa chỉ</label>
                <textarea name="address" rows="4"><?= htmlspecialchars($address) ?></textarea>
                <button type="submit">Lưu địa chỉ</button>
            </form>
        </div>

        <!-- Tab Bảo mật -->
        <div class="tab-content" id="security">
            <h2>Đổi mật khẩu</h2>
            <form id="passwordForm">
                <label>Mật khẩu hiện tại</label>
                <input type="password" name="current_password" required>

                <label>Mật khẩu mới</label>
                <input type="password" name="new_password" required>

                <label>Xác nhận mật khẩu mới</label>
                <input type="password" name="confirm_password" required>

                <button type="submit">Đổi mật khẩu</button>
            </form>
        </div>
    </div>
</div>

<div id="accountMessage" class="alert" style="display:none;"></div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/account.js"></script>

<?php include 'includes/footer.php'; ?>