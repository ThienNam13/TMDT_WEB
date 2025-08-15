<?php
session_start();
include 'php/database.php';
include 'includes/header.php';
include 'includes/navbar.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id   = (int) $_SESSION['user_id'];
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;        // san_pham_id
$order_id   = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($product_id <= 0 || $order_id <= 0) {
    echo "<div class='container'><p>Tham số không hợp lệ.</p></div>";
    include 'includes/footer.php';
    exit;
}

// Kiểm tra: đơn này có thuộc user và có sản phẩm này không?
$sql_check_purchase = "
    SELECT oi.id 
    FROM order_items oi
    JOIN orders o ON o.id = oi.order_id
    WHERE o.user_id = ? AND oi.order_id = ? AND oi.san_pham_id = ?
    LIMIT 1
";
$stmt = $conn->prepare($sql_check_purchase);
if (!$stmt) { die("Lỗi prepare kiểm tra mua hàng: " . $conn->error); }
$stmt->bind_param("iii", $user_id, $order_id, $product_id);
$stmt->execute();
$purchase = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$purchase) {
    echo "<div class='container'><p>Bạn không mua sản phẩm này trong đơn hàng đã chọn, nên không thể đánh giá.</p></div>";
    include 'includes/footer.php';
    exit;
}

// Chặn đánh giá trùng theo (user, order, product)
$sql_check_review = "SELECT 1 FROM danh_gia WHERE user_id = ? AND order_id = ? AND san_pham_id = ? LIMIT 1";
$stmt = $conn->prepare($sql_check_review);
if (!$stmt) { die("Lỗi prepare kiểm tra đánh giá: " . $conn->error); }
$stmt->bind_param("iii", $user_id, $order_id, $product_id);
$stmt->execute();
$already = $stmt->get_result()->fetch_assoc();
$stmt->close();

$show_success_modal = false;

// Xử lý submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $so_sao = isset($_POST['so_sao']) ? intval($_POST['so_sao']) : 0;
    $binh_luan = trim($_POST['binh_luan'] ?? '');
    $anh_minh_chung = null;

    // Upload ảnh nếu có
    if (isset($_FILES['anh_san_pham']) && $_FILES['anh_san_pham']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/uploads/reviews/';
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0775, true); }
        $ext = strtolower(pathinfo($_FILES['anh_san_pham']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg','jpeg','png','gif'];
        if (in_array($ext, $allowed_ext)) {
            $filename = time() . '_' . uniqid() . '.' . $ext;
            $target = $upload_dir . $filename;
            if (move_uploaded_file($_FILES['anh_san_pham']['tmp_name'], $target)) {
                $anh_minh_chung = 'uploads/reviews/' . $filename;
            }
        }
    }

    if ($so_sao < 1 || $so_sao > 5) {
        echo "<div class='alert error'>Vui lòng chọn số sao hợp lệ (1–5).</div>";
    } elseif ($already) {
        echo "<div class='alert error'>Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi.</div>";
    } else {
        // Lưu đánh giá kèm order_id
        $sql_insert = "INSERT INTO danh_gia (order_id, san_pham_id, user_id, so_sao, binh_luan, anh_minh_chung)
                       VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        if (!$stmt) { die('Lỗi prepare insert: ' . $conn->error); }
        $stmt->bind_param("iiiiss", $order_id, $product_id, $user_id, $so_sao, $binh_luan, $anh_minh_chung);
        if ($stmt->execute()) {
            $show_success_modal = true;
        } else {
            echo "<div class='alert error'>Lỗi khi lưu đánh giá: " . $conn->error . "</div>";
        }
        $stmt->close();
    }
}

// Lấy tên sản phẩm hiển thị
$sql_name = "SELECT ten_san_pham FROM san_pham WHERE id = ?";
$stmt_name = $conn->prepare($sql_name);
$stmt_name->bind_param("i", $product_id);
$stmt_name->execute();
$product_name = $stmt_name->get_result()->fetch_assoc()['ten_san_pham'] ?? 'Sản phẩm không tồn tại';
$stmt_name->close();
?>

