<?php
$pageTitle = "Giới thiệu";
include 'includes/header.php';
include 'includes/navbar.php';

?>
<div class="about-container">
    <div class="about-header">
        <h1>GIỚI THIỆU VỀ BEAUTYCARE</h1>
        <div class="divider"></div>
    </div>
    
    <div class="about-content">
        <div class="mission-section">
            <p><strong>BeautyCare</strong> được thành lập với sứ mệnh mang đến vẻ đẹp rạng rỡ và sự tự tin cho phụ nữ Việt Nam thông qua những sản phẩm mỹ phẩm chính hãng, chất lượng cao và an toàn tuyệt đối. Chúng tôi tin rằng, mỗi người phụ nữ đều xứng đáng được tỏa sáng với vẻ đẹp riêng của mình.</p>
        </div>
        
        <div class="vision-section">
            <h3><i class="icon vision-icon"></i> TẦM NHÌN</h3>
            <div class="content-box">
                <p>Trở thành một trong những chuỗi cửa hàng mỹ phẩm uy tín hàng đầu Việt Nam, là điểm đến tin cậy của mọi tín đồ làm đẹp.</p>
            </div>
        </div>
        
        <div class="values-section">
            <h3><i class="icon values-icon"></i> GIÁ TRỊ CỐT LÕI</h3>
            <div class="content-box">
                <ul class="values-list">
                    <li>
                        <strong>Chất lượng:</strong> 
                        <span>Chúng tôi chỉ cung cấp các sản phẩm có nguồn gốc rõ ràng, được kiểm định chất lượng nghiêm ngặt.</span>
                    </li>
                    <li>
                        <strong>Tận tâm:</strong> 
                        <span>Đội ngũ nhân viên chuyên nghiệp, nhiệt tình, luôn sẵn sàng lắng nghe và tư vấn để khách hàng tìm được sản phẩm phù hợp nhất.</span>
                    </li>
                    <li>
                        <strong>Uy tín:</strong> 
                        <span>Lấy sự hài lòng của khách hàng làm thước đo thành công. Chúng tôi luôn minh bạch trong mọi chính sách và hoạt động.</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="closing-section">
            <p>Hãy để BeautyCare đồng hành cùng bạn trên hành trình chăm sóc và tôn vinh vẻ đẹp!</p>
        </div>
    </div>
</div>

<style>
.about-container {
    max-width: 900px;
    margin: 30px auto;
    padding: 0 20px;
}

.about-header {
    text-align: center;
    margin-bottom: 40px;
}

.about-header h1 {
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

.about-content p {
    color: #555;
    line-height: 1.8;
    font-size: 16px;
}

.mission-section {
    background-color: #fff9f9;
    padding: 25px;
    border-radius: 8px;
    margin-bottom: 30px;
    border-left: 4px solid #ff6b81;
}

.vision-section, .values-section {
    margin-bottom: 30px;
}

.vision-section h3, .values-section h3 {
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

.vision-icon {
    background-image: url('vision-icon.png');
}

.values-icon {
    background-image: url('values-icon.png');
}

.content-box {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

.values-list {
    padding-left: 20px;
}

.values-list li {
    margin-bottom: 15px;
    color: #555;
}

.values-list strong {
    color: #d23669;
}

.closing-section {
    text-align: center;
    margin-top: 40px;
    font-style: italic;
    color: #888;
}
</style>
<?php include 'includes/footer.php'; ?>
