  const wardCoordinates = {
    "Phường Bình Thạnh": { lat: 10.8035, lng: 106.7072 },
    "Phường Tân Sơn Nhất": { lat: 10.8012, lng: 106.6658 },
    "Phường Cầu Kiệu": { lat: 10.7905, lng: 106.6821 },
    "Phường An Nhơn": { lat: 10.8275, lng: 106.6789 },
    "Phường Hạnh Thông": { lat: 10.8322, lng: 106.6765 },
    "Phường Sài Gòn": { lat: 10.7794, lng: 106.6992 },
    "Phường Minh Phụng": { lat: 10.7628, lng: 106.6445 },
    "Phường Tân Sơn Hòa": { lat: 10.7998, lng: 106.6732 },
    "Phường Phú Nhuận": { lat: 10.7991, lng: 106.6805 },
    "Phường An Hội Đông": { lat: 10.8346, lng: 106.6781 }
  };
document.addEventListener("DOMContentLoaded", () => {
  renderOrderItems();
  initFormValidation();
});

// ✅ Hiển thị giỏ hàng tóm tắt
function renderOrderItems() {
  const orderItemsContainer = document.querySelector(".order-items-container");
  const checkoutTotal = document.querySelector(".checkout-total span:last-child");
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  let total = 0;

  // Xóa các món cũ (giữ lại phí giao hàng)
  orderItemsContainer.querySelectorAll(".order-item:not(:last-child)").forEach(item => item.remove());

  cart.forEach(item => {
    const itemTotal = item.so_luong * item.gia;
    total += itemTotal;

    const div = document.createElement("div");
    div.className = "order-item";
    div.innerHTML = `<span>${item.ten_mon} x${item.so_luong}</span><span>${formatCurrency(itemTotal)}</span>`;
    orderItemsContainer.insertBefore(div, orderItemsContainer.querySelector(".order-item:last-child"));
  });

  const shipping = 15000;
  orderItemsContainer.querySelector(".order-item:last-child span:last-child").textContent = formatCurrency(shipping);
  checkoutTotal.textContent = formatCurrency(total + shipping);
}

function formatCurrency(number) {
  return number.toLocaleString("vi-VN") + "₫";
}

// ✅ Bắt sự kiện submit form
function initFormValidation() {
  const form = document.getElementById("checkout-form");

  form.addEventListener("submit", function (e) {
    e.preventDefault(); // Ngăn form gửi ngay

    if (!validateForm()) return; // Nếu sai thì dừng lại

    showConfirm(); // Đúng thì hỏi xác nhận
  });
}

// ✅ Kiểm tra input
function validateForm() {
  const fullname = document.getElementById("fullname").value.trim();
  const phone = document.getElementById("phone").value.trim();
  const address = document.getElementById("address").value.trim();

  const validNameRegex = /^(?=.*[\p{L}])[ \p{L}'.-]{4,}$/u;
  const validPhoneRegex = /^(0\d{9}|\+84\d{9})$/;
  const validAddressRegex = /^\d[\d\/\-]{0,10}\s+[\p{L}\s'.\-]{3,}$/u;

  if (!validNameRegex.test(fullname)) {
    showError("Họ tên chưa đầy đủ."); return false;
  }
  if (!validPhoneRegex.test(phone)) {
    showError("Số điện thoại không hợp lệ."); return false;
  }
  if (address.length < 5 || !validAddressRegex.test(address)) {
    showError("Địa chỉ không hợp lệ."); return false;
  }

  // Nếu hợp lệ thì reset dữ liệu giỏ hàng
  const cart = localStorage.getItem("cart") || "[]";
  document.getElementById("cart-data").value = cart;

  return true;
}

// ✅ Hiển thị popup cảnh báo
function showError(msg) {
  document.getElementById("popup-message").innerText = msg;
  document.getElementById("popup-alert").style.display = "flex";
}
function closePopup() {
  document.getElementById("popup-alert").style.display = "none";
}

// ✅ Hiển thị popup xác nhận
function showConfirm() {
  document.getElementById("popup-confirm").style.display = "flex";
}
function closeConfirm() {
  document.getElementById("popup-confirm").style.display = "none";
}

// ✅ Nếu xác nhận → submit form
function submitRealOrder() {
  // Xóa cart trong localStorage
  localStorage.removeItem("cart");
  document.getElementById("checkout-form").submit();
}

let map;
let marker;

document.getElementById('ward').addEventListener('change', function() {
    const wardName = this.value;
    const coords = wardCoordinates[wardName];
    if (!coords) return;

    if (!map) {
        map = L.map('map').setView([coords.lat, coords.lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([coords.lat, coords.lng], { draggable: true }).addTo(map);
    } else {
        map.setView([coords.lat, coords.lng], 15);
        marker.setLatLng([coords.lat, coords.lng]);
    }

    document.getElementById("map").style.display = "block";
    setTimeout(() => {
      map.invalidateSize();
    }, 100);

    // Cập nhật hidden input
    marker.on('dragend', function (e) {
        const position = e.target.getLatLng();
        console.log("Tọa độ mới:", position.lat, position.lng);
        document.getElementById('lat').value = position.lat;
        document.getElementById('lng').value = position.lng;
    });

    // Gán mặc định tọa độ
    document.getElementById('lat').value = coords.lat;
    document.getElementById('lng').value = coords.lng;
});