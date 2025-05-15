<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ADMIN quản lý bài đăng</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">


<style>


table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    th, td {
        border: 1px solid #222;
        padding: 8px 12px;
        text-align: center;
        font-size: 15px;
    }
    th {
        background: #fff;
        font-weight: bold;
    }


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
    background: #e6f0ff; /* Màu xanh nhạt khi hover */
    cursor: pointer;
}

.approve-btn, .delete-btn {
    padding: 6px 14px;
    border: none;
    border-radius: 4px;
    margin: 0 2px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.2s;
}
.approve-btn {
    background: #4caf50;
    color: #fff;
}
.approve-btn:hover {
    background: #388e3c;
}
.delete-btn {
    background: #f44336;
    color: #fff;
}
.delete-btn:hover {
    background: #b71c1c;
}
</style>
</head>
<body>
    

<table class="custom-table" id="check-table">
    <thead>
        <tr>
            <th>Hình ảnh</th>
            <th>Tiêu đề</th>
            <th>Dữ liệu</th>
            <th>Vi phạm</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Img 1</td>
            <td>Đẹp quá...</td>
            <td>chính xác</td>
            <td>không</td>
            <td>
                <button class="approve-btn">Duyệt</button>
                <button class="delete-btn">Xóa</button>
            </td>
        </tr>
        <tr>
            <td>Img 2</td>
            <td>Xinh quá đi...</td>
            <td>lỗi chính tả</td>
            <td>không</td>
            <td>
                <button class="approve-btn">Duyệt</button>
                <button class="delete-btn">Xóa</button>
            </td>
        </tr>
        <tr>
            <td>Img 3</td>
            <td>Cưng quá...</td>
            <td>thiếu dữ liệu</td>
            <td>không</td>
            <td>
                <button class="approve-btn">Duyệt</button>
                <button class="delete-btn">Xóa</button>
            </td>
        </tr>
    </tbody>
</table>


</body>
</html>











