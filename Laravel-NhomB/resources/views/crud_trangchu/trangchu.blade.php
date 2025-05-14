<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trang chủ - Hệ thống quản lý bài đăng</title>
    <link rel="stylesheet" href="{{ asset('css/trangchu.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .modal h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 1.2em;
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .modal-button {
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .confirm-button {
            background-color: #dc3545;
            color: white;
        }

        .confirm-button:hover {
            background-color: #c82333;
        }

        .cancel-button {
            background-color: #6c757d;
            color: white;
        }

        .cancel-button:hover {
            background-color: #5a6268;
        }

        .post {
            position: relative;
        }
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
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-left">
            <a href="{{ url('/trangchu') }}" class="navbar-brand">Fite</a>
        </div>
        <div class="navbar-center">
            <a href="{{ url('/trangchu') }}" class="nav-icon active"><i class="fas fa-home"></i></a>
        </div>
        <div class="navbar-right">
            <a href="/canhan" class="navbar-avatar-link">
                <img src="{{ Auth::user()->avatar_url ?? '/images/default-avatar.png' }}" alt="Avatar" class="navbar-avatar">
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="button" class="logout-btn" onclick="showLogoutModal()">Đăng xuất</button>
            </form>
        </div>
    </nav>

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

    <div class="container">
        <!-- Phần thêm bài viết mới -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <form class="create-post" method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" style="box-shadow:0 2px 8px rgba(0,0,0,0.08);border-radius:16px;padding:24px 24px 16px 24px;background:#fff;">
            @csrf
            <input type="hidden" name="status" value="pending">
            <div class="create-post-header" style="display:flex;align-items:center;gap:16px;">
                <img src="{{ Auth::user()->avatar_url ?? '/images/default-avatar.png' }}" alt="Avatar" class="avatar" style="width:56px;height:56px;border-radius:50%;object-fit:cover;">
                <input type="text" name="content" id="post-content" placeholder="Bạn đang nghĩ gì thế?" style="flex:1;padding:14px 18px;border-radius:24px;border:1px solid #e4e6eb;background:#f0f2f5;font-size:16px;">
            </div>
            <div class="create-post-expanded" id="create-post-expanded" style="margin-top:16px;">
                <input type="text" name="title" id="post-title" placeholder="Tiêu đề bài viết..." required style="width:100%;padding:12px 16px;border-radius:8px;border:1px solid #e4e6eb;margin-bottom:12px;font-size:15px;">
                <div class="create-post-actions" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                    <div class="action-options" style="display:flex;gap:18px;align-items:center;">
                        <label for="post-media" style="cursor:pointer;display:flex;align-items:center;gap:6px;font-weight:500;color:#1877f2;"><i class="fas fa-photo-video photo-video"></i> Ảnh/Video</label>
                        <span style="display:flex;align-items:center;gap:6px;color:#ff4444;"><i class="fas fa-map-marker-alt location-icon"></i> Thêm vị trí</span>
                    </div>
                    <input type="file" id="post-media" name="media[]" accept="image/*,video/*" multiple style="display:none;" onchange="previewMedia(event)">
                    <button class="btn-post" type="submit" style="background:#1877f2;color:#fff;padding:10px 32px;border-radius:24px;font-weight:600;font-size:16px;border:none;box-shadow:0 2px 8px rgba(24,119,242,0.08);transition:background 0.2s;">Đăng bài</button>
                </div>
                <div id="media-preview" style="margin-top:14px;display:flex;gap:12px;flex-wrap:wrap;"></div>
            </div>
        </form>

        <!-- Danh sách bài viết đã đăng -->
        <div class="posts-list">
            @foreach($posts as $post)
                <div class="post" id="post-{{ $post->id }}" data-user-id="{{ $post->user_id }}">
                    <div class="post-header">
                        <img src="{{ $post->user->avatar_url ?? '/images/default-avatar.png' }}" alt="Avatar" class="avatar">
                        <div class="post-info">
                            <h3>{{ $post->user->full_name ?? $post->user->username }}</h3>
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                            @if($post->latitude && $post->longitude)
                                <div class="post-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Đang ở gần <span class="location-name" data-lat="{{ $post->latitude }}" data-lng="{{ $post->longitude }}">Đang tải...</span></span>
                                </div>
                            @endif
                        </div>
                        <!-- Dấu 3 chấm và menu -->
                        <div class="post-menu-wrapper">
                            <span class="post-menu-btn" onclick="togglePostMenu(this)">&#x22EE;</span>
                            <div class="post-menu">
                                @if(Auth::id() === $post->user_id)
                                    <div onclick="editPost({{ $post->id }})">Chỉnh sửa</div>
                                    <div onclick="deletePost({{ $post->id }})">Xóa</div>
                                @else
                                    <div onclick="reportPost({{ $post->id }})">Báo cáo</div>
                                @endif
                            </div>
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
                        <button onclick="likePost({{ $post->id }})">
                            Thích ({{ $post->likes->count() }})
                        </button>
                        <button onclick="showComments({{ $post->id }})">
                            Bình luận ({{ $post->comments->count() }})
                        </button>
                    </div>
                    <!-- Phần bình luận -->
                    <div class="comments" id="comments-{{ $post->id }}" style="display: none;">
                        @foreach($post->comments as $comment)
                            <div class="comment" id="comment-{{ $comment->comment_id }}">
                                <strong>{{ $comment->user->full_name ?? $comment->user->username }}</strong>
                                <p>{{ $comment->content }}</p>
                            </div>
                        @endforeach
                        <div class="comment-input">
                            <input type="text" id="comment-input-{{ $post->id }}" placeholder="Viết bình luận...">
                            <button onclick="addComment({{ $post->id }})">Gửi</button>
                        </div>
                    </div>
                </div>
                <!-- Form xóa ẩn -->
                <form id="delete-form-{{ $post->id }}" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        </div>
    </div>

    <script src="{{ asset('js/trangchu.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
</body>
</html>
