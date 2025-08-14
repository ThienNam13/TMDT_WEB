<?php
include 'includes/header.php';
include 'includes/navbar.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'php/database.php';

// Get user information including phone and address
$stmt = $conn->prepare("SELECT fullname, email, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($fullname, $email, $phone, $address);
$stmt->fetch();
$stmt->close();

// Get cart items from session
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
$shippingFee = 15000; // 15,000 VND

// Calculate total from cart items and get product details
$cartDetails = [];
if (!empty($cartItems)) {
    foreach ($cartItems as $productId => $item) {
        $quantity = $item['qty'] ?? 1;
        
        $stmt = $conn->prepare("SELECT id, ten_san_pham, gia, hinh_anh FROM san_pham WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $stmt->bind_result($id, $ten_san_pham, $gia, $hinh_anh);
        if ($stmt->fetch()) {
            $lineTotal = $gia * $quantity;
            $total += $lineTotal;
            $cartDetails[] = [
                'id' => $id,
                'ten_san_pham' => $ten_san_pham,
                'gia' => $gia,
                'hinh_anh' => $hinh_anh,
                'qty' => $quantity,
                'line_total' => $lineTotal
            ];
        }
        $stmt->close();
    }
}

$grandTotal = $total + $shippingFee;

// Ho Chi Minh City wards
$hoChiMinhWards = [
    'Quận 1' => ['Phường Bến Nghé', 'Phường Bến Thành', 'Phường Cầu Kho', 'Phường Cầu Ông Lãnh', 'Phường Cô Giang', 'Phường Đa Kao', 'Phường Nguyễn Cư Trinh', 'Phường Nguyễn Thái Bình', 'Phường Phạm Ngũ Lão', 'Phường Tân Định'],
    'Quận 2' => ['Phường An Khánh', 'Phường An Lợi Đông', 'Phường An Phú', 'Phường Bình An', 'Phường Bình Khánh', 'Phường Bình Trưng Đông', 'Phường Bình Trưng Tây', 'Phường Cát Lái', 'Phường Thạnh Mỹ Lợi', 'Phường Thảo Điền', 'Phường Thủ Thiêm'],
    'Quận 3' => ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 5', 'Phường 6', 'Phường 7', 'Phường 8', 'Phường 9', 'Phường 10', 'Phường 11', 'Phường 12', 'Phường 13', 'Phường 14'],
    'Quận 4' => ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 5', 'Phường 6', 'Phường 7', 'Phường 8', 'Phường 9', 'Phường 10', 'Phường 11', 'Phường 12', 'Phường 13', 'Phường 14', 'Phường 15', 'Phường 16', 'Phường 17', 'Phường 18'],
    'Quận 5' => ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 5', 'Phường 6', 'Phường 7', 'Phường 8', 'Phường 9', 'Phường 10', 'Phường 11', 'Phường 12', 'Phường 13', 'Phường 14', 'Phường 15'],
    'Quận 6' => ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 5', 'Phường 6', 'Phường 7', 'Phường 8', 'Phường 9', 'Phường 10', 'Phường 11', 'Phường 12', 'Phường 13', 'Phường 14'],
    'Quận 7' => ['Phường Bình Thuận', 'Phường Phú Mỹ', 'Phường Phú Thuận', 'Phường Tân Hưng', 'Phường Tân Kiểng', 'Phường Tân Phong', 'Phường Tân Phú', 'Phường Tân Quy', 'Phường Tân Thuận Đông', 'Phường Tân Thuận Tây'],
    'Quận 8' => ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 5', 'Phường 6', 'Phường 7', 'Phường 8', 'Phường 9', 'Phường 10', 'Phường 11', 'Phường 12', 'Phường 13', 'Phường 14', 'Phường 15', 'Phường 16'],
    'Quận 9' => ['Phường Hiệp Phú', 'Phường Long Bình', 'Phường Long Phước', 'Phường Long Thạnh Mỹ', 'Phường Long Trường', 'Phường Phú Hữu', 'Phường Phước Bình', 'Phường Phước Long A', 'Phường Phước Long B', 'Phường Tăng Nhơn Phú A', 'Phường Tăng Nhơn Phú B', 'Phường Tân Phú', 'Phường Trường Thọ'],
    'Quận 10' => ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 5', 'Phường 6', 'Phường 7', 'Phường 8', 'Phường 9', 'Phường 10', 'Phường 11', 'Phường 12', 'Phường 13', 'Phường 14', 'Phường 15'],
    'Quận 11' => ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 5', 'Phường 6', 'Phường 7', 'Phường 8', 'Phường 9', 'Phường 10', 'Phường 11', 'Phường 12', 'Phường 13', 'Phường 14', 'Phường 15', 'Phường 16'],
    'Quận 12' => ['Phường An Phú Đông', 'Phường Đông Hưng Thuận', 'Phường Hiệp Thành', 'Phường Tân Chánh Hiệp', 'Phường Tân Thới Hiệp', 'Phường Tân Thới Nhất', 'Phường Thạnh Lộc', 'Phường Thạnh Xuân', 'Phường Thới An', 'Phường Trung Mỹ Tây'],
    'Quận Bình Tân' => ['Phường An Lạc', 'Phường An Lạc A', 'Phường Bình Hưng Hòa', 'Phường Bình Hưng Hòa A', 'Phường Bình Hưng Hòa B', 'Phường Bình Trị Đông', 'Phường Bình Trị Đông A', 'Phường Bình Trị Đông B', 'Phường Tân Tạo', 'Phường Tân Tạo A'],
    'Quận Bình Thạnh' => ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 5', 'Phường 6', 'Phường 7', 'Phường 11', 'Phường 12', 'Phường 13', 'Phường 14', 'Phường 15', 'Phường 17', 'Phường 19', 'Phường 21', 'Phường 22', 'Phường 24', 'Phường 25', 'Phường 26', 'Phường 27', 'Phường 28'],
    'Quận Gò Vấp' => ['Phường 1', 'Phường 3', 'Phường 4', 'Phường 5', 'Phường 6', 'Phường 7', 'Phường 8', 'Phường 9', 'Phường 10', 'Phường 11', 'Phường 12', 'Phường 13', 'Phường 14', 'Phường 15', 'Phường 16', 'Phường 17'],
    'Quận Phú Nhuận' => ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 5', 'Phường 7', 'Phường 8', 'Phường 9', 'Phường 10', 'Phường 11', 'Phường 12', 'Phường 13', 'Phường 14', 'Phường 15', 'Phường 17'],
    'Quận Tân Bình' => ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 5', 'Phường 6', 'Phường 7', 'Phường 8', 'Phường 9', 'Phường 10', 'Phường 11', 'Phường 12', 'Phường 13', 'Phường 14', 'Phường 15'],
    'Quận Tân Phú' => ['Phường Hiệp Tân', 'Phường Hòa Thạnh', 'Phường Phú Thạnh', 'Phường Phú Thọ Hòa', 'Phường Phú Trung', 'Phường Sơn Kỳ', 'Phường Tân Quý', 'Phường Tân Sơn Nhì', 'Phường Tân Thành', 'Phường Tân Thới Hòa', 'Phường Tây Thạnh'],
    'Quận Thủ Đức' => ['Phường An Khánh', 'Phường An Lợi Đông', 'Phường An Phú', 'Phường Bình Chiểu', 'Phường Bình Thọ', 'Phường Hiệp Bình Chánh', 'Phường Hiệp Bình Phước', 'Phường Linh Chiểu', 'Phường Linh Đông', 'Phường Linh Tây', 'Phường Linh Trung', 'Phường Linh Xuân', 'Phường Tam Bình', 'Phường Tam Phú', 'Phường Trường Thọ']
];
?>

<link rel="stylesheet" href="assets/css/style.css">
<style>
.payment-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 2rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.payment-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
}

.payment-section h3 {
    color: #b5838d;
    margin-bottom: 1rem;
    border-bottom: 2px solid #b5838d;
    padding-bottom: 0.5rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
}

.form-group select {
    cursor: pointer;
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

.form-group .error-message {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: none;
}

.form-group input.error,
.form-group textarea.error,
.form-group select.error {
    border-color: #dc3545;
}

.payment-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.payment-method {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment-method:hover {
    border-color: #b5838d;
}

.payment-method.selected {
    border-color: #b5838d;
    background-color: #f8f0f2;
}

.payment-method input[type="radio"] {
    margin-right: 0.5rem;
}

.cart-items {
    margin-bottom: 1rem;
}

.cart-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 1rem;
    background: #f9f9f9;
}

.cart-item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    margin-right: 1rem;
}

.cart-item-details {
    flex: 1;
}

.cart-item-name {
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: #333;
}

.cart-item-price {
    color: #b5838d;
    font-weight: 500;
}

.cart-item-quantity {
    color: #666;
    font-size: 0.9rem;
}

.cart-item-total {
    font-weight: 600;
    color: #b5838d;
    font-size: 1.1rem;
}

.order-summary {
    background-color: #f9f9f9;
    padding: 1.5rem;
    border-radius: 8px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    padding: 0.5rem 0;
}

.summary-row.total {
    border-top: 2px solid #b5838d;
    font-weight: bold;
    font-size: 1.1rem;
    color: #b5838d;
}

.submit-btn {
    background-color: #b5838d;
    color: white;
    padding: 1rem 2rem;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 500;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
}

.submit-btn:hover {
    background-color: #9a6b75;
}

.submit-btn:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 5px;
    display: none;
}

