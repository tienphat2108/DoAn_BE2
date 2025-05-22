<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ADMIN quản lý bài đăng</title>
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
                    <li><a href="{{ route('admin.quanlybinhluan') }}">PHÂN TÍCH TƯƠNG TÁC</a></li>
                    <li><a href="#" onclick="showLogoutModal()">ĐĂNG XUẤT</a></li>
                </ul>
            </div>

            <div class="admin-main">
                <h2>ADMIN quản lý bài đăng</h2>
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

                <div class="posts-table">
                    <table class="table-users">
                        <thead>
                            <tr>
                                <th>Hình ảnh</th>
                                <th>Tiêu đề</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts as $post)
                            <tr>
                                <td>
                                    @if($post->media && $post->media->count() > 0)
                                        @php $media = $post->media->first(); @endphp
                                        @if(str_contains($media->file_type, 'image'))
                                            <img src="{{ asset('storage/' . $media->file_url) }}" alt="{{ $post->title }}" style="max-width: 100px; border-radius: 8px;">
                                        @elseif(str_contains($media->file_type, 'video'))
                                            <video src="{{ asset('storage/' . $media->file_url) }}" style="max-width: 100px; border-radius: 8px;" controls></video>
                                        @endif
                                    @else
                                        Không có hình
                                    @endif
                                </td>
                                <td>{{ $post->title }}</td>
                                <td>
                                    @if($post->status == 'request')
                                        Chờ duyệt sơ bộ
                                    @elseif($post->status == 'pending')
                                        Chờ duyệt chính thức
                                    @elseif($post->status == 'approved')
                                        Đã duyệt
                                    @elseif($post->status == 'bản nháp' || $post->status == 'draft')
                                        Bản nháp
                                    @elseif($post->status == 'bị từ chối')
                                        Bị từ chối
                                    @elseif($post->status == 'canceled')
                                        Đã hủy
                                    @else
                                        {{ $post->status }}
                                    @endif
                                </td>
                                <td>{{ $post->created_at->format('d/m/Y') }}</td>
                                <td class="action-cell">
                                    @if($post->status == 'request')
                                        <form action="{{ route('admin.approvePost', $post->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class="btn-approve">Duyệt</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.deletePost', $post->id) }}" method="POST" style="display: inline-block; margin-left: 16px;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Không có bài viết nào</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <h2>Xác nhận đăng xuất</h2>
            <p>Bạn có chắc chắn muốn đăng xuất không?</p>
            <div class="modal-buttons">
                <button class="modal-button confirm-button" onclick="confirmLogout()">Đăng xuất</button>
                <button class="modal-button cancel-button" onclick="hideLogoutModal()">Hủy</button>
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
            var modal = document.getElementById('logoutModal');
            if (event.target == modal) {
                hideLogoutModal();
            }
        }
    </script>

    <style>
        .filter-bar select, .filter-bar input[type="date"], .filter-bar .filter-search {
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
            margin-right: 0;
            outline: none;
            min-width: 140px;
        }
        .filter-bar .filter-search {
            min-width: 200px;
        }
        .filter-bar .filter-btn {
            padding: 10px 24px;
            border-radius: 8px;
            background: #222;
            color: #fff;
            font-weight: 600;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .filter-bar .filter-btn:hover {
            background: #444;
        }
        @media (max-width: 900px) {
            .filter-bar { flex-direction: column; gap: 10px; }
            .filter-bar select, .filter-bar input, .filter-bar .filter-btn { width: 100%; min-width: 0; }
        }
    </style>
</body>
</html> 