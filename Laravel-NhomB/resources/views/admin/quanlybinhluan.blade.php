<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý bình luận</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .comment-item {
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .comment-content {
            flex-grow: 1;
            margin-right: 10px;
        }
        .comment-actions {
            display: flex;
            gap: 5px;
        }
        .edit-form {
            display: none;
            margin-top: 10px;
        }
        .edit-input {
            width: 100%;
            padding: 5px;
            margin-bottom: 5px;
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
                    <a href="{{ route('admin.quanlybinhluan') }}"><button class="interaction-btn selected">QUẢN LÝ BÌNH LUẬN</button></a>
                    <a href="{{ route('admin.tuongtac') }}"><button class="interaction-btn">QUẢN LÝ TƯƠNG TÁC</button></a>
                    <a href="{{ route('admin.theodoiluotxem') }}"><button class="interaction-btn">THEO DÕI LƯỢT XEM</button></a>
                    <a href="{{ route('admin.xuatdulieu') }}"><button class="interaction-btn">XUẤT DỮ LIỆU</button></a>
                    <a href="{{ route('admin.baocaohieusuat') }}"><button class="interaction-btn">BÁO CÁO</button></a>
                    <a href="{{ route('admin.guithongbao') }}"><button class="interaction-btn">GỬI THÔNG BÁO</button></a>
                </div>
                <h2 class="interaction-title">Quản Lý Bình Luận</h2>
                <div class="interaction-filters" style="display: flex; gap: 12px; margin-bottom: 24px;">
                    <input type="text" id="searchInput" placeholder="Tìm kiếm bình luận..." class="interaction-select">
                    <select id="postFilter" class="interaction-select">
                        <option value="all">Tất cả bài viết</option>
                        @foreach($posts as $post)
                            <option value="{{ $post->id }}">{{ $post->title }}</option>
                        @endforeach
                    </select>
                    <select id="userFilter" class="interaction-select">
                        <option value="all">Tất cả người dùng</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <button onclick="searchComments()" class="interaction-filter-btn">Tìm kiếm</button>
                </div>
                <div class="comment-list">
                    @forelse($comments as $comment)
                        <div class="comment-item" id="comment-{{ $comment->id }}">
                            <div class="comment-content">
                                <b>{{ $comment->user->name }}:</b> <span class="comment-text">{{ $comment->content }}</span>
                                <div class="edit-form">
                                    <input type="text" class="edit-input" value="{{ $comment->content }}">
                                    <button onclick="updateComment({{ $comment->id }}, this.parentElement)">Lưu</button>
                                    <button onclick="cancelEdit(this.parentElement)">Hủy</button>
                                </div>
                            </div>
                            <div class="comment-actions">
                                <button onclick="showEditForm({{ $comment->id }})">Sửa</button>
                                <button onclick="deleteComment({{ $comment->id }})">Xóa</button>
                            </div>
                        </div>
                    @empty
                        <p>Không có bình luận nào.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        // Lấy CSRF token từ meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Hàm hiển thị form sửa bình luận
        function showEditForm(commentId) {
            const commentItem = document.querySelector(`#comment-${commentId}`);
            const editForm = commentItem.querySelector('.edit-form');
            const commentText = commentItem.querySelector('.comment-text');
            
            editForm.style.display = 'block';
            commentText.style.display = 'none';
        }

        // Hàm hủy sửa bình luận
        function cancelEdit(editForm) {
            const commentText = editForm.parentElement.querySelector('.comment-text');
            editForm.style.display = 'none';
            commentText.style.display = 'inline';
        }

        // Hàm cập nhật bình luận
        function updateComment(commentId, editForm) {
            const newContent = editForm.querySelector('.edit-input').value;
            
            fetch(`/admin/comments/${commentId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    content: newContent
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const commentItem = document.querySelector(`#comment-${commentId}`);
                    const commentText = commentItem.querySelector('.comment-text');
                    commentText.textContent = newContent;
                    cancelEdit(editForm);
                } else {
                    alert('Có lỗi xảy ra khi cập nhật bình luận');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật bình luận');
            });
        }

        // Hàm xóa bình luận
        function deleteComment(commentId) {
            if (confirm('Bạn có chắc chắn muốn xóa bình luận này?')) {
                fetch(`/admin/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const commentElement = document.querySelector(`#comment-${commentId}`);
                        commentElement.remove();
                    } else {
                        alert('Có lỗi xảy ra khi xóa bình luận');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa bình luận');
                });
            }
        }

        // Hàm tìm kiếm bình luận
        function searchComments() {
            const searchQuery = document.getElementById('searchInput').value;
            const postId = document.getElementById('postFilter').value;
            const userId = document.getElementById('userFilter').value;

            // Xây dựng URL với các tham số tìm kiếm
            const searchParams = new URLSearchParams();
            if (searchQuery) searchParams.append('search', searchQuery);
            if (postId !== 'all') searchParams.append('post_id', postId);
            if (userId !== 'all') searchParams.append('user_id', userId);

            // Chuyển hướng đến URL với các tham số tìm kiếm
            window.location.href = `{{ route('admin.quanlybinhluan') }}?${searchParams.toString()}`;
        }

        // Thiết lập giá trị cho các trường tìm kiếm từ URL
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            
            // Thiết lập giá trị cho ô tìm kiếm
            const searchQuery = urlParams.get('search');
            if (searchQuery) {
                document.getElementById('searchInput').value = searchQuery;
            }

            // Thiết lập giá trị cho bộ lọc bài viết
            const postId = urlParams.get('post_id');
            if (postId) {
                document.getElementById('postFilter').value = postId;
            }

            // Thiết lập giá trị cho bộ lọc người dùng
            const userId = urlParams.get('user_id');
            if (userId) {
                document.getElementById('userFilter').value = userId;
            }
        };

        // Thêm sự kiện Enter cho ô tìm kiếm
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchComments();
            }
        });
    </script>
</body>
</html>