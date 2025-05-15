<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trang chủ - Hệ thống quản lý bài đăng</title>
    <link rel="stylesheet" href="{{ asset('css/trangchu.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
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
                        <span style="display:flex;align-items:center;gap:6px;color:#ff4444;cursor:pointer;" onclick="showLocationModal()"><i class="fas fa-map-marker-alt location-icon"></i> Thêm vị trí</span>
                    </div>
                    <input type="file" id="post-media" name="media[]" accept="image/*,video/*" multiple style="display:none;" onchange="previewMedia(event)">
                    <button class="btn-post" type="submit" style="background:#1877f2;color:#fff;padding:10px 32px;border-radius:24px;font-weight:600;font-size:16px;border:none;box-shadow:0 2px 8px rgba(24,119,242,0.08);transition:background 0.2s;">Đăng bài</button>
                </div>
                <div id="media-preview" style="margin-top:14px;display:flex;gap:12px;flex-wrap:wrap;"></div>
            </div>
            <input type="hidden" id="post-latitude" name="latitude">
            <input type="hidden" id="post-longitude" name="longitude">
            <div id="location-display" style="display:none;color:#1976d2;font-weight:500;margin-top:8px;"></div>
        </form>

        <!-- Danh sách bài viết đã đăng -->
        <div class="posts-list">
            @foreach($posts as $post)
                @php
                    $userLiked = $post->likes->contains('user_id', Auth::id());
                @endphp
                <div class="post" id="post-{{ $post->id }}" data-user-id="{{ $post->user_id }}">
                    <div class="post-header">
                        <img src="{{ $post->user->avatar_url ?? '/images/default-avatar.png' }}" alt="Avatar" class="avatar">
                        <div class="post-info">
                            <h3>{{ $post->user->full_name ?? $post->user->username }}</h3>
                            <span>
                                {{ $post->created_at->diffForHumans() }}
                                @if($post->latitude && $post->longitude)
                                    · <span class="location-name" data-lat="{{ $post->latitude }}" data-lng="{{ $post->longitude }}">Đang tải...</span>
                                @endif
                            </span>
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
                        <button class="like-btn{{ $userLiked ? ' liked' : '' }}" data-post-id="{{ $post->id }}" style="{{ $userLiked ? 'color:#1877f2;' : '' }}">
                            Thích (<span id="like-count-{{ $post->id }}">{{ $post->likes->count() }}</span>)
                        </button>
                        <button class="toggle-comments-btn" data-post-id="{{ $post->id }}">
                            Bình luận (<span id="comment-count-{{ $post->id }}">{{ $post->comments->count() }}</span>)
                        </button>
                        <button class="share-btn" onclick="sharePost({{ $post->id }})">
                            Chia sẻ (<span id="share-count-{{ $post->id }}">{{ $post->shares_count ?? 0 }}</span>)
                        </button>
                        <span class="view-count" style="margin-left: 10px; color: #888; font-size: 14px;">
                            👁️ <span id="view-count-{{ $post->id }}">{{ $post->views->count() }}</span> lượt xem
                        </span>
                    </div>
                    <!-- Phần bình luận -->
                    <div class="comments" id="comments-{{ $post->id }}" style="display:none;">
                        @foreach($post->comments as $comment)
                            <div class="comment" id="comment-{{ $comment->id }}">
                                <strong>{{ $comment->user->full_name ?? $comment->user->username }}</strong>
                                <p>{{ $comment->content }}</p>
                                <span style="color: #888; font-size: 12px;">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endforeach
                        @if(Auth::check())
                            <form class="comment-form" action="/admin/comments" method="POST" style="margin-top: 8px;">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <textarea name="content" required placeholder="Nhập bình luận..." style="width:100%;min-height:40px;"></textarea>
                                <button type="submit">Gửi bình luận</button>
                            </form>
                        @else
                            <p><a href="{{ route('login') }}">Đăng nhập</a> để bình luận.</p>
                        @endif
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

    <!-- Modal chọn vị trí -->
    <div id="locationModal" class="modal" style="display:none;z-index:99999;">
        <div class="modal-content" style="width:400px;max-width:90vw;">
            <h2>Chọn vị trí</h2>
            <div id="leaflet-map" style="width:100%;height:300px;"></div>
            <div id="modal-location-address" style="margin:10px 0;color:#1976d2;"></div>
            <div class="modal-buttons">
                <button type="button" class="modal-button confirm-button" onclick="confirmLocation()">Chọn vị trí này</button>
                <button type="button" class="modal-button cancel-button" onclick="hideLocationModal()">Hủy</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/trangchu.js') }}"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
    console.log('Script AJAX bình luận đã chạy!');
    document.querySelectorAll('form.comment-form').forEach(function(form) {
        console.log('Đã gắn submit cho form:', form);
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var postId = form.querySelector('input[name="post_id"]').value;
            var content = form.querySelector('textarea[name="content"]').value;
            var countSpan = document.getElementById('comment-count-' + postId);
            var commentsDiv = form.closest('.comments');
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    post_id: postId,
                    content: content
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then(data => {
                if (data.success || data.id) {
                    if (countSpan) {
                        var match = countSpan.textContent.match(/\d+/);
                        if (match) {
                            var current = parseInt(match[0]);
                            countSpan.textContent = current + 1;
                        } else {
                            countSpan.textContent = 1;
                        }
                    }
                    var newComment = document.createElement('div');
                    newComment.className = 'comment';
                    newComment.innerHTML = '<strong>Bạn</strong><p>' + content + '</p><span style=\"color: #888; font-size: 12px;\">Vừa xong</span>';
                    commentsDiv.insertBefore(newComment, form);
                    form.querySelector('textarea[name="content"]').value = '';
                } else {
                    alert('Có lỗi khi gửi bình luận!');
                }
            })
            .catch(err => {
                alert('Có lỗi khi gửi bình luận!');
                console.error('AJAX error:', err);
            });
        });
    });

    // Toggle hiển thị bình luận
    document.querySelectorAll('.toggle-comments-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var postId = btn.getAttribute('data-post-id');
            var commentsDiv = document.getElementById('comments-' + postId);
            if (commentsDiv.style.display === 'none' || commentsDiv.style.display === '') {
                commentsDiv.style.display = 'block';
            } else {
                commentsDiv.style.display = 'none';
            }
        });
    });

    // Xử lý like/unlike động cho nút Thích
    document.querySelectorAll('.post-actions button').forEach(function(btn) {
        if (btn.textContent.trim().startsWith('Thích')) {
            btn.addEventListener('click', function() {
                var postId = btn.getAttribute('data-post-id');
                var likeCountSpan = btn.querySelector('span') || btn;
                // Nếu chưa có span, tạo span cho số like
                if (!likeCountSpan || likeCountSpan === btn) {
                    var currentText = btn.textContent;
                    var match = currentText.match(/Thích \((\d+)\)/);
                    var current = match ? parseInt(match[1]) : 0;
                    btn.innerHTML = 'Thích (<span id="like-count-' + postId + '">' + current + '</span>)';
                    likeCountSpan = document.getElementById('like-count-' + postId);
                }
                var liked = btn.classList.contains('liked');
                var url = '/posts/' + postId + '/like';
                var method = liked ? 'DELETE' : 'POST';
                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var current = parseInt(likeCountSpan.textContent);
                        if (liked) {
                            likeCountSpan.textContent = current > 0 ? current - 1 : 0;
                            btn.classList.remove('liked');
                            btn.style.color = '';
                        } else {
                            likeCountSpan.textContent = current + 1;
                            btn.classList.add('liked');
                            btn.style.color = '#1877f2';
                        }
                    } else {
                        alert('Có lỗi khi thích bài viết!');
                    }
                })
                .catch(err => {
                    alert('Có lỗi khi thích bài viết!');
                    console.error('AJAX like error:', err);
                });
            });
        }
    });

    // Hàm chia sẻ bài viết
    function sharePost(postId) {
        // Tạo URL của bài viết
        const postUrl = `${window.location.origin}/posts/${postId}`;
        
        // Kiểm tra xem trình duyệt có hỗ trợ Web Share API không
        if (navigator.share) {
            navigator.share({
                title: 'Chia sẻ bài viết từ Fite',
                text: 'Xem bài viết này trên Fite',
                url: postUrl
            })
            .then(() => {
                // Cập nhật số lượt chia sẻ
                updateShareCount(postId);
            })
            .catch(error => {
                console.log('Lỗi khi chia sẻ:', error);
                fallbackShare(postUrl);
            });
        } else {
            fallbackShare(postUrl);
        }
    }

    // Hàm thay thế khi trình duyệt không hỗ trợ Web Share API
    function fallbackShare(url) {
        // Tạo một input ẩn để copy URL
        const tempInput = document.createElement('input');
        tempInput.value = url;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        alert('Đã sao chép liên kết bài viết vào clipboard!');
    }

    // Hàm cập nhật số lượt chia sẻ
    function updateShareCount(postId) {
        fetch(`/posts/${postId}/share`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const shareCountSpan = document.getElementById(`share-count-${postId}`);
                if (shareCountSpan) {
                    shareCountSpan.textContent = parseInt(shareCountSpan.textContent) + 1;
                }
            }
        })
        .catch(error => {
            console.error('Lỗi khi cập nhật lượt chia sẻ:', error);
        });
    }

    // Xử lý báo cáo bài viết
    function reportPost(postId) {
        if (!confirm('Bạn chắc chắn muốn báo cáo bài viết này?')) return;
        fetch('/posts/' + postId + '/report', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || (data.success ? 'Đã báo cáo bài viết!' : 'Có lỗi xảy ra!'));
        })
        .catch(err => {
            alert('Có lỗi khi báo cáo bài viết!');
            console.error('Report error:', err);
        });
    }
    </script>
</body>
</html>
