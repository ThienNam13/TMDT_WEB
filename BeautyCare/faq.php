<?php
$pageTitle = "Các câu hỏi thường gặp";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="faq-container">
    <div class="faq-header">
        <h1>CÁC CÂU HỎI THƯỜNG GẶP</h1>
        <div class="divider"></div>
        <p class="intro-text">Chào mừng bạn đến với BeautyCare! Dưới đây là một số câu hỏi thường gặp giúp bạn có trải nghiệm mua sắm tốt nhất.</p>
    </div>

    <div class="faq-content">
        <div class="faq-section">
            <div class="section-header">
                <i class="fas fa-spa section-icon"></i>
                <h3>Về sản phẩm</h3>
            </div>
            
            <div class="faq-item">
                <div class="question-box">
                    <h4><span class="question-number">1.</span> Sản phẩm của BeautyCare có chính hãng không?</h4>
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="answer-box">
                    <p>Tất cả sản phẩm tại BeautyCare đều được nhập khẩu chính ngạch từ các thương hiệu uy tín trên thế giới. Chúng tôi cam kết 100% sản phẩm chính hãng, có tem nhãn, giấy tờ đầy đủ.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="question-box">
                    <h4><span class="question-number">2.</span> Làm thế nào để kiểm tra hạn sử dụng của sản phẩm?</h4>
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="answer-box">
                    <p>Hạn sử dụng được in rõ trên bao bì sản phẩm. Ngoài ra, bạn có thể kiểm tra mã vạch hoặc mã lô sản phẩm (batch code) để xác thực thông tin.</p>
                </div>
            </div>
        </div>

        <div class="faq-section">
            <div class="section-header">
                <i class="fas fa-shopping-bag section-icon"></i>
                <h3>Về đơn hàng và thanh toán</h3>
            </div>
            
            <div class="faq-item">
                <div class="question-box">
                    <h4><span class="question-number">1.</span> Tôi có thể thanh toán bằng những hình thức nào?</h4>
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="answer-box">
                    <p>Chúng tôi chấp nhận nhiều hình thức thanh toán: thanh toán khi nhận hàng (COD), chuyển khoản ngân hàng, ví điện tử (MoMo, ZaloPay) và thẻ tín dụng/thẻ ghi nợ (Visa, Mastercard).</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="question-box">
                    <h4><span class="question-number">2.</span> Sau bao lâu tôi sẽ nhận được hàng?</h4>
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="answer-box">
                    <p>Thời gian giao hàng phụ thuộc vào địa chỉ của bạn. Thường là 1-3 ngày làm việc trong TP.HCM và 3-5 ngày làm việc đối với các tỉnh/thành phố khác.</p>
                </div>
            </div>
        </div>

        <div class="faq-section">
            <div class="section-header">
                <i class="fas fa-headset section-icon"></i>
                <h3>Về chính sách và hỗ trợ</h3>
            </div>
            
            <div class="faq-item">
                <div class="question-box">
                    <h4><span class="question-number">1.</span> Tôi muốn đổi/trả sản phẩm thì làm thế nào?</h4>
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="answer-box">
                    <p>Bạn vui lòng tham khảo chi tiết tại trang <a href="return-policy.php">Chính sách đổi trả</a> hoặc liên hệ với bộ phận hỗ trợ khách hàng qua hotline.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="question-box">
                    <h4><span class="question-number">2.</span> Tôi cần tư vấn sản phẩm, BeautyCare có hỗ trợ không?</h4>
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="answer-box">
                    <p>Đội ngũ tư vấn viên của chúng tôi luôn sẵn sàng hỗ trợ bạn. Vui lòng liên hệ qua Hotline hoặc Fanpage để được tư vấn miễn phí.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.faq-container {
    max-width: 900px;
    margin: 30px auto;
    padding: 0 20px;
}

.faq-header {
    text-align: center;
    margin-bottom: 40px;
}

.faq-header h1 {
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
}

.faq-section {
    margin-bottom: 40px;
}

.section-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ffebee;
}

.section-header h3 {
    color: #d23669;
    font-size: 22px;
    margin: 0;
}

.section-icon {
    color: #ff6b81;
    font-size: 24px;
    margin-right: 15px;
}

.faq-item {
    margin-bottom: 15px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.question-box {
    background-color: #fff9f9;
    padding: 18px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.question-box:hover {
    background-color: #ffebee;
}

.question-box h4 {
    color: #333;
    font-size: 16px;
    margin: 0;
    display: flex;
    align-items: center;
}

.question-number {
    color: #d23669;
    font-weight: bold;
    margin-right: 10px;
}

.toggle-icon {
    color: #d23669;
    transition: transform 0.3s ease;
}

.answer-box {
    background-color: white;
    padding: 0 20px;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease, padding 0.3s ease;
}

.answer-box p {
    color: #555;
    line-height: 1.8;
    font-size: 15px;
    margin: 0;
    padding: 5px 0 20px;
}

.answer-box a {
    color: #ff6b81;
    text-decoration: none;
    font-weight: bold;
}

.answer-box a:hover {
    text-decoration: underline;
}

/* Active state */
.faq-item.active .question-box {
    background-color: #ffebee;
}

.faq-item.active .toggle-icon {
    transform: rotate(180deg);
}

.faq-item.active .answer-box {
    max-height: 500px;
    padding: 0 20px 10px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const questionBox = item.querySelector('.question-box');
        
        questionBox.addEventListener('click', () => {
            item.classList.toggle('active');
            
            // Close other open items
            faqItems.forEach(otherItem => {
                if (otherItem !== item && otherItem.classList.contains('active')) {
                    otherItem.classList.remove('active');
                }
            });
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
