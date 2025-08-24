<?php
$pageTitle = "Phương thức vận chuyển";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="shipping-container">
    <div class="shipping-header">
        <h1>PHƯƠNG THỨC VẬN CHUYỂN</h1>
        <div class="divider"></div>
    </div>
    
    <div class="shipping-content">
        <div class="intro-section">
            <p><strong>BeautyCare</strong> luôn nỗ lực để giao hàng đến tay khách hàng một cách nhanh chóng và an toàn nhất với chất lượng dịch vụ tốt nhất.</p>
        </div>
        
        <div class="partners-section">
            <h3><i class="icon partner-icon"></i> ĐỐI TÁC VẬN CHUYỂN</h3>
            <div class="content-box">
                <div class="partner-logos">
                    <div class="partner-logos">
                    <div class="partner-item">
                        <i class="fas fa-truck fa-3x"></i>
                        <span>Giao Hàng Nhanh</span>
                    </div>
                    <div class="partner-item">
                        <i class="fas fa-shipping-fast fa-3x"></i>
                        <span>Viettel Post</span>
                    </div>
                    <div class="partner-item">
                        <i class="fas fa-box-open fa-3x"></i>
                        <span>J&T Express</span>
                    </div>
                </div>
                <p>Chúng tôi hợp tác với các đơn vị vận chuyển uy tín hàng đầu để đảm bảo hàng hóa đến tay bạn an toàn và nhanh chóng.</p>
            </div>
        </div>
        
        <div class="timeline-section">
            <h3><i class="icon timeline-icon"></i> THỜI GIAN GIAO HÀNG</h3>
            <div class="content-box">
                <ul class="shipping-list">
                    <li>
                        <strong>Nội thành TP.HCM:</strong> 
                        <span class="highlight-text">1-2 ngày làm việc</span>
                    </li>
                    <li>
                        <strong>Các tỉnh/thành phố khác:</strong> 
                        <span class="highlight-text"> *Chưa hỗ trợ*</span>
                    </li>
                    <li class="note-item">
                        <i class="fas fa-info-circle"></i> Lưu ý: Thời gian giao hàng có thể thay đổi tùy thuộc vào thời điểm đặt hàng, các ngày lễ, Tết hoặc điều kiện thời tiết.
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="fee-section">
            <h3><i class="icon fee-icon"></i> PHÍ VẬN CHUYỂN</h3>
            <div class="content-box">
                <ul class="shipping-list">
                    <li>Phí vận chuyển được tính dựa trên <strong>trọng lượng</strong> sản phẩm và <strong>địa chỉ</strong> nhận hàng.</li>
                    <li>
                        <span class="free-shipping">BeautyCare thường xuyên có chương trình <strong>MIỄN PHÍ VẬN CHUYỂN</strong> cho đơn hàng đạt giá trị tối thiểu.</span>
                        <a href="#" class="check-link">Kiểm tra tại trang thanh toán <i class="fas fa-arrow-right"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="support-section">
            <p>Mọi thắc mắc về vận chuyển, vui lòng liên hệ hotline <span class="highlight">1900 1234</span> hoặc email <span class="highlight">support@beautycare.com</span></p>
        </div>
    </div>
</div>

<style>


.shipping-header {
    text-align: center;
    margin-bottom: 40px;
}

.shipping-header h1 {
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

.partners-section, .timeline-section, .fee-section {
    margin-bottom: 30px;
}

.partners-section h3, .timeline-section h3, .fee-section h3 {
    color: #d23669;
    font-size: 20px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
}

.icon {
    display: inline-block;
    width: 24px;
    height: 24px;
    margin-right: 10px;
    background-size: contain;
}

.partner-icon {
    background-image: url('images/partner-icon.png');
}

.timeline-icon {
    background-image: url('images/timeline-icon.png');
}

.fee-icon {
    background-image: url('images/fee-icon.png');
}

.content-box {
    background-color: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

.partner-logos {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.partner-item {
    text-align: center;
    margin: 10px;
    flex: 1;
    min-width: 120px;
}

.partner-item img {
    height: 50px;
    margin-bottom: 10px;
    object-fit: contain;
}

.partner-item span {
    display: block;
    color: #555;
    font-size: 14px;
}

.shipping-list {
    padding-left: 20px;
}

.shipping-list li {
    margin-bottom: 15px;
    color: #555;
    list-style-type: none;
    position: relative;
    padding-left: 25px;
}

.shipping-list li:before {
    content: "•";
    color: #ff6b81;
    font-size: 20px;
    position: absolute;
    left: 0;
    top: -2px;
}

.highlight {
    color: #d23669;
    font-weight: bold;
}

.highlight-text {
    color: #d23669;
    font-weight: 500;
}

.note-item {
    background-color: #f8f9fa;
    padding: 10px 15px;
    border-radius: 5px;
    margin-top: 15px;
    border-left: 3px solid #ffc107;
}

.note-item i {
    color: #ffc107;
    margin-right: 8px;
}

.free-shipping {
    color: #28a745;
    font-weight: 500;
}

.check-link {
    display: inline-block;
    margin-top: 8px;
    color: #d23669;
    text-decoration: none;
    font-size: 14px;
}

.check-link:hover {
    text-decoration: underline;
}

.check-link i {
    margin-left: 5px;
    font-size: 12px;
}

.support-section {
    text-align: center;
    margin-top: 40px;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 8px;
    font-size: 15px;
}
</style>

<?php include 'includes/footer.php'; ?>
