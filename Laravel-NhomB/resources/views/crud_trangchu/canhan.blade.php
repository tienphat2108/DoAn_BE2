<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            border: 1px solid #007bff; /* Added border */
            color: #007bff; /* Or a suitable color for edit button */
            cursor: pointer;
            font-size: 0.9em;
            padding: 2px 6px; /* Added padding */
            border-radius: 4px; /* Optional: add rounded corners */
        }
        .edit-comment-btn:hover {
            text-decoration: underline;
            background-color: rgba(0, 123, 255, 0.1); /* Optional: subtle background change on hover */
        }
    </style>
</head>
<body data-current-user-id="{{ Auth::id() }}" data-profile-user-id="{{ $user->id }}" data-current-user-name="{{ Auth::check() ? (Auth::user()->full_name ?? Auth::user()->username) : 'Ẩn danh' }}">
    @if(session('success'))
        <div id="toast-success" style="position:fixed;top:24px;right:24px;z-index:9999;background:#28a745;color:#fff;padding:16px 32px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.12);font-size:1.1em;">
            {{ session('success') }}
        </div>
        <script>setTimeout(()=>{var t=document.getElementById('toast-success');if(t)t.style.display='none';},3000);</script>
    @endif
    @if(session('error'))
        <div id="toast-error" style="position:fixed;top:24px;right:24px;z-index:9999;background:#dc3545;color:#fff;padding:16px 32px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.12);font-size:1.1em;">
            {{ session('error') }}
        </div>
        <script>setTimeout(()=>{var t=document.getElementById('toast-error');if(t)t.style.display='none';},3000);</script>
    @endif
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
                <img src="{{ asset(Auth::user()->avatar_url ?? '/images/default-avatar.png') }}" alt="Avatar" class="avatar" style="width:56px;height:56px;border-radius:50%;object-fit:cover;">
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
                            <img src="{{ asset($post->user->avatar_url ?? '/images/default-avatar.png') }}" alt="Avatar" class="avatar">
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
                            @if($post->status === 'canceled' && $post->admin_note)
                                <div style="color:#dc3545;font-weight:500;margin-top:6px;">Lý do hủy: {{ $post->admin_note }}</div>
                            @endif
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
                            <button class="like-btn action{{ $post->likes->contains('user_id', Auth::id()) ? ' liked' : '' }}" data-post-id="{{ $post->id }}" data-action="like" style="{{ $post->likes->contains('user_id', Auth::id()) ? 'color:#0056b3;' : '' }}">
                                Thích (<span id="like-count-{{ $post->id }}">{{ $post->likes->count() }}</span>)
                            </button>
                            <button class="toggle-comments-btn action" data-post-id="{{ $post->id }}" data-action="comment">
                                Bình luận (<span id="comment-count-{{ $post->id }}">{{ $post->comments->count() }}</span>)
                            </button>
                            <span class="view-count" style="margin-left: 10px; color: #888; font-size: 14px;">
                                👁️ <span id="view-count-{{ $post->id }}">{{ $post->views->count() }}</span> lượt xem
                            </span>
                        </div>
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
                                <form class="comment-form" action="/comments" method="POST" style="margin-top: 8px;">
                                    @csrf
                                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                                    <textarea name="content" required placeholder="Nhập bình luận..." style="width:100%;min-height:40px;"></textarea>
                                    <button type="submit">Gửi bình luận</button>
                                </form>
                            @else
                                <p><a href="{{ route('login') }}">Đăng nhập</a> để bình luận.</p>
                            @endif
                        </div>
                        <form id="delete-form-{{ $post->id }}" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST" style="display:none;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');">
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
    <!-- Modal đặt lịch hàng loạt -->
    <div id="bulkScheduleModal" class="modal" style="display:none;z-index:99999;">
        <div class="modal-content" style="width:500px;max-width:95vw;">
            <h2>Đặt lịch đăng bài hàng loạt</h2>
            <form id="bulk-schedule-form" method="POST" action="/canhan/schedule-multi">
                @csrf
                <table style="width:100%;margin-bottom:12px;">
                    <thead><tr><th></th><th>Tiêu đề</th><th>Thời gian đăng</th></tr></thead>
                    <tbody>
                        @foreach($posts->whereIn('status', ['bản nháp','pending']) as $post)
                        <tr>
                            <td><input type="checkbox" name="post_ids[]" value="{{ $post->id }}"></td>
                            <td>{{ $post->title }}</td>
                            <td><input type="datetime-local" name="scheduled_at[{{ $post->id }}]"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="modal-buttons">
                    <button type="submit" class="modal-button confirm-button">Lên lịch</button>
                    <button type="button" class="modal-button cancel-button" onclick="hideBulkScheduleModal()">Hủy</button>
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.schedule-tab');
        const contents = document.querySelectorAll('.schedule-content');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                contents.forEach(content => {
                    if (content.id === tabName + '-posts') {
                        content.style.display = 'block';
                    } else {
                        content.style.display = 'none';
                    }
                });
            });
        });
    });
    </script>
    <script>
    function showBulkScheduleModal() {
        document.getElementById('bulkScheduleModal').style.display = 'flex';
    }
    function hideBulkScheduleModal() {
        document.getElementById('bulkScheduleModal').style.display = 'none';
    }
    </script>
    <script>
    // Use event delegation for comment edit/save/cancel buttons
    document.body.addEventListener('click', function(event) {
        const target = event.target;

        // Handle Edit button click
        if (target.classList.contains('edit-comment-btn')) {
            const commentElement = target.closest('.comment');
            if (commentElement) {
                const commentId = commentElement.id.replace('comment-', '');
                showEditCommentForm(commentId);
            }
        }

        // Handle Save button click
        if (target.tagName === 'BUTTON' && target.textContent.trim() === 'Lưu' && target.closest('.edit-comment-form')) {
             const commentElement = target.closest('.comment');
            if (commentElement) {
                const commentId = commentElement.id.replace('comment-', '');
                submitEditComment(commentId);
            }
        }

        // Handle Cancel button click
        if (target.tagName === 'BUTTON' && target.textContent.trim() === 'Hủy' && target.closest('.edit-comment-form')) {
             const commentElement = target.closest('.comment');
            if (commentElement) {
                const commentId = commentElement.id.replace('comment-', '');
                cancelEditComment(commentId);
            }
        }
    });
    </script>
    {{-- Include necessary scripts for notifications --}}
    <script src="{{ asset('js/trangchu.js') }}"></script>
    <script>
        // Pass route URLs to JavaScript functions after trangchu.js is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure elements and functions from trangchu.js are available
            if (typeof fetchUnreadNotificationsCount === 'function') {
                // Fetch initial unread count on DOMContentLoaded
                fetchInitialUnreadCount("{{ route('notifications.unread') }}", document.getElementById('bell-badge'));
            }
            if (typeof setupBellNotificationListener === 'function') {
                // Setup bell notification listener
                const bellIconElement = document.getElementById('bell-icon');
                const bellDropdownElement = document.getElementById('bell-dropdown');
                const bellListElement = document.getElementById('bell-list');
                const bellBadgeElement = document.getElementById('bell-badge');
                setupBellNotificationListener(
                    bellIconElement,
                    bellDropdownElement,
                    bellListElement,
                    "{{ route('notifications.unread') }}",
                    "{{ route('notifications.markAsRead') }}",
                    bellBadgeElement // Pass bellBadgeElement here
                );
            }
        });
    </script>
</body>
</html>
