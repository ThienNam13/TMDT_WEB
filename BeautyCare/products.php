<?php
include 'php/database.php';
include 'includes/header.php';
include 'includes/navbar.php';

// Lấy category từ GET
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : null;
$brands = isset($_GET['brand']) && is_array($_GET['brand']) ? $_GET['brand'] : [];
// Định nghĩa biến tìm kiếm từ URL
$search_term = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : null;
$price_min = isset($_GET['price_min']) ? (int)$_GET['price_min'] : null;
$price_max = isset($_GET['price_max']) ? (int)$_GET['price_max'] : null;

// Lấy mã danh mục từ tên danh mục
$ma_danh_muc = null;
if ($category) {
    $res_dm = $conn->query("SELECT ma_danh_muc FROM danh_muc WHERE ten_danh_muc = '$category'");
    if ($res_dm && $row_dm = $res_dm->fetch_assoc()) {
        $ma_danh_muc = (int)$row_dm['ma_danh_muc'];
    }
}

// Lấy danh sách thương hiệu có trong danh mục
$brands_list = [];
if ($ma_danh_muc) {
    // Nếu có danh mục thì lọc brand theo danh mục
    $sql_brands = "SELECT DISTINCT thuong_hieu FROM san_pham WHERE ma_danh_muc = $ma_danh_muc AND is_available = 1";
} else {
    // Nếu không có danh mục thì lấy tất cả brand
    $sql_brands = "SELECT DISTINCT thuong_hieu FROM san_pham WHERE is_available = 1";
}
$res_brands = $conn->query($sql_brands);
while ($row_b = $res_brands->fetch_assoc()) {
    $brands_list[] = $row_b['thuong_hieu'];
}

// Truy vấn sản phẩm
$sql = "SELECT * FROM san_pham WHERE is_available = 1";

if ($search_term) {
    $sql .= " AND ten_san_pham LIKE '%$search_term%'";
}

if ($ma_danh_muc) {
    $sql .= " AND ma_danh_muc = $ma_danh_muc";
}

if (!empty($brands)) {
    $escaped_brands = array_map(function($b) use ($conn) {
        return "'" . $conn->real_escape_string($b) . "'";
    }, $brands);
    $sql .= " AND thuong_hieu IN (" . implode(',', $escaped_brands) . ")";
}

// --- Lọc giá ---
if ($price_min !== null) {
    $sql .= " AND gia >= $price_min";
}
if ($price_max !== null) {
    $sql .= " AND gia <= $price_max";
}
$result = $conn->query($sql);
?>

<style>
/* Layout chung */
.products-page {
    display: flex;
    gap: 20px;
    margin-top: 20px;
    align-items: stretch; /* Sidebar và product list cao bằng nhau */
}

/* Sidebar lọc */
.filter-sidebar {
    width: 250px;
    background: #fff;
    padding: 15px;
    border-radius: 6px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
}

.filter-sidebar h3 {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
    border-bottom: 1px solid #eee;
    padding-bottom: 5px;
}

/* Danh sách checkbox căn đều */
.brand-filter {
    flex: 1; /* Chiếm toàn bộ khoảng trống */
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Dàn đều từ trên xuống dưới */
    max-height: 300px;
    overflow-y: auto;
}

.brand-filter label {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 4px 0;
    cursor: pointer;
}

.brand-filter input[type="checkbox"] {
    transform: scale(1.1);
    cursor: pointer;
}

.btn-primary:hover {
    background: #b5838d;
    cursor: pointer;
}

/* Grid sản phẩm */
.product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}

.product-card {
    background: #fff;
    padding: 10px;
    border-radius: 6px;
    text-align: center;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    height: 100%;
}

.product-card img {
    max-width: 100%;
    height: auto;
}

.product-card h3 {
    flex-grow: 1;
}

.product-card a.btn-primary {
    margin-top: auto;
    display: inline-block;
    padding: 8px 12px;
}

