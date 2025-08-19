<?php
include 'header.php';
include '../php/database.php'; // file kết nối CSDL

// Tìm kiếm theo tên sản phẩm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = $search ? "WHERE sp.ten_san_pham LIKE ?" : "";

// Truy vấn dữ liệu từ bảng kho_hang + san_pham
$sql = "SELECT w.id, sp.ten_san_pham, w.vi_tri_kho, w.so_luong_ton
        FROM kho_hang w
        LEFT JOIN san_pham sp ON w.san_pham_id = sp.id
        $where
        ORDER BY w.id DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Lỗi truy vấn: " . $conn->error);
}

if ($search) {
    $param = "%$search%";
    $stmt->bind_param("s", $param);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<head>
    <meta charset="UTF-8">
    <title>Quản lý kho</title>
    <link rel="stylesheet" href="../assets/css/kho.css">
</head>

<div class="kho-container">
    <div class="kho-header">
        <h2>Danh sách kho</h2>
        <a href="them_kho.php" class="btn btn-success">+ Thêm kho</a>
    </div>
    <form method="GET" class="kho-search-form">
        <input type="text" name="search" placeholder="Tìm theo tên sản phẩm..." value="<?= htmlspecialchars($search) ?>" />
        <button type="submit">Tìm</button>
    </form>

    <table class="kho-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên sản phẩm</th>
                <th>Vị trí kho</th>
                <th>Số lượng tồn</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['ten_san_pham']) ?></td>
                    <td><?= htmlspecialchars($row['vi_tri_kho']) ?></td>
                    <td><?= $row['so_luong_ton'] ?></td>
                    <td class="kho-actions">
                        <a href="sua_kho.php?id=<?= $row['id'] ?>" class="btn btn-warning">Sửa</a>
                        <a href="xoa_kho.php?id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Xoá kho này?')">Xoá</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if ($result->num_rows == 0): ?>
                <tr><td colspan="5">Không tìm thấy sản phẩm nào trong kho.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
