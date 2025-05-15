<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ADMIN quản lý bài đăng</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
   <style>
    .custom-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 18px;
}

.custom-table th, .custom-table td {
    border: 2px solid #222;
    padding: 14px 18px;
    text-align: center;
    transition: background 0.2s;
}

.custom-table tbody tr:hover {
    background: #e6f0ff;
    cursor: pointer;
}
.status-btn, .update-btn {
    padding: 6px 14px;
    border: 1.5px solid #4caf50;
    border-radius: 4px;
    margin: 0 2px;
    font-size: 16px;
    cursor: pointer;
    background: #fff;
    color: #222;
    transition: background 0.2s, color 0.2s, border 0.2s;
}
.status-btn.active,
.status-btn:focus {
    background: #d0f5dd !important; /* Xanh lá nhạt */
    color: #388e3c !important;
    border: 2px solid #388e3c;
    outline: none;
}
.update-btn.success {
    background: #d0f5dd !important; /* Xanh lá nhạt */
    color: #388e3c !important;
    border: 2px solid #388e3c;
    outline: none;
}
.update-btn.waiting {
    background: #fff9c4 !important; /* Vàng nhạt */
    color: #bfa100 !important;
    border: 2px solid #bfa100;
    outline: none;
}

   </style>

</head>
<body>



<table class="custom-table" id="status-table">
    <thead>
        <tr>
            <th>Bài viết</th>
            <th>Chỉnh sửa</th>
            <th>Trạng thái</th>
            <th>Cập nhật</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>nội dung | tiêu đề</td>
            <td>
                <button class="status-btn">chờ</button>
                <button class="status-btn">duyệt</button>
                <button class="status-btn">xóa</button>
                <button class="status-btn">ẩn</button>
            </td>
            <td>
                <button class="update-btn">Thành công</button>
                <button class="update-btn">Chờ</button>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>nội dung | tiêu đề</td>
            <td>
                <button class="status-btn">chờ</button>
                <button class="status-btn">duyệt</button>
                <button class="status-btn">xóa</button>
                <button class="status-btn">ẩn</button>
            </td>
            <td>
                <button class="update-btn">Thành công</button>
                <button class="update-btn">Chờ</button>
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td>nội dung | tiêu đề</td>
            <td>
                <button class="status-btn">chờ</button>
                <button class="status-btn">duyệt</button>
                <button class="status-btn">xóa</button>
                <button class="status-btn">ẩn</button>
            </td>
            <td>
                <button class="update-btn">Thành công</button>
                <button class="update-btn">Chờ</button>
            </td>
        </tr>
        <tr>
            <td>4</td>
            <td>nội dung | tiêu đề</td>
            <td>
                <button class="status-btn">chờ</button>
                <button class="status-btn">duyệt</button>
                <button class="status-btn">xóa</button>
                <button class="status-btn">ẩn</button>
            </td>
            <td>
                <button class="update-btn">Thành công</button>
                <button class="update-btn">Chờ</button>
            </td>
        </tr>
        <tr>
            <td>5</td>
            <td>nội dung | tiêu đề</td>
            <td>
                <button class="status-btn">chờ</button>
                <button class="status-btn">duyệt</button>
                <button class="status-btn">xóa</button>
                <button class="status-btn">ẩn</button>
            </td>
            <td>
                <button class="update-btn">Thành công</button>
                <button class="update-btn">Chờ</button>
            </td>
        </tr>
    </tbody>
</table>

</body>
</html>
