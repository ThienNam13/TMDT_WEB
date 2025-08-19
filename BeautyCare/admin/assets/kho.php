<?php
include 'header.php';
include '../php/database.php'; // Đảm bảo đường dẫn đúng tới file kết nối CSDL

// Tìm kiếm theo tên kho
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = $search ? "WHERE w.name LIKE ?" : "";

// Truy vấn dữ liệu từ bảng warehouses và showroom (nếu có bảng showrooms)
$sql = "SELECT w.*, s.name AS showroom_name
        FROM warehouses w
        LEFT JOIN showrooms s ON w.showroom_id = s.showroom_id
        $where ORDER BY w.warehouse_id DESC";

// Chuẩn bị truy vấn
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý kho - BeautyCare Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/kho.css">
</head>

<main class="container">
    <div class="kho-container">
        <div class="kho-header">
            <h2>Danh sách kho</h2>
            <a href="them_kho.php" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm kho</a>
        </div>

        <form method="GET" class="kho-search-form">
            <input type="text" name="search" placeholder="Tìm theo tên kho..." value="<?= htmlspecialchars($search) ?>" />
            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Tìm</button>
        </form>

        <table class="kho-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên Kho</th>
                    <th>Showroom</th>
                    <th>Địa chỉ</th>
                    <th>Sức chứa</th>
                    <th>Đang chứa</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['showroom_name'] ?? 'Không có') ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                        <td><?= $row['capacity'] ?></td>
                        <td><?= $row['current_count'] ?></td>
                        <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                        <td class="kho-actions">
                            <a href="sua_kho.php?id=<?= $row['warehouse_id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Sửa</a>
                            <a href="xoa_kho.php?id=<?= $row['warehouse_id'] ?>" class="btn btn-danger" onclick="return confirm('Xoá kho này?')"><i class="fas fa-trash"></i> Xoá</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if ($result->num_rows == 0): ?>
                    <tr><td colspan="8">Không tìm thấy kho nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include 'footer.php'; ?>
