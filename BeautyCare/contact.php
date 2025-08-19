<?php
session_start();
$pageTitle = "Liên hệ";
include 'includes/header.php';
include 'includes/navbar.php';
include 'php\database.php'; // Thêm kết nối database

// Xử lý khi form được submit
$showSuccess = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    
    try {
        // Lưu vào database
        $stmt = $conn->prepare("INSERT INTO lien_he (user_id, ho_ten, email, noi_dung, ngay_gui) 
                               VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$userId, $name, $email, $message]);
        
        // Gửi email thông báo (code giả lập)
        // mail('support@beautycare.com', 'Liên hệ mới từ ' . $name, $message, 'From: ' . $email);
        
        $showSuccess = true;
        $_SESSION['form_submitted'] = true;
    } catch (PDOException $e) {
        $error = "Lỗi khi gửi liên hệ: " . $e->getMessage();
    }
}
?>

<!-- Phần HTML giữ nguyên như file gốc -->
<?php if ($showSuccess || (isset($_SESSION['form_submitted']) && $_SESSION['form_submitted'])): ?>
<div class="success-message">
    <div class="success-content">
        <i class="fas fa-check-circle"></i>
        <h3>Cảm ơn bạn đã liên hệ!</h3>
        <p>Chúng tôi đã nhận được tin nhắn của bạn và sẽ phản hồi trong thời gian sớm nhất.</p>
        <p>Bạn sẽ được chuyển về trang chủ sau <span id="countdown">5</span> giây...</p>
    </div>
</div>
<script>
// Đếm ngược và chuyển hướng sau 5 giây
let seconds = 5;
const countdown = setInterval(() => {
    seconds--;
    document.getElementById('countdown').textContent = seconds;
    if (seconds <= 0) {
        clearInterval(countdown);
        window.location.href = 'index.php';
    }
}, 1000);
</script>
<?php 
    unset($_SESSION['form_submitted']);
else: 
?>

<div class="contact-container">
    <div class="contact-header">
        <h1>LIÊN HỆ VỚI CHÚNG TÔI</h1>
        <div class="header-divider"></div>
        <p class="subtitle">Hãy liên hệ với chúng tôi nếu bạn có bất kỳ câu hỏi nào. BeautyCare luôn sẵn lòng hỗ trợ bạn.</p>
    </div>

    <div class="contact-content">
        <div class="contact-info-section">
            <div class="info-card">
                <h2><i class="fas fa-map-marker-alt"></i> Thông tin liên hệ</h2>
                <div class="info-details">
                    <p><strong>Địa chỉ:</strong> 6 Phan Đăng Lưu, Phường Bình Thạnh, TP.HCM</p>
                    <p><strong>Hotline:</strong> 0123 456 789 (Hỗ trợ 24/7)</p>
                    <p><strong>Email:</strong> support@beautycare.com</p>
                </div>
            </div>

            <div class="about-card">
                <h3>BeautyCare</h3>
                <p>Chuyên cung cấp mỹ phẩm chính hãng, an toàn cho da. Cam kết mang đến vẻ đẹp và sự tự tin cho bạn.</p>
            </div>

            <div class="quick-links-card">
                <h3>Liên kết nhanh</h3>
                <ul>
                    <li><a href="about.php">Giới thiệu</a></li>
                    <li><a href="privacy-policy.php">Chính sách bảo mật</a></li>
                </ul>
            </div>
        </div>

        <div class="contact-form-section">
            <div class="form-card">
                <h2><i class="fas fa-envelope"></i> Gửi tin nhắn cho chúng tôi</h2>
                <form action="submit-contact.php" method="POST" class="contact-form">
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
                    <button type="submit" class="btn-submit">Gửi tin nhắn</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<style>
.success-message {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    animation: fadeIn 0.3s;
}

.success-content {
    background: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    max-width: 500px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
}

.success-content i {
    color: #4CAF50;
    font-size: 50px;
    margin-bottom: 20px;
}

.success-content h3 {
    color: #d23669;
    margin-bottom: 15px;
}

.success-content p {
    color: #555;
    margin-bottom: 10px;
    line-height: 1.6;
}

#countdown {
    font-weight: bold;
    color: #d23669;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
.contact-container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 20px;
}

.contact-header {
    text-align: center;
    margin-bottom: 40px;
}

.contact-header h1 {
    color: #d23669;
    font-size: 32px;
    margin-bottom: 15px;
}

.header-divider {
    width: 120px;
    height: 3px;
    background: linear-gradient(to right, #ff6b81, #d23669);
    margin: 0 auto 20px;
}

.subtitle {
    color: #666;
    font-size: 16px;
    max-width: 700px;
    margin: 0 auto;
}

.contact-content {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
}

.contact-info-section {
    flex: 1;
    min-width: 300px;
}

.contact-form-section {
    flex: 2;
    min-width: 300px;
}

.info-card, .about-card, .quick-links-card, .form-card {
    background: white;
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.info-card h2, .form-card h2 {
    color: #d23669;
    font-size: 22px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}

.info-card i, .form-card i {
    margin-right: 10px;
    color: #ff6b81;
}

.info-details p {
    margin-bottom: 15px;
    color: #555;
    line-height: 1.6;
}

.about-card h3 {
    color: #d23669;
    font-size: 18px;
    margin-bottom: 15px;
}

.about-card p {
    color: #555;
    line-height: 1.6;
}

.quick-links-card h3 {
    color: #d23669;
    font-size: 18px;
    margin-bottom: 15px;
}

.quick-links-card ul {
    list-style: none;
    padding-left: 0;
}

.quick-links-card li {
    margin-bottom: 10px;
}

.quick-links-card a {
    color: #555;
    text-decoration: none;
    transition: color 0.3s;
}

.quick-links-card a:hover {
    color: #ff6b81;
}

.contact-form .form-group {
    margin-bottom: 20px;
}

.contact-form label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-weight: 500;
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.contact-form input:focus,
.contact-form textarea:focus {
    border-color: #ff6b81;
    outline: none;
}

.btn-submit {
    background: linear-gradient(to right, #ff6b81, #d23669);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: opacity 0.3s;
}

.btn-submit:hover {
    opacity: 0.9;
}

@media (max-width: 768px) {
    .contact-content {
        flex-direction: column;
    }
}
</style>

<?php include 'includes/footer.php'; 
?>
