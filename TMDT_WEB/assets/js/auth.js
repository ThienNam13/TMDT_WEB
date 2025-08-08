// auth.js chuyên nghiệp hóa
function showToast(message, bg = '#4CAF50') {
  const toast = document.createElement('div');
  toast.textContent = message;
  Object.assign(toast.style, {
    position: 'fixed',
    top: '20px',
    left: '50%',
    transform: 'translateX(-50%)',
    background: bg,
    color: '#fff',
    padding: '10px 24px',
    borderRadius: '8px',
    boxShadow: '0 2px 6px rgba(0,0,0,0.2)',
    zIndex: 10000,
    fontWeight: '600',
    transition: 'opacity .3s'
  });
  document.body.appendChild(toast);
  setTimeout(() => { toast.style.opacity = '0'; }, 1400);
  setTimeout(() => toast.remove(), 1700);
}

function closeAuthPopup() {
  document.getElementById("auth-popup").style.display = "none";
  const iframe = document.getElementById("auth-frame");
  iframe.src = "";
}

function openLoginPopup() {
  sessionStorage.setItem("redirectAfterLogin", window.location.href);
  const iframe = document.getElementById("auth-frame");
  iframe.src = "login.php?popup=1";
  iframe.style.height = "420px";
  document.getElementById("auth-popup").style.display = "block";
}

document.getElementById("btnLogin")?.addEventListener("click", () => {
  sessionStorage.setItem("redirectAfterLogin", window.location.href);
  const iframe = document.getElementById("auth-frame");
  iframe.src = "login.php?popup=1";
  iframe.style.height = "420px";
  document.getElementById("auth-popup").style.display = "block";
});

document.getElementById("auth-close")?.addEventListener("click", closeAuthPopup);

window.addEventListener("message", (event) => {
  const iframe = document.getElementById("auth-frame");
  console.log("[auth.js] message received:", event.data);

  if (event.data.startsWith("auth-msg:")) {
    const msgCode = event.data.replace("auth-msg:", "");
    let message = "Đã xảy ra lỗi!";
    switch (msgCode) {
      case "fail": message = "Email hoặc mật khẩu sai!"; break;
      case "blocked": message = "Tài khoản bị khóa!"; break;
      case "empty": message = "Vui lòng nhập Email và Mật khẩu!"; break;
      case "invalid": message = "Thông tin không hợp lệ!"; break;
      case "exists": message = "Email đã tồn tại!"; break;
      case "invalid_email": message = "Email không hợp lệ!"; break;
      case "notmatch": message = "Mật khẩu không khớp!"; break;
      case "invalid_fullname": message = "Họ tên không hợp lệ!"; break;
    }
    showToast(message, "#e74c3c");
    return;
  }

  switch (event.data) {
    case "login-success":
      showToast("Đăng nhập thành công!");
      closeAuthPopup();
      const redirectURL = sessionStorage.getItem("redirectAfterLogin");
      setTimeout(() => {
        window.location.href = redirectURL;
        sessionStorage.removeItem("redirectAfterLogin");
      }, 800);
      break;

    case "register-success":
      showToast("Đăng ký thành công!");
      iframe.src = "login.php?popup=1";
      iframe.style.height = "420px";
      break;
    
    case "gotoRegister":
      iframe.src = "register.php?popup=1";
      iframe.style.height = "650px";
      break;

    case "gotoLogin":
      iframe.src = "login.php?popup=1";
      iframe.style.height = "420px";
      break;

    default:
      console.warn("Unknown message:", event.data);
  }
});
