<?php 
include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="assets/css/login.css">

<div class="auth-container" style="max-width: 500px;">
    <form id="forgotForm">
        <h1>Quên mật khẩu</h1>
        <p>Nhập email để nhận liên kết đặt lại mật khẩu</p>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Gửi yêu cầu</button> <br>
        <p>Nếu không còn cách nào khác, liên hệ 0902777999 hoặc vui lòng tạo mới</p>
    </form>
</div>

<script>
document.getElementById('forgotForm').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Đang gửi yêu cầu...',
        text: 'Vui lòng chờ trong giây lát',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('php/forgot_password_process.php', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        Swal.close();
        Swal.fire({
            icon: data.status === 'success' ? 'success' : 'error',
            title: data.status === 'success' ? 'Thành công' : 'Lỗi',
            text: data.message
        }).then(() => {
            if (data.status === 'success') {
                // Xóa form khi gửi thành công
                document.getElementById('forgotForm').reset();
            }
        });
    })
    .catch(() => {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Không thể kết nối tới máy chủ. Vui lòng thử lại.'
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
