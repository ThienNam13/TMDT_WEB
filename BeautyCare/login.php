<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<link rel="stylesheet" href="assets/css/login.css">

<div id="authMessage" class="alert" style="display:none;"></div>

<div class="auth-container">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert error">
            <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert success">
            <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>
    <div class="form-container sign-up-container">
        <form id="registerForm">
            <h1>Tạo tài khoản</h1>
            <input type="text" name="fullname" placeholder="Họ và tên" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu" required>
            <button type="submit">Đăng ký</button>
        </form>
    </div>

    <div class="form-container sign-in-container">
        <form id="loginForm">
            <h1>Đăng nhập</h1>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">Đăng nhập</button>
        </form>
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Lại là BeautyCare đây!</h1>
                <p>Để tiếp tục mua hàng, vui lòng đăng nhập</p>
                <button class="ghost" id="signIn">Đăng nhập</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Chào mừng trở lại!</h1>
                <p>Tạo tài khoản mới, mua sắm cùng BeautyCare!</p>
                <button class="ghost" id="signUp">Đăng ký</button>
            </div>
        </div>
    </div>
</div>

<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.querySelector('.auth-container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
</script>

<script>
function showMessage(type, text) {
    const box = document.getElementById('authMessage');
    box.className = `alert ${type}`;
    box.textContent = text;
    box.style.display = 'block';
}

document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    fetch('php/login_process.php', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            showMessage('success', data.message);
            setTimeout(() => window.location.href = 'index.php', 1000);
        } else {
            showMessage('error', data.message);
        }
    });
});

document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    fetch('php/register_process.php', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            showMessage('success', data.message);
            // Chuyển qua form đăng nhập
            document.querySelector('.auth-container').classList.remove("right-panel-active");
        } else {
            showMessage('error', data.message);
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>
