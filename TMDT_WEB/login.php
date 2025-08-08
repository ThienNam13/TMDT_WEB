<?php
  if (isset($_GET['error'])) echo "<script>alert('Sai tài khoản hoặc mật khẩu');</script>";
  $messageCode = $_GET['msg'] ?? null;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Đăng nhập</title>
  <link rel="stylesheet" href="assets/css/form.css" />
</head>
<body>
  
  <?php
$map = [
  'invalid' => 'Thông tin không hợp lệ.',
  'exists'  => 'Email đã tồn tại.',
  'empty'   => 'Vui lòng nhập Email và Mật khẩu.',
  'fail'    => 'Email hoặc mật khẩu chưa đúng.',
  'blocked' => 'Tài khoản đã bị khóa!',
  'welcome' => 'Đăng ký thành công!',
  'loginok' => 'Đăng nhập thành công!'
];

if ($messageCode && isset($map[$messageCode])) {
    if (empty($_GET['popup'])) {
        echo '<p style="color:red;text-align:center;">' . htmlspecialchars($map[$messageCode]) . '</p>';
    }

    echo '<script>window.parent.postMessage("auth-msg:' . $messageCode . '", "*");</script>';
}

// Đăng nhập thành công
if ($messageCode === 'loginok' && isset($_GET['popup'])) {
    echo '<script>window.parent.postMessage("login-success", "*");</script>';
}

?>

  <div class="form-container">
    <h2>Đăng nhập</h2>
    <form id="loginForm" method="POST" action="php/auth/login.php">
      <input type="hidden" name="popup" value="<?= isset($_GET['popup']) ? '1' : '' ?>">
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Mật khẩu" required />
      <button type="submit">Đăng nhập</button>
      <div class="form-link">
        Chưa có tài khoản? <a href="register.php<?= empty($_GET['popup']) ? '' : '?popup=1' ?>" onclick="<?= empty($_GET['popup']) ? '' : "window.parent.postMessage('gotoRegister', '*'); return false;" ?>">Đăng ký ngay</a>
      </div>
    </form>
  </div>
</body>
</html>