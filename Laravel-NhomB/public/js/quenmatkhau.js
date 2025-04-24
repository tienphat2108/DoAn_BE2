// quenmatkhau.js
document.addEventListener('DOMContentLoaded', function() {
    // Lấy các phần tử
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const emailInput = document.getElementById('email');
    const confirmationModal = document.getElementById('confirmationModal');
    const confirmYesBtn = document.getElementById('confirmYes');
    const confirmNoBtn = document.getElementById('confirmNo');
    
    // Biến để lưu trữ email đã xác thực
    let verifiedEmail = '';
    
    // Xử lý sự kiện submit form
    if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener('submit', function(event) {
            // Ngăn chặn hành vi mặc định của form
            event.preventDefault();
            
            // Lấy giá trị email
            const email = emailInput.value.trim();
            
            // Kiểm tra định dạng email
            if (!isValidEmail(email)) {
                showError(emailInput, 'Email không hợp lệ');
                return;
            }
            
            // Xóa thông báo lỗi nếu có
            clearError(emailInput);
            
            // Kiểm tra email có tồn tại trong hệ thống không
            checkEmailExists(email)
                .then(exists => {
                    if (exists) {
                        // Lưu email đã xác thực
                        verifiedEmail = email;
                        
                        // Hiển thị modal xác nhận
                        showModal();
                    } else {
                        showError(emailInput, 'Email này chưa được đăng ký');
                    }
                })
                .catch(error => {
                    console.error('Lỗi kiểm tra email:', error);
                    showError(emailInput, 'Đã xảy ra lỗi, vui lòng thử lại sau');
                });
        });
    }
    
    // Xử lý nút "CÓ" trong modal
    confirmYesBtn.addEventListener('click', function() {
        // Ẩn modal
        hideModal();
        
        // Gửi yêu cầu đặt lại mật khẩu
        sendPasswordResetRequest(verifiedEmail)
            .then(response => {
                // Hiển thị thông báo thành công
                alert('Yêu cầu đặt lại mật khẩu đã được gửi đến email của bạn');
                
                // Chuyển hướng về trang đăng nhập
                window.location.href = '/login';
            })
            .catch(error => {
                console.error('Lỗi gửi yêu cầu đặt lại mật khẩu:', error);
                showError(emailInput, 'Đã xảy ra lỗi, vui lòng thử lại sau');
            });
    });
    
    // Xử lý nút "KHÔNG" trong modal
    confirmNoBtn.addEventListener('click', function() {
        // Ẩn modal
        hideModal();
    });
    
    // Hàm kiểm tra email hợp lệ
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Hàm kiểm tra email có tồn tại trong hệ thống không
    function checkEmailExists(email) {
        // Trong môi trường thực tế, đây sẽ là một AJAX request đến server
        // Ví dụ:
        return new Promise((resolve, reject) => {
            // Mô phỏng gọi API kiểm tra email
            // Trong ứng dụng thực tế, bạn sẽ gửi request đến server
            
            // Sử dụng fetch API để gửi request kiểm tra email
            fetch('/api/check-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                resolve(data.exists);
            })
            .catch(error => {
                // Trong môi trường demo, luôn trả về true để hiển thị modal
                console.error('Error checking email:', error);
                resolve(true);
            });
            
            // Trong môi trường demo, luôn trả về true để hiển thị modal
            // setTimeout(() => resolve(true), 500);
        });
    }
    
    // Hàm gửi yêu cầu đặt lại mật khẩu
    function sendPasswordResetRequest(email) {
        // Trong môi trường thực tế, đây sẽ là một AJAX request đến server
        // Ví dụ:
        return new Promise((resolve, reject) => {
            // Mô phỏng gọi API gửi yêu cầu đặt lại mật khẩu
            // Trong ứng dụng thực tế, bạn sẽ gửi request đến server
            
            // Sử dụng fetch API để gửi request đặt lại mật khẩu
            fetch('/api/password/email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                resolve(data);
            })
            .catch(error => {
                // Trong môi trường demo, luôn trả về thành công
                console.error('Error sending password reset:', error);
                resolve({ success: true });
            });
            
            // Trong môi trường demo, luôn trả về thành công
            // setTimeout(() => resolve({ success: true }), 1000);
        });
    }
    
    // Hàm hiển thị modal
    function showModal() {
        confirmationModal.style.display = 'flex';
    }
    
    // Hàm ẩn modal
    function hideModal() {
        confirmationModal.style.display = 'none';
    }
    
    // Hàm hiển thị lỗi
    function showError(input, message) {
        const formGroup = input.closest('.form-group');
        
        // Xóa thông báo lỗi cũ nếu có
        clearError(input);
        
        // Thêm class lỗi
        formGroup.classList.add('has-error');
        
        // Tạo phần tử hiển thị lỗi
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        
        // Thêm vào form group
        formGroup.appendChild(errorElement);
    }
    
    // Hàm xóa thông báo lỗi
    function clearError(input) {
        const formGroup = input.closest('.form-group');
        formGroup.classList.remove('has-error');
        
        const errorElement = formGroup.querySelector('.error-message');
        if (errorElement) {
            errorElement.remove();
        }
    }
    
    // Đóng modal khi click bên ngoài
    window.addEventListener('click', function(event) {
        if (event.target === confirmationModal) {
            hideModal();
        }
    });
});
