<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang cá nhân</title>
    <link rel="stylesheet" href="{{ asset('css/canhan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="/trangchu" class="navbar-brand">Fite</a>
        </div>
        <div class="navbar-center">
            <a href="/trangchu" class="nav-icon"><i class="fas fa-home"></i></a>
            <a href="/canhan" class="nav-icon active"><i class="fas fa-user"></i></a>
        </div>
        <div class="navbar-right">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="button" class="logout-btn" onclick="showLogoutModal()">Đăng xuất</button>
            </form>
        </div>
    </nav>
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
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar-status">
                <img src="{{ $user->avatar_url ?? '/images/default-avatar.png' }}" alt="Avatar" class="profile-avatar">
                <span class="profile-status online"></span>
            </div>
            <div class="profile-info">
                <h2 class="profile-name">{{ $user->full_name ?? $user->name ?? $user->username }}</h2>
                <span class="profile-status-text">Đang hoạt động</span>
            </div>
            <div class="profile-actions">
                <button class="profile-menu-btn" onclick="toggleProfileMenu()"><i class="fas fa-ellipsis-h"></i></button>
                <div class="profile-menu" id="profileMenu">
                    <a href="#" onclick="showChangePassword()"><i class="fas fa-key"></i> Đổi mật khẩu</a>
                    <a href="#" onclick="showChangeAvatar()"><i class="fas fa-image"></i> Đổi avatar</a>
                </div>
            </div>
        </div>
        <div class="profile-posts">
            <h3>Bài đăng của bạn</h3>
            <div class="posts-list">
                @forelse($posts as $post)
                    <div class="post" id="post-{{ $post->post_id }}">
                        <div class="post-header">
                            <img src="{{ $user->avatar_url ?? '/images/default-avatar.png' }}" alt="Avatar" class="avatar">
                            <div class="post-info">
                                <h3>{{ $user->full_name ?? $user->username }}</h3>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="post-body">
                            <h4>{{ $post->title }}</h4>
                            <p>{{ $post->content }}</p>
                            @if($post->media->isNotEmpty())
                                @foreach($post->media as $media)
                                    @if(str_contains($media->file_url, '.mp4'))
                                        <video controls>
                                            <source src="{{ asset('storage/' . $media->file_url) }}" type="video/mp4">
                                        </video>
                                    @else
                                        <img src="{{ asset('storage/' . $media->file_url) }}" alt="Media">
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div class="post-actions">
                            <button>Thích ({{ $post->likes->count() }})</button>
                            <button>Bình luận ({{ $post->comments->count() }})</button>
                        </div>
                    </div>
                @empty
                    <p>Bạn chưa đăng bài nào.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div id="changePasswordModal" class="modal" style="display:none;">
        <div class="modal-content">
            <h2>Đổi mật khẩu</h2>
            <form method="POST" action="{{ route('canhan.password') }}">
                @csrf
                <input type="password" name="password" placeholder="Mật khẩu mới" class="input-modal" required>
                <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" class="input-modal" required>
                <div class="modal-buttons">
                    <button type="submit" class="modal-button confirm-button">Lưu</button>
                    <button type="button" class="modal-button cancel-button" onclick="hideChangePassword()">Hủy</button>
                </div>
            </form>
        </div>
    </div>
    <div id="changeAvatarModal" class="modal" style="display:none;">
        <div class="modal-content">
            <h2>Đổi avatar</h2>
            <form method="POST" action="{{ route('canhan.avatar') }}" enctype="multipart/form-data">
                @csrf
                <input type="file" name="avatar" class="input-modal" accept="image/*" required>
                <div class="modal-buttons">
                    <button type="submit" class="modal-button confirm-button">Lưu</button>
                    <button type="button" class="modal-button cancel-button" onclick="hideChangeAvatar()">Hủy</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/canhan.js') }}"></script>
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
        function toggleProfileMenu() {
            var menu = document.getElementById('profileMenu');
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }
        window.onclick = function(event) {
            var menu = document.getElementById('profileMenu');
            if (event.target !== menu && !menu.contains(event.target) && event.target.className !== 'profile-menu-btn') {
                menu.style.display = 'none';
            }
            var logoutModal = document.getElementById('logoutModal');
            if (event.target == logoutModal) {
                hideLogoutModal();
            }
        }
        function showChangePassword() {
            document.getElementById('changePasswordModal').style.display = 'flex';
            document.getElementById('profileMenu').style.display = 'none';
        }
        function hideChangePassword() {
            document.getElementById('changePasswordModal').style.display = 'none';
        }
        function showChangeAvatar() {
            document.getElementById('changeAvatarModal').style.display = 'flex';
            document.getElementById('profileMenu').style.display = 'none';
        }
        function hideChangeAvatar() {
            document.getElementById('changeAvatarModal').style.display = 'none';
        }
    </script>
</body>
</html>
