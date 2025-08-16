<?php
session_start();
include '../php/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, email, password, role, fullname FROM users WHERE email = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['role'] === 'admin') {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['fullname'] ?: $user['email'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Tài khoản không có quyền quản trị!";
        }
    } else {
        $error = "Sai tên đăng nhập hoặc mật khẩu!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - BeautyCare</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Đăng nhập Admin</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>Tên đăng nhập</label>
            <input type="text" name="email" required>
            <label>Mật khẩu</label>
            <input type="password" name="password" required>
            <button type="submit">Đăng nhập</button>
        </form>
    </div>
</body>
</html>
