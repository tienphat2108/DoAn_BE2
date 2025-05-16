<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ADMIN quản lý lịch đăng bài</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .tab-nav {
            margin-bottom: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000;
            padding: 10px 0;
            border-radius: 40px;
            flex-wrap: wrap;
        }
        .tab-btn {
            border-radius: 48px;
            background: #fff;
            color: #222;
            font-weight: 600;
            font-size: .9rem;
            padding: 10px 20px;
            margin: 0 18px 1px 18px;
            border: none;
            transition: background 0.25s, color 0.25s, box-shadow 0.25s;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            outline: none;
        }
        .tab-btn.active {
            background: #e0e0e0;
            color: #111;
            box-shadow: 0 4px 16px rgba(0,0,0,0.10);
        }
        .tab-btn:hover:not(.active) {
            background: #f5f5f5;
            color: #111;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        @media (max-width: 900px) {
            .tab-btn { font-size: 1rem; padding: 12px 20px; margin: 0 8px 8px 8px; }
            .tab-nav { padding: 10px 0; }
        }
        .filter-bar {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 18px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .filter-bar select, .filter-bar input[type="date"], .filter-bar .filter-search {
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
            margin-right: 0;
            outline: none;
            min-width: 140px;
        }
        .filter-bar .filter-search {
            min-width: 200px;
        }
        .filter-bar .filter-btn {
            padding: 10px 24px;
            border-radius: 8px;
            background: #222;
            color: #fff;
            font-weight: 600;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .filter-bar .filter-btn:hover {
            background: #444;
        }
        @media (max-width: 900px) {
            .filter-bar { flex-direction: column; gap: 10px; }
            .filter-bar select, .filter-bar input, .filter-bar .filter-btn { width: 100%; min-width: 0; }
        }
        .calendar-grid {
            min-height: 400px;
        }
        .calendar-day {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 8px;
            min-height: 100px;
        }
        .calendar-day.today {
            background: #e3f2fd;
        }
        .calendar-day.has-posts {
            border-color: #28a745;
        }
        .post-item {
            background: #f8f9fa;
            border-radius: 4px;
            padding: 4px 8px;
            margin-bottom: 4px;
            font-size: 0.9rem;
            cursor: pointer;
        }
        .post-item:hover {
            background: #e9ecef;
        }
        .action-btn {
            padding: 4px 8px;
            border-radius: 4px;
            border: none;
            margin-right: 4px;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .view-btn { background: #007bff; color: white; }
        .cancel-btn { background: #dc3545; color: white; }
        .approve-btn { background: #28a745; color: white; }
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
                    <li><a href="{{ route('admin.phantichtruycap') }}">PHÂN TÍCH TRUY CẬP</a></li>
                    <li><a href="#" onclick="showLogoutModal()">ĐĂNG XUẤT</a></li>
                </ul>
            </div>

            <div class="admin-main">
                <h2>ADMIN quản lý lịch đăng bài</h2>
               


                <!-- Thanh điều hướng tab -->
                <div class="tab-nav">
                    <button class="tab-btn active" onclick="showTab('all', event)">All</button>
                    <button class="tab-btn" onclick="showTab('schedule', event)">Lịch trình Đăng Bài</button>
                    <button class="tab-btn" onclick="showTab('bulk', event)">Đăng Bài Hàng Loạt</button>
                    <button class="tab-btn" onclick="showTab('history', event)">Lịch sử Đăng Bài</button>
                    <button class="tab-btn" onclick="showTab('settings', event)">Cài đặt</button>
                </div>
                <!-- Đã xoá filter-bar trùng khỏi ngoài các tab -->
                <!-- Tab All -->
              
                <div class="tab-content" id="tab-all">
                    <!-- Cảnh báo và Thống kê chỉ hiển thị ở tab All -->
                    <div style="display: flex; flex-wrap: wrap; gap: 24px; margin-bottom: 32px;">
                        <!-- Cảnh báo -->
                        <div style="flex: 2; min-width: 320px; background: #fff3cd; border: 1px solid #ffeeba; border-radius: 12px; padding: 20px 24px;">
                            <h3 style="color: #856404; font-size: 1.1rem; font-weight: bold; margin-bottom: 12px;">Cảnh báo</h3>
                            <ul style="padding-left: 18px;">
                                <li style="margin-bottom: 8px;">- <b>Bài viết X</b> - Lỗi: Thiếu tiêu đề <a href="#" style="color: #007bff; text-decoration: underline;">Xem chi tiết</a></li>
                                <li style="margin-bottom: 8px;">- <b>Bài viết Y</b> - Lỗi: Trùng giờ đăng <a href="#" style="color: #007bff; text-decoration: underline;">Xem lịch trình</a></li>
                                <li>- <b>Bài viết Z</b> - Lỗi: Nội dung vi phạm <a href="#" style="color: #007bff; text-decoration: underline;">Xem chi tiết</a></li>
                            </ul>
                        </div>
                        <!-- Thống kê, Lịch trình gần đây, Truy cập nhanh -->
                        <div style="flex: 3; display: flex; gap: 18px; min-width: 320px;">
                            <!-- Thống kê -->
                            <div style="flex: 1; background: #f8f9fa; border-radius: 12px; padding: 16px 12px; border: 1px solid #e2e3e5;">
                                <h4 style="font-size: 1rem; font-weight: bold; margin-bottom: 10px;">Thống kê</h4>
                                <div>- Đã đăng: <b>12</b></div>
                                <div>- Chờ duyệt: <b>3</b></div>
                                <div>- Lỗi: <b>1</b></div>
                            </div>
                            <!-- Lịch trình gần đây -->
                            <div style="flex: 2; background: #f8f9fa; border-radius: 12px; padding: 16px 12px; border: 1px solid #e2e3e5;">
                                <h4 style="font-size: 1rem; font-weight: bold; margin-bottom: 10px;">Lịch trình gần đây</h4>
                                <div>- Bài viết A - 12:00 12/05 - Đã đăng</div>
                                <div>- Bài viết B - 14:00 12/05 - Chờ duyệt</div>
                                <div>- Bài viết C - 16:00 12/05 - Lỗi</div>
                            </div>
                            <!-- Truy cập nhanh -->
                            <div style="flex: 1; background: #f8f9fa; border-radius: 12px; padding: 16px 12px; border: 1px solid #e2e3e5; display: flex; flex-direction: column; gap: 10px; align-items: stretch;">
                                <h4 style="font-size: 1rem; font-weight: bold; margin-bottom: 10px;">Truy cập nhanh</h4>
                                <a href="#" class="filter-btn" style="margin-bottom: 4px; text-align: center;">Đăng bài mới</a>
                                <a href="#" class="filter-btn" style="margin-bottom: 4px; text-align: center;">Xem Lịch trình</a>
                                <a href="#" class="filter-btn" style="text-align: center;">Đăng hàng loạt</a>
                            </div>
                        </div>
                    </div>
                    <div class="posts-table">
                        <table class="table-users">
                            <thead>
                                <tr>
                                    <th>Hình ảnh</th>
                                    <th>Tiêu đề</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đăng</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="5" class="text-center">Không có dữ liệu</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Tab Lịch trình Đăng Bài -->
                <div class="tab-content" id="tab-schedule" style="display:none;">
                    <!-- Bộ lọc và Tìm kiếm chỉ cho tab Lịch trình Đăng Bài -->
                    <div class="filter-bar" style="display: flex; justify-content: center; align-items: center; gap: 18px; margin-bottom: 24px; flex-wrap: wrap;">
                        <input type="date" class="filter-select" id="date-filter">
                        <select class="filter-select" id="status-filter">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending">Chờ duyệt</option>
                            <option value="approved">Đã duyệt</option>
                            <option value="canceled">Đã hủy</option>
                            <option value="emergency">Khẩn cấp</option>
                        </select>
                        <input type="text" class="filter-search" placeholder="Tìm kiếm...">
                        <button class="filter-btn">Tìm kiếm</button>
                    </div>
                    <!-- Modal chi tiết bài đăng -->
                    <div id="postModal" class="modal" style="display:none;">
                        <div class="modal-content">
                            <h3 id="modal-title"></h3>
                            <p id="modal-time"></p>
                            <p id="modal-status"></p>
                            <div class="modal-buttons">
                                <button class="action-btn view-btn" id="modal-view-btn">Xem chi tiết</button>
                                <button class="action-btn cancel-btn" id="modal-cancel-btn">Hủy lịch</button>
                                <button class="action-btn approve-btn" id="modal-approve-btn">Cho phép đăng</button>
                                <button class="modal-button cancel-button" onclick="closePostModal()">Đóng</button>
                            </div>
                        </div>
                    </div>
                    <div class="posts-table">
                        <table class="table-users">
                            <thead>
                                <tr>
                                    <th>Hình ảnh</th>
                                    <th>Tiêu đề</th>
                                    <th>Người đăng</th>
                                    <th>Trạng thái</th>
                                    <th>Thời gian</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($scheduledPosts as $post)
                                    <tr>
                                        <td>
                                            @if($post->image)
                                                <img src="{{ asset('storage/' . $post->image) }}" alt="Hình ảnh" style="width:48px;height:48px;border-radius:8px;object-fit:cover;">
                                            @else
                                                <span>Không có</span>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($post->title, 50) }}</td>
                                        <td>{{ $post->user->name ?? 'N/A' }}</td>
                                        <td>{{ $post->status }}</td>
                                        <td>{{ $post->scheduled_at ? $post->scheduled_at->format('H:i:s d/m/Y') : '' }}</td>
                                        <td>
                                            <button class="action-btn view-btn" onclick="viewPost({{ $post->id }})">Xem chi tiết</button>
                                            <button class="action-btn cancel-btn" onclick="cancelSchedule({{ $post->id }})">Hủy lịch</button>
                                            <button class="action-btn approve-btn" onclick="approvePost({{ $post->id }})">Cho phép đăng</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center">Không có dữ liệu</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Tab Đăng Bài Hàng Loạt -->
                <div class="tab-content" id="tab-bulk" style="display:none;">
                    <div class="bulk-upload-container" style="max-width: 800px; margin: 0 auto; padding: 20px;">
                        <!-- Thông tin cột bắt buộc -->
                        <div class="required-fields" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 24px;">
                            <h3 style="margin-bottom: 16px;">Thông tin cột bắt buộc</h3>
                            <ul style="list-style: none; padding: 0;">
                                <li style="margin-bottom: 8px;">• Tiêu đề</li>
                                <li style="margin-bottom: 8px;">• Nội dung</li>
                                <li style="margin-bottom: 8px;">• Thời gian đăng</li>
                            </ul>
                            <a href="#" class="filter-btn" style="display: inline-block; margin-top: 12px;">Tải tệp mẫu</a>
                        </div>

                        <!-- Form tải lên tệp -->
                        <div class="upload-section" style="background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #dee2e6;">
                            <h3 style="margin-bottom: 16px;">Tải lên tệp</h3>
                            <form id="bulkUploadForm" enctype="multipart/form-data">
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <input type="file" id="bulkFile" accept=".xlsx,.xls,.csv" style="display: none;">
                                    <button type="button" class="filter-btn" onclick="document.getElementById('bulkFile').click()">Chọn tệp</button>
                                    <span id="selectedFileName" style="color: #666;">Chưa chọn tệp nào</span>
                                </div>
                            </form>
                        </div>

                        <!-- Xem trước dữ liệu -->
                        <div class="preview-section" style="background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #dee2e6;">
                            <h3 style="margin-bottom: 16px;">Xem trước dữ liệu</h3>
                            <div id="previewTable" style="overflow-x: auto;">
                                <table class="table-users" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Tiêu đề</th>
                                            <th>Nội dung</th>
                                            <th>Thời gian đăng</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="text-center">Chưa có dữ liệu</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="errorMessages" style="margin-top: 16px; color: #dc3545; display: none;"></div>
                        </div>

                        <!-- Nút thao tác -->
                        <div class="action-buttons" style="display: flex; gap: 16px; justify-content: center;">
                            <button class="filter-btn" onclick="scheduleBulkPosts()">Lên lịch hàng loạt</button>
                            <button class="filter-btn" style="background: #dc3545;" onclick="cancelBulkUpload()">Hủy</button>
                        </div>
                    </div>
                </div>
                <!-- Tab Lịch sử Đăng Bài -->
                <div class="tab-content" id="tab-history" style="display:none;">
                    <!-- Bộ lọc cho lịch sử -->
                    <div class="filter-bar">
                        <select class="filter-select" id="history-action-filter">
                            <option value="">Tất cả hành động</option>
                            <option value="create">Tạo bài viết</option>
                            <option value="edit">Sửa bài viết</option>
                            <option value="schedule">Lên lịch</option>
                            <option value="publish">Đăng bài</option>
                            <option value="cancel">Hủy bài</option>
                        </select>
                        <input type="date" class="filter-select" id="history-date-filter">
                        <input type="text" class="filter-search" id="history-search" placeholder="Tìm theo tiêu đề...">
                        <button class="filter-btn" onclick="applyHistoryFilters()">Lọc</button>
                    </div>

                    <div class="posts-table">
                        <table class="table-users">
                            <thead>
                                <tr>
                                    <th>Tiêu đề bài viết</th>
                                    <th>Thời gian</th>
                                    <th>Người thực hiện</th>
                                    <th>Hành động</th>
                                    <th>Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($histories as $history)
                                    <tr>
                                        <td>{{ $history->post->title ?? 'Bài viết đã bị xóa' }}</td>
                                        <td>{{ $history->created_at->format('H:i:s d/m/Y') }}</td>
                                        <td>{{ $history->user->name }}</td>
                                        <td>
                                            @switch($history->action)
                                                @case('create')
                                                    <span class="badge bg-primary">Tạo bài viết</span>
                                                    @break
                                                @case('edit')
                                                    <span class="badge bg-info">Sửa bài viết</span>
                                                    @break
                                                @case('schedule')
                                                    <span class="badge bg-warning">Lên lịch</span>
                                                    @break
                                                @case('publish')
                                                    <span class="badge bg-success">Đăng bài</span>
                                                    @break
                                                @case('cancel')
                                                    <span class="badge bg-danger">Hủy bài</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $history->details }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">Không có lịch sử</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $histories->links() }}
                    </div>
                </div>
                <!-- Tab Cài đặt -->
                <div class="tab-content" id="tab-settings" style="display:none;">
                    <div style="max-width: 900px; margin: 0 auto; padding: 24px; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                        <h2 style="margin-bottom: 24px;">Cài đặt Hệ Thống</h2>
                        <!-- 1. Cài đặt Thông báo -->
                        <div style="margin-bottom: 32px;">
                            <h3>Cài đặt Thông báo</h3>
                            <div style="margin-left: 18px;">
                                <label><input type="checkbox" name="notify_schedule"> Bật thông báo khi bài viết được lên lịch</label>
                                <div style="color: #888; font-size: 0.95em; margin-bottom: 8px; margin-left: 24px;">Nhận thông báo khi có bài viết mới được lên lịch đăng.</div>
                                <label><input type="checkbox" name="notify_cancel"> Bật thông báo khi bài viết bị hủy</label>
                                <div style="color: #888; font-size: 0.95em; margin-bottom: 8px; margin-left: 24px;">Nhận thông báo khi bài viết bị hủy lịch đăng.</div>
                                <label><input type="checkbox" name="notify_error"> Bật thông báo khi có bài viết bị lỗi</label>
                                <div style="color: #888; font-size: 0.95em; margin-bottom: 8px; margin-left: 24px;">Nhận thông báo khi hệ thống phát hiện bài viết lỗi.</div>
                                <label><input type="checkbox" name="notify_approve"> Bật thông báo khi duyệt lịch đăng bài</label>
                                <div style="color: #888; font-size: 0.95em; margin-bottom: 8px; margin-left: 24px;">Nhận thông báo khi bài viết được duyệt đăng.</div>
                            </div>
                        </div>
                        <!-- 2. Cài đặt Quy tắc Kiểm duyệt -->
                        <div style="margin-bottom: 32px;">
                            <h3>Cài đặt Quy tắc Kiểm duyệt</h3>
                            <label><input type="checkbox" name="auto_moderate"> Bật chức năng kiểm duyệt tự động</label>
                            <div style="color: #888; font-size: 0.95em; margin-bottom: 12px; margin-left: 24px;">Tự động kiểm tra nội dung bài viết theo các quy tắc bên dưới.</div>
                            <div style="background: #f8f9fa; border-radius: 8px; padding: 16px; margin-bottom: 12px;">
                                <h4 style="margin-bottom: 10px;">Danh sách Quy tắc</h4>
                                <table style="width:100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background: #e9ecef;">
                                            <th style="padding: 8px; border: 1px solid #dee2e6;">#</th>
                                            <th style="padding: 8px; border: 1px solid #dee2e6;">Tên Quy tắc</th>
                                            <th style="padding: 8px; border: 1px solid #dee2e6;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="padding: 8px; border: 1px solid #dee2e6;">1</td>
                                            <td style="padding: 8px; border: 1px solid #dee2e6;">Chứa từ ngữ nhạy cảm</td>
                                            <td style="padding: 8px; border: 1px solid #dee2e6;"><button class="filter-btn" style="padding:4px 12px;">Sửa</button> <button class="filter-btn" style="background:#dc3545;padding:4px 12px;">Xóa</button></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px; border: 1px solid #dee2e6;">2</td>
                                            <td style="padding: 8px; border: 1px solid #dee2e6;">Lặp lại ký tự quá nhiều</td>
                                            <td style="padding: 8px; border: 1px solid #dee2e6;"><button class="filter-btn" style="padding:4px 12px;">Sửa</button> <button class="filter-btn" style="background:#dc3545;padding:4px 12px;">Xóa</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button class="filter-btn" style="margin-top: 10px;">Thêm Quy tắc Mới</button>
                            </div>
                        </div>
                        <!-- 3. Cài đặt khác -->
                        <div style="margin-bottom: 32px;">
                            <h3>Cài đặt khác</h3>
                            <label><input type="checkbox" name="auto_save_draft"> Bật tự động lưu bản nháp</label>
                            <div style="color: #888; font-size: 0.95em; margin-bottom: 8px; margin-left: 24px;">Tự động lưu bài viết đang soạn thảo thành bản nháp.</div>
                            <label style="margin-left: 24px;">Tần suất lưu:
                                <select name="auto_save_interval" style="margin-left: 8px; padding: 4px 12px; border-radius: 4px;">
                                    <option value="5">5 phút</option>
                                    <option value="10">10 phút</option>
                                    <option value="15">15 phút</option>
                                </select>
                            </label>
                        </div>
                        <div style="text-align: center;">
                            <button class="filter-btn" style="font-size: 1.1em; padding: 12px 36px;">Lưu Cài Đặt</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        // Khai báo let currentDate chỉ 1 lần ở đầu script
        let currentDate = new Date();

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
        function showTab(tab, e) {
            var tabs = document.querySelectorAll('.tab-content');
            var btns = document.querySelectorAll('.tab-btn');
            tabs.forEach(t => {
                t.style.display = 'none';
                t.classList.remove('tab-active');
            });
            btns.forEach(b => b.classList.remove('active'));
            var tabEl = document.getElementById('tab-' + tab);
            if (tabEl) {
                tabEl.style.display = 'block';
                setTimeout(() => tabEl.classList.add('tab-active'), 10); // hiệu ứng mượt
            }
            if(e) {
                e.target.classList.add('active');
            }
            if(tab === 'schedule') {
                renderCalendar && renderCalendar();
            }
        }
        // CSS hiệu ứng tab
        const style = document.createElement('style');
        style.innerHTML = `.tab-content {transition: opacity 0.25s;} .tab-content:not(.tab-active) {opacity:0;} .tab-content.tab-active {opacity:1;}`;
        document.head.appendChild(style);

        // Calendar functionality
        function updateCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const currentMonthEl = document.getElementById('current-month');
            if (currentMonthEl) {
                currentMonthEl.textContent = `${month + 1}/${year}`;
            }
            const calendarGrid = document.querySelector('.calendar-grid');
            if (!calendarGrid) return;
            calendarGrid.innerHTML = '';
            // Add day headers
            const days = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
            days.forEach(day => {
                const dayHeader = document.createElement('div');
                dayHeader.textContent = day;
                dayHeader.style.textAlign = 'center';
                dayHeader.style.fontWeight = 'bold';
                calendarGrid.appendChild(dayHeader);
            });
            // Add empty cells for days before first day of month
            const firstDay = new Date(year, month, 1);
            for(let i = 0; i < firstDay.getDay(); i++) {
                calendarGrid.appendChild(document.createElement('div'));
            }
            // Add days
            const lastDay = new Date(year, month + 1, 0);
            for(let day = 1; day <= lastDay.getDate(); day++) {
                const dayCell = document.createElement('div');
                dayCell.className = 'calendar-day';
                dayCell.textContent = day;
                // Highlight today
                if(day === new Date().getDate() && 
                   month === new Date().getMonth() && 
                   year === new Date().getFullYear()) {
                    dayCell.classList.add('today');
                }
                calendarGrid.appendChild(dayCell);
            }
        }
        function prevMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            updateCalendar();
        }
        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            updateCalendar();
        }
        // Lấy dữ liệu bài đã lên lịch từ backend
        const scheduledPosts = @json($scheduledPosts);
        // Render calendar
        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const currentMonthEl = document.getElementById('current-month');
            if (currentMonthEl) {
                currentMonthEl.textContent = `${month + 1}/${year}`;
            }
            const calendarGrid = document.querySelector('.calendar-grid');
            if (!calendarGrid) return;
            calendarGrid.innerHTML = '';
            // Add day headers
            const days = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
            days.forEach(day => {
                const dayHeader = document.createElement('div');
                dayHeader.textContent = day;
                dayHeader.style.textAlign = 'center';
                dayHeader.style.fontWeight = 'bold';
                calendarGrid.appendChild(dayHeader);
            });
            // Add empty cells for days before first day of month
            const firstDay = new Date(year, month, 1);
            for(let i = 0; i < firstDay.getDay(); i++) {
                calendarGrid.appendChild(document.createElement('div'));
            }
            // Add days
            const lastDay = new Date(year, month + 1, 0);
            for(let day = 1; day <= lastDay.getDate(); day++) {
                const dayCell = document.createElement('div');
                dayCell.className = 'calendar-day';
                dayCell.textContent = day;
                // Highlight today
                if(day === new Date().getDate() && 
                   month === new Date().getMonth() && 
                   year === new Date().getFullYear()) {
                    dayCell.classList.add('today');
                }
                // Hiển thị các bài đã lên lịch trong ngày này
                const postsInDay = scheduledPosts.filter(post => {
                    if (!post.scheduled_at) return false;
                    const postDate = new Date(post.scheduled_at);
                    return postDate.getFullYear() === year && postDate.getMonth() === month && postDate.getDate() === day;
                });
                postsInDay.forEach(post => {
                    const postDiv = document.createElement('div');
                    postDiv.className = 'post-item';
                    postDiv.textContent = `${post.title} (${post.scheduled_at ? post.scheduled_at.substring(11,16) : ''})`;
                    postDiv.onclick = () => openPostModal(post);
                    dayCell.appendChild(postDiv);
                });
                calendarGrid.appendChild(dayCell);
            }
        }
        // Modal thao tác bài viết
        function openPostModal(post) {
            document.getElementById('modal-title').textContent = post.title;
            document.getElementById('modal-time').textContent = 'Thời gian: ' + (post.scheduled_at || '');
            document.getElementById('modal-status').textContent = 'Trạng thái: ' + (post.status || '');
            document.getElementById('postModal').style.display = 'flex';
            document.getElementById('modal-view-btn').onclick = () => viewPost(post.id);
            document.getElementById('modal-cancel-btn').onclick = () => cancelSchedule(post.id);
            document.getElementById('modal-approve-btn').onclick = () => approvePost(post.id);
        }
        function closePostModal() {
            document.getElementById('postModal').style.display = 'none';
        }
        // Khi load trang, chỉ hiển thị tab All
        document.addEventListener('DOMContentLoaded', function() {
            showTab('all', {target: document.querySelector('.tab-btn.active')});
        });

        // Bulk Upload Functions
        let bulkFileData = null;

        // Xử lý khi chọn file
        document.getElementById('bulkFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Hiển thị tên file
            document.getElementById('selectedFileName').textContent = file.name;

            // Kiểm tra định dạng file
            const validTypes = ['.xlsx', '.xls', '.csv'];
            const fileExt = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();
            if (!validTypes.includes(fileExt)) {
                showError('Định dạng file không hợp lệ. Vui lòng chọn file Excel hoặc CSV.');
                return;
            }

            // Đọc file
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    if (fileExt === '.csv') {
                        parseCSV(e.target.result);
                    } else {
                        parseExcel(e.target.result);
                    }
                } catch (error) {
                    showError('Lỗi khi đọc file: ' + error.message);
                }
            };
            reader.readAsArrayBuffer(file);
        });

        // Parse CSV file
        function parseCSV(content) {
            const lines = content.split('\n');
            const headers = lines[0].split(',').map(h => h.trim());
            
            // Kiểm tra headers bắt buộc
            const requiredHeaders = ['Tiêu đề', 'Nội dung', 'Thời gian đăng'];
            const missingHeaders = requiredHeaders.filter(h => !headers.includes(h));
            
            if (missingHeaders.length > 0) {
                showError('Thiếu các cột bắt buộc: ' + missingHeaders.join(', '));
                return;
            }

            // Parse dữ liệu
            const data = [];
            for (let i = 1; i < lines.length; i++) {
                if (!lines[i].trim()) continue;
                
                const values = lines[i].split(',').map(v => v.trim());
                const row = {
                    title: values[headers.indexOf('Tiêu đề')],
                    content: values[headers.indexOf('Nội dung')],
                    scheduled_at: values[headers.indexOf('Thời gian đăng')],
                    status: 'Chờ duyệt'
                };
                data.push(row);
            }

            bulkFileData = data;
            updatePreviewTable(data);
        }

        // Parse Excel file
        function parseExcel(content) {
            // Sử dụng SheetJS để đọc file Excel
            const workbook = XLSX.read(content, { type: 'array' });
            const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
            const data = XLSX.utils.sheet_to_json(firstSheet);

            // Kiểm tra headers bắt buộc
            const requiredHeaders = ['Tiêu đề', 'Nội dung', 'Thời gian đăng'];
            const headers = Object.keys(data[0]);
            const missingHeaders = requiredHeaders.filter(h => !headers.includes(h));
            
            if (missingHeaders.length > 0) {
                showError('Thiếu các cột bắt buộc: ' + missingHeaders.join(', '));
                return;
            }

            // Format dữ liệu
            const formattedData = data.map(row => ({
                title: row['Tiêu đề'],
                content: row['Nội dung'],
                scheduled_at: row['Thời gian đăng'],
                status: 'Chờ duyệt'
            }));

            bulkFileData = formattedData;
            updatePreviewTable(formattedData);
        }

        // Cập nhật bảng preview
        function updatePreviewTable(data) {
            const tbody = document.querySelector('#previewTable tbody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">Chưa có dữ liệu</td></tr>';
                return;
            }

            data.forEach((row, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.title || ''}</td>
                    <td>${row.content ? row.content.substring(0, 100) + (row.content.length > 100 ? '...' : '') : ''}</td>
                    <td>${row.scheduled_at || ''}</td>
                    <td>
                        <span class="status-badge ${row.status.toLowerCase()}">${row.status}</span>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            // Thêm style cho status badge
            const style = document.createElement('style');
            style.textContent = `
                .status-badge {
                    padding: 4px 8px;
                    border-radius: 4px;
                    font-size: 0.9rem;
                }
                .status-badge.chờ duyệt {
                    background: #ffc107;
                    color: #000;
                }
                .status-badge.đã duyệt {
                    background: #28a745;
                    color: #fff;
                }
                .status-badge.đã hủy {
                    background: #dc3545;
                    color: #fff;
                }
            `;
            document.head.appendChild(style);
        }

        // Hiển thị lỗi
        function showError(message) {
            const errorDiv = document.getElementById('errorMessages');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }

        // Xóa lỗi
        function clearError() {
            const errorDiv = document.getElementById('errorMessages');
            errorDiv.textContent = '';
            errorDiv.style.display = 'none';
        }

        // Lên lịch hàng loạt
        function scheduleBulkPosts() {
            if (!bulkFileData || bulkFileData.length === 0) {
                showError('Vui lòng chọn file và kiểm tra dữ liệu trước khi lên lịch.');
                return;
            }

            // Gửi request lên server
            fetch('/admin/bulk-schedule', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ posts: bulkFileData })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đã lên lịch thành công ' + data.count + ' bài viết!');
                    cancelBulkUpload();
                } else {
                    showError(data.message || 'Có lỗi xảy ra khi lên lịch bài viết.');
                }
            })
            .catch(error => {
                showError('Lỗi kết nối: ' + error.message);
            });
        }

        // Hủy tải lên
        function cancelBulkUpload() {
            document.getElementById('bulkFile').value = '';
            document.getElementById('selectedFileName').textContent = 'Chưa chọn tệp nào';
            document.querySelector('#previewTable tbody').innerHTML = '<tr><td colspan="4" class="text-center">Chưa có dữ liệu</td></tr>';
            clearError();
            bulkFileData = null;
        }

        // Thêm các hàm xử lý cho bảng bài viết cần duyệt
        function filterPosts(searchText) {
            const rows = document.querySelectorAll('.post-row');
            searchText = searchText.toLowerCase();
            
            rows.forEach(row => {
                const title = row.querySelector('td:first-child').textContent.toLowerCase();
                const content = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const author = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                if (title.includes(searchText) || content.includes(searchText) || author.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function filterByStatus(status) {
            const rows = document.querySelectorAll('.post-row');
            
            rows.forEach(row => {
                if (!status || row.dataset.status === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function approvePost(postId) {
            if (confirm('Bạn có chắc chắn muốn duyệt bài viết này?')) {
                fetch(`/admin/quanlybainguoidung/${postId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Đã duyệt bài viết thành công!');
                        location.reload();
                    } else {
                        alert('Có lỗi xảy ra: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Lỗi kết nối: ' + error.message);
                });
            }
        }

        function rejectPost(postId) {
            if (confirm('Bạn có chắc chắn muốn từ chối bài viết này?')) {
                fetch(`/admin/quanlybainguoidung/${postId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Đã từ chối bài viết!');
                        location.reload();
                    } else {
                        alert('Có lỗi xảy ra: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Lỗi kết nối: ' + error.message);
                });
            }
        }

        // Hàm lọc lịch sử
        function applyHistoryFilters() {
            const action = document.getElementById('history-action-filter').value;
            const date = document.getElementById('history-date-filter').value;
            const search = document.getElementById('history-search').value;

            fetch('/admin/api/post-history/filter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    action: action,
                    date: date,
                    search: search
                })
            })
            .then(response => response.json())
            .then(data => {
                updateHistoryTable(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Hàm cập nhật bảng lịch sử
        function updateHistoryTable(histories) {
            const tbody = document.querySelector('#tab-history tbody');
            tbody.innerHTML = '';

            if (histories.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">Không có lịch sử</td></tr>';
                return;
            }

            histories.forEach(history => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${history.post ? history.post.title : 'Bài viết đã bị xóa'}</td>
                    <td>${new Date(history.created_at).toLocaleString('vi-VN')}</td>
                    <td>${history.user.name}</td>
                    <td>
                        <span class="badge bg-${getActionColor(history.action)}">${getActionText(history.action)}</span>
                    </td>
                    <td>${history.details}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Hàm lấy màu cho badge dựa vào hành động
        function getActionColor(action) {
            switch(action) {
                case 'create': return 'primary';
                case 'edit': return 'info';
                case 'schedule': return 'warning';
                case 'publish': return 'success';
                case 'cancel': return 'danger';
                default: return 'secondary';
            }
        }

        // Hàm lấy text hiển thị cho hành động
        function getActionText(action) {
            switch(action) {
                case 'create': return 'Tạo bài viết';
                case 'edit': return 'Sửa bài viết';
                case 'schedule': return 'Lên lịch';
                case 'publish': return 'Đăng bài';
                case 'cancel': return 'Hủy bài';
                default: return action;
            }
        }
    </script>
    <!-- Thêm thư viện SheetJS để đọc file Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</body>
</html>
