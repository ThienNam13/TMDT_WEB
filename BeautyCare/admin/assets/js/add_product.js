async function addProduct(productData) {
    const formData = new FormData();
    
    // Thêm các trường dữ liệu vào formData
    for (const key in productData) {
        if (key !== 'hinh_anh') {
            formData.append(key, productData[key]);
        }
    }
    
    if (productData.hinh_anh) {
        formData.append('hinh_anh', productData.hinh_anh);
    }

    try {
        const response = await fetch('http://yourdomain.com/api_add_product.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Lỗi khi thêm sản phẩm');
        }
        
        return result;
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

// Ví dụ sử dụng
document.getElementById('productForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const productData = {
        ten_san_pham: document.getElementById('ten_san_pham').value,
        thuong_hieu: document.getElementById('thuong_hieu').value,
        phan_loai: document.getElementById('phan_loai').value,
        mo_ta: document.getElementById('mo_ta').value,
        thanh_phan: document.getElementById('thanh_phan').value,
        gia: document.getElementById('gia').value,
        han_su_dung: document.getElementById('han_su_dung').value,
        ma_danh_muc: document.getElementById('ma_danh_muc').value,
        is_available: document.getElementById('is_available').checked ? 1 : 0,
        hinh_anh: document.getElementById('hinh_anh').files[0]
    };

    try {
        const result = await addProduct(productData);
        alert('Thêm sản phẩm thành công! ID: ' + result.product_id);
        // Reset form hoặc chuyển hướng
    } catch (error) {
        alert(error.message);
    }
});