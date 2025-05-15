<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ADMIN quản lý bài đăng</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>.custom-table {
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

.published-row {
    background: #d0f5dd !important; /* Xanh lá nhạt */
}
.rejected-row {
    background: #ffd6d6 !important; /* Đỏ nhạt */
}

.check-btn, .x-btn {
    padding: 6px 14px;
    border: none;
    border-radius: 4px;
    margin: 0 2px;
    font-size: 18px;
    cursor: pointer;
    transition: background 0.2s;
}
.check-btn {
    background: #4caf50;
    color: #fff;
}
.check-btn:hover {
    background: #388e3c;
}
.x-btn {
    background: #f44336;
    color: #fff;
}
.x-btn:hover {
    background: #b71c1c;
}</style>

</head>
<body>
    
<table class="custom-table" id="publish-table">
    <thead>
        <tr>
            <th>Hình ảnh</th>
            <th>Tiêu đề</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Đăng bài</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Img 1</td>
            <td>Đẹp quá...</td>
            <td>chờ</td>
            <td>19/03/2025</td>
            <td>
                <button class="check-btn">✔</button>
                <button class="x-btn">✖</button>
            </td>
        </tr>
        <tr>
            <td>Img 2</td>
            <td>Xinh quá đi...</td>
            <td>chờ</td>
            <td>19/03/2025</td>
            <td>
                <button class="check-btn">✔</button>
                <button class="x-btn">✖</button>
            </td>
        </tr>
        <tr>
            <td>Img 3</td>
            <td>Cưng quá...</td>
            <td>chờ</td>
            <td>19/03/2025</td>
            <td>
                <button class="check-btn">✔</button>
                <button class="x-btn">✖</button>
            </td>
        </tr>
        <tr>
            <td>Img 4</td>
            <td>Này hay nè...</td>
            <td>chờ</td>
            <td>19/03/2025</td>
            <td>
                <button class="check-btn">✔</button>
                <button class="x-btn">✖</button>
            </td>
        </tr>
        <tr>
            <td>Img 5</td>
            <td>Drama đi mn...</td>
            <td>chờ</td>
            <td>19/03/2025</td>
            <td>
                <button class="check-btn">✔</button>
                <button class="x-btn">✖</button>
            </td>
        </tr>
    </tbody>
</table>


</body>
</html>

