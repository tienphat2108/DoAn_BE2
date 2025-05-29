<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gửi Thông Báo - Fite Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .interaction-select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
        }
        .interaction-filter-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .interaction-filter-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <div class="logo">
                <span>Fite</span>
            </div>
            <h1>Fite hệ thống ADMIN</h1>
            <div class="admin-info">
                <span class="admin-name">{{ Auth::user()->name }}</span>
            </div>
        </div>

        <div class="admin-content">
            <div class="admin-sidebar">
                <ul class="admin-menu">
                    <li><a href="{{ route('admin.quanlynguoidung') }}">QUẢN LÝ NGƯỜI DÙNG</a></li>
                    <li><a href="{{ route('admin.quanlybainguoidung') }}">QUẢN LÝ BÀI VIẾT CỦA NGƯỜI DÙNG</a></li>
                    <li><a href="{{ route('admin.baichoduyet') }}">BÀI CHỜ DUYỆT</a></li>
                    <li><a href="{{ route('admin.baidaduyet') }}">BÀI ĐÃ DUYỆT</a></li>
                    <li><a href="{{ route('admin.lichdangbai') }}">LỊCH ĐĂNG BÀI</a></li>
                    <li><a href="{{ route('admin.quanlybinhluan') }}">PHÂN TÍCH TƯƠNG TÁC</a></li>
                    <li><a href="#" onclick="showLogoutModal()">ĐĂNG XUẤT</a></li>
                </ul>
            </div>

            <div class="admin-main">
                <div class="interaction-actions" style="margin-bottom: 24px;">
                    <a href="{{ route('admin.quanlybinhluan') }}"><button class="interaction-btn">QUẢN LÝ BÌNH LUẬN</button></a>
                    <a href="{{ route('admin.quanlytuongtac') }}"><button class="interaction-btn">QUẢN LÝ TƯƠNG TÁC</button></a>
                    <a href="{{ route('admin.theodoiluotxem') }}"><button class="interaction-btn">THEO DÕI LƯỢT XEM</button></a>
                    <a href="{{ route('admin.xuatdulieu') }}"><button class="interaction-btn">XUẤT DỮ LIỆU</button></a>
                    <a href="{{ route('admin.baocaohieusuat') }}"><button class="interaction-btn">BÁO CÁO</button></a>
                    <a href="{{ route('admin.guithongbao') }}"><button class="interaction-btn selected">GỬI THÔNG BÁO</button></a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h2 class="interaction-title">Gửi Thông Báo</h2>
                <form class="notify-form" method="POST" action="{{ route('admin.send-notification') }}" style="max-width: 700px; margin: 0 auto 32px auto;">
                    @csrf
                    <div style="margin-bottom: 18px;">
                        <label for="user_id" style="font-weight: bold; display: block; margin-bottom: 8px;">Chọn người nhận:</label>
                        <select id="user_id" name="user_id" class="interaction-select" required>
                            <option value="">-- Chọn người nhận --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-bottom: 18px;">
                        <label for="message" style="font-weight: bold; display: block; margin-bottom: 8px;">Nội dung thông báo:</label>
                        <textarea id="message" name="message" class="interaction-select" style="width: 100%; height: 100px; resize: vertical;" placeholder="Nhập nội dung thông báo..." required></textarea>
                    </div>
                    <div style="margin-bottom: 18px;">
                        <label for="method" style="font-weight: bold; display: block; margin-bottom: 8px;">Hình thức gửi:</label>
                        <select id="method" name="method" class="interaction-select" required>
                            <option value="database">Thông báo hệ thống</option>
                        </select>
                    </div>
                    <button type="submit" class="interaction-filter-btn" style="width: 100%;">Gửi thông báo</button>
                </form>

                <h3 style="margin-top: 32px; margin-bottom: 16px;">Lịch sử gửi thông báo</h3>
                <div class="interaction-table-wrapper">
                    <table class="interaction-table">
                        <thead>
                            <tr>
                                <th>Người nhận</th>
                                <th>Nội dung</th>
                                <th>Hình thức</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody id="notification-history">
                            <!-- Sẽ được cập nhật bằng JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Đăng xuất -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <h2>Đăng xuất</h2>
            <p>Bạn có chắc chắn muốn đăng xuất không?</p>
            <div class="modal-buttons">
                <button class="modal-button confirm-button" onclick="confirmLogout()">Có</button>
                <button class="modal-button cancel-button" onclick="hideLogoutModal()">Không</button>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        function showLogoutModal() {
            document.getElementById('logoutModal').style.display = 'flex';
        }

        function hideLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        function confirmLogout() {
            document.getElementById('logout-form').submit();
        }

        // Đóng modal khi click ra ngoài
        window.onclick = function(event) {
            var logoutModal = document.getElementById('logoutModal');
            if (event.target == logoutModal) {
                hideLogoutModal();
            }
        }

        // Tải lịch sử thông báo
        function loadNotificationHistory() {
            fetch('{{ route("admin.notification-history") }}')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('notification-history');
                    tbody.innerHTML = '';
                    data.forEach(notification => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${notification.user}</td>
                            <td>${notification.message}</td>
                            <td>${notification.method}</td>
                            <td>${new Date(notification.sent_at).toLocaleString('vi-VN')}</td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Lỗi khi tải lịch sử thông báo:', error);
                    const tbody = document.getElementById('notification-history');
                    tbody.innerHTML = '<tr><td colspan="4" style="text-align: center;">Không thể tải lịch sử thông báo</td></tr>';
                });
        }

        // Tải lịch sử thông báo khi trang được tải
        document.addEventListener('DOMContentLoaded', loadNotificationHistory);
    </script>
</body>
</html> 