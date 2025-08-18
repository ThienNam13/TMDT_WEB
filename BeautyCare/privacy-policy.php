<?php
$pageTitle = "Chính sách bảo mật";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="privacy-container">
    <div class="privacy-header">
        <h1>CHÍNH SÁCH BẢO MẬT THÔNG TIN</h1>
        <div class="divider"></div>
    </div>
    
    <div class="privacy-content">
        <div class="intro-section">
            <div class="security-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <p><strong>BeautyCare</strong> cam kết bảo mật tuyệt đối thông tin cá nhân của khách hàng. Chúng tôi hiểu rằng quyền riêng tư là rất quan trọng và sẽ không bao giờ chia sẻ thông tin của bạn với bên thứ ba vì mục đích thương mại.</p>
        </div>
        
        <div class="privacy-section">
            <div class="section-header">
                <div class="section-number">1</div>
                <h3>MỤC ĐÍCH THU THẬP THÔNG TIN</h3>
            </div>
            <div class="section-content">
                <p>Chúng tôi thu thập thông tin cá nhân của bạn (họ tên, địa chỉ, số điện thoại, email) để:</p>
                <ul class="privacy-list">
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Xử lý đơn hàng và giao hàng</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Hỗ trợ khách hàng và giải quyết các khiếu nại</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Cung cấp thông tin về các chương trình khuyến mãi, sản phẩm mới (nếu bạn đồng ý)</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="privacy-section">
            <div class="section-header">
                <div class="section-number">2</div>
                <h3>BẢO MẬT THÔNG TIN</h3>
            </div>
            <div class="section-content">
                <div class="security-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Hệ thống mã hóa</h4>
                            <p>Thông tin được mã hóa SSL 256-bit để đảm bảo an toàn tuyệt đối</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Lưu trữ an toàn</h4>
                            <p>Dữ liệu được lưu trữ trên hệ thống máy chủ bảo mật cao</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Kiểm soát truy cập</h4>
                            <p>Chỉ nhân viên được ủy quyền mới có thể tiếp cận thông tin</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="privacy-section">
            <div class="section-header">
                <div class="section-number">3</div>
                <h3>QUYỀN LỢI KHÁCH HÀNG</h3>
            </div>
            <div class="section-content">
                <ul class="privacy-list">
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Được quyền truy cập và chỉnh sửa thông tin cá nhân</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Được quyền yêu cầu ngừng sử dụng thông tin cho mục đích tiếp thị</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Được thông báo nếu có bất kỳ vi phạm bảo mật nào ảnh hưởng đến thông tin cá nhân</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="contact-section">
            <div class="contact-card">
                <i class="fas fa-headset"></i>
                <h4>CẦN HỖ TRỢ THÊM?</h4>
                <p>Nếu bạn có bất kỳ câu hỏi nào về chính sách bảo mật của chúng tôi, vui lòng liên hệ:</p>
                <div class="contact-methods">
                    <a href="tel:19001234" class="contact-link">
                        <i class="fas fa-phone"></i> 1900 1234
                    </a>
                    <a href="mailto:privacy@beautycare.com" class="contact-link">
                        <i class="fas fa-envelope"></i> privacy@beautycare.com
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.privacy-container {
    max-width: 900px;
    margin: 30px auto;
    padding: 0 20px;
}

.privacy-header {
    text-align: center;
    margin-bottom: 40px;
}

.privacy-header h1 {
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

.intro-section {
    background-color: #fff9f9;
    padding: 25px;
    border-radius: 8px;
    margin-bottom: 30px;
    border-left: 4px solid #ff6b81;
    text-align: center;
    position: relative;
}

.security-icon {
    color: #d23669;
    font-size: 40px;
    margin-bottom: 15px;
}

.intro-section p {
    color: #555;
    font-size: 16px;
    line-height: 1.7;
    margin: 0;
}

.privacy-section {
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
    padding: 25px;
}

.privacy-list {
    padding-left: 0;
    list-style: none;
    margin-top: 15px;
}

.privacy-list li {
    margin-bottom: 15px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    color: #555;
}

.privacy-list i {
    color: #d23669;
    margin-top: 3px;
    font-size: 16px;
}

.security-features {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 20px;
}

.feature-item {
    flex: 1;
    min-width: 250px;
    display: flex;
    gap: 15px;
    align-items: flex-start;
    margin-bottom: 15px;
}

.feature-icon {
    color: #d23669;
    font-size: 24px;
    margin-top: 3px;
}

.feature-text h4 {
    color: #d23669;
    margin: 0 0 5px 0;
    font-size: 16px;
}

.feature-text p {
    color: #555;
    margin: 0;
    font-size: 14px;
    line-height: 1.6;
}

.contact-section {
    margin-top: 40px;
}

.contact-card {
    background: linear-gradient(to right, rgba(255,107,129,0.1), rgba(210,54,105,0.1));
    padding: 30px;
    border-radius: 8px;
    text-align: center;
}

.contact-card i {
    color: #d23669;
    font-size: 40px;
    margin-bottom: 15px;
}

.contact-card h4 {
    color: #d23669;
    margin: 0 0 10px 0;
}

.contact-card p {
    color: #555;
    margin-bottom: 20px;
}

.contact-methods {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.contact-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background-color: #d23669;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
}

.contact-link:hover {
    background-color: #ff6b81;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .privacy-container {
        padding: 0 15px;
    }
    
    .feature-item {
        min-width: 100%;
    }
    
    .contact-methods {
        flex-direction: column;
        gap: 10px;
    }
    
    .contact-link {
        justify-content: center;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
