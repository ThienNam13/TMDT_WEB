<?php
include '../php/database.php';
include 'header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID đơn hàng không hợp lệ";
    header("Location: manage-orders.php");
    exit();
}

$order_id = (int)$_GET['id'];

// Start transaction
$conn->begin_transaction();

try {
    // First delete order items
    $delete_items_sql = "DELETE FROM order_items WHERE order_id = ?";
    $stmt = $conn->prepare($delete_items_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    
    // Then delete the order
    $delete_order_sql = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn->prepare($delete_order_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    
    $conn->commit();
    
    $_SESSION['success'] = "Đã xóa đơn hàng thành công";
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Lỗi khi xóa đơn hàng: " . $e->getMessage();
}


header("Location: manage-orders.php");
exit();
?>