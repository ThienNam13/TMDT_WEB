function searchFood() {
  const keyword = document.getElementById('searchInput').value.trim();
  if (!keyword) return;

  fetch(`php/food/search.php?q=${encodeURIComponent(keyword)}`)
    .then(res => res.json())
    .then(data => {
      // Xóa toàn bộ các món đang hiển thị
      document.querySelectorAll('.food-container').forEach(container => container.innerHTML = '');

      if (data.length === 0) {
        alert("Không tìm thấy món phù hợp!");
        return;
      }

      // Phân loại lại theo "loai"
      const categories = {
        "Khuyến mãi": "khuyen-mai",
        "Món mới": "mon-moi",
        "Combo": "combo",
        "Gà rán": "ga-ran",
        "Burger - Cơm - Mỳ ý": "burger-com-my-y",
        "Tráng miệng": "trang-mieng"
      };

      data.forEach(food => {
        const container = document.querySelector(`#${categories[food.loai]}`)?.nextElementSibling;
        if (!container) return;

        container.insertAdjacentHTML("beforeend", `
          <div class="food-card">
            <img src="./${food.hinh_anh}" alt="${food.ten_mon}">
            <h3>${food.ten_mon}</h3>
            <p>${food.mo_ta}</p>
            <p class="price" data-value="${food.gia}">${Number(food.gia).toLocaleString()}₫</p>
            <button class="btn-add" data-id="${food.id}">Thêm món</button>
          </div>
        `);
      });

      // Kích hoạt lại nút Thêm giỏ hàng
      initAddToCart();
    });
}
