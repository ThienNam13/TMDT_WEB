<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer Example</title>
    <style>
        /* CSS cho footer */
        .site-footer {
            background-color: #cdb4db;
            color: #333;
            padding: 40px 20px;
            font-family: 'Arial', sans-serif;
            border-top: 1px solid #e0e0e0;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 30px;
        }

        .footer-section {
            flex: 1;
            min-width: 220px;
            margin-bottom: 20px;
        }

        .footer-section h3 {
            font-size: 16px;
            text-transform: uppercase;
            margin-bottom: 15px;
            color: #111;
        }

        .footer-section p, .footer-section ul {
            font-size: 14px;
            line-height: 1.6;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-section ul li a {
            color: #555;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section ul li a:hover {
            color: #007bff;
        }

        .social-icons a {
            display: inline-block;
            margin-right: 15px;
        }

        .social-icons img {
            height: 30px;
        }

        .payment-logos img {
            height: 30px;
            margin-right: 10px;
        }

        .footer-newsletter input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .footer-newsletter button {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .footer-newsletter button:hover {
            background-color: #555;
        }

        .map-container {
            width: 100%;
            height: 250px;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 15px;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 40px auto 0;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 14px;
            color: #777;
        }

        .footer-bottom p {
            margin: 0;
        }

        /* Media Queries cho responsive */
        @media (max-width: 768px) {
            .footer-container {
                flex-direction: column;
            }
            .footer-section {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>

<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>BeautyCare</h3>
            <p>Chuyên cung cấp mỹ phẩm chính hãng, an toàn, giá tốt. Cam kết mang đến vẻ đẹp rạng rỡ và sự tự tin cho bạn.</p>
            <p><strong>Hotline:</strong> <a href="tel:0367513155">0367513155</a></p>
            <p><strong>Email:</strong> <a href="mailto:support@beautycare.com">support@beautycare.com</a></p>
            <p><strong>Địa chỉ:</strong> <a href="https://maps.app.goo.gl/YbFqoHRjbSBLi9xu8" target="_blank">02 Võ Oanh, P. Thạnh Mỹ Tây, TP. HCM</a></p>
            
            <div class="map-container">
                <iframe
                    width="100%"
                    height="100%"
                    frameborder="0" style="border:0"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.167265888062!2d106.69766951474936!3d10.79633639231454!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317528e5784a0c8b%3A0xc3f7a4d5386f6d0a!2zNiBQaGFuIMSQxINuZyBMxrB1LCBQaMaw4buduZyBCw6xuaCBUaOG6oW5oLCBRdeG6rW4gQgA!5e0!3m2!1svi!2s!4v1628784841804!5m2!1svi!2s"
                    allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>

        <div class="footer-section">
            <h3>Hỗ trợ khách hàng</h3>
            <ul>
                <li><a href="faq.php">Các câu hỏi thường gặp</a></li>
                <li><a href="return-policy.php">Chính sách đổi trả</a></li>
                <li><a href="order-guide.php">Hướng dẫn đặt hàng</a></li>
                <li><a href="shipping-methods.php">Phương thức vận chuyển</a></li>
                <li><a href="support.php">Gửi yêu cầu hỗ trợ</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>Liên kết nhanh</h3>
            <ul>
                <li><a href="about.php">Giới thiệu</a></li>
                <li><a href="privacy-policy.php">Chính sách bảo mật</a></li>
                <li><a href="terms.php">Điều khoản sử dụng</a></li>
                <li><a href="contact.php">Liên hệ</a></li>
                <li><a href="careers.php">Tuyển dụng</a></li>
            </ul>
        </div>
        
        <div class="footer-section">
            <h3>Kết nối với chúng tôi</h3>
            <div class="social-icons">
                <a href="https://www.facebook.com/share/1CAJq7h7M1/?mibextid=wwXIfr" target="_blank" rel="noopener">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/1/1b/Facebook_icon.svg" alt="Facebook" style="height: 30px;">
                </a>
                <a href="https://www.instagram.com/beauty.care.vn/?igsh=YzdrdXF1OGE3YjZh&utm_source=qr" target="_blank" rel="noopener">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" alt="Instagram" style="height: 30px;">
                </a>
                <a href="https://www.youtube.com/@beautycare-HCM" target="_blank" rel="noopener">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/09/YouTube_full-color_icon_(2017).svg" alt="YouTube" style="height: 30px;">
                </a>
            </div>

            <h3 style="margin-top: 30px;">Phương thức thanh toán</h3>
            <div class="payment-logos">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa" style="height: 30px;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="Mastercard" style="height: 30px;">
                <img src="assets/img/JCB_logo.png" alt="JCB" style="height: 30px;">
            </div>

            <h3 style="margin-top: 30px;">Tải ứng dụng</h3>
            <div class="app-links">
                <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" style="height: 40px;"></a>
                <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" style="height: 40px;"></a>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> BeautyCare. Tất cả quyền được bảo lưu.</p>
        <p>Dự án được tạo bởi nhóm sinh viên Trường Đại học Giao thông vận tải Thành phố Hồ Chí Minh.</p>
        <p>Nguyễn Thiên Nam - Đào Duy Cường - Lê Nguyễn Hải Nam - Đặng Hoàng Gia Tiên - Đặng Nguyễn Anh Thư</p>
    </div>
</footer>

</body>
</html>
