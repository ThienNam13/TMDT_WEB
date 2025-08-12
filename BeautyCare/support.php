<?php
$pageTitle = "Gửi yêu cầu hỗ trợ";
include 'includes/header.php';
?>
<h1>GỬI YÊU CẦU HỖ TRỢ</h1>
<p>Bạn có bất kỳ thắc mắc, phản hồi hay cần hỗ trợ? Vui lòng điền thông tin vào form dưới đây, chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất.</p>

<form action="submit_support.php" method="POST" class="contact-form">
    <div class="form-group">
        <label for="name">Họ và tên:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="phone">Số điện thoại:</label>
        <input type="tel" id="phone" name="phone">
    </div>
    <div class="form-group">
        <label for="subject">Tiêu đề:</label>
        <input type="text" id="subject" name="subject" required>
    </div>
    <div class="form-group">
        <label for="message">Nội dung yêu cầu:</label>
        <textarea id="message" name="message" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn-submit">Gửi yêu cầu</button>
</form>
<?php include 'includes/footer.php'; ?>