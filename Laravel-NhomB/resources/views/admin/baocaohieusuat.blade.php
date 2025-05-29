<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Báo Cáo Hiệu Suất Hàng Tháng</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .change-positive {
            color: #28a745;
        }
        .change-negative {
            color: #dc3545;
        }
        .interaction-filters {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            align-items: center;
        }
        .interaction-select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .interaction-filter-btn {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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
                    <a href="{{ route('admin.baocaohieusuat') }}"><button class="interaction-btn selected">BÁO CÁO</button></a>
                    <a href="{{ route('admin.guithongbao') }}"><button class="interaction-btn">GỬI THÔNG BÁO</button></a>
                </div>
                <h2 class="interaction-title">Báo Cáo Hiệu Suất Hàng Tháng</h2>
                <form action="{{ route('admin.baocaohieusuat') }}" method="GET" class="interaction-filters">
                    <select name="month" class="interaction-select">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                        @endfor
                    </select>
                    <select name="year" class="interaction-select">
                        @for($i = now()->year; $i >= now()->year - 2; $i--)
                            <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>Năm {{ $i }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="interaction-filter-btn">Xem Báo Cáo</button>
                </form>
                <div class="interaction-table-wrapper">
                    <table class="interaction-table">
                        <thead>
                            <tr>
                                <th>Chỉ số</th>
                                <th>Tháng này</th>
                                <th>Tháng trước</th>
                                <th>Thay đổi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Lượt xem</td>
                                <td>{{ number_format($stats['views']['current']) }}</td>
                                <td>{{ number_format($stats['views']['previous']) }}</td>
                                <td class="{{ $stats['views']['change'] >= 0 ? 'change-positive' : 'change-negative' }}">
                                    {{ $stats['views']['change'] }}%
                                </td>
                            </tr>
                            <tr>
                                <td>Lượt thích</td>
                                <td>{{ number_format($stats['likes']['current']) }}</td>
                                <td>{{ number_format($stats['likes']['previous']) }}</td>
                                <td class="{{ $stats['likes']['change'] >= 0 ? 'change-positive' : 'change-negative' }}">
                                    {{ $stats['likes']['change'] }}%
                                </td>
                            </tr>
                            <tr>
                                <td>Lượt chia sẻ</td>
                                <td>{{ number_format($stats['shares']['current']) }}</td>
                                <td>{{ number_format($stats['shares']['previous']) }}</td>
                                <td class="{{ $stats['shares']['change'] >= 0 ? 'change-positive' : 'change-negative' }}">
                                    {{ $stats['shares']['change'] }}%
                                </td>
                            </tr>
                            <tr>
                                <td>Lượt bình luận</td>
                                <td>{{ number_format($stats['comments']['current']) }}</td>
                                <td>{{ number_format($stats['comments']['previous']) }}</td>
                                <td class="{{ $stats['comments']['change'] >= 0 ? 'change-positive' : 'change-negative' }}">
                                    {{ $stats['comments']['change'] }}%
                                </td>
                            </tr>
                            <tr>
                                <td>Người dùng mới</td>
                                <td>{{ number_format($stats['users']['current']) }}</td>
                                <td>{{ number_format($stats['users']['previous']) }}</td>
                                <td class="{{ $stats['users']['change'] >= 0 ? 'change-positive' : 'change-negative' }}">
                                    {{ $stats['users']['change'] }}%
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
    </script>
    <!-- Logout Modal -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <h2>Đăng xuất</h2>
            <p>Bạn có muốn đăng xuất không?</p>
            <div class="modal-buttons">
                <button class="modal-button confirm-button" onclick="confirmLogout()">Có</button>
                <button class="modal-button cancel-button" onclick="hideLogoutModal()">Không</button>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>
</html> 