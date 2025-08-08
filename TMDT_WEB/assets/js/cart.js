document.addEventListener("DOMContentLoaded", () => {
  const cartBody = document.getElementById("cart-body");
  const totalPriceEl = document.getElementById("total-price");
  const checkoutBtn = document.getElementById("checkout-btn");
  const modal = document.getElementById("edit-modal");
  const closeModal = modal.querySelector(".close");
  const saveBtn = document.getElementById("save-edit");
  const quantityInput = document.getElementById("new-quantity");
  const itemNameEl = document.getElementById("modal-item-name");

  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  function formatCurrency(number) {
    return number.toLocaleString("vi-VN") + " đ";
  }

  function renderCart() {
    cartBody.innerHTML = "";
    let total = 0;

    cart.forEach((item, index) => {
      const itemTotal = item.so_luong * item.gia;
      total += itemTotal;

      const row = document.createElement("tr");
      row.innerHTML = `
        <td data-label="Món">${item.ten_mon}</td>
        <td data-label="Số lượng">${item.so_luong}</td>
        <td data-label="Giá">${formatCurrency(item.gia)}</td>
        <td data-label="Thành tiền">${formatCurrency(itemTotal)}</td>
        <td class="actions" data-label="Hành động">
          <button class="edit-btn" data-index="${index}">Sửa</button>
          <button class="delete-btn" data-index="${index}">Xóa</button>
        </td>
      `;
      cartBody.appendChild(row);
    });

    totalPriceEl.textContent = formatCurrency(total);
    attachDeleteEvents();
    attachEditEvents();
    checkoutBtn.onclick = () => {
      if (!window.isLoggedIn) {
        document.getElementById("login-popup").style.display = "flex";
        return;
      }

      if (cart.length > 0) {
        window.location.href = "checkout.php";
      }
    };
    checkoutBtn.disabled = cart.length === 0;
  }

  function attachDeleteEvents() {
    document.querySelectorAll(".delete-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const index = btn.dataset.index;
        cart.splice(index, 1);
        localStorage.setItem("cart", JSON.stringify(cart));
        renderCart();
      });
    });
  }

  function attachEditEvents() {
    let editingIndex = null;

    document.querySelectorAll(".edit-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        editingIndex = btn.dataset.index;
        const item = cart[editingIndex];
        itemNameEl.textContent = `Món: ${item.ten_mon}`;
        quantityInput.value = item.so_luong;
        modal.style.display = "block";
      });
    });

    closeModal.onclick = () => {
      modal.style.display = "none";
    };

    saveBtn.onclick = () => {
      const newQty = parseInt(quantityInput.value);
      if (!isNaN(newQty) && newQty > 0) {
        cart[editingIndex].so_luong = newQty;
        localStorage.setItem("cart", JSON.stringify(cart));
        modal.style.display = "none";
        renderCart();
      } else {
        alert("Số lượng không hợp lệ!");
      }
    };

    window.onclick = (e) => {
      if (e.target === modal) modal.style.display = "none";
    };
  }

  window.closeLoginPopup = function () {
    document.getElementById("login-popup").style.display = "none";
  }

  renderCart();
});