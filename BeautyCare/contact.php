<?php
$pageTitle = "Liên hệ";
include 'includes/header.php';
?>
<h1>LIÊN HỆ VỚI CHÚNG TÔI</h1>
<p>Hãy liên hệ với chúng tôi nếu bạn có bất kỳ câu hỏi nào. BeautyCare luôn sẵn lòng hỗ trợ bạn.</p>

<div class="contact-info">
    <h3>Thông tin liên hệ</h3>
    <p><strong>Địa chỉ:</strong> 6 Phan Đăng Lưu, Phường Bình Thạnh, TP.HCM</p>
    <p><strong>Hotline:</strong> 0123 456 789 (Hỗ trợ 24/7)</p>
    <p><strong>Email:</strong> support@beautycare.com</p>
</div>

<div class="contact-form-section">
    <h3>Gửi tin nhắn cho chúng tôi</h3>
    <form action="submit_contact.php" method="POST" class="contact-form">
        <div class="form-group">
            <label for="name">Họ và tên:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="message">Nội dung:</label>
            <textarea id="message" name="message" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn-submit">Gửi</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>