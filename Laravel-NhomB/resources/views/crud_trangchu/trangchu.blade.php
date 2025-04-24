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
            <a href="#" class="nav-icon"><i class="fas fa-users"></i></a>
        </div>
        <div class="navbar-right">
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

    <div class="container">
        <!-- Phần thêm bài viết mới -->
        <div class="create-post">
            <div class="create-post-header">
                <img src="{{ Auth::user()->avatar_url ?? '/images/default-avatar.png' }}" alt="Avatar" class="avatar">
                <input type="text" id="post-content" placeholder="Bạn đang nghĩ gì thế?" onclick="expandPostInput()">
            </div>
            <div class="create-post-expanded" id="create-post-expanded" style="display: none;">
                <input type="text" id="post-title" placeholder="Tiêu đề bài viết..." required>
                <textarea id="post-content-expanded" placeholder="Bạn đang nghĩ gì?"></textarea>
                <div class="create-post-actions">
                    <div class="action-options">
                        <label for="post-media"><i class="fas fa-photo-video photo-video"></i> Ảnh/Video</label>
                        <span onclick="getLocation()"><i class="fas fa-map-marker-alt location-icon"></i> Thêm vị trí</span>
                    </div>
                    <input type="file" id="post-media" accept="image/*,video/*" style="display: none;" onchange="previewMedia()">
                    <input type="hidden" id="post-latitude">
                    <input type="hidden" id="post-longitude">
                    <span id="location-display" style="color: #65676b; margin-top: 5px; display: none;"></span>
                    <div id="media-preview" style="margin-top: 10px;"></div>
                    <button class="btn-post" onclick="createPost()">Đăng bài</button>
                </div>
            </div>
        </div>

        <!-- Danh sách bài viết đã đăng -->
        <div class="posts-list">
            @foreach($posts as $post)
                <div class="post" id="post-{{ $post->post_id }}">
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
                        <button onclick="likePost({{ $post->post_id }})">
                            Thích ({{ $post->likes->count() }})
                        </button>
                        <button onclick="showComments({{ $post->post_id }})">
                            Bình luận ({{ $post->comments->count() }})
                        </button>
                    </div>
                    <!-- Phần bình luận -->
                    <div class="comments" id="comments-{{ $post->post_id }}" style="display: none;">
                        @foreach($post->comments as $comment)
                            <div class="comment" id="comment-{{ $comment->comment_id }}">
                                <strong>{{ $comment->user->full_name ?? $comment->user->username }}</strong>
                                <p>{{ $comment->content }}</p>
                            </div>
                        @endforeach
                        <div class="comment-input">
                            <input type="text" id="comment-input-{{ $post->post_id }}" placeholder="Viết bình luận...">
                            <button onclick="addComment({{ $post->post_id }})">Gửi</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="{{ asset('js/trangchu.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
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
</body>
</html>