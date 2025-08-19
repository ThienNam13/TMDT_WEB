<?php
$pageTitle = "Chính sách đổi trả";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="policy-container">
    <div class="policy-header">
        <h1>CHÍNH SÁCH ĐỔI TRẢ SẢN PHẨM</h1>
        <div class="divider"></div>
    </div>
    
    <div class="policy-content">
        <div class="intro-section">
            <p><strong>BeautyCare</strong> luôn mong muốn mang đến trải nghiệm mua sắm hài lòng nhất. Chúng tôi áp dụng chính sách đổi trả linh hoạt, đảm bảo quyền lợi của khách hàng.</p>
        </div>
        
        <div class="condition-section">
            <h3><i class="icon condition-icon"></i> ĐIỀU KIỆN ĐỔI TRẢ</h3>
            <div class="content-box">
                <ul class="policy-list">
                    <li>Sản phẩm còn nguyên tem mác, chưa qua sử dụng, còn đầy đủ phụ kiện, quà tặng đi kèm (nếu có).</li>
                    <li>Sản phẩm bị lỗi do nhà sản xuất hoặc do vận chuyển (hư hỏng, bể vỡ).</li>
                    <li>Sản phẩm không đúng chủng loại, mẫu mã so với đơn hàng đã đặt.</li>
                    <li>Thời gian đổi trả trong vòng 7 ngày kể từ khi nhận hàng.</li>
                </ul>
            </div>
        </div>
        
        <div class="exception-section">
            <h3><i class="icon exception-icon"></i> CÁC TRƯỜNG HỢP KHÔNG ĐƯỢC ĐỔI TRẢ</h3>
            <div class="content-box">
                <ul class="policy-list">
                    <li>Sản phẩm đã qua sử dụng, không còn nguyên vẹn.</li>
                    <li>Hết thời gian 7 ngày kể từ khi nhận hàng.</li>
                    <li>Sản phẩm không có lỗi và khách hàng thay đổi ý định mua hàng.</li>
                </ul>
            </div>
        </div>
        
        <div class="process-section">
            <h3><i class="icon process-icon"></i> QUY TRÌNH ĐỔI TRẢ</h3>
            <div class="content-box">
                <div class="process-step">
                    <strong>Bước 1:</strong> Liên hệ hotline <span class="highlight">0123 456 789</span> hoặc gửi yêu cầu hỗ trợ qua email <span class="highlight">support@beautycare.com</span>, cung cấp mã đơn hàng và hình ảnh sản phẩm cần đổi/trả.
                </div>
                <div class="process-step">
                    <strong>Bước 2:</strong> BeautyCare sẽ xác nhận và hướng dẫn bạn cách thức gửi sản phẩm về cho chúng tôi.
                </div>
                <div class="process-step">
                    <strong>Bước 3:</strong> Sau khi nhận và kiểm tra sản phẩm, chúng tôi sẽ tiến hành đổi sản phẩm mới hoặc hoàn tiền cho bạn.
                </div>
            </div>
        </div>
        
        <div class="closing-section">
            <p>Mọi thắc mắc về chính sách đổi trả, vui lòng liên hệ với chúng tôi để được hỗ trợ!</p>
        </div>
    </div>
</div>

<style>
.policy-container {
    max-width: 900px;
    margin: 30px auto;
    padding: 0 20px;
}

.policy-header {
    text-align: center;
    margin-bottom: 40px;
}

.policy-header h1 {
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

.policy-content p {
    color: #555;
    line-height: 1.8;
    font-size: 16px;
}

.intro-section {
    background-color: #fff9f9;
    padding: 25px;
    border-radius: 8px;
    margin-bottom: 30px;
    border-left: 4px solid #ff6b81;
}

.condition-section, .exception-section, .process-section {
    margin-bottom: 30px;
}

.condition-section h3, .exception-section h3, .process-section h3 {
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

.condition-icon {
    background-image: url('condition-icon.png');
}

.exception-icon {
    background-image: url('exception-icon.png');
}

.process-icon {
    background-image: url('process-icon.png');
}

.content-box {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

.policy-list {
    padding-left: 20px;
}

.policy-list li {
    margin-bottom: 15px;
    color: #555;
    list-style-type: disc;
}

.process-step {
    margin-bottom: 15px;
    color: #555;
    line-height: 1.8;
}

.highlight {
    color: #d23669;
    font-weight: bold;
}

.closing-section {
    text-align: center;
    margin-top: 40px;
    font-style: italic;
    color: #888;
}
</style>
<?php include 'includes/footer.php'; ?>
