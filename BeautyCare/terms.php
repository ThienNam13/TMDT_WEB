<?php
$pageTitle = "Điều khoản sử dụng";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="terms-container">
    <div class="terms-header">
        <h1>ĐIỀU KHOẢN SỬ DỤNG WEBSITE</h1>
        <div class="divider"></div>
        <p class="intro-text">Khi truy cập và sử dụng website BeautyCare, bạn đồng ý tuân thủ các điều khoản sau:</p>
    </div>
    
    <div class="terms-content">
        <div class="terms-section">
            <div class="section-header">
                <div class="section-number">1</div>
                <h3>Tài khoản người dùng</h3>
            </div>
            <div class="section-content">
                <ul class="terms-list">
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Bạn phải cung cấp thông tin chính xác và đầy đủ khi đăng ký tài khoản.</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Bạn có trách nhiệm bảo mật thông tin đăng nhập và không chia sẻ với người khác.</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="terms-section">
            <div class="section-header">
                <div class="section-number">2</div>
                <h3>Quyền sở hữu trí tuệ</h3>
            </div>
            <div class="section-content">
                <div class="content-box">
                    <i class="fas fa-copyright icon-feature"></i>
                    <p>Toàn bộ nội dung, hình ảnh, logo trên website BeautyCare là tài sản của chúng tôi và được bảo hộ bởi pháp luật. Mọi hành vi sao chép, sử dụng khi chưa được cho phép đều là vi phạm pháp luật.</p>
                </div>
            </div>
        </div>
        
        <div class="terms-section">
            <div class="section-header">
                <div class="section-number">3</div>
                <h3>Trách nhiệm của người dùng</h3>
            </div>
            <div class="section-content">
                <ul class="terms-list">
                    <li>
                        <i class="fas fa-ban"></i>
                        <span>Không sử dụng website vào mục đích bất hợp pháp, phá hoại hệ thống.</span>
                    </li>
                    <li>
                        <i class="fas fa-ban"></i>
                        <span>Không đăng tải các nội dung vi phạm pháp luật hoặc đạo đức.</span>
                    </li>
                    <li>
                        <i class="fas fa-ban"></i>
                        <span>Không thực hiện các hành vi gian lận, mua bán không chính đáng.</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="notice-box">
            <div class="notice-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="notice-content">
                <h4>Lưu ý quan trọng</h4>
                <p>BeautyCare có quyền từ chối phục vụ hoặc chấm dứt tài khoản của bạn nếu vi phạm các điều khoản sử dụng này.</p>
            </div>
        </div>
        
        <div class="contact-support">
            <p>Mọi thắc mắc về điều khoản sử dụng, vui lòng liên hệ:</p>
            <a href="mailto:support@beautycare.com" class="contact-link">
                <i class="fas fa-envelope"></i> support@beautycare.com
            </a>
        </div>
    </div>
</div>

<style>
.terms-container {
    max-width: 900px;
    margin: 30px auto;
    padding: 0 20px;
}

.terms-header {
    text-align: center;
    margin-bottom: 40px;
}

.terms-header h1 {
    color: #d23669;
    font-size: 28px;
    margin-bottom: 15px;
}

.divider {
    width: 100px;
    height: 3px;
    background: linear-gradient(to right, #ff6b81, #d23669);
    margin: 15px auto 20px;
}

.intro-text {
    color: #555;
    font-size: 16px;
    max-width: 700px;
    margin: 0 auto;
}

.terms-section {
    margin-bottom: 35px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

.section-header {
    background: linear-gradient(to right, #ff6b81, #d23669);
    color: white;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.section-number {
    background-color: white;
    color: #d23669;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
}

.section-header h3 {
    margin: 0;
    font-size: 18px;
    color: white;
}

.section-content {
    background-color: white;
    padding: 20px 25px;
}

.terms-list {
    padding-left: 0;
    list-style: none;
}

.terms-list li {
    margin-bottom: 15px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    color: #555;
}

.terms-list i {
    color: #d23669;
    margin-top: 3px;
    font-size: 16px;
}

.content-box {
    position: relative;
    padding-left: 50px;
    color: #555;
    line-height: 1.7;
}

.icon-feature {
    position: absolute;
    left: 0;
    top: 0;
    color: #d23669;
    font-size: 30px;
}

.notice-box {
    display: flex;
    gap: 20px;
    background-color: #fff9f9;
    border-left: 4px solid #ff6b81;
    padding: 20px;
    border-radius: 8px;
    margin: 30px 0;
}

.notice-icon {
    color: #ff6b81;
    font-size: 24px;
}

.notice-content h4 {
    color: #d23669;
    margin-top: 0;
    margin-bottom: 10px;
}

.notice-content p {
    color: #555;
    margin: 0;
}

.contact-support {
    text-align: center;
    margin-top: 40px;
    color: #555;
}

.contact-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #d23669;
    text-decoration: none;
    margin-top: 10px;
    font-weight: 500;
}

.contact-link:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .terms-container {
        padding: 0 15px;
    }
    
    .content-box {
        padding-left: 40px;
    }
    
    .icon-feature {
        font-size: 24px;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
