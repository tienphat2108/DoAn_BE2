document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const infoMessage = document.getElementById('info-message');

    // Hàm kiểm tra email hợp lệ
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Hàm hiển thị thông báo
    function showMessage(message, isError = false) {
        infoMessage.textContent = message;
        infoMessage.style.color = isError ? '#dc3545' : '#28a745';
        infoMessage.style.display = 'block';
    }

    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Lấy giá trị từ form
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;

        // Kiểm tra các trường bắt buộc
        if (!email || !password) {
            showMessage('Vui lòng điền đầy đủ email và mật khẩu!', true);
            return;
        }

        // Kiểm tra email
        if (!isValidEmail(email)) {
            showMessage('Email không hợp lệ!', true);
            return;
        }

        // Tạo FormData object
        const formData = new FormData(loginForm);

        // Gửi request đăng nhập
        fetch('/login', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Đăng nhập thành công! Đang chuyển hướng...');
                
                // Nếu là admin, chuyển hướng đến trang quản lý người dùng
                if (data.is_admin) {
                    setTimeout(() => {
                        window.location.href = '/quanlybainguoidung';
                    }, 1500);
                } else {
                    // Nếu là user thường, chuyển hướng đến trang chủ
                    setTimeout(() => {
                        window.location.href = '/trangchu';
                    }, 1500);
                }
            } else {
                showMessage(data.message || 'Email hoặc mật khẩu không đúng!', true);
            }
        })
        .catch(error => {
            showMessage('Có lỗi xảy ra! Vui lòng thử lại sau.', true);
            console.error('Error:', error);
        });
    });
});
