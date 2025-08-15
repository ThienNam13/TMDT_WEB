<?php
// export_customers.php
include '../php/database.php';

// Set headers for Excel file download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="danh_sach_khach_hang_' . date('Y-m-d') . '.xls"');
header('Pragma: no-cache');
header('Expires: 0');

// Query to get all customers
$query = "SELECT id, email, fullname, 
           CASE WHEN COALESCE(blocked, 0) = 1 THEN 'Đã khóa' ELSE 'Hoạt động' END AS status
           FROM users 
           WHERE role='user'
           ORDER BY id DESC";
$result = $conn->query($query);

// Start Excel HTML table
echo '<table border="1">';
echo '<tr>
        <th>ID</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Trạng thái</th>
      </tr>';

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . htmlspecialchars($row['fullname'] ?? 'Chưa cập nhật') . '</td>';
        echo '<td>' . htmlspecialchars($row['email']) . '</td>';
        echo '<td>' . $row['status'] . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="4">Không có dữ liệu khách hàng</td></tr>';
}

echo '</table>';

// Close database connection
$conn->close();
?>