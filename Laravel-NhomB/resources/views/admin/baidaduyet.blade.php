<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ADMIN quản lý bài đăng</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        #menuPopup div {
            padding: 4px 0;
            font-size: 15px;
        }

        #menuPopup div:not(:last-child) {
            border-bottom: 1px solid #eee;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #222;
            padding: 8px 12px;
            text-align: center;
            font-size: 15px;
        }

        th {
            background: #fff;
            font-weight: bold;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin: 16px 0 8px 0;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 8px;
        }

        .dot-btn {
            background: #fff;
            border: 1px solid #222;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            padding: 0;
            cursor: pointer;
            transition: background 0.2s;
        }

        .dot-btn:hover {
            background: #eee;
        }

        .popup-menu {
            position: absolute;
            background: #fff;
            border: 1px solid #222;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
            z-index: 100;
            min-width: 200px;
        }

        .popup-menu div {
            padding: 8px 16px;
            cursor: pointer;

        }

        .popup-menu div:hover {
            background: #f0f0f0;
        }

        .popup-menu {
            position: absolute;
            top: 40px;
            /* Đặt menu ngay dưới nút 3 chấm (cao 32px + margin) */
            left: 0;
            /* Thẳng hàng với nút 3 chấm */
            background: #fff;
            border: 1px solid #222;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            z-index: 100;
            min-width: 220px;
            padding: 0;
        }

        .popup-menu div {
            padding: 10px 16px;
            cursor: pointer;
            white-space: nowrap;
        }

        .popup-menu div:hover {
            background: #f0f0f0;
        }

        h2 {
            display: block;
            font-size: 1.5em;
            margin-block-start: 0.83em;
            margin-block-end: 0.83em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
            unicode-bidi: isolate;
        }

        body {
            font-family: Arial, sans-serif;
            background: #fafbfc;
        }

        .nav-row-container {
            display: flex;
            margin-top: 32px;
        }

        .nav-row {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            background: #f7f7f7;
            padding: 18px 12px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
            width: 100%;
            max-width: 1200px;
        }

        .nav-btn {
            background: #e0e0e0;
            color: #222;
            border: none;
            border-radius: 7px;
            padding: 14px 18px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            min-width: 180px;
            text-align: center;
            white-space: normal;
        }

        .nav-btn.active,
        .nav-btn.active:hover {
            background: #222 !important;
            color: #fff !important;
        }

        .nav-btn:hover:not(.active) {
            background: #d0d0d0;
            color: #222;
        }

        @media (max-width: 900px) {
            .nav-row {
                max-width: 100vw;
                padding: 10px 2px;
            }

            .nav-btn {
                min-width: 140px;
                font-size: 14px;
                padding: 10px 8px;
            }
        }

        @media (max-width: 600px) {
            .nav-row {
                flex-direction: column;
                align-items: stretch;
            }

            .nav-btn {
                min-width: unset;
                width: 100%;
            }
        }

        .custom-nav-row-container {
            display: flex;
            margin-bottom: 24px;
        }

        .custom-nav-row {
            display: flex;
            gap: 14px;
            background: #f7f7f7;
            padding: 12px 10px;
            border-radius: 12px;
            width: 100%;
            max-width: 1200px;
        }

        .custom-nav-btn {
            background: #e0e0e0;
            color: #222;
            border: none;
            border-radius: 7px;
            padding: 14px 18px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            min-width: 180px;
            text-align: center;
            white-space: normal;
        }

        .custom-nav-btn.active,
        .custom-nav-btn.active:hover {
            background: #222 !important;
            color: #fff !important;
        }

        .custom-nav-btn:hover {
            background: #222 !important;
            color: #fff !important;
        }

        @media (max-width: 900px) {
            .custom-nav-row {
                max-width: 100vw;
                padding: 10px 2px;
            }

            .custom-nav-btn {
                min-width: 140px;
                font-size: 14px;
                padding: 10px 8px;
            }
        }

        @media (max-width: 600px) {
            .custom-nav-row {
                flex-direction: column;
                align-items: stretch;
            }

            .custom-nav-btn {
                min-width: unset;
                width: 100%;
            }
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            margin-top: 18px;
            padding: 0;
            overflow: hidden;
        }

        .card-header {
            background: #f7f7f7;
            padding: 16px 24px;
            border-bottom: 1px solid #eee;
        }

        .card-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .card-body {
            padding: 20px 24px;
        }

        .filter-form {
            display: flex;
            gap: 18px;
            align-items: center;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .filter-form label {
            font-size: 15px;
            font-weight: 500;
        }

        .filter-form input[type="date"],
        .filter-form input[type="text"] {
            margin-left: 6px;
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
        }

        .apply-btn {
            background: #222;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 18px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.15s;
        }

        .apply-btn:hover {
            background: #444;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .approved-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .approved-table th,
        .approved-table td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            text-align: center;
            font-size: 15px;
        }

        .approved-table th {
            background: #f7f7f7;
            font-weight: bold;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            border: none;
            border-radius: 6px;
            padding: 6px 14px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.15s, color 0.15s, box-shadow 0.15s;
            text-decoration: none;
        }

        .action-btn.view {
            background: #e0e0e0;
            color: #222;
        }

        .action-btn.view:hover {
            background: #1976d2;
            color: #fff;
        }

        .action-btn.check {
            background: #fffbe7;
            color: #b8860b;
            border: 1px solid #ffe082;
        }

        .action-btn.check:hover {
            background: #ffe082;
            color: #222;
        }

        .action-btn.delete {
            background: #ffebee;
            color: #c62828;
        }

        .action-btn.delete:hover {
            background: #c62828;
            color: #fff;
        }

        .pagination {
            display: flex;
            gap: 6px;
            justify-content: flex-end;
            align-items: center;
        }

        .pagination button {
            background: #e0e0e0;
            color: #222;
            border: none;
            border-radius: 5px;
            padding: 6px 12px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
        }

        .pagination button.active,
        .pagination button:hover {
            background: #222;
            color: #fff;
        }

        .tab-buttons {
            margin-bottom: 16px;
            display: flex;
            gap: 8px;
        }

        .tab-buttons button {
            background: #e5e5e5;
            color: #222;
            border: none;
            padding: 10px 24px;
            font-size: 15px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.2s, color 0.2s;
        }

        .tab-buttons button.active,
        .tab-buttons button:hover {
            background: #111;
            color: #fff;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .bg-success {
            background-color: #28a745;
            color: white;
        }

        .bg-warning {
            background-color: #ffc107;
            color: #000;
        }

        .bg-secondary {
            background-color: #6c757d;
            color: white;
        }

        .form-control {
            padding: 6px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .action-btn i {
            font-size: 14px;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .star-rating {
            display: inline-block;
            font-size: 22px;
            color: #ffd700;
            cursor: pointer;
            vertical-align: middle;
        }

        .star-rating .fa-star {
            margin-right: 2px;
        }

        /* #evaluateForm { display: none !important; } */
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
                <span class="admin-name">
                    {{ Auth::check() ? Auth::user()->name : 'Khách' }}
                </span>
            </div>
        </div>

        <div class="admin-content" style="display: flex; align-items: flex-start; width: 100%;">
            <div class="admin-sidebar" style="width: 240px; flex-shrink: 0;">
                <ul class="admin-menu">
                    <li><a href="{{ route('admin.quanlynguoidung') }}">QUẢN LÝ NGƯỜI DÙNG</a></li>
                    <li><a href="{{ route('admin.quanlybainguoidung') }}">QUẢN LÝ BÀI VIẾT CỦA NGƯỜI DÙNG</a></li>
                    <li><a href="{{ route('admin.baichoduyet') }}">BÀI CHỜ DUYỆT</a></li>
                    <li><a href="{{ route('admin.baidaduyet') }}">BÀI ĐÃ DUYỆT</a></li>
                    <li><a href="{{ route('admin.lichdangbai') }}">LỊCH ĐĂNG BÀI</a></li>
                    <li><a href="{{ route('admin.quanlybinhluan') }}">PHÂN TÍCH TƯƠNG TÁC</a></li>
                    <li>
                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ĐĂNG
                            XUẤT</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
            <div class="admin-main">
                <h2>ADMIN quản lý bài đã duyệt</h2>
                <div class="tab-buttons">
                    <button onclick="showTab('tab1', this)" class="active">QUẢN LÝ BÀI ĐÃ ĐƯỢC DUYỆT</button>
                    <button onclick="showTab('tab2', this)">KIỂM TRA BÀI TRƯỚC KHI ĐƯA RA</button>
                    <button onclick="showTab('tab3', this)">CẬP NHẬT TRẠNG THÁI BÀI VIẾT</button>
                    <button onclick="showTab('tab4', this)">XÓA BÀI NẾU CÓ SAI PHẠM</button>
                    <button onclick="showTab('tab5', this)">THÊM NHẬN XÉT VÀO BÀI VIẾT</button>
                    <button onclick="showTab('tab6', this)">ĐÁNH GIÁ CHẤT LƯỢNG NỘI DUNG</button>
                </div>
                <div id="tab1" class="tab-content" style="display:block;">
                    <div class="card">
                        <div class="card-header">
                            <h3>TIÊU ĐỀ: Bài viết Đã Duyệt</h3>
                        </div>
                        <div class="card-body">
                            <form class="filter-form" method="GET" action="{{ route('admin.baidaduyet') }}">
                                <div style="display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 16px;">
                                    <div>
                                        <label>Ngày từ:
                                            <input type="date" name="from_date" value="{{ request('from_date') }}"
                                                class="form-control">
                                        </label>
                                    </div>
                                    <div>
                                        <label>Đến:
                                            <input type="date" name="to_date" value="{{ request('to_date') }}"
                                                class="form-control">
                                        </label>
                                    </div>
                                    <div>
                                        <label>Tìm kiếm:
                                            <input type="text" name="search" value="{{ request('search') }}"
                                                placeholder="Nhập tiêu đề..." class="form-control">
                                        </label>
                                    </div>
                                    <div>
                                        <label>Sắp xếp theo:
                                            <select name="sort_by" class="form-control">
                                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                                                <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Tiêu đề</option>
                                                <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Trạng thái</option>
                                            </select>
                                        </label>
                                    </div>
                                    <div>
                                        <label>Thứ tự:
                                            <select name="sort_order" class="form-control">
                                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                                            </select>
                                        </label>
                                    </div>
                                    <div>
                                        <button type="submit" class="apply-btn">Áp dụng</button>
                                        <a href="{{ route('admin.baidaduyet') }}" class="action-btn"
                                            style="margin-left: 8px;">Reset</a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="approved-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tiêu đề bài viết</th>
                                            <th>Ngày duyệt</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($posts as $post)
                                            <tr>
                                                <td>{{ $post->id }}</td>
                                                <td>{{ $post->title }}</td>
                                                <td>{{ $post->created_at ? \Carbon\Carbon::parse($post->created_at)->format('d/m/Y') : '' }}
                                                </td>
                                                <td>
                                                    @if($post->status == 'published')
                                                        <span class="badge bg-success">Đã đăng</span>
                                                    @elseif($post->status == 'pending')
                                                        <span class="badge bg-warning">Chờ đăng</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $post->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div
                                                        style="display: flex; gap: 8px; align-items: center; justify-content: center;">
                                                        <a href="{{ route('admin.baidaduyet.show', $post->id) }}"
                                                            class="action-btn view" title="Xem">
                                                            <i class="fas fa-eye"></i> Xem
                                                        </a>
                                                        <a href="{{ route('admin.baidaduyet.show', $post->id) }}"
                                                            class="action-btn check" title="Kiểm tra">
                                                            <i class="fas fa-check"></i> Kiểm tra
                                                        </a>
                                                        <form action="{{ route('admin.baidaduyet.delete', $post->id) }}"
                                                            method="POST" style="display:inline;"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="action-btn delete" title="Xóa">
                                                                <i class="fas fa-trash"></i> Xóa
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Không có bài viết nào.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination">
                                {{ $posts->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tab2" class="tab-content" style="display:none;">
                    <div class="card">
                        <div class="card-header">
                            <h3>Danh sách bài viết cần kiểm tra</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="approved-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tiêu đề bài viết</th>
                                            <th>Tác giả</th>
                                            <th>Ngày tạo</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pendingPosts as $post)
                                            <tr>
                                                <td>{{ $post->id }}</td>
                                                <td>{{ $post->title }}</td>
                                                <td>{{ $post->author->name }}</td>
                                                <td>{{ $post->created_at ? \Carbon\Carbon::parse($post->created_at)->format('d/m/Y') : '' }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.baidaduyet.show', $post->id) }}"
                                                        class="btn btn-primary">
                                                        Kiểm tra
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">Không có bài viết nào cần kiểm tra.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tab3" class="tab-content" style="display:none;">
                    <div class="card">
                        <div class="card-header">
                            <h3>TIÊU ĐỀ: Cập nhật Trạng thái Bài viết</h3>
                        </div>
                        <div class="card-body">
                            <form class="filter-form" method="GET" action="">
                                <label>Tìm kiếm bài viết:
                                    <input type="text" name="search" placeholder="Nhập ID hoặc tiêu đề bài viết">
                                    <button type="submit" class="apply-btn">Tìm kiếm</button>
                                </label>
                            </form>

                            <div class="table-responsive">
                                <table class="approved-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tiêu đề</th>
                                            <th>Trạng thái hiện tại</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($posts as $post)
                                            <tr>
                                                <td>{{ $post->id }}</td>
                                                <td>{{ $post->title }}</td>
                                                <td>
                                                    @if($post->status == 'published')
                                                        Đã đăng
                                                    @elseif($post->status == 'pending')
                                                        Chờ duyệt
                                                    @elseif($post->status == 'approved')
                                                        Đã duyệt
                                                    @elseif($post->status == 'error')
                                                        Lỗi
                                                    @elseif($post->status == 'deleted')
                                                        Đã xóa
                                                    @else
                                                        {{ $post->status }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <button onclick="selectPost({{ $post->id }}, '{{ $post->title }}')"
                                                        class="action-btn">Chọn</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">Không có bài viết nào.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div id="updateStatusForm"
                                style="display: none; margin-top: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
                                <h4>Cập nhật trạng thái cho: <span id="selectedPostTitle"></span> (ID: <span
                                        id="selectedPostId"></span>)</h4>

                                <form action="{{ route('admin.posts.update-status') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="post_id" id="postIdInput">

                                    <div style="margin: 15px 0;">
                                        <label for="newStatus">Chọn trạng thái mới:</label>
                                        <select name="status" id="newStatus" class="form-control"
                                            style="width: 200px; padding: 8px; margin-top: 5px;">
                                            <option value="pending">Chờ duyệt</option>
                                            <option value="approved">Đã duyệt</option>
                                            <option value="published">Đã đăng</option>
                                            <option value="error">Lỗi</option>
                                            <option value="deleted">Đã xóa</option>
                                        </select>
                                    </div>

                                    <div style="margin: 15px 0;">
                                        <label for="note">Ghi chú (tùy chọn):</label>
                                        <textarea name="note" id="note" rows="4"
                                            style="width: 100%; padding: 8px; margin-top: 5px;"></textarea>
                                    </div>

                                    <div style="margin-top: 20px;">
                                        <button type="submit" class="action-btn">Cập nhật</button>
                                        <button type="button" onclick="cancelUpdate()" class="action-btn"
                                            style="background: #ffdddd; color: #c00;">Hủy</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tab4" class="tab-content" style="display:none;">
                    <div class="card">
                        <div class="card-header">
                            <h3>TIÊU ĐỀ: Xóa Bài viết</h3>
                        </div>
                        <div class="card-body">
                            <form class="filter-form" method="GET" action="">
                                <label>Tìm kiếm bài viết để xóa:
                                    <input type="text" name="search_delete" placeholder="Nhập ID hoặc tiêu đề bài viết">
                                    <button type="submit" class="apply-btn">Tìm kiếm</button>
                                </label>
                            </form>

                            <div class="table-responsive">
                                <table class="approved-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tiêu đề bài viết</th>
                                            <th>Ngày đăng</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($posts as $post)
                                            <tr>
                                                <td>{{ $post->id }}</td>
                                                <td>{{ $post->title }}</td>
                                                <td>{{ $post->created_at ? \Carbon\Carbon::parse($post->created_at)->format('d/m/Y') : '' }}
                                                </td>
                                                <td>
                                                    @if($post->status == 'published')
                                                        Đã đăng
                                                    @elseif($post->status == 'approved')
                                                        Đã duyệt
                                                    @elseif($post->status == 'pending')
                                                        Chờ duyệt
                                                    @else
                                                        {{ $post->status }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button"
                                                        onclick="selectDeletePost({{ $post->id }}, '{{ $post->title }}')"
                                                        class="action-btn delete">Chọn</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">Không có bài viết nào.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div id="deletePostForm"
                                style="display: none; margin-top: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
                                <h4>Xác nhận xóa bài viết:</h4>
                                <p>Bạn đang chuẩn bị xóa bài viết: <b><span id="deletePostTitle"></span></b> (ID: <span
                                        id="deletePostId"></span>)</p>
                                <p style="color: #c00;">Hành động này không thể hoàn tác. Bạn có chắc chắn muốn tiếp
                                    tục?</p>
                                <form action="{{ route('admin.baidaduyet.delete', 0) }}" method="POST"
                                    id="realDeleteForm"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="post_id" id="deletePostIdInput">
                                    <div style="margin: 15px 0;">
                                        <label for="deleteReason">Lý do xóa (bắt buộc):</label>
                                        <textarea name="delete_reason" id="deleteReason" rows="3"
                                            style="width: 100%; padding: 8px; margin-top: 5px;" required></textarea>
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <button type="submit" class="action-btn delete"
                                            style="background: #c00; color: #fff;">Xác nhận Xóa</button>
                                        <button type="button" onclick="cancelDelete()" class="action-btn"
                                            style="background: #eee; color: #222;">Hủy</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tab5" class="tab-content" style="display:none;">
                    <div class="card">
                        <div class="card-header">
                            <h3>TIÊU ĐỀ: Thêm Nhận xét vào Bài viết</h3>
                        </div>
                        <div class="card-body">
                            <form id="selectPostForm" style="margin-bottom: 18px;">
                                <label>Chọn bài viết:
                                    <select id="commentPostSelect" class="form-control"
                                        style="width: 300px; display: inline-block;">
                                        <option value="">-- Chọn bài viết --</option>
                                        @foreach($posts as $post)
                                            <option value="{{ $post->id }}">{{ $post->title }} (ID: {{ $post->id }})
                                            </option>
                                        @endforeach
                                    </select>
                                </label>
                            </form>

                            <div id="selectedPostInfo" style="margin-bottom: 18px; display: none;">
                                Bài viết đang chọn: <b><span id="selectedPostTitle"></span></b> (ID: <span
                                    id="selectedPostId"></span>)
                            </div>

                            <form id="addCommentForm" action="{{ route('admin.posts.add-comment') }}" method="POST">
                                @csrf
                                <input type="hidden" name="post_id" id="commentPostIdInput">

                                <div style="margin-bottom: 12px;">
                                    <label for="commentContent">Nội dung nhận xét:</label>
                                    <textarea name="content" id="commentContent" rows="3"
                                        style="width: 100%; padding: 8px; margin-top: 5px;" required></textarea>
                                </div>

                                <div style="margin-bottom: 12px;">
                                    <label style="margin-right: 10px;">
                                        <input type="radio" name="type" value="internal" checked> Nội bộ
                                    </label>
                                    <label>
                                        <input type="radio" name="type" value="action"> Cần hành động
                                    </label>
                                </div>

                                <div>
                                    <button type="submit" class="action-btn">Gửi nhận xét</button>
                                    <button type="button" onclick="cancelComment()" class="action-btn"
                                        style="background: #eee;">Hủy</button>
                                </div>
                            </form>

                            <div id="commentsList" style="margin-top: 30px; display: none;">
                                <h4>Các nhận xét đã có:</h4>
                                <div class="table-responsive">
                                    <table class="approved-table">
                                        <thead>
                                            <tr>
                                                <th>Người gửi</th>
                                                <th>Thời gian</th>
                                                <th>Nội dung nhận xét</th>
                                            </tr>
                                        </thead>
                                        <tbody id="commentsTableBody">
                                            <!-- Dữ liệu nhận xét sẽ được render ở đây -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tab6" class="tab-content" style="display:none;">
                    <div class="card">
                        <div class="card-header">
                            <h3>TIÊU ĐỀ: Đánh giá Chất lượng Nội dung Bài viết</h3>
                        </div>
                        <div class="card-body">
                            <form id="selectEvaluatePostForm" style="margin-bottom: 18px;">
                                <label>Chọn bài viết cần đánh giá:
                                    <select id="evaluatePostSelect" class="form-control" style="width: 300px; display: inline-block;">
                                        <option value="">-- Chọn bài viết --</option>
                                        @foreach($posts as $post)
                                            <option value="{{ $post->id }}">{{ $post->title }} (ID: {{ $post->id }})</option>
                                        @endforeach
                                    </select>
                                </label>
                            </form>

                            <div id="selectedEvaluatePostInfo" style="margin-bottom: 18px; display: none;">
                                <b>Bài viết đang chọn:</b> <span id="selectedEvaluatePostTitle"></span> (ID: <span id="selectedEvaluatePostId"></span>)
                            </div>

                            <form id="evaluateForm" action="{{ route('admin.posts.evaluate') }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="post_id" id="evaluatePostIdInput">
                                <div style="margin-bottom: 16px;">
                                    <label>Chất lượng nội dung:
                                        <span class="star-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star" data-value="{{ $i }}"></i>
                                            @endfor
                                        </span>
                                        <input type="hidden" name="rating" id="ratingInput" required>
                                    </label>
                                </div>
                                <div style="margin-bottom: 16px;">
                                    <label>Nhận xét:
                                        <textarea name="comment" id="evaluateComment" rows="3" style="width: 100%; padding: 8px; margin-top: 5px;" required></textarea>
                                    </label>
                                </div>
                                <button type="submit" class="action-btn">Gửi đánh giá</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        document.querySelectorAll('.custom-nav-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.custom-nav-btn').forEach(function (b) {
                    b.classList.remove('active');
                });
                this.classList.add('active');
            });
        });

        function showTab(tabId, btn) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
            document.getElementById(tabId).style.display = 'block';
            document.querySelectorAll('.tab-buttons button').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }

        function selectPost(id, title) {
            document.getElementById('selectedPostId').textContent = id;
            document.getElementById('selectedPostTitle').textContent = title;
            document.getElementById('postIdInput').value = id;
            document.getElementById('updateStatusForm').style.display = 'block';
        }

        function cancelUpdate() {
            document.getElementById('updateStatusForm').style.display = 'none';
        }

        function selectDeletePost(id, title) {
            document.getElementById('deletePostId').textContent = id;
            document.getElementById('deletePostTitle').textContent = title;
            document.getElementById('deletePostIdInput').value = id;
            // Sửa action form để đúng id
            document.getElementById('realDeleteForm').action = '/admin/baidaduyet/' + id;
            document.getElementById('deletePostForm').style.display = 'block';
        }

        function cancelDelete() {
            document.getElementById('deletePostForm').style.display = 'none';
        }

        // Giả lập dữ liệu nhận xét (bạn sẽ thay bằng dữ liệu thực từ backend)
        const allComments = @json($commentsByPost ?? []);

        const postsData = @json($posts instanceof \Illuminate\Pagination\AbstractPaginator ? $posts->items() : $posts);

        document.getElementById('commentPostSelect').addEventListener('change', function () {
            const postId = this.value;
            if (!postId) {
                document.getElementById('selectedPostInfo').style.display = 'none';
                document.getElementById('addCommentForm').style.display = 'none';
                document.getElementById('commentsList').style.display = 'none';
                return;
            }
        });

        function cancelComment() {
            document.getElementById('addCommentForm').reset();
        }

        function showLogoutModal() {
            document.getElementById('logout-form').submit();
        }


        //tab6
        // Hiện form khi chọn bài viết
document.getElementById('evaluatePostSelect').addEventListener('change', function () {
    const postId = this.value;
    if (!postId) {
        document.getElementById('selectedEvaluatePostInfo').style.display = 'none';
        document.getElementById('evaluateForm').style.display = 'none';
        return;
    }
    // Lấy tiêu đề bài viết từ option
    const selectedOption = this.options[this.selectedIndex];
    const titleMatch = selectedOption.text.match(/^(.*) \(ID:/);
    const title = titleMatch ? titleMatch[1] : '';
    document.getElementById('selectedEvaluatePostTitle').textContent = title;
    document.getElementById('selectedEvaluatePostId').textContent = postId;
    document.getElementById('evaluatePostIdInput').value = postId;
    document.getElementById('selectedEvaluatePostInfo').style.display = 'block';
    document.getElementById('evaluateForm').style.display = 'block';
});

// Xử lý chọn sao
document.querySelectorAll('.star-rating .fa-star').forEach(function(star) {
    star.addEventListener('click', function() {
        const value = this.getAttribute('data-value');
        document.getElementById('ratingInput').value = value;
        document.querySelectorAll('.star-rating .fa-star').forEach(function(s, idx) {
            if (idx < value) {
                s.style.color = '#ffd700';
            } else {
                s.style.color = '#ccc';
            }
        });
    });
});
    </script>

</body>

</html>