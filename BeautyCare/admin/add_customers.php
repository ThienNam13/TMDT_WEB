<?php
include '../php/database.php';
include 'header.php';

// Khởi tạo session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    
    // Validate dữ liệu
    if (empty($fullname) || empty($email) || empty($_POST['password'])) {
        $error = "Vui lòng điền đầy đủ thông tin bắt buộc";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ";
    } else {
        // Kiểm tra email đã tồn tại chưa
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            $error = "Email đã được sử dụng";
        } else {
            // Thêm khách hàng mới
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, phone, address, password, role) VALUES (?, ?, ?, ?, ?, 'user')");
            $stmt->bind_param("sssss", $fullname, $email, $phone, $address, $password);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Thêm khách hàng thành công";
                header("Location: manage-customers.php");
                exit();
            } else {
                $error = "Lỗi khi thêm khách hàng: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm khách hàng mới | BeautyCare Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        .customer-form {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-group input, 
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .text-danger {
            color: #f44336;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            padding: 8px 15px;
            background: #6c757d;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }
        .btn-back i {
            margin-right: 5px;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
<main class="container">
    <section class="customer-management">
        <div class="dashboard-header">
            <h2><i class="fas fa-user-plus"></i> Thêm khách hàng mới</h2>
            <a href="manage-customers.php" class="btn-back"><i class="fas fa-arrow-left"></i> Quay lại</a>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="customer-form">
            <div class="form-group">
                <label>Họ và tên <span class="text-danger">*</span></label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Email <span class="text-danger">*</span></label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Địa chỉ</label>
                <textarea name="address" rows="3"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Mật khẩu <span class="text-danger">*</span></label>
                <input type="password" name="password" required minlength="6">
                <small class="text-muted">Ít nhất 6 ký tự</small>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Thêm khách hàng
            </button>
        </form>
    </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>