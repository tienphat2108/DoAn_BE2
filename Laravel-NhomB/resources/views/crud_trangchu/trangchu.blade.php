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
        .comment-body {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .comment-content {
            margin: 0; /* Remove default paragraph margin */
        }
        .edit-comment-btn {
            background: none;
            border: 1px solid #007bff;
            color: #007bff;
            cursor: pointer;
            font-size: 0.9em;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .edit-comment-btn:hover {
            text-decoration: underline;
            background-color: rgba(0, 123, 255, 0.1);
        }
    </style>
</head>
<body data-current-user-name="{{ Auth::user()->full_name ?? Auth::user()->username ?? 'Ẩn danh' }}">
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
                <img src="{{ asset(Auth::user()->avatar_url ?? '/images/default-avatar.png') }}" alt="Avatar" class="navbar-avatar">
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
                <input type="hidden" name="original_updated_at" id="edit-post-original-updated-at">
                <div class="modal-buttons">
                    <button type="submit" class="modal-button confirm-button">Lưu</button>
                    <button type="button" class="modal-button cancel-button" onclick="hideEditModal()">Hủy</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bell notification -->
    <div id="bell-notification" style="position:fixed;top:70px;right:24px;z-index:10000;">
        <div style="position:relative;">
            <i class="fas fa-bell" id="bell-icon" style="font-size:2rem;color:#1877f2;cursor:pointer;"></i>
            <span id="bell-badge" style="display:none;position:absolute;top:-6px;right:-6px;background:#dc3545;color:#fff;border-radius:50%;padding:2px 7px;font-size:0.9em;font-weight:bold;">1</span>
        </div>
        <div id="bell-dropdown" style="display:none;position:absolute;right:0;top:36px;background:#fff;min-width:260px;box-shadow:0 2px 12px rgba(0,0,0,0.12);border-radius:8px;overflow:hidden;">
            <div id="bell-list"></div>
        </div>
    </div>

    <div class="container">
        <!-- Phần thêm bài viết mới -->
        @if(session('success'))
            <div id="toast-success" style="position:fixed;top:24px;right:24px;z-index:9999;background:#28a745;color:#fff;padding:16px 32px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.12);font-size:1.1em;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div id="toast-error" style="position:fixed;top:24px;right:24px;z-index:9999;background:#dc3545;color:#fff;padding:16px 32px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.12);font-size:1.1em;">
                {{ session('error') }}
            </div>
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
            <input type="hidden" name="draft_id" id="draft_id" value="{{ session('draft_id') ?? '' }}">
            <input type="hidden" name="scheduled_at" id="scheduled_at" value="{{ old('scheduled_at') }}">
            <div class="create-post-header" style="display:flex;align-items:center;gap:16px;">
                <img src="{{ asset(Auth::user()->avatar_url ?? '/images/default-avatar.png') }}" alt="Avatar" class="avatar" style="width:56px;height:56px;border-radius:50%;object-fit:cover;">
                <input type="text" name="content" id="post-content" placeholder="Bạn đang nghĩ gì thế?" style="flex:1;padding:14px 18px;border-radius:24px;border:1px solid #e4e6eb;background:#f0f2f5;font-size:16px;">
            </div>
            <div class="create-post-expanded" id="create-post-expanded" style="margin-top:16px;">
                <input type="text" name="title" id="post-title" placeholder="Tiêu đề bài viết..." required style="width:100%;padding:12px 16px;border-radius:8px;border:1px solid #e4e6eb;margin-bottom:12px;font-size:15px;">
                <div class="post-type-options" style="margin-bottom: 12px; display: flex; gap: 18px; align-items: center;">
                    <label><input type="radio" name="post_type" value="now" checked onchange="handlePostTypeChange(this.value)"> Đăng ngay</label>
                    <label><input type="radio" name="post_type" value="scheduled" onchange="handlePostTypeChange(this.value)"> Đặt lịch</label>
                    <label><input type="radio" name="post_type" value="urgent" onchange="handlePostTypeChange(this.value)"> Đăng khẩn cấp</label>
                </div>
                <div id="scheduled-time-display" style="display:none; margin-bottom: 12px; font-weight: 500; color: #007bff;"></div>
                <div class="create-post-actions" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                    <div class="action-options" style="display:flex;gap:18px;align-items:center;">
                        <label for="post-media" style="cursor:pointer;display:flex;align-items:center;gap:6px;font-weight:500;color:#1877f2;"><i class="fas fa-photo-video photo-video"></i> Ảnh/Video</label>
                        <span style="display:flex;align-items:center;gap:6px;color:#ff4444;cursor:pointer;" onclick="showLocationModal()"><i class="fas fa-map-marker-alt location-icon"></i> Thêm vị trí</span>
                        <span style="display:flex;align-items:center;gap:6px;color:#ff4444;cursor:pointer;" onclick="showScheduleModal()" id="scheduleBtn"><i class="fas fa-calendar-alt"></i> Đặt lịch</span>
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
            @php
                $urgentPosts = $posts->where('status', 'urgent');
                $normalPosts = $posts->where('status', '!=', 'urgent');
            @endphp
            @foreach($urgentPosts as $post)
                @php $userLiked = $post->likes->contains('user_id', Auth::id()); @endphp
                <div class="post" id="post-{{ $post->id }}" data-user-id="{{ $post->user_id }}" style="border:2px solid #dc3545;box-shadow:0 2px 12px rgba(220,53,69,0.08);">
                    <div class="post-header">
                        <img src="{{ asset(optional($post->user)->avatar_url ?? '/images/default-avatar.png') }}" alt="Avatar" class="avatar">
                        <div class="post-info">
                            <h3 style="color:#dc3545;font-weight:bold;">🔥 {{ optional($post->user)->full_name ?? optional($post->user)->username ?? '[Người dùng đã xóa]' }} <span style="font-size:0.9em;font-weight:400;">(Khẩn cấp)</span></h3>
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                        <!-- Dấu 3 chấm và menu -->
                        <div class="post-menu-wrapper">
                            <span class="post-menu-btn" onclick="togglePostMenu(this)">&#x22EE;</span>
                            <div class="post-menu">
                                @if(Auth::id() === $post->user_id)
                                    <div onclick="editPost({{ $post->id }})">Chỉnh sửa</div>
                                    <div data-action="delete">Xóa</div>
                                @else
                                    <div onclick="reportPost({{ $post->id }})">Báo cáo</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="post-body">
                        <h4 style="color:#dc3545;">🔥 {{ $post->title }}</h4>
                        <p>{{ $post->content }}</p>
                        @if($post->media->isNotEmpty())
                            @foreach($post->media as $media)
                                @if(str_contains($media->file_type, 'video'))
                                    <video controls style="max-width: 100%; max-height: 320px; border-radius: 8px; margin-top: 10px;">
                                        <source src="{{ asset('storage/' . $media->file_url) }}" type="video/mp4">
                                    </video>
                                @else
                                    <img src="{{ asset('storage/' . $media->file_url) }}" alt="Media" style="max-width: 100%; max-height: 320px; border-radius: 8px; margin-top: 10px; object-fit: contain;">
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="post-actions">
                        <button class="like-btn {{ $post->likes->contains('user_id', Auth::id()) ? 'liked' : '' }}" data-post-id="{{ $post->id }}">
                            Thích (<span id="like-count-{{ $post->id }}">{{ $post->likes->count() }}</span>)
                        </button>
                        <button class="toggle-comments-btn" data-post-id="{{ $post->id }}">
                            Bình luận (<span id="comment-count-{{ $post->id }}">{{ $post->comments->count() }}</span>)
                        </button>
                        <button class="share-btn" data-post-id="{{ $post->id }}">
                            Chia sẻ (<span id="share-count-{{ $post->id }}">{{ $post->shares_count ?? 0 }}</span>)
                        </button>
                        <span class="view-count" style="margin-left: 10px; color: #888; font-size: 14px;">
                            👁️ <span id="view-count-{{ $post->id }}">{{ $post->views->count() }}</span> lượt xem
                        </span>
                    </div>
                    <!-- Phần bình luận -->
                    <div class="comments" id="comments-{{ $post->id }}" style="display:none;">
                        @foreach($post->comments as $comment)
                            <div class="comment" id="comment-{{ $comment->comment_id }}">
                                <div class="comment-header">
                                    <strong>{{ optional($comment->user)->full_name ?? optional($comment->user)->username ?? '[Người dùng đã xóa]' }}</strong>
                                    <span style="color: #888; font-size: 12px; margin-left: 8px;">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="comment-body">
                                    <p class="comment-content">{{ $comment->content }}</p>
                                    @if(Auth::id() === $comment->user_id)
                                        <button class="edit-comment-btn" data-comment-id="{{ $comment->comment_id }}">Sửa</button>
                                    @endif
                                </div>
                                <form class="edit-comment-form" id="edit-comment-form-{{ $comment->comment_id }}" style="display:none; margin-top:5px;">
                                    <input type="text" class="edit-comment-input" value="{{ $comment->content }}" style="width:70%;padding:3px;">
                                    <button type="button" data-comment-id="{{ $comment->comment_id }}" class="save-comment-btn">Lưu</button>
                                    <button type="button" data-comment-id="{{ $comment->comment_id }}" class="cancel-comment-btn">Hủy</button>
                                </form>
                            </div>
                        @endforeach
                        @if(Auth::check())
                            <form class="comment-form" onsubmit="return false;">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <textarea name="content" required placeholder="Nhập bình luận..."></textarea>
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
            @foreach($normalPosts as $post)
                @php $userLiked = $post->likes->contains('user_id', Auth::id()); @endphp
                <div class="post" id="post-{{ $post->id }}" data-user-id="{{ $post->user_id }}">
                    <div class="post-header">
                        <img src="{{ asset(optional($post->user)->avatar_url ?? '/images/default-avatar.png') }}" alt="Avatar" class="avatar">
                        <div class="post-info">
                            <h3>{{ optional($post->user)->full_name ?? optional($post->user)->username ?? '[Người dùng đã xóa]' }}</h3>
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
                                    <div data-action="delete">Xóa</div>
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
                                @if(str_contains($media->file_type, 'video'))
                                    <video controls style="max-width: 100%; max-height: 320px; border-radius: 8px; margin-top: 10px;">
                                        <source src="{{ asset('storage/' . $media->file_url) }}" type="video/mp4">
                                    </video>
                                @else
                                    <img src="{{ asset('storage/' . $media->file_url) }}" alt="Media" style="max-width: 100%; max-height: 320px; border-radius: 8px; margin-top: 10px; object-fit: contain;">
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="post-actions">
                        <button class="like-btn {{ $post->likes->contains('user_id', Auth::id()) ? 'liked' : '' }}" data-post-id="{{ $post->id }}">
                            Thích (<span id="like-count-{{ $post->id }}">{{ $post->likes->count() }}</span>)
                        </button>
                        <button class="toggle-comments-btn" data-post-id="{{ $post->id }}">
                            Bình luận (<span id="comment-count-{{ $post->id }}">{{ $post->comments->count() }}</span>)
                        </button>
                        <button class="share-btn" data-post-id="{{ $post->id }}">
                            Chia sẻ (<span id="share-count-{{ $post->id }}">{{ $post->shares_count ?? 0 }}</span>)
                        </button>
                        <span class="view-count" style="margin-left: 10px; color: #888; font-size: 14px;">
                            👁️ <span id="view-count-{{ $post->id }}">{{ $post->views->count() }}</span> lượt xem
                        </span>
                    </div>
                    <!-- Phần bình luận -->
                    <div class="comments" id="comments-{{ $post->id }}" style="display:none;">
                        @foreach($post->comments as $comment)
                            <div class="comment" id="comment-{{ $comment->comment_id }}">
                                <div class="comment-header">
                                    <strong>{{ optional($comment->user)->full_name ?? optional($comment->user)->username ?? '[Người dùng đã xóa]' }}</strong>
                                    <span style="color: #888; font-size: 12px; margin-left: 8px;">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="comment-body">
                                    <p class="comment-content">{{ $comment->content }}</p>
                                    @if(Auth::id() === $comment->user_id)
                                        <button class="edit-comment-btn" data-comment-id="{{ $comment->comment_id }}">Sửa</button>
                                    @endif
                                </div>
                                <form class="edit-comment-form" id="edit-comment-form-{{ $comment->comment_id }}" style="display:none; margin-top:5px;">
                                    <input type="text" class="edit-comment-input" value="{{ $comment->content }}" style="width:70%;padding:3px;">
                                    <button type="button" data-comment-id="{{ $comment->comment_id }}" class="save-comment-btn">Lưu</button>
                                    <button type="button" data-comment-id="{{ $comment->comment_id }}" class="cancel-comment-btn">Hủy</button>
                                </form>
                            </div>
                        @endforeach
                        @if(Auth::check())
                            <form class="comment-form" onsubmit="return false;">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <textarea name="content" required placeholder="Nhập bình luận..."></textarea>
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

    <!-- Modal đặt lịch -->
    <div id="scheduleModal" class="modal" style="display:none;z-index:99999;">
        <div class="modal-content" style="width:400px;max-width:90vw;">
            <h2>Đặt lịch đăng bài</h2>
            <input type="datetime-local" id="scheduled_at_modal" class="form-control" style="margin:10px 0;">
            <div class="modal-buttons">
                <button type="button" class="modal-button confirm-button" onclick="confirmSchedule()">Xác nhận</button>
                <button type="button" class="modal-button cancel-button" onclick="hideScheduleModal()">Hủy</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/trangchu.js') }}"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        // Pass route URLs and elements to JavaScript functions after trangchu.js is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure elements and functions from trangchu.js are available
            const bellIconElement = document.getElementById('bell-icon');
            const bellDropdownElement = document.getElementById('bell-dropdown');
            const bellListElement = document.getElementById('bell-list');
            const bellBadgeElement = document.getElementById('bell-badge');

            if (typeof fetchInitialUnreadCount === 'function' && bellBadgeElement) {
                // Fetch initial unread count on DOMContentLoaded
                fetchInitialUnreadCount("{{ route('notifications.unread') }}", bellBadgeElement);
            }

            if (typeof setupBellNotificationListener === 'function' && bellIconElement && bellDropdownElement && bellListElement && bellBadgeElement) {
                // Setup bell notification listener
                setupBellNotificationListener(
                    bellIconElement,
                    bellDropdownElement,
                    bellListElement,
                    "{{ route('notifications.unread') }}",
                    "{{ route('notifications.markAsRead') }}",
                    bellBadgeElement
                );
            }
        });
    </script>

</body>
</html>
