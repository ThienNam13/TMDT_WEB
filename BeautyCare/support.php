<?php
session_start();
$pageTitle = "Gửi yêu cầu hỗ trợ";
include 'includes/header.php';
include 'includes/navbar.php';
include 'php\database.php';

// Xử lý form hỗ trợ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'] ?? null;
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    
    try {
        $stmt = $conn->prepare("INSERT INTO lien_he (user_id, ho_ten, email, so_dien_thoai, tieu_de, noi_dung, ngay_gui) 
                               VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$userId, $name, $email, $phone, $subject, $message]);
        
        $_SESSION['support_submitted'] = true;
        header('Location: support.php?success=1');
        exit();
    } catch (PDOException $e) {
        $error = "Lỗi khi gửi yêu cầu hỗ trợ: " . $e->getMessage();
    }
}

// Hiển thị thông báo thành công nếu có
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo '
    <div class="success-overlay">
        <div class="success-modal">
            <div class="success-icon">
                <svg viewBox="0 0 24 24" width="64" height="64">
                    <path fill="#fff" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
            </div>
            <h3>Gửi thành công!</h3>
            <p>Yêu cầu hỗ trợ của bạn đã được gửi thành công</p>
            <p class="countdown">Tự động chuyển về trang chủ sau <span id="countdown">3</span> giây...</p>
            <a href="index.php" class="btn-go-home">Về trang chủ ngay</a>
        </div>
    </div>
    
    <script>
        // Đếm ngược
        let seconds = 3;
        const countdownElement = document.getElementById("countdown");
        const countdownInterval = setInterval(() => {
            seconds--;
            countdownElement.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(countdownInterval);
                window.location.href = "index.php";
            }
        }, 1000);
        
        // Hiệu ứng xuất hiện
        setTimeout(() => {
            document.querySelector(".success-modal").style.transform = "translateY(0)";
            document.querySelector(".success-modal").style.opacity = "1";
        }, 100);
    </script>
    ';
}
?>

<!-- Phần HTML giữ nguyên như file gốc -->
<div class="support-container">
    <div class="support-header">
        <h1>GỬI YÊU CẦU HỖ TRỢ</h1>
        <div class="divider"></div>
    </div>
    
    <div class="support-content">
        <div class="intro-section">
            <p><strong>BeautyCare</strong> luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy điền thông tin vào form dưới đây, chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất.</p>
        </div>
        
        <form action="submit-support.php" method="POST" class="contact-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Họ và tên <span class="required">*</span></label>
                    <input type="text" id="name" name="name" placeholder="Nhập họ tên của bạn" required>
                    <i class="fas fa-user form-icon"></i>
                </div>
                
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" placeholder="Nhập email của bạn" required>
                    <i class="fas fa-envelope form-icon"></i>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="tel" id="phone" name="phone" placeholder="Nhập số điện thoại">
                    <i class="fas fa-phone form-icon"></i>
                </div>
                
                <div class="form-group">
                    <label for="subject">Tiêu đề <span class="required">*</span></label>
                    <input type="text" id="subject" name="subject" placeholder="Nhập tiêu đề yêu cầu" required>
                    <i class="fas fa-heading form-icon"></i>
                </div>
            </div>
            
            <div class="form-group full-width">
                <label for="message">Nội dung yêu cầu <span class="required">*</span></label>
                <textarea id="message" name="message" rows="6" placeholder="Mô tả chi tiết yêu cầu của bạn..." required></textarea>
                <i class="fas fa-comment-dots textarea-icon"></i>
            </div>
            
            <div class="form-submit">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Gửi yêu cầu
                </button>
            </div>
        </form>
        
        <div class="contact-alternative">
            <p>Hoặc liên hệ trực tiếp với chúng tôi qua:</p>
            <div class="contact-methods">
                <div class="contact-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>Hotline: <strong>1900 1234</strong></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>Email: <strong>support@beautycare.com</strong></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Địa chỉ: 02 Võ Oanh, Phường Trung Mỹ Tây, TP.HCM</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.support-container {
    max-width: 800px;
    margin: 30px auto;
    padding: 0 20px;
}

.support-header {
    text-align: center;
    margin-bottom: 40px;
}

.support-header h1 {
    color: #d23669;
    font-size: 28px;
    margin-bottom: 15px;
}

.divider {
    width: 100px;
    height: 3px;
    background: linear-gradient(to right, #ff6b81, #d23669);
    margin: 0 auto;
}

.intro-section {
    background-color: #fff9f9;
    padding: 25px;
    border-radius: 8px;
    margin-bottom: 30px;
    border-left: 4px solid #ff6b81;
    text-align: center;
    font-size: 16px;
    color: #555;
}

.contact-form {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.05);
}

.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    flex: 1;
    position: relative;
    margin-bottom: 15px;
}

.form-group.full-width {
    flex: 0 0 100%;
}

label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-weight: 500;
}

.required {
    color: #d23669;
}

input, textarea {
    width: 100%;
    padding: 12px 15px 12px 40px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.3s;
}

textarea {
    padding: 15px 15px 15px 40px;
    resize: vertical;
}

input:focus, textarea:focus {
    border-color: #ff6b81;
    box-shadow: 0 0 0 3px rgba(255, 107, 129, 0.1);
    outline: none;
}

.form-icon, .textarea-icon {
    position: absolute;
    left: 15px;
    top: 38px;
    color: #d23669;
    font-size: 16px;
}

.textarea-icon {
    top: 40px;
}

.btn-submit {
    background: linear-gradient(to right, #ff6b81, #d23669);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-submit:hover {
    background: linear-gradient(to right, #d23669, #ff6b81);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(210, 54, 105, 0.3);
}

.form-submit {
    text-align: center;
    margin-top: 30px;
}

.contact-alternative {
    text-align: center;
    margin-top: 40px;
    color: #666;
}

.contact-methods {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 30px;
    margin-top: 20px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
}

.contact-item i {
    color: #d23669;
    font-size: 18px;
}
.success-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.7);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
}

.success-modal {
    background: white;
    padding: 40px;
    border-radius: 15px;
    text-align: center;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    transform: translateY(20px);
    opacity: 0;
    transition: all 0.4s ease-out;
}

.success-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(to right, #ff6b81, #d23669);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto 20px;
}

.success-modal h3 {
    color: #d23669;
    font-size: 24px;
    margin-bottom: 10px;
}

.success-modal p {
    color: #555;
    margin-bottom: 20px;
}

.countdown {
    font-size: 14px;
    color: #888;
}

#countdown {
    font-weight: bold;
    color: #d23669;
}

.btn-go-home {
    display: inline-block;
    padding: 10px 20px;
    background: linear-gradient(to right, #ff6b81, #d23669);
    color: white;
    text-decoration: none;
    border-radius: 30px;
    margin-top: 15px;
    transition: all 0.3s;
}

.btn-go-home:hover {
    background: linear-gradient(to right, #d23669, #ff6b81);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(210, 54, 105, 0.3);
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
        gap: 0;
    }
    
    .contact-methods {
        flex-direction: column;
        gap: 15px;
    }
}

</style>

<?php include 'includes/footer.php'; ?>
