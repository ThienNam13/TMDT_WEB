<?php
include 'includes/header.php';
include 'includes/navbar.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'php/database.php';

// Get user information
$stmt = $conn->prepare("SELECT fullname, email FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($fullname, $email);
$stmt->fetch();
$stmt->close();

// Get cart items from session (if exists)
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
$shippingFee = 15000; // 15,000 VND

// Calculate total from cart items
if (!empty($cartItems)) {
    foreach ($cartItems as $productId => $item) {
        $quantity = $item['qty'] ?? 1;

        $stmt = $conn->prepare("SELECT gia FROM san_pham WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $stmt->bind_result($price);
        if ($stmt->fetch()) {
            $total += $price * $quantity;
        }
        $stmt->close();
    }
}

$grandTotal = $total + $shippingFee;
?>

<link rel="stylesheet" href="assets/css/style.css">
<style>
.payment-container {
    max-width: 800px;
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

.form-group textarea {
    resize: vertical;
    min-height: 80px;
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
</style>

<div class="payment-container">
    <h2>Thông tin thanh toán</h2>
    
    <div id="message" class="alert" style="display:none;"></div>
    
    <form id="paymentForm">
        <!-- Shipping Information Section -->
        <div class="payment-section">
            <h3>Thông tin giao hàng</h3>
            
            <div class="form-group">
                <label for="ho_ten">Họ và tên người nhận *</label>
                <input type="text" id="ho_ten" name="ho_ten" value="<?= htmlspecialchars($fullname ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="sdt">Số điện thoại *</label>
                <input type="tel" id="sdt" name="sdt" placeholder="Nhập số điện thoại" required>
            </div>
            
            <div class="form-group">
                <label for="dia_chi">Địa chỉ *</label>
                <textarea id="dia_chi" name="dia_chi" placeholder="Nhập địa chỉ chi tiết" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="phuong_xa">Phường/Xã</label>
                <input type="text" id="phuong_xa" name="phuong_xa" placeholder="Nhập phường/xã">
            </div>
            
            <div class="form-group">
                <label for="khu_vuc">Quận/Huyện *</label>
                <input type="text" id="khu_vuc" name="khu_vuc" placeholder="Nhập quận/huyện" required>
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentForm');
    const messageDiv = document.getElementById('message');
    
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
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = form.querySelector('.submit-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Đang xử lý...';
        
        // Get form data
        const formData = new FormData(form);
        
        // Add cart items to form data
        const cartItems = <?= json_encode($cartItems) ?>;
        formData.append('items', JSON.stringify(cartItems));
        
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
    });
    
    function showMessage(message, type) {
        messageDiv.textContent = message;
        messageDiv.className = `alert ${type}`;
        messageDiv.style.display = 'block';
        
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 5000);
    }
});
</script>

<?php include 'includes/footer.php'; ?>
