<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ADMIN quản lý bài đăng</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">


<style>
    .content-area {
    width: 100%;
    /* Nếu content bị giới hạn bởi cha, thêm dòng dưới để ép full width */
    flex: 1 1 0%;
    /* Nếu dùng flexbox cho layout cha, đảm bảo content-area có flex-grow */
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 20px;
}

.custom-table th, .custom-table td {
    border: 2px solid #222;
    padding: 18px 20px;
    text-align: center;
    white-space: nowrap;
    transition: background 0.2s;
}

.custom-table th {
    background: #fff;
    font-weight: bold;
}

.custom-table tbody tr:hover {
    background: #e6f0ff;
    cursor: pointer;
}

.dot-btn {
    background: #fff;
    border: 1.5px solid #222;
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
</style>
</head>
<body>
<div class="table-wrapper">
   

    <!-- Wrapper cho toàn bộ nội dung dưới nút 3 chấm -->
    <div class="content-area">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Img 1</td>
                    <td>Đẹp quá...</td>
                    <td>Đã đăng</td>
                    <td>19/03/2025</td>
                    <td>Theo dõi</td>
                </tr>
                <tr>
                    <td>Img 2</td>
                    <td>Xinh quá đi...</td>
                    <td>Đã đăng</td>
                    <td>19/03/2025</td>
                    <td>Theo dõi</td>
                </tr>
                <tr>
                    <td>Img 3</td>
                    <td>Cưng quá...</td>
                    <td>Đã đăng</td>
                    <td>19/03/2025</td>
                    <td>Theo dõi</td>
                </tr>
                <tr>
                    <td>Img 4</td>
                    <td>Này hay nè...</td>
                    <td>Đã đăng</td>
                    <td>19/03/2025</td>
                    <td>Theo dõi</td>
                </tr>
                <tr>
                    <td>Img 5</td>
                    <td>Drama đi mn...</td>
                    <td>Đã đăng</td>
                    <td>19/03/2025</td>
                    <td>Theo dõi</td>
                </tr>
            </tbody>
        </table>

        <div style="font-weight: bold; margin: 32px 0 10px 0; font-size: 22px;">Phân loại bài viết</div>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Chủ đề</th>
                    <th>Tác giả</th>
                    <th>Trạng thái</th>
                    <th>Thống kê đã đăng</th>
                    <th>Chờ đăng</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>xã hội</td>
                    <td>messy</td>
                    <td>Đã đăng</td>
                    <td>33</td>
                    <td>5</td>
                </tr>
                <tr>
                    <td>văn học</td>
                    <td>tố hữu</td>
                    <td>chờ duyệt</td>
                    <td>2</td>
                    <td>6</td>
                </tr>
                <tr>
                    <td>âm nhạc</td>
                    <td>APT</td>
                    <td>Yêu cầu duyệt</td>
                    <td>12</td>
                    <td>0</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>





















<!-- <table>
    <thead>
        <tr>
            <th>Hình ảnh</th>
            <th>Tiêu đề</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Img 1</td>
            <td>Đẹp quá...</td>
            <td>Đã đăng</td>
            <td>19/03/2025</td>
            <td>Theo dõi</td>
        </tr>
        <tr>
            <td>Img 2</td>
            <td>Xinh quá đi...</td>
            <td>Đã đăng</td>
            <td>19/03/2025</td>
            <td>Theo dõi</td>
        </tr>
        <tr>
            <td>Img 3</td>
            <td>Cưng quá...</td>
            <td>Đã đăng</td>
            <td>19/03/2025</td>
            <td>Theo dõi</td>
        </tr>
        <tr>
            <td>Img 4</td>
            <td>Này hay nè...</td>
            <td>Đã đăng</td>
            <td>19/03/2025</td>
            <td>Theo dõi</td>
        </tr>
        <tr>
            <td>Img 5</td>
            <td>Drama đi mn...</td>
            <td>Đã đăng</td>
            <td>19/03/2025</td>
            <td>Theo dõi</td>
        </tr>
    </tbody>
</table> -->

