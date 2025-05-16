<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang cá nhân</title>
    <link rel="stylesheet" href="{{ asset('css/canhan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .post-menu-wrapper {
            position: absolute;
            top: 16px;
            right: 16px;
            z-index: 10;
        }
        .post-menu-btn {
            cursor: pointer;
            font-size: 22px;
            padding: 4px 8px;
            border-radius: 50%;
            transition: background 0.2s;
        }
        .post-menu-btn:hover {
            background: #f0f0f0;
        }
        .post-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 28px;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 6px;
            min-width: 120px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .post-menu.active {
            display: block;
        }
        .post-menu div {
            padding: 10px 18px;
            cursor: pointer;
            font-size: 15px;
        }
        .post-menu div:hover {
            background: #f0f0f0;
        }
        .post {
            position: relative;
        }
    </style>
</head>
<body data-current-user-id="{{ Auth::id() }}" data-profile-user-id="{{ $user->id }}">
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
                <img src="{{ $user->avatar_url ?? asset('images/default-avatar.png') }}" alt="Avatar" class="profile-avatar">
                <span class="profile-status online"></span>
            </div>
            <div class="profile-info">
                <h2 class="profile-name">{{ $user->full_name ?? $user->name ?? $user->username }}</h2>
                <span class="profile-status-text">Đang hoạt động</span>
            </div>
            <div class="profile-actions">
                <button class="profile-menu-btn" onclick="toggleProfileMenu()"><i class="fas fa-ellipsis-h"></i></button>
                <div class="profile-menu" id="profileMenu">
                    <a href="#" onclick="showChangePassword(event)"><i class="fas fa-key"></i> Đổi mật khẩu</a>
                    <a href="#" onclick="showChangeAvatar(event)"><i class="fas fa-image"></i> Đổi avatar</a>
                </div>
            </div>
        </div>
        <div class="profile-posts">
            <h3>Bài đăng của bạn</h3>
            <div class="posts-list">
                @forelse($posts as $post)
                    <div class="post" id="post-{{ $post->id }}" data-user-id="{{ $post->user_id }}">
                        <div class="post-header">
                            <img src="{{ $user->avatar_url ?? asset('images/default-avatar.png') }}" alt="Avatar" class="avatar">
                            <div class="post-info">
                                <h3>{{ $user->full_name ?? $user->username }}</h3>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="post-menu-wrapper">
                                <span class="post-menu-btn" onclick="togglePostMenu(this)">&#x22EE;</span>
                                <div class="post-menu"></div>
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
                        <form id="delete-form-{{ $post->id }}" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
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
    <!-- Modal chỉnh sửa bài viết -->
    <div id="editPostModal" class="modal" style="display:none;">
        <div class="modal-content">
            <h2>Chỉnh sửa tiêu đề bài viết</h2>
            <form id="edit-post-form" method="POST">
                @csrf
                @method('PUT')
                <input type="text" name="title" id="edit-post-title" placeholder="Tiêu đề mới" required style="width:100%;margin-bottom:10px;">
                <div class="modal-buttons">
                    <button type="submit" class="modal-button confirm-button">Lưu</button>
                    <button type="button" class="modal-button cancel-button" onclick="hideEditModal()">Hủy</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/canhan.js') }}"></script>
    {{-- <script src="{{ asset('js/trangchu.js') }}"></script> --}}
    <script>
    console.log('showChangePassword:', typeof window.showChangePassword);
    console.log('showChangeAvatar:', typeof window.showChangeAvatar);
    console.log('showLogoutModal:', typeof window.showLogoutModal);
    console.log('editPost:', typeof window.editPost);
    console.log('deletePost:', typeof window.deletePost);
    console.log('togglePostMenu:', typeof window.togglePostMenu);
    </script>
</body>
</html>