<style>
.product-review-page .product-review-container {
    max-width: 600px; margin: 30px auto; background: #fff; padding: 20px;
    border-radius: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); font-family: 'Poppins', sans-serif;
}
.product-review-page .product-review-container h2 { text-align: center; color: #6a4c93; margin-bottom: 20px; }
.product-review-page .product-review-container label { font-weight: 500; display: block; margin-bottom: 6px; color: #333; }
.product-review-page .product-review-container select,
.product-review-page .product-review-container textarea,
.product-review-page .product-review-container input[type="file"] {
    width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 15px; font-size: 14px;
}
.product-review-page .product-review-container textarea { resize: vertical; }
.product-review-page .product-review-container button {
    background: #cdb4db; color: #fff; padding: 10px 20px; border: none; border-radius: 25px;
    cursor: pointer; transition: background 0.3s; width: 100%; font-size: 15px;
}
.product-review-page .product-review-container button:hover { background: #b5838d; }
.product-review-page .product-review-container .product-review-preview-img { max-width: 120px; margin-top: 10px; border-radius: 8px; }
.product-review-page .product-review-btn-back {
    display: inline-block; margin-top: 15px; padding: 10px 20px; background: #e0e0e0; color: #333;
    border-radius: 25px; text-decoration: none; text-align: center; transition: background 0.3s;
}
.product-review-page .product-review-btn-back:hover { background: #ccc; }

/* Popup thành công */
.success-modal {
    position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);
    display:flex; justify-content:center; align-items:center; z-index: 9999;
}
.success-content {
    background:#fff; padding:30px; border-radius:12px; text-align:center; max-width:400px;
    box-shadow:0 4px 10px rgba(0,0,0,0.2);
}
.success-icon { font-size:60px; color:green; margin-bottom:10px; }
.success-actions a {
    display:inline-block; margin:10px 5px; padding:10px 15px; border-radius:5px; text-decoration:none; font-weight:bold;
}
.btn-view { background:#6a4c93; color:#fff; }
.btn-continue { background:#555; color:#fff; }
.btn-view:hover { background:#553b76; }
.btn-continue:hover { background:#333; }
</style>

<div class="product-review-page">
  <div class="product-review-container">
    <h2>Đánh giá sản phẩm: "<?php echo htmlspecialchars($product_name); ?>"</h2>
    <?php if (!$show_success_modal): ?>
    <form method="post" enctype="multipart/form-data">
      <label for="so_sao">Số sao:</label>
      <select name="so_sao" id="so_sao" required>
        <option value="">--Chọn số sao--</option>
        <?php for ($i=1; $i<=5; $i++): ?>
          <option value="<?php echo $i; ?>"><?php echo $i; ?> ⭐</option>
        <?php endfor; ?>
      </select>

      <label for="binh_luan">Bình luận:</label>
      <textarea name="binh_luan" id="binh_luan" rows="4" placeholder="Nhập bình luận của bạn..."></textarea>

      <label for="anh_san_pham">Hình minh chứng sản phẩm:</label>
      <input type="file" name="anh_san_pham" id="anh_san_pham" accept="image/*" onchange="previewImage(event)">
      <img id="preview" class="product-review-preview-img" style="display:none;">

      <button type="submit">Gửi đánh giá</button>
    </form>
    <?php endif; ?>

    <a href="order-history.php" class="product-review-btn-back">⬅ Quay về lịch sử đơn hàng</a>
  </div>
</div>

<?php if ($show_success_modal): ?>
<div class="success-modal">
  <div class="success-content">
    <div class="success-icon">&#10004;</div>
    <h3>Đánh giá thành công!</h3>
    <div class="success-actions">
      <a href="order-history.php" class="btn-view">Về lịch sử đơn hàng</a>
      <a href="index.php" class="btn-continue">Về trang chủ</a>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
function previewImage(event) {
  const preview = document.getElementById('preview');
  preview.src = URL.createObjectURL(event.target.files[0]);
  preview.style.display = 'block';
}
</script>

<?php include 'includes/footer.php'; ?>


