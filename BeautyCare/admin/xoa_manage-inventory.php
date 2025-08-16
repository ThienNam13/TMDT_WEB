<?php
include '../php/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Kiểm tra tồn tại trước khi xóa
    $check = $conn->query("SELECT id FROM kho_hang WHERE id = $id");
    if ($check->num_rows > 0) {
        $conn->query("DELETE FROM kho_hang WHERE id = $id");
    }
}

header("Location: manage-inventory.php");
exit();
?>