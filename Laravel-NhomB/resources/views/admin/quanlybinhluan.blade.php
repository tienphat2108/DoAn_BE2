<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý bình luận</title>
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
                    <li><a href="{{ route('admin.phantichtruycap') }}">PHÂN TÍCH TƯƠNG TÁC</a></li>
                    <li><a href="#" onclick="showLogoutModal()">ĐĂNG XUẤT</a></li>
                </ul>
            </div>

            <div class="admin-main">
                <div class="interaction-actions" style="margin-bottom: 24px;">
                    <button class="interaction-btn selected">QUẢN LÝ BÌNH LUẬN</button>
                    <button class="interaction-btn">QUẢN LÝ TƯƠNG TÁC</button>
                    <button class="interaction-btn">THEO DÕI LƯỢT XEM</button>
                    <button class="interaction-btn">XUẤT DỮ LIỆU</button>
                    <button class="interaction-btn">BÁO CÁO</button>
                    <button class="interaction-btn">GỬI THÔNG BÁO</button>
                </div>
                <h2 class="interaction-title">Quản Lý Bình Luận</h2>
                <div class="interaction-filters" style="display: flex; gap: 12px; margin-bottom: 24px;">
                    <input type="text" placeholder="Tìm kiếm bình luận..." class="interaction-select">
                    <select class="interaction-select">
                        <option>Tất cả bài viết</option>
                    </select>
                    <select class="interaction-select">
                        <option>Tất cả người dùng</option>
                    </select>
                    <button class="interaction-filter-btn">Tìm kiếm</button>
                </div>
                <div class="comment-list">
                    <div class="comment-item">
                        <b>Người dùng A:</b> Bình luận hay quá!
                        <div>
                            <button>Trả lời</button>
                            <button>Xóa</button>
                            <button>Sửa</button>
                        </div>
                    </div>
                    <div class="comment-item">
                        <b>Người dùng A:</b> Tôi có một vài thắc mắc
                        <div>
                            <button>Trả lời</button>
                            <button>Xóa</button>
                            <button>Sửa</button>
                        </div>
                    </div>
                    <div class="comment-item">
                        <b>Người dùng A:</b> Bài viết rất hữu ích!
                        <div>
                            <button>Trả lời</button>
                            <button>Xóa</button>
                            <button>Sửa</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Logout Modal và script giữ nguyên như file quanlytuongtac.blade.php -->
</body>
</html>