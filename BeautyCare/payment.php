<?php
session_start();

// Kiểm tra đăng nhập trước khi xuất bất kỳ nội dung nào
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'php/database.php';
include 'includes/header.php';
include 'includes/navbar.php';

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

// Danh sách phường/xã TP.HCM (sau sáp nhập - mẫu, có thể bổ sung/điều chỉnh)
$hcmWards = [
    // Q1 cũ
    'Phường Bến Nghé','Phường Bến Thành','Phường Cầu Kho','Phường Cầu Ông Lãnh','Phường Cô Giang',
    'Phường Đa Kao','Phường Nguyễn Cư Trinh','Phường Nguyễn Thái Bình','Phường Phạm Ngũ Lão','Phường Tân Định',
    // Khu Thủ Đức (TP. Thủ Đức)
    'Phường Thảo Điền','Phường An Phú','Phường An Khánh','Phường Thủ Thiêm','Phường Bình An','Phường Bình Khánh',
    'Phường Cát Lái','Phường Thạnh Mỹ Lợi','Phường An Lợi Đông','Phường Hiệp Phú','Phường Long Bình','Phường Long Phước',
    'Phường Long Thạnh Mỹ','Phường Long Trường','Phường Phú Hữu','Phường Phước Bình','Phường Phước Long A','Phường Phước Long B',
    'Phường Tăng Nhơn Phú A','Phường Tăng Nhơn Phú B','Phường Tân Phú','Phường Trường Thọ','Phường Linh Chiểu','Phường Linh Tây',
    'Phường Linh Trung','Phường Linh Xuân','Phường Linh Đông','Phường Bình Thọ','Phường Bình Chiểu','Phường Hiệp Bình Chánh','Phường Hiệp Bình Phước',
    'Phường Tam Bình','Phường Tam Phú',
    // Bình Thạnh cũ
    'Phường Gia Định', 'Phường Bình Thạnh', 'Phường Bình Lợi Trung', 'Phường Thạnh Mỹ Tây', 'Phường Bình Quới',
    // Q7 cũ
    'Phường Tân Phú','Phường Tân Phong','Phường Tân Quy','Phường Tân Kiểng','Phường Tân Hưng','Phường Bình Thuận',
    'Phường Phú Thuận','Phường Tân Thuận Đông','Phường Tân Thuận Tây','Phường Phú Mỹ',
    // Q4 cũ
    'Phường Khánh Hội', 'Phường Vĩnh Hội', 'Phường Xóm Chiếu',
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
                    <label for="dia_chi">Số nhà, tên đường <span class="required">*</span></label>
                    <input type="text" id="dia_chi" name="dia_chi" placeholder="Ví dụ: 123 Lý Thường Kiệt" value="<?= htmlspecialchars($address ?? '') ?>" required>
                    <div class="error-message" id="dia_chi_error"></div>
                </div>

                <div class="form-group">
                    <label for="phuong_xa">Phường/Xã <span class="required">*</span></label>
                    <input list="phuongList" id="phuong_xa" name="phuong_xa" placeholder="Gõ để chọn phường/xã" required>
                    <datalist id="phuongList">                        <option value="">-- Chọn phường/xã --</option>
                        <?php foreach ($hcmWards as $ward): ?>
                            <option value="<?= htmlspecialchars($ward) ?>">
                        <?php endforeach; ?>
                    </datalist>
                    <div class="error-message" id="phuong_xa_error"></div>
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
                        <p style="font-size: 0.85rem; color:#777;">*Phí vận chuyển áp dụng toàn TP.HCM</p>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentForm');
    const messageDiv = document.getElementById('message');

    // Chọn phương thức thanh toán
    const paymentMethods = document.querySelectorAll('.payment-method');
    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            paymentMethods.forEach(m => m.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });

    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorDiv = document.getElementById(fieldId + '_error');
        field.classList.add('error');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }

    function validateForm() {
        let isValid = true;

        document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.form-group input, .form-group textarea, .form-group select')
            .forEach(el => el.classList.remove('error'));

        const hoTen = document.getElementById('ho_ten').value.trim();
        if (hoTen.length < 2) {
            showFieldError('ho_ten', 'Họ tên phải có ít nhất 2 ký tự');
            isValid = false;
        } else if (!/^[a-zA-ZÀ-ỹ\s]+$/.test(hoTen)) {
            showFieldError('ho_ten', 'Họ tên chỉ được chứa chữ cái và khoảng trắng');
            isValid = false;
        }

        const sdt = document.getElementById('sdt').value.trim();
        if (!/^[0-9]{10,11}$/.test(sdt)) {
            showFieldError('sdt', 'Số điện thoại phải có 10-11 chữ số');
            isValid = false;
        }

        const diaChi = document.getElementById('dia_chi').value.trim();
        if (diaChi.length < 5) {
            showFieldError('dia_chi', 'Địa chỉ phải có ít nhất 5 ký tự');
            isValid = false;
        }

        const phuongXa = document.getElementById('phuong_xa').value.trim();
        if (phuongXa === "") {
            showFieldError('phuong_xa', 'Vui lòng chọn phường/xã');
            isValid = false;
        }

        const validWards = <?= json_encode($hcmWards) ?>;
        if (!validWards.includes(phuongXa)) {
            showFieldError('phuong_xa', 'Vui lòng chọn đúng phường/xã trong danh sách');
            isValid = false;
        }

        return isValid;
    }

    function showMessage(message, type) {
        messageDiv.textContent = message;
        messageDiv.className = `alert ${type}`;
        messageDiv.style.display = 'block';
        setTimeout(() => { messageDiv.style.display = 'none'; }, 5000);
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!validateForm()) return;

            const submitBtn = form.querySelector('.submit-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang xử lý...';

            const formData = new FormData(form);

            // Gói items (giống code bạn đang có)
            const cartItems = <?= json_encode($cartDetails) ?>;
            const processedItems = cartItems.map(item => ({ id: item.id, quantity: item.qty }));
            formData.append('items', JSON.stringify(processedItems));

            Swal.fire({
                title: 'Xác nhận thanh toán?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title:'Đang xử lý...', didOpen:()=>Swal.showLoading(), allowOutsideClick:false });

                    fetch('php/process_order.php', { method: 'POST', body: formData })
                        .then(r => r.json())
                        .then(data => {
                            Swal.close();
                            Swal.fire({
                                icon: data.status === 'success' ? 'success' : 'error',
                                title: data.status === 'success' ? 'Đặt hàng thành công' : 'Lỗi',
                                html: `<p>${data.message}</p>`,
                                timer: data.status === 'success' ? 2000 : undefined,
                                showConfirmButton: data.status !== 'success'
                            }).then(() => {
                                if (data.status === 'success') {
                                    window.location.href = data.redirect || 'order-success.php';
                                }
                            });
                            if (data.status !== 'success') {
                                submitBtn.disabled = false;
                                submitBtn.textContent = 'Đặt hàng';
                            }
                        })
                        .catch(() => {
                            Swal.close();
                            Swal.fire('Lỗi','Không thể kết nối tới server','error');
                        });
                } else {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Đặt hàng';
                }
            });
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>
