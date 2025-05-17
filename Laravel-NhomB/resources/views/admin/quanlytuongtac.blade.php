<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý tương tác</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .interaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .interaction-table th,
        .interaction-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .interaction-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .interaction-table tbody tr:hover {
            background-color: #f5f5f5;
        }

        .sort-icon {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 5px;
            vertical-align: middle;
            border-right: 4px solid transparent;
            border-left: 4px solid transparent;
        }

        .sort-icon.asc {
            border-bottom: 4px solid #333;
        }

        .sort-icon.desc {
            border-top: 4px solid #333;
        }

        .filter-section {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-section select,
        .filter-section button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }

        .filter-section button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .filter-section button:hover {
            background: #0056b3;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .pagination a {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #333;
            text-decoration: none;
        }

        .pagination a:hover {
            background: #f5f5f5;
        }

        .pagination .active {
            background: #007bff;
            color: white;
            border-color: #007bff;
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
                    <li><a href="{{ route('admin.phantichtruycap') }}">PHÂN TÍCH TƯƠNG TÁC</a></li>
                    <li><a href="#" onclick="showLogoutModal()">ĐĂNG XUẤT</a></li>
                </ul>
            </div>

            <div class="admin-main">
                <div class="interaction-actions" style="margin-bottom: 24px;">
                    <a href="{{ route('admin.quanlybinhluan') }}"><button class="interaction-btn">QUẢN LÝ BÌNH LUẬN</button></a>
                    <a href="{{ route('admin.quanlytuongtac') }}"><button class="interaction-btn selected">QUẢN LÝ TƯƠNG TÁC</button></a>
                    <a href="{{ route('admin.theodoiluotxem') }}"><button class="interaction-btn">THEO DÕI LƯỢT XEM</button></a>
                    <a href="{{ route('admin.xuatdulieu') }}"><button class="interaction-btn">XUẤT DỮ LIỆU</button></a>
                    <a href="{{ route('admin.baocaohieusuat') }}"><button class="interaction-btn">BÁO CÁO</button></a>
                    <a href="{{ route('admin.guithongbao') }}"><button class="interaction-btn">GỬI THÔNG BÁO</button></a>
                </div>

                <h2 class="interaction-title">Quản Lý Tương Tác</h2>

                <div class="filter-section">
                    <select id="userFilter" onchange="applyFilters()">
                        <option value="all">Tất cả người dùng</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>

                    <select id="timeRange" onchange="applyFilters()">
                        <option value="all" {{ request('time_range') == 'all' ? 'selected' : '' }}>Tất cả thời gian</option>
                        <option value="today" {{ request('time_range') == 'today' ? 'selected' : '' }}>Hôm nay</option>
                        <option value="week" {{ request('time_range') == 'week' ? 'selected' : '' }}>Tuần này</option>
                        <option value="month" {{ request('time_range') == 'month' ? 'selected' : '' }}>Tháng này</option>
                        <option value="year" {{ request('time_range') == 'year' ? 'selected' : '' }}>Năm nay</option>
                    </select>

                    <select id="sortBy" onchange="applyFilters()">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Sắp xếp theo thời gian</option>
                        <option value="likes" {{ request('sort_by') == 'likes' ? 'selected' : '' }}>Sắp xếp theo lượt thích</option>
                        <option value="comments" {{ request('sort_by') == 'comments' ? 'selected' : '' }}>Sắp xếp theo bình luận</option>
                        <option value="shares" {{ request('sort_by') == 'shares' ? 'selected' : '' }}>Sắp xếp theo chia sẻ</option>
                    </select>

                    <select id="sortOrder" onchange="applyFilters()">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                    </select>
                </div>

                <div class="interaction-table-wrapper">
                    <table class="interaction-table">
                        <thead>
                            <tr>
                                <th>Bài Viết</th>
                                <th>Người Đăng</th>
                                <th>Ngày Đăng</th>
                                <th>Lượt Thích</th>
                                <th>Bình Luận</th>
                                <th>Chia Sẻ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                            <tr>
                                <td>{{ $post->title }}</td>
                                <td>{{ $post->user->name }}</td>
                                <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $post->likes_count }}</td>
                                <td>{{ $post->comments_count }}</td>
                                <td>{{ $post->shares_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    {{ $posts->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

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

    <script>
        function applyFilters() {
            const userId = document.getElementById('userFilter').value;
            const timeRange = document.getElementById('timeRange').value;
            const sortBy = document.getElementById('sortBy').value;
            const sortOrder = document.getElementById('sortOrder').value;

            const params = new URLSearchParams();
            if (userId !== 'all') params.append('user_id', userId);
            if (timeRange !== 'all') params.append('time_range', timeRange);
            params.append('sort_by', sortBy);
            params.append('sort_order', sortOrder);

            window.location.href = `{{ route('admin.quanlytuongtac') }}?${params.toString()}`;
        }

        // Hiển thị modal đăng xuất
        function showLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.style.display = 'flex';
        }

        // Ẩn modal đăng xuất
        function hideLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.style.display = 'none';
        }

        // Xác nhận đăng xuất
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
</body>
</html>