.price-filter {
    display: flex;
    justify-content: space-between;
    gap: 5px;
}
.price-filter input {
    flex: 1;
}

/* Responsive */
@media (max-width: 768px) {
    .products-page {
        flex-direction: column;
    }
    .filter-sidebar {
        width: 100% !important;
    }
}
</style>

<div class="container products-page">

    <aside class="filter-sidebar">
        <form method="GET" action="">
            <?php if ($category): ?>
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
            <?php endif; ?>
             <?php if ($search_term): ?>
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_term); ?>">
            <?php endif; ?>

            <h3>Thương hiệu</h3>
            <div class="brand-filter">
                <?php
                foreach ($brands_list as $brand) {
                    $checked = in_array($brand, $brands) ? 'checked' : '';
                    echo '<label><input type="checkbox" name="brand[]" value="'.htmlspecialchars($brand).'" '.$checked.'> '.htmlspecialchars($brand).'</label>';
                }
                ?>
            </div>
            
            <h3>Khoảng giá</h3>
            <div class="price-filter" style="margin-bottom: 15px;">
                <input type="number" name="price_min" placeholder="Từ" 
                        value="<?php echo htmlspecialchars($price_min); ?>" 
                        style="width: 45%; padding: 5px; margin-right:5%; border:1px solid #ddd; border-radius:4px;">
                <input type="number" name="price_max" placeholder="Đến" 
                        value="<?php echo htmlspecialchars($price_max); ?>" 
                        style="width: 45%; padding: 5px; border:1px solid #ddd; border-radius:4px;">
            </div>

            <h3>Mức giá nhanh</h3>
            <div class="quick-price" style="margin-bottom: 15px;">
                <?php
                $ranges = [
                    '0-100000' => 'Dưới 100K',
                    '100000-500000' => '100K - 500K',
                    '500000-1000000' => '500K - 1 Triệu',
                    '1000000-' => 'Trên 1 Triệu'
                ];
                foreach ($ranges as $value => $label) {
                    $checked = '';
                    if ($price_min !== null || $price_max !== null) {
                        list($min, $max) = explode('-', $value);
                        if (($min === '' || $price_min == (int)$min) && 
                            ($max === '' || $price_max == (int)$max)) {
                            $checked = 'checked';
                        }
                    }
                    echo '<label style="display:block; margin:3px 0;">
                            <input type="radio" name="quick_price" value="'.$value.'" '.$checked.'> '.$label.'
                        </label>';
                }
                ?>
            </div>
            
            <button type="submit" class="btn-primary" style="width: 100%;">Lọc</button>
        </form>
    </aside>

    <section class="product-list" style="flex: 1;">
        <div class="product-grid">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product-card">';
                    // Thêm biến $base_url để đường dẫn ảnh chính xác
                    echo '<a href="' . $base_url . 'product-detail.php?id=' . intval($row['id']) . '">';
                    echo '<img src="' . $base_url . 'assets/img/products/' . htmlspecialchars($row['hinh_anh']) . '" alt="' . htmlspecialchars($row['ten_san_pham']) . '">';
                    echo '</a>';
                    echo '<h3>' . htmlspecialchars($row['ten_san_pham']) . '</h3>';
                    echo '<p class="price" style="color: red; font-weight: bold;">' . number_format($row['gia'], 0, ',', '.') . ' VND</p>';
                    echo '<a href="' . $base_url . 'product-detail.php?id=' . intval($row['id']) . '" class="btn-primary">Xem chi tiết</a>';
                    echo '</div>';
                }
            } else {
                echo "<p>Không tìm thấy sản phẩm</p>";
            }
            ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('input[name="quick_price"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const val = this.value.split("-");
            const min = val[0] !== "" ? val[0] : "";
            const max = val[1] !== "" ? val[1] : "";
            document.querySelector('[name="price_min"]').value = min;
            document.querySelector('[name="price_max"]').value = max;
        });
    });
});
</script>