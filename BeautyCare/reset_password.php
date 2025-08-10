<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'php/database.php';

$token = $_GET['token'] ?? '';

$stmt = $conn->prepare("SELECT id FROM users WHERE reset_token=? AND reset_expires > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->bind_result($userId);

if ($stmt->fetch()):
?>
<div class="auth-container" style="max-width: 500px;">
    <form method="POST" action="php/reset_password_process.php">
        <h1>Đặt lại mật khẩu</h1>
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <input type="password" name="password" placeholder="Mật khẩu mới" required>
        <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu" required>
        <button type="submit">Cập nhật mật khẩu</button>
    </form>
</div>
<?php
else:
    echo "<p style='text-align:center; color:red;'>Liên kết không hợp lệ hoặc đã hết hạn.</p>";
endif;

include 'includes/footer.php';
