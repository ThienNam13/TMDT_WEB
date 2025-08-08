<?php
$map = [
  'invalid' => 'Thông tin không hợp lệ.',
  'exists'  => 'Email đã tồn tại.',
  'empty'   => 'Vui lòng nhập Email và Mật khẩu.',
  'fail'    => 'Email hoặc mật khẩu chưa đúng.',
  'blocked' => 'Tài khoản này đã bị khóa!',
  'welcome' => 'Đăng ký thành công!',
  'loginok' => 'Đăng nhập thành công!',
  'notmatch'    => 'Mật khẩu và xác nhận không khớp!',
  'invalid_email' => 'Email không hợp lệ!',
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="assets/css/form.css" />
</head>
<body>
  <?php
  $messageCode = $_GET['msg'] ?? null;
  if ($messageCode && isset($map[$messageCode])) {
    echo '<script>window.parent.postMessage("auth-msg:' . $messageCode . '", "*");</script>';
  }

 // Đăng ký thành công
  if ($messageCode === 'welcome') {
    echo '<script>window.parent.postMessage("register-success", "*");</script>';
  }
  ?>
  
    <div class="form-container">
      <h2>Đăng ký</h2>
      <form id="registerForm" method="POST" action="php/auth/register.php">
        <input type="text" id="fullname" name="fullname" placeholder="Họ và tên" required />
        <input type="email" id="email" name="email" placeholder="Email" required />
        <input type="password" id="password" name="password" placeholder="Mật khẩu" required />
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Xác nhận mật khẩu" required />
        <p style="color: #888; font-size: 13px; margin-bottom: 12px;">
          Lưu ý: Chúng tôi cam kết bảo mật thông tin của bạn.
        </p>
        <button type="submit">Tạo tài khoản</button>
        <div class="form-link">
          Đã có tài khoản? <a href="login.php?popup=1" onclick="window.parent.postMessage('gotoLogin', '*'); return false;">Đăng nhập</a>
        </div>
      </form>
    </div>
    <div id="toast" class="toast hidden">
      <span id="toast-msg"></span>
    </div>

<script>
function showToast(message, type = "error") {
  const toast = document.getElementById("toast");
  const msg = document.getElementById("toast-msg");
  msg.textContent = message;

  toast.className = "toast show";
  if (type === "success") toast.classList.add("success");
  else if (type === "warning") toast.classList.add("warning");

  setTimeout(() => {
    toast.className = "toast hidden";
  }, 3000);
}

document.getElementById("registerForm").addEventListener("submit", function (e) {
  const fullname = document.getElementById("fullname").value.trim();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirm_password").value;

  const fullnameRegex = /^(?=.*[\p{L}])(?=.*\s)[ \p{L}'.-]{5,}$/u;
  if (!fullnameRegex.test(fullname)) {
    showToast("Họ và tên không hợp lệ!", "warning");
    e.preventDefault();
    return;
  }

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    showToast("Email không hợp lệ!", "warning");
    e.preventDefault();
    return;
  }

  if (password !== confirmPassword) {
    showToast("Mật khẩu và xác nhận mật khẩu không khớp!");
    e.preventDefault();
    return;
  }
});
</script>

</body>
</html>