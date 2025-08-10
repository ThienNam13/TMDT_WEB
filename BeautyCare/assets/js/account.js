document.addEventListener("DOMContentLoaded", () => {
    // Chuyển tab
    const tabs = document.querySelectorAll(".tab-btn");
    const contents = document.querySelectorAll(".tab-content");

    tabs.forEach(tab => {
        tab.addEventListener("click", () => {
            tabs.forEach(t => t.classList.remove("active"));
            contents.forEach(c => c.classList.remove("active"));
            tab.classList.add("active");
            document.getElementById(tab.dataset.tab).classList.add("active");
        });
    });

    // Hàm hiển thị thông báo đẹp
    function showMsg(type, msg) {
        Swal.fire({
            icon: type,
            title: msg,
            timer: 1500,
            showConfirmButton: false
        });
    }

    // Cập nhật thông tin cá nhân
    document.getElementById("profileForm").addEventListener("submit", e => {
        e.preventDefault();
        fetch("php/update_profile.php", {
            method: "POST",
            body: new FormData(e.target)
        })
        .then(r => r.json())
        .then(d => showMsg(d.status, d.message));
    });

    // Cập nhật địa chỉ
    document.getElementById("addressForm").addEventListener("submit", e => {
        e.preventDefault();
        fetch("php/update_address.php", {
            method: "POST",
            body: new FormData(e.target)
        })
        .then(r => r.json())
        .then(d => showMsg(d.status, d.message));
    });

    // Đổi mật khẩu
    document.getElementById("passwordForm").addEventListener("submit", e => {
        e.preventDefault();
        fetch("php/change_password.php", {
            method: "POST",
            body: new FormData(e.target)
        })
        .then(r => r.json())
        .then(d => showMsg(d.status, d.message));
    });
});
