<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Xuất Dữ Liệu Phân Tích</title>
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
                    <a href="{{ route('admin.quanlybinhluan') }}"><button class="interaction-btn">QUẢN LÝ BÌNH LUẬN</button></a>
                    <a href="{{ route('admin.tuongtac') }}"><button class="interaction-btn">QUẢN LÝ TƯƠNG TÁC</button></a>
                    <a href="{{ route('admin.theodoiluotxem') }}"><button class="interaction-btn">THEO DÕI LƯỢT XEM</button></a>
                    <a href="{{ route('admin.xuatdulieu') }}"><button class="interaction-btn selected">XUẤT DỮ LIỆU</button></a>
                    <a href="{{ route('admin.baocaohieusuat') }}"><button class="interaction-btn">BÁO CÁO</button></a>
                    <a href="{{ route('admin.guithongbao') }}"><button class="interaction-btn">GỬI THÔNG BÁO</button></a>
                </div>
                <h2 class="interaction-title">Xuất Dữ Liệu Phân Tích</h2>
                <form class="export-form" style="max-width: 600px; margin: 0 auto;">
                    <div style="margin-bottom: 24px;">
                        <label for="data-type" style="font-weight: bold; display: block; margin-bottom: 8px;">Chọn loại dữ liệu:</label>
                        <select id="data-type" class="interaction-select" style="width: 100%;">
                            <option>Lượt thích</option>
                            <option>Lượt xem</option>
                            <option>Bình luận</option>
                            <option>Chia sẻ</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 24px;">
                        <label for="format-type" style="font-weight: bold; display: block; margin-bottom: 8px;">Chọn định dạng:</label>
                        <select id="format-type" class="interaction-select" style="width: 100%;">
                            <option>PDF</option>
                            <option>Excel</option>
                        </select>
                    </div>
                    <button type="submit" class="interaction-filter-btn" style="width: 100%; background: #ccc; color: #222;">Xuất Dữ Liệu</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Logout Modal và script giữ nguyên như file quanlytuongtac.blade.php -->
</body>
</html> 