.alert.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.required {
    color: #dc3545;
}

@media (max-width: 768px) {
    .payment-container {
        margin: 1rem;
        padding: 1rem;
    }
    
    .cart-item {
        flex-direction: column;
        text-align: center;
    }
    
    .cart-item img {
        margin-right: 0;
        margin-bottom: 1rem;
    }
}
</style>

<div class="payment-container">
    <h2>Thông tin thanh toán</h2>
    
    <div id="message" class="alert" style="display:none;"></div>
    
    <?php if (empty($cartDetails)): ?>
        <div class="payment-section">
            <p style="text-align: center; color: #666;">Giỏ hàng của bạn đang trống.</p>
            <div style="text-align: center; margin-top: 1rem;">
                <a href="products.php" class="submit-btn" style="display: inline-block; width: auto; text-decoration: none;">Tiếp tục mua sắm</a>
            </div>
        </div>
    <?php else: ?>
        <form id="paymentForm">
            <!-- Cart Items Section -->
            <div class="payment-section">
                <h3>Sản phẩm trong giỏ hàng</h3>
                <div class="cart-items">
                    <?php foreach ($cartDetails as $item): ?>
                        <div class="cart-item">
                            <img src="assets/img/products/<?= htmlspecialchars($item['hinh_anh']) ?>" alt="<?= htmlspecialchars($item['ten_san_pham']) ?>">
                            <div class="cart-item-details">
                                <div class="cart-item-name"><?= htmlspecialchars($item['ten_san_pham']) ?></div>
                                <div class="cart-item-price"><?= number_format($item['gia'], 0, ',', '.') ?> VNĐ</div>
                                <div class="cart-item-quantity">Số lượng: <?= $item['qty'] ?></div>
                            </div>
                            <div class="cart-item-total">
                                <?= number_format($item['line_total'], 0, ',', '.') ?> VNĐ
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Shipping Information Section -->
            <div class="payment-section">
                <h3>Thông tin giao hàng</h3>
                
                <div class="form-group">
                    <label for="ho_ten">Họ và tên người nhận <span class="required">*</span></label>
                    <input type="text" id="ho_ten" name="ho_ten" value="<?= htmlspecialchars($fullname ?? '') ?>" required>
                    <div class="error-message" id="ho_ten_error"></div>
                </div>
                
                <div class="form-group">
                    <label for="sdt">Số điện thoại <span class="required">*</span></label>
                    <input type="tel" id="sdt" name="sdt" value="<?= htmlspecialchars($phone ?? '') ?>" placeholder="Nhập số điện thoại" required>
                    <div class="error-message" id="sdt_error"></div>
                </div>
                
                <div class="form-group">
                    <label for="dia_chi">Địa chỉ <span class="required">*</span></label>
                    <textarea id="dia_chi" name="dia_chi" placeholder="Nhập địa chỉ chi tiết (số nhà, tên đường)" required><?= htmlspecialchars($address ?? '') ?></textarea>
                    <div class="error-message" id="dia_chi_error"></div>
                </div>
                
                <div class="form-group">
                    <label for="khu_vuc">Khu vực</label>
                    <input type="text" id="khu_vuc" name="khu_vuc" value="TP.HCM" readonly style="background-color: #f5f5f5;">
                </div>
                
                <div class="form-group">
                    <label for="ghi_chu">Ghi chú</label>
                    <textarea id="ghi_chu" name="ghi_chu" placeholder="Ghi chú về đơn hàng (tùy chọn)"></textarea>
                </div>
            </div>
            
            <!-- Payment Method Section -->
            <div class="payment-section">
                <h3>Phương thức thanh toán</h3>
                <div class="payment-methods">
                    <div class="payment-method selected">
                        <input type="radio" id="cod" name="hinh_thuc_thanh_toan" value="COD" checked>
                        <label for="cod">Thanh toán khi nhận hàng (COD)</label>
                    </div>
                    <div class="payment-method">
                        <input type="radio" id="bank" name="hinh_thuc_thanh_toan" value="Bank Transfer">
                        <label for="bank">Chuyển khoản ngân hàng</label>
                    </div>
                    <div class="payment-method">
                        <input type="radio" id="momo" name="hinh_thuc_thanh_toan" value="MoMo">
                        <label for="momo">Ví MoMo</label>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary Section -->
            <div class="payment-section">
                <h3>Tóm tắt đơn hàng</h3>
                <div class="order-summary">
                    <div class="summary-row">
                        <span>Tổng tiền sản phẩm:</span>
                        <span><?= number_format($total, 0, ',', '.') ?> VNĐ</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển:</span>
                        <span><?= number_format($shippingFee, 0, ',', '.') ?> VNĐ</span>
                    </div>
                    <div class="summary-row total">
                        <span>Tổng cộng:</span>
                        <span><?= number_format($grandTotal, 0, ',', '.') ?> VNĐ</span>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="submit-btn">Đặt hàng</button>
        </form>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentForm');
    const messageDiv = document.getElementById('message');
    const khuVucSelect = document.getElementById('khu_vuc');
    const phuongXaSelect = document.getElementById('phuong_xa');
    
    // Ho Chi Minh City wards data
    const hoChiMinhWards = <?= json_encode($hoChiMinhWards) ?>;
    
    // Update wards when district changes
    khuVucSelect.addEventListener('change', function() {
        const selectedDistrict = this.value;
        phuongXaSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        
        if (selectedDistrict && hoChiMinhWards[selectedDistrict]) {
            hoChiMinhWards[selectedDistrict].forEach(ward => {
                const option = document.createElement('option');
                option.value = ward;
                option.textContent = ward;
                phuongXaSelect.appendChild(option);
            });
        }
    });
    
    // Payment method selection
    const paymentMethods = document.querySelectorAll('.payment-method');
    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            // Remove selected class from all methods
            paymentMethods.forEach(m => m.classList.remove('selected'));
            // Add selected class to clicked method
            this.classList.add('selected');
            // Check the radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
        });
    });
    
    // Form validation
    function validateForm() {
        let isValid = true;
        
        // Clear previous errors
        document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.form-group input, .form-group textarea, .form-group select').forEach(el => el.classList.remove('error'));
        
        // Validate Full Name
        const hoTen = document.getElementById('ho_ten').value.trim();
        if (hoTen.length < 2) {
            showFieldError('ho_ten', 'Họ tên phải có ít nhất 2 ký tự');
            isValid = false;
        } else if (!/^[a-zA-ZÀ-ỹ\s]+$/.test(hoTen)) {
            showFieldError('ho_ten', 'Họ tên chỉ được chứa chữ cái và khoảng trắng');
            isValid = false;
        }
        
        // Validate Phone Number
        const sdt = document.getElementById('sdt').value.trim();
        if (!/^[0-9]{10,11}$/.test(sdt)) {
            showFieldError('sdt', 'Số điện thoại phải có 10-11 chữ số');
            isValid = false;
        }
        
        // Validate Address
        const diaChi = document.getElementById('dia_chi').value.trim();
        if (diaChi.length < 10) {
            showFieldError('dia_chi', 'Địa chỉ phải có ít nhất 10 ký tự');
            isValid = false;
        }
        
        return isValid;
    }
    
    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorDiv = document.getElementById(fieldId + '_error');
        field.classList.add('error');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }
    
    // Form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                return;
            }
            
            const submitBtn = form.querySelector('.submit-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang xử lý...';
            
            // Get form data
            const formData = new FormData(form);
            
            // Add cart items to form data with correct field names
            const cartItems = <?= json_encode($cartDetails) ?>;
            const processedItems = cartItems.map(item => ({
                id: item.id,
                quantity: item.qty
            }));
            formData.append('items', JSON.stringify(processedItems));
            
            // Show confirmation dialog
            if (confirm('Xác nhận thanh toán')) {
                // Send order
                fetch('php/process_order.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showMessage('Đặt hàng thành công! Đang chuyển hướng...', 'success');
                        setTimeout(() => {
                            window.location.href = data.redirect || 'order-success.php';
                        }, 2000);
                    } else {
                        showMessage(data.message || 'Có lỗi xảy ra khi đặt hàng', 'error');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Đặt hàng';
                    }
                })
                .catch(error => {
                    showMessage('Có lỗi xảy ra khi kết nối server', 'error');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Đặt hàng';
                });
            } else {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Đặt hàng';
            }
        });
    }
    
    function showMessage(message, type) {
        messageDiv.textContent = message;
        messageDiv.className = `alert ${type}`;
        messageDiv.style.display = 'block';
        
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 5000);
    }
    
    // Initialize wards if district is pre-selected
    if (khuVucSelect.value) {
        khuVucSelect.dispatchEvent(new Event('change'));
    }
});
</script>

<?php include 'includes/footer.php'; ?>
