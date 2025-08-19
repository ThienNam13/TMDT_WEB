<?php
$pageTitle = "Hướng dẫn đặt hàng";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="order-guide-container">
    <div class="order-guide-header">
        <h1>HƯỚNG DẪN ĐẶT HÀNG TRÊN BEAUTYCARE</h1>
        <div class="divider"></div>
        <p class="intro-text">Bạn có thể dễ dàng đặt hàng tại BeautyCare chỉ với vài bước đơn giản.</p>
    </div>

    <div class="order-steps">
        <!-- Bước 1 -->
        <div class="step-container">
            <div class="step-number">1</div>
            <div class="step-content">
                <div class="step-header">
                    <i class="fas fa-search step-icon"></i>
                    <h3>Tìm kiếm sản phẩm</h3>
                </div>
                <div class="step-details">
                    <p>Bạn có thể tìm kiếm sản phẩm mong muốn bằng cách:</p>
                    <ul class="step-list">
                        <li>Sử dụng thanh tìm kiếm ở đầu trang</li>
                        <li>Duyệt qua các danh mục sản phẩm trên menu</li>
                    </ul>
                    <div class="step-image-placeholder">
                        <!-- Thay bằng hình ảnh thực tế sau -->
                        <img src="assets/img/huongdan1.jpg" alt="Tìm kiếm sản phẩm" class="step-image">
                    </div>
                </div>
            </div>
        </div>

        <!-- Bước 2 -->
        <div class="step-container">
            <div class="step-number">2</div>
            <div class="step-content">
                <div class="step-header">
                    <i class="fas fa-cart-plus step-icon"></i>
                    <h3>Thêm sản phẩm vào giỏ hàng</h3>
                </div>
                <div class="step-details">
                    <p>Khi đã tìm được sản phẩm ưng ý, bạn nhấn vào nút "Thêm vào giỏ hàng".</p>
                    <div class="step-image-placeholder">
                        <img src="assets/img/huongdan2.jpg" alt="Thêm vào giỏ hàng" class="step-image">
                    </div>
                </div>
            </div>
        </div>

        <!-- Bước 3 -->
        <div class="step-container">
            <div class="step-number">3</div>
            <div class="step-content">
                <div class="step-header">
                    <i class="fas fa-shopping-cart step-icon"></i>
                    <h3>Xem giỏ hàng và thanh toán</h3>
                </div>
                <div class="step-details">
                    <p>Click vào biểu tượng giỏ hàng để xem lại các sản phẩm đã chọn. Tại đây, bạn có thể:</p>
                    <ul class="step-list">
                        <li>Điều chỉnh số lượng sản phẩm</li>
                        <li>Xóa sản phẩm không mong muốn</li>
                        <li>Nhấn "Thanh toán" để tiếp tục</li>
                    </ul>
                    <div class="step-image-placeholder">
                        <img src="assets/img/huongdan7.jpg" alt="Giỏ hàng" class="step-image">
                    </div>
                </div>
            </div>
        </div>

        <!-- Bước 4 -->
        <div class="step-container">
            <div class="step-number">4</div>
            <div class="step-content">
                <div class="step-header">
                    <i class="fas fa-clipboard-check step-icon"></i>
                    <h3>Điền thông tin và xác nhận</h3>
                </div>
                <div class="step-details">
                    <p>Điền đầy đủ thông tin giao hàng (tên, số điện thoại, địa chỉ). Chọn hình thức vận chuyển và thanh toán. Cuối cùng, nhấn "Đặt hàng" để hoàn tất.</p>
                    <p class="highlight-text">Sau khi đặt hàng thành công, chúng tôi sẽ gửi email xác nhận và liên hệ để giao hàng sớm nhất.</p>
                    <div class="step-image-placeholder">
                        <img src="assets/img/huongdan6.jpg" alt="Thanh toán" class="step-image">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="support-section">
        <div class="support-box">
            <i class="fas fa-question-circle support-icon"></i>
            <h3>Cần hỗ trợ thêm?</h3>
            <p>Liên hệ với chúng tôi qua Hotline <span class="highlight">1900 1234</span> hoặc <a href="contact.php" class="support-link">trang liên hệ</a></p>
        </div>
    </div>
</div>

<style>
.order-guide-container {
    max-width: 900px;
    margin: 30px auto;
    padding: 0 20px;
}

.order-guide-header {
    text-align: center;
    margin-bottom: 40px;
}

.order-guide-header h1 {
    color: #d23669;
    font-size: 28px;
    margin-bottom: 15px;
}

.divider {
    width: 100px;
    height: 3px;
    background: linear-gradient(to right, #ff6b81, #d23669);
    margin: 15px auto 25px;
}

.intro-text {
    color: #666;
    font-size: 16px;
    line-height: 1.6;
    max-width: 700px;
    margin: 0 auto;
    text-align: center;
}

.order-steps {
    margin-top: 40px;
}

.step-container {
    display: flex;
    margin-bottom: 40px;
    position: relative;
}

.step-container:before {
    content: '';
    position: absolute;
    left: 25px;
    top: 40px;
    bottom: -40px;
    width: 2px;
    background: #ffebee;
    z-index: 1;
}

.step-container:last-child:before {
    display: none;
}

.step-number {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #ff6b81, #d23669);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    margin-right: 20px;
    flex-shrink: 0;
    position: relative;
    z-index: 2;
    box-shadow: 0 4px 10px rgba(210, 54, 105, 0.3);
}

.step-content {
    flex-grow: 1;
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.05);
    border-top: 3px solid #ff6b81;
}

.step-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.step-header h3 {
    color: #d23669;
    font-size: 20px;
    margin: 0;
}

.step-icon {
    color: #ff6b81;
    font-size: 24px;
    margin-right: 15px;
}

.step-details p {
    color: #555;
    line-height: 1.8;
    margin-bottom: 15px;
}

.step-list {
    padding-left: 20px;
    color: #555;
    margin-bottom: 20px;
}

.step-list li {
    margin-bottom: 8px;
    position: relative;
    padding-left: 15px;
}

.step-list li:before {
    content: '•';
    color: #ff6b81;
    position: absolute;
    left: 0;
}

.step-image-placeholder {
    margin-top: 20px;
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    border: 1px solid #eee;
}

.step-image {
    width: 100%;
    height: auto;
    display: block;
}

.highlight-text {
    background-color: #fff9f9;
    padding: 15px;
    border-radius: 6px;
    border-left: 3px solid #ff6b81;
    color: #555;
}

.support-section {
    margin-top: 50px;
    text-align: center;
}

.support-box {
    background: linear-gradient(135deg, #fff9f9, #ffffff);
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 5px 20px rgba(210, 54, 105, 0.1);
    border: 1px solid #ffebee;
    max-width: 600px;
    margin: 0 auto;
}

.support-icon {
    font-size: 40px;
    color: #d23669;
    margin-bottom: 15px;
}

.support-box h3 {
    color: #d23669;
    margin-bottom: 10px;
}

.support-box p {
    color: #666;
    margin-bottom: 0;
}

.highlight {
    color: #d23669;
    font-weight: bold;
}

.support-link {
    color: #ff6b81;
    font-weight: bold;
    text-decoration: none;
}

.support-link:hover {
    text-decoration: underline;
}
</style>

<?php include 'includes/footer.php'; ?>
