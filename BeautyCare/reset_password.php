<?php 
include 'includes/header.php';
include 'includes/navbar.php';
include 'php/database.php';

$token = $_GET['token'] ?? '';
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Kiểm tra token hợp lệ
$stmt = $conn->prepare("SELECT id FROM users WHERE reset_token=? AND reset_expires > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->bind_result($userId);

?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="assets/css/login.css">

<div class="auth-container" style="max-width: 350px;">
<?php if ($stmt->fetch()): ?>
    <form id="resetForm">
        <h1>Đặt lại mật khẩu</h1>
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <input type="password" name="password" placeholder="Mật khẩu mới" required>
        <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu" required> <br>
        <button type="submit">Cập nhật mật khẩu</button>
    </form>
<?php else: ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Liên kết không hợp lệ',
            text: 'Liên kết đã hết hạn hoặc không tồn tại.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'forgot_password.php';
        });
    </script>
<?php endif; ?>
</div>

<script>
document.getElementById('resetForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    fetch('php/reset_password_process.php', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        Swal.fire({
            icon: data.status === 'success' ? 'success' : 'error',
            title: data.status === 'success' ? 'Thành công' : 'Lỗi',
            text: data.message,
            timer: data.status === 'success' ? 2000 : undefined,
            showConfirmButton: data.status !== 'success'
        }).then(() => {
            if (data.status === 'success') {
                window.location.href = 'login.php';
            }
        });
    })
    .catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Có lỗi xảy ra. Vui lòng thử lại.'
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
