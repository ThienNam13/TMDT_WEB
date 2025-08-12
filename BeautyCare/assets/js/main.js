document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo Swiper slider
    const swiper = new Swiper('.banner-slider', {
        loop: true,
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
    });

    // Thêm hiệu ứng khi scroll
    window.addEventListener('scroll', function() {
        const elements = document.querySelectorAll('.category-card, .product-card');
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.2;

            if (elementPosition < screenPosition) {
                element.classList.add('animate');
            }
        });
    });

    // Xử lý hover cho product card
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.querySelector('img').style.transform = 'scale(1.05)';
            this.querySelector('.btn-primary').style.backgroundColor = '#ff6b8b';
        });
        
        card.addEventListener('mouseleave', function() {
            this.querySelector('img').style.transform = 'scale(1)';
            this.querySelector('.btn-primary').style.backgroundColor = '';
        });
    });

    // Thêm sản phẩm vào giỏ hàng
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-id');
            
            // Hiển thị thông báo
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.textContent = 'Đã thêm sản phẩm vào giỏ hàng';
            document.body.appendChild(notification);
            
            // Ẩn thông báo sau 3 giây
            setTimeout(() => {
                notification.classList.add('hide');
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }, 3000);
            
            // Gửi yêu cầu AJAX (có thể thêm vào sau)
            // addToCart(productId);
        });
    });

    // Xử lý wishlist
    const wishlistButtons = document.querySelectorAll('.wishlist-btn');
    wishlistButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const icon = this.querySelector('i');
            
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.style.color = '#ff6b8b';
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.style.color = '';
            }
            
            // Gửi yêu cầu AJAX (có thể thêm vào sau)
            // toggleWishlist(this.getAttribute('data-id'));
        });
    });

    // Back to top button
    const backToTop = document.createElement('div');
    backToTop.className = 'back-to-top';
    backToTop.innerHTML = '<i class="fas fa-arrow-up"></i>';
    document.body.appendChild(backToTop);

    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    });

    backToTop.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Quick view modal
    const quickViewButtons = document.querySelectorAll('.quick-view');
    const modal = document.createElement('div');
    modal.className = 'product-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="modal-body"></div>
        </div>
    `;
    document.body.appendChild(modal);

    quickViewButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-id');
            
            // Giả lập dữ liệu sản phẩm (trong thực tế sẽ gọi AJAX)
            const productData = {
                id: productId,
                name: 'Sản phẩm ' + productId,
                price: '1.290.000 VND',
                description: 'Mô tả chi tiết sản phẩm sẽ hiển thị ở đây.'
            };
            
            document.querySelector('.modal-body').innerHTML = `
                <div class="modal-product">
                    <div class="modal-image">
                        <img src="assets/img/products/product${productId}.jpg" alt="${productData.name}">
                    </div>
                    <div class="modal-info">
                        <h3>${productData.name}</h3>
                        <p class="price">${productData.price}</p>
                        <p class="description">${productData.description}</p>
                        <div class="modal-actions">
                            <a href="#" class="btn-primary add-to-cart" data-id="${productId}">Thêm vào giỏ</a>
                            <a href="product-detail.php?id=${productId}" class="btn-outline">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            `;
            
            modal.style.display = 'block';
        });
    });

    document.querySelector('.close-modal').addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});

// Hàm thêm vào giỏ hàng (AJAX)
function addToCart(productId) {
    fetch('add-to-cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        updateCartCount(data.cart_count);
    })
    .catch(error => console.error('Error:', error));
}

// Hàm cập nhật số lượng giỏ hàng
function updateCartCount(count) {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = count;
        cartCount.classList.add('animate');
        setTimeout(() => {
            cartCount.classList.remove('animate');
        }, 500);
    }
}