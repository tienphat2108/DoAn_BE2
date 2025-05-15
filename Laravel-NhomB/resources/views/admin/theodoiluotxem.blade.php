<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Theo dõi lượt xem</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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
                    <a href="{{ route('admin.tuongtac') }}"><button class="interaction-btn">QUẢN LÝ TƯƠNG TÁC</button></a>
                    <a href="{{ route('admin.theodoiluotxem') }}"><button class="interaction-btn selected">THEO DÕI LƯỢT XEM</button></a>
                    <a href="{{ route('admin.xuatdulieu') }}"><button class="interaction-btn">XUẤT DỮ LIỆU</button></a>
                    <a href="{{ route('admin.baocaohieusuat') }}"><button class="interaction-btn">BÁO CÁO</button></a>
                    <a href="{{ route('admin.guithongbao') }}"><button class="interaction-btn">GỬI THÔNG BÁO</button></a>
                </div>
                <h2 class="interaction-title">Theo Dõi Lượt Xem</h2>
                <div class="interaction-filters" style="display: flex; gap: 12px; margin-bottom: 24px;">
                    <form method="GET" style="display: flex; gap: 12px; align-items: center;">
                        <select name="post_id" class="interaction-select">
                            <option value="all">Tất cả bài viết</option>
                            @foreach($allPosts as $p)
                                <option value="{{ $p->id }}" {{ $selectedPostId == $p->id ? 'selected' : '' }}>{{ $p->title }}</option>
                            @endforeach
                        </select>
                        <select name="time_range" class="interaction-select">
                            <option value="all" {{ $selectedTimeRange == 'all' ? 'selected' : '' }}>Tất cả thời gian</option>
                            <option value="today" {{ $selectedTimeRange == 'today' ? 'selected' : '' }}>Hôm nay</option>
                            <option value="week" {{ $selectedTimeRange == 'week' ? 'selected' : '' }}>Tuần này</option>
                            <option value="month" {{ $selectedTimeRange == 'month' ? 'selected' : '' }}>Tháng này</option>
                        </select>
                        <button class="interaction-filter-btn" type="submit">Lọc</button>
                    </form>
                </div>
                <div class="interaction-table-wrapper">
                    <table class="interaction-table">
                        <thead>
                            <tr>
                                <th>Bài viết</th>
                                <th>Lượt xem hôm nay</th>
                                <th>Lượt xem tuần này</th>
                                <th>Lượt xem tháng này</th>
                                <th>Tổng lượt xem</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>{{ $post->title }}</td>
                                <td>{{ $post->today_views ?? 0 }}</td>
                                <td>{{ $post->week_views ?? 0 }}</td>
                                <td>{{ $post->month_views ?? 0 }}</td>
                                <td>{{ $post->all_views ?? $post->views->count() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
</body>
</html> 