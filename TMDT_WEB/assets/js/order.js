      const params = new URLSearchParams(window.location.search);
      const orderId = params.get("order_id");
      document.getElementById("order-id").textContent = orderId || "N/A";

      if (orderId) {
        // Chỉ xóa nếu đúng trang đơn hàng
        localStorage.removeItem("cart");
      }
      if (!orderId || orderId === "N/A") {
        alert("Không tìm thấy mã đơn hàng");
        throw new Error("Thiếu order_id trong URL");
      }

      fetch(`php/order/detail.php?order_id=${orderId}`)
        .then(response => response.json())
        .then(data => {
          const tbody = document.getElementById("order-table-body");
          const totalEl = document.getElementById("total");
          let total = 0;

          if (!data.items || data.items.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4">Không có món nào trong đơn hàng.</td></tr>`;
            return;
          }

          data.items.forEach(item => {
            const row = document.createElement("tr");
            const itemTotal = item.quantity * item.price;
            total += itemTotal;

            row.innerHTML = `
              <td>${item.name}</td>
              <td>${item.quantity}</td>
              <td>${Number(item.price).toLocaleString()}đ</td>
              <td>${itemTotal.toLocaleString()}đ</td>
            `;
            tbody.appendChild(row);
          });

          totalEl.textContent = Number(data.tong_tien).toLocaleString() + "đ";
        })
        .catch(err => {
          console.error(err);
          alert("Không thể tải thông tin đơn hàng");
        });