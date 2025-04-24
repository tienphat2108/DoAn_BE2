document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    const infoMessage = document.getElementById('info-message');

    // Hàm kiểm tra email hợp lệ
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Hàm kiểm tra số điện thoại hợp lệ
    function isValidPhone(phone) {
        const phoneRegex = /^[0-9]{10}$/;
        return phoneRegex.test(phone);
    }

    // Hàm kiểm tra mật khẩu hợp lệ
    function isValidPassword(password) {
        return password.length >= 6;
    }

    // Hàm hiển thị thông báo
    function showMessage(message, isError = false) {
        infoMessage.textContent = message;
        infoMessage.style.color = isError ? '#dc3545' : '#28a745';
        infoMessage.style.display = 'block';
    }

    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Lấy giá trị từ form
        const lastName = document.getElementById('last_name').value.trim();
        const firstName = document.getElementById('first_name').value.trim();
        const birthDate = document.getElementById('birth_date').value;
        const gender = document.querySelector('input[name="gender"]:checked');
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const password = document.getElementById('password').value;

        // Kiểm tra các trường bắt buộc
        if (!lastName || !firstName || !birthDate || !gender || !email || !phone || !password) {
            showMessage('Vui lòng điền đầy đủ thông tin!', true);
            return;
        }

        // Kiểm tra email
        if (!isValidEmail(email)) {
            showMessage('Email không hợp lệ!', true);
            return;
        }

        // Kiểm tra số điện thoại
        if (!isValidPhone(phone)) {
            showMessage('Số điện thoại phải có 10 chữ số!', true);
            return;
        }

        // Kiểm tra mật khẩu
        if (!isValidPassword(password)) {
            showMessage('Mật khẩu phải có ít nhất 6 ký tự!', true);
            return;
        }

        // Tạo FormData object
        const formData = new FormData(registerForm);

        // Gửi request đăng ký
        fetch('/register', {
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
                showMessage('Đăng ký thành công! Đang chuyển hướng...');
                // Chuyển hướng đến trang đăng nhập sau 2 giây
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                // Hiển thị lỗi validation nếu có
                if (data.errors) {
                    const errorMessages = Object.values(data.errors).flat();
                    showMessage(errorMessages.join(', '), true);
                } else {
                    showMessage(data.message || 'Có lỗi xảy ra!', true);
                }
            }
        })
        .catch(error => {
            showMessage('Có lỗi xảy ra! Vui lòng thử lại sau.', true);
            console.error('Error:', error);
        });
    });
});
