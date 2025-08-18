<?php
$pageTitle = "Tuyển dụng";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="career-container">
    <div class="career-header">
        <h1>TUYỂN DỤNG CÙNG BEAUTYCARE</h1>
        <div class="divider"></div>
        <p class="career-intro">Bạn đam mê ngành làm đẹp và mong muốn phát triển sự nghiệp trong môi trường chuyên nghiệp, năng động? Hãy gia nhập đội ngũ BeautyCare!</p>
    </div>
    
    <div class="career-content">
        <div class="section-title">
            <i class="fas fa-briefcase"></i>
            <h2>CÁC VỊ TRÍ ĐANG TUYỂN DỤNG</h2>
        </div>
        
        <div class="career-list">
            <div class="career-item">
                <div class="career-number">1</div>
                <div class="career-details">
                    <h3>Chuyên viên tư vấn mỹ phẩm</h3>
                    <div class="job-description">
                        <h4><i class="fas fa-tasks"></i> MÔ TẢ CÔNG VIỆC</h4>
                        <ul>
                            <li>Tư vấn và giới thiệu sản phẩm phù hợp với nhu cầu khách hàng</li>
                            <li>Chăm sóc khách hàng sau bán hàng</li>
                            <li>Theo dõi và cập nhật xu hướng làm đẹp mới</li>
                        </ul>
                    </div>
                    <div class="job-requirement">
                        <h4><i class="fas fa-user-check"></i> YÊU CẦU</h4>
                        <ul>
                            <li>Có kiến thức về mỹ phẩm và skincare</li>
                            <li>Kỹ năng giao tiếp tốt, nhiệt tình, trung thực</li>
                            <li>Ưu tiên ứng viên có kinh nghiệm trong ngành làm đẹp</li>
                        </ul>
                    </div>
                    <div class="job-benefit">
                        <h4><i class="fas fa-gift"></i> QUYỀN LỢI</h4>
                        <ul>
                            <li>Lương cơ bản + hoa hồng hấp dẫn</li>
                            <li>Đào tạo sản phẩm và kỹ năng bán hàng</li>
                            <li>Môi trường làm việc trẻ trung, năng động</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="career-item">
                <div class="career-number">2</div>
                <div class="career-details">
                    <h3>Nhân viên bán hàng tại cửa hàng</h3>
                    <div class="job-description">
                        <h4><i class="fas fa-tasks"></i> MÔ TẢ CÔNG VIỆC</h4>
                        <ul>
                            <li>Sắp xếp và trưng bày sản phẩm tại cửa hàng</li>
                            <li>Quản lý kho và kiểm kê hàng hóa</li>
                            <li>Bán hàng và hỗ trợ khách hàng tại cửa hàng</li>
                        </ul>
                    </div>
                    <div class="job-requirement">
                        <h4><i class="fas fa-user-check"></i> YÊU CẦU</h4>
                        <ul>
                            <li>Nhanh nhẹn, trung thực, chăm chỉ</li>
                            <li>Có tinh thần trách nhiệm cao</li>
                            <li>Ưu tiên ứng viên có kinh nghiệm bán hàng</li>
                        </ul>
                    </div>
                    <div class="job-benefit">
                        <h4><i class="fas fa-gift"></i> QUYỀN LỢI</h4>
                        <ul>
                            <li>Lương cơ bản + thưởng doanh số</li>
                            <li>Được hưởng chính sách ưu đãi khi mua sản phẩm</li>
                            <li>Cơ hội thăng tiến trong công việc</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="application-section">
            <div class="section-title">
                <i class="fas fa-paper-plane"></i>
                <h2>CÁCH THỨC ỨNG TUYỂN</h2>
            </div>
            <div class="application-content">
                <p>Gửi CV và thư ứng tuyển về email:</p>
                <a href="mailto:tuyendung@beautycare.com" class="application-email">
                    <i class="fas fa-envelope"></i> tuyendung@beautycare.com
                </a>
                <p>Hoặc liên hệ trực tiếp:</p>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>Hotline: <strong>1900 1234</strong></span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Trụ sở: 123 Đường ABC, Quận 1, TP.HCM</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="join-us">
            <h3>HÃY TRỞ THÀNH MỘT PHẦN CỦA BEAUTYCARE!</h3>
            <p>Chúng tôi tin rằng mỗi cá nhân đều có thể tỏa sáng và phát triển trong môi trường chuyên nghiệp của BeautyCare.</p>
        </div>
    </div>
</div>

<style>
.career-container {
    max-width: 900px;
    margin: 30px auto;
    padding: 0 20px;
}

.career-header {
    text-align: center;
    margin-bottom: 40px;
}

.career-header h1 {
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

.career-intro {
    color: #555;
    font-size: 16px;
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.6;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 40px 0 20px;
    color: #d23669;
}

.section-title h2 {
    margin: 0;
    font-size: 22px;
}

.section-title i {
    font-size: 24px;
}

.career-list {
    margin-bottom: 40px;
}

.career-item {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.05);
    overflow: hidden;
}

.career-number {
    background: linear-gradient(to bottom, #ff6b81, #d23669);
    color: white;
    font-size: 24px;
    font-weight: bold;
    width: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.career-details {
    padding: 25px;
    flex: 1;
}

.career-details h3 {
    color: #d23669;
    margin-top: 0;
    margin-bottom: 20px;
    font-size: 20px;
}

.job-description, .job-requirement, .job-benefit {
    margin-bottom: 20px;
}

.job-description h4, .job-requirement h4, .job-benefit h4 {
    color: #555;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    font-size: 16px;
}

.job-description ul, .job-requirement ul, .job-benefit ul {
    padding-left: 30px;
    color: #555;
}

.job-description li, .job-requirement li, .job-benefit li {
    margin-bottom: 8px;
    position: relative;
}

.job-description li:before, .job-requirement li:before, .job-benefit li:before {
    content: "•";
    color: #ff6b81;
    font-size: 20px;
    position: absolute;
    left: -20px;
    top: -2px;
}

.application-section {
    background-color: #fff9f9;
    padding: 25px;
    border-radius: 8px;
    margin-bottom: 30px;
    border-left: 4px solid #ff6b81;
}

.application-content {
    text-align: center;
    color: #555;
}

.application-email {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background-color: #d23669;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    margin: 15px 0;
    font-weight: 500;
    transition: all 0.3s;
}

.application-email:hover {
    background-color: #ff6b81;
    transform: translateY(-2px);
}

.contact-info {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-top: 20px;
    flex-wrap: wrap;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #555;
}

.contact-item i {
    color: #d23669;
}

.join-us {
    text-align: center;
    padding: 30px;
    background: linear-gradient(to right, rgba(255,107,129,0.1), rgba(210,54,105,0.1));
    border-radius: 8px;
    margin-top: 40px;
}

.join-us h3 {
    color: #d23669;
    margin-bottom: 15px;
}

.join-us p {
    color: #555;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .career-item {
        flex-direction: column;
    }
    
    .career-number {
        width: 100%;
        height: 50px;
    }
    
    .contact-info {
        flex-direction: column;
        gap: 15px;
        align-items: center;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
