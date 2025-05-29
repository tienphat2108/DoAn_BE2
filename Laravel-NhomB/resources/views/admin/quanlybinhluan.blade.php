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
        .edit-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .edit-btn:hover {
            background-color: #218838;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        .comment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .comment-table th,
        .comment-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .comment-table th {
            background-color: #f2f2f2;
        }
        .comment-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .comment-table tr:hover {
            background-color: #e9e9e9;
        }
        .comment-actions button {
            padding: 5px 10px;
            cursor: pointer;
        }
        .bulk-actions {
            margin-bottom: 15px;
        }
        .bulk-delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        .bulk-delete-btn:hover {
            background-color: #c82333;
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
                    <li><a href="{{ route('admin.quanlybinhluan') }}">PHÂN TÍCH TƯƠNG TÁC</a></li>
                    <li><a href="#" onclick="showLogoutModal()">ĐĂNG XUẤT</a></li>
                </ul>
            </div>

            <div class="admin-main">
                <div class="interaction-actions" style="margin-bottom: 24px;">
                    <a href="{{ route('admin.quanlybinhluan') }}"><button class="interaction-btn selected">QUẢN LÝ BÌNH LUẬN</button></a>
                    <a href="{{ route('admin.quanlytuongtac') }}"><button class="interaction-btn">QUẢN LÝ TƯƠNG TÁC</button></a>
                    <a href="{{ route('admin.theodoiluotxem') }}"><button class="interaction-btn">THEO DÕI LƯỢT XEM</button></a>
                    <a href="{{ route('admin.xuatdulieu') }}"><button class="interaction-btn">XUẤT DỮ LIỆU</button></a>
                    <a href="{{ route('admin.baocaohieusuat') }}"><button class="interaction-btn">BÁO CÁO</button></a>
                    <a href="{{ route('admin.guithongbao') }}"><button class="interaction-btn">GỬI THÔNG BÁO</button></a>
                </div>
                <h2 class="interaction-title">Quản Lý Bình Luận</h2>
                {{-- Hiển thị thông báo flash --}}
                @if(Session::has('success'))
                    <div class="alert alert-success" style="background-color: #d4edda; color: #155724; border-color: #c3e6cb; padding: .75rem 1.25rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: .25rem;">
                        {{ Session::get('success') }}
                    </div>
                @endif
                @if(Session::has('error'))
                    <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; padding: .75rem 1.25rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: .25rem;">
                        {{ Session::get('error') }}
                    </div>
                @endif
                {{-- Kết thúc hiển thị thông báo flash --}}
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
                            @continue(is_null($user))
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <button onclick="searchComments()" class="interaction-filter-btn">Tìm kiếm</button>
                </div>
                <form id="bulkDeleteForm" method="POST" action="{{ route('admin.comments.bulkDelete') }}">
                    @csrf
                    <div class="bulk-actions">
                        <button type="submit" class="bulk-delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa các bình luận đã chọn không?');">Xóa bình luận đã chọn</button>
                    </div>
                    <table class="comment-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>Nội dung</th>
                                <th>Người dùng</th>
                                <th>Bài viết</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($comments as $comment)
                                <tr id="comment-row-{{ $comment->comment_id }}">
                                    <td><input type="checkbox" name="comment_ids[]" value="{{ $comment->comment_id }}"></td>
                                    <td><span class="comment-text">{{ $comment->content }}</span></td>
                                    <td>{{ $comment->user ? $comment->user->name : 'Người dùng không tồn tại' }}</td>
                                    <td>{{ $comment->post ? Str::limit($comment->post->title, 50) : 'Bài viết không tồn tại' }}</td>
                                    <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <button onclick="deleteComment({{ $comment->comment_id }})" class="delete-btn">Xóa</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Không có bình luận nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>

    {{-- Logout Modal --}}
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
        // Lấy CSRF token từ meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                        const commentElement = document.querySelector(`#comment-row-${commentId}`);
                        if (commentElement) {
                            commentElement.remove();
                        } else {
                            console.error('Không tìm thấy phần tử bình luận để xóa');
                        }
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

            // JavaScript cho chức năng chọn tất cả
            const selectAllCheckbox = document.getElementById('selectAll');
            const commentCheckboxes = document.querySelectorAll('input[name="comment_ids[]"]');

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    commentCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }
        };

        // Thêm sự kiện Enter cho ô tìm kiếm
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchComments();
            }
        });

        //quanlybainguoidung.blade.php đăng xuất
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

        document.getElementById('bulkDeleteForm').addEventListener('submit', function(event) {
            console.log('Bulk delete form submit event triggered.');

            const selectedComments = document.querySelectorAll('input[name="comment_ids[]"]:checked');
            const selectedIds = Array.from(selectedComments).map(checkbox => checkbox.value);
            console.log('Selected comment IDs before form submission:', selectedIds);
        });
    </script>
</body>
</html>