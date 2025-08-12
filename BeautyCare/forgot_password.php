<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<link rel="stylesheet" href="assets/css/login.css">

<div class="auth-container" style="max-width: 500px;">
    <form id="forgotForm" method="POST" action="php/forgot_password_process.php">
        <h1>Quên mật khẩu</h1>
        <p>Nhập email để nhận liên kết đặt lại mật khẩu</p>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Gửi yêu cầu</button> <br> <br>

        <p><strong>Nếu không còn cách nào khác, vui lòng tạo mới</strong></p>
        <a href="login.php" class="btn-primary">Tạo ngay</a>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
