<?php
session_start();
session_unset();    // Xóa toàn bộ biến session
session_destroy();  // Huỷ phiên đăng nhập

//  Xóa cookie đã lưu món đã xem
setcookie("recentViewedItems", "", time() - 3600, "/");

//  Quay về trang chủ
header("Location: ../../index.php");
exit;
?>