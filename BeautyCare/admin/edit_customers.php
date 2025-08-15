<?php
include '../php/database.php';
include 'header.php';

// Lấy ID khách hàng từ URL
$customerId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin khách hàng từ database
$query = "SELECT * FROM users WHERE id = ? AND role='user'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customerId);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if (!$customer) {
    header("Location: customers.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Khách hàng - BeautyCare Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        .edit-form-container {
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
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-actions {
            margin-top: 20px;
            text-align: right;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-save {
            background-color: #4CAF50;
            color: white;
        }
        .btn-cancel {
            background-color: #f44336;
            color: white;
            margin-right: 10px;
        }
        .status-toggle {
            display: flex;
            align-items: center;
        }
        .status-toggle input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<main class="container">
    <section class="edit-customer">
        <div class="dashboard-header">
            <h2><i class="fas fa-user-edit"></i> Chỉnh sửa Khách hàng #<?= $customer['id'] ?></h2>
        </div>

        <div class="edit-form-container">
            <form action="update_customer.php" method="POST">
                <input type="hidden" name="id" value="<?= $customer['id'] ?>">
                
                <div class="form-group">
                    <label for="fullname">Họ và tên</label>
                    <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($customer['fullname'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="address">Địa chỉ</label>
                    <textarea id="address" name="address" rows="3"><?= htmlspecialchars($customer['address'] ?? '') ?></textarea>
                </div>

                <div class="form-group status-toggle">
                    <label>
                        <input type="checkbox" name="blocked" value="1" <?= $customer['blocked'] ? 'checked' : '' ?>>
                        Khóa tài khoản
                    </label>
                </div>

                <div class="form-actions">
                    <a href="manage-customers.php" class="btn btn-cancel">Hủy bỏ</a>
                    <button type="submit" class="btn btn-save">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </section>
</main>
<!-- Thêm thư viện jQuery (nếu chưa có) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Xử lý form submit bằng AJAX
    $('form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'update_customer.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Thành công',
                        text: response.message,
                        icon: 'success'
                    }).then(() => {
                        window.location.href = 'manage-customers.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Lỗi',
                        text: response.message,
                        icon: 'error'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Lỗi',
                    text: 'Có lỗi xảy ra khi kết nối đến server',
                    icon: 'error'
                });
            }
        });
    });

    // Sửa nút hủy để chắc chắn chuyển hướng
    $('.btn-cancel').on('click', function(e) {
        e.preventDefault();
        window.location.href = 'manage-customers.php';
    });
});
</script>
<script>
$(document).ready(function() {
    $('form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Thành công',
                        text: response.message,
                        icon: 'success'
                    }).then(() => {
                        window.location.href = 'manage-customers.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Lỗi',
                        text: response.message,
                        icon: 'error'
                    });
                }
            },
            error: function(xhr) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    Swal.fire({
                        title: 'Lỗi',
                        text: response.message || 'Có lỗi xảy ra',
                        icon: 'error'
                    });
                } catch (e) {
                    Swal.fire({
                        title: 'Lỗi',
                        text: 'Không thể phân tích phản hồi từ server',
                        icon: 'error'
                    });
                }
            }
        });
    });
});
</script>
</body>
</html>

<?php include 'footer.php'; ?>