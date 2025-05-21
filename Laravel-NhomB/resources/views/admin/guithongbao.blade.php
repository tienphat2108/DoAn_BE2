<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gửi Thông Báo</title>
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
                    <li><a href="{{ route('admin.quanlybinhluan') }}">PHÂN TÍCH TƯƠNG TÁC</a></li>
                    <li><a href="#" onclick="showLogoutModal()">ĐĂNG XUẤT</a></li>
                </ul>
            </div>

            <div class="admin-main">
                <div class="interaction-actions" style="margin-bottom: 24px;">
                    <a href="{{ route('admin.quanlybinhluan') }}"><button class="interaction-btn">QUẢN LÝ BÌNH LUẬN</button></a>
                    <a href="{{ route('admin.quanlytuongtac') }}"><button class="interaction-btn">QUẢN LÝ TƯƠNG TÁC</button></a>
                    <a href="{{ route('admin.theodoiluotxem') }}"><button class="interaction-btn">THEO DÕI LƯỢT XEM</button></a>
                    <a href="{{ route('admin.xuatdulieu') }}"><button class="interaction-btn">XUẤT DỮ LIỆU</button></a>
                    <a href="{{ route('admin.baocaohieusuat') }}"><button class="interaction-btn">BÁO CÁO</button></a>
                    <a href="{{ route('admin.guithongbao') }}"><button class="interaction-btn selected">GỬI THÔNG BÁO</button></a>
                </div>
                <h2 class="interaction-title">Gửi Thông Báo</h2>
                <form class="notify-form" style="max-width: 700px; margin: 0 auto 32px auto;">
                    <div style="margin-bottom: 18px;">
                        <label for="author" style="font-weight: bold; display: block; margin-bottom: 8px;">Chọn tác giả:</label>
                        <select id="author" class="interaction-select" style="width: 100%;">
                            <option>Đặng Ngọc Hạnh Nguyên</option>
                            <option>Nguyễn Văn A</option>
                            <option>Trần Thị B</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 18px;">
                        <label for="message" style="font-weight: bold; display: block; margin-bottom: 8px;">Nội dung thông báo:</label>
                        <textarea id="message" class="interaction-select" style="width: 100%; height: 100px; resize: vertical;" placeholder="Nhập nội dung thông báo..."></textarea>
                    </div>
                    <div style="margin-bottom: 18px;">
                        <label for="method" style="font-weight: bold; display: block; margin-bottom: 8px;">Hình thức gửi:</label>
                        <select id="method" class="interaction-select" style="width: 100%;">
                            <option>Email</option>
                            <option>Thông báo hệ thống</option>
                        </select>
                    </div>
                    <button type="submit" class="interaction-filter-btn" style="width: 100%; background: #ccc; color: #222;">Gửi thông báo</button>
                </form>
                <h3 style="margin-top: 32px; margin-bottom: 16px;">Lịch sử gửi</h3>
                <div class="interaction-table-wrapper">
                    <table class="interaction-table">
                        <thead>
                            <tr>
                                <th>Tác giả</th>
                                <th>Nội dung</th>
                                <th>Hình thức</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Đặng Ngọc Hạnh Nguyên</td>
                                <td>Bài viết của bạn đạt 10,000 lượt xem!</td>
                                <td>Email</td>
                                <td>27/03/2025</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Logout Modal và script giữ nguyên như file quanlytuongtac.blade.php -->
</body>
</html> 