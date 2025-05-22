<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Báo Cáo Hiệu Suất Hàng Tháng</title>
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
                    <a href="{{ route('admin.baocaohieusuat') }}"><button class="interaction-btn selected">BÁO CÁO</button></a>
                    <a href="{{ route('admin.guithongbao') }}"><button class="interaction-btn">GỬI THÔNG BÁO</button></a>
                </div>
                <h2 class="interaction-title">Báo Cáo Hiệu Suất Hàng Tháng</h2>
                <div class="interaction-filters" style="display: flex; gap: 12px; margin-bottom: 24px;">
                    <select class="interaction-select">
                        <option>Tháng 1</option>
                        <option>Tháng 2</option>
                        <option>Tháng 3</option>
                        <option>Tháng 4</option>
                        <option>Tháng 5</option>
                        <option>Tháng 6</option>
                        <option>Tháng 7</option>
                        <option>Tháng 8</option>
                        <option>Tháng 9</option>
                        <option>Tháng 10</option>
                        <option>Tháng 11</option>
                        <option>Tháng 12</option>
                    </select>
                    <button class="interaction-filter-btn">Xem Báo Cáo</button>
                </div>
                <div class="interaction-table-wrapper">
                    <table class="interaction-table">
                        <thead>
                            <tr>
                                <th>Chỉ số</th>
                                <th>Tháng này</th>
                                <th>Tháng trước</th>
                                <th>Thay đổi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Lượt xem</td>
                                <td>12,000</td>
                                <td>10,000</td>
                                <td>14%</td>
                            </tr>
                            <tr>
                                <td>Lượt thích</td>
                                <td>5,500</td>
                                <td>4,800</td>
                                <td>14%</td>
                            </tr>
                            <tr>
                                <td>Lượt chia sẻ</td>
                                <td>2,100</td>
                                <td>2,000</td>
                                <td>10%</td>
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