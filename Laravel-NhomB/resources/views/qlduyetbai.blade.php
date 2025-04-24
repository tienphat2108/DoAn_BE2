<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Fite Admin - Quản lý bài đăng</title>
    <link rel="stylesheet" href="{{ asset('css/qlduyetbai.css') }}">
    <!-- <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    display: flex;
}

.container {
    display: flex;
    width: 100%;
}

.sidebar {
    width: 230px;
    background-color: #f2f2f2;
    padding: 20px;
    border-right: 1px solid #ccc;
}

.sidebar .logo {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 10px;
}

.sidebar nav ul {
    list-style: none;
    padding: 0;
}

.sidebar nav ul li {
    margin-bottom: 10px;
}

.sidebar nav ul li.active a {
    background-color: black;
    color: white;
    padding: 8px;
    border-radius: 5px;
    display: block;
}

.sidebar a {
    text-decoration: none;
    color: black;
    font-weight: bold;
}

.logout-btn {
    margin-top: 20px;
    background-color: black;
    color: white;
    border: none;
    padding: 8px;
    cursor: pointer;
    width: 100%;
}

.content {
    flex: 1;
    padding: 20px;
}

h1 {
    background-color: black;
    color: white;
    padding: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th, td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: left;
}

    </style> -->
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">Fite</div>
            <h2>Fite hệ thống ADMIN</h2>
            <nav>
                <ul>
                    <li><a href="#">TRANG CHỦ</a></li>
                    <li><a href="#">QUẢN LÝ BÀI VIẾT<br> CỦA NGƯỜI DÙNG</a></li>
                    <li><a href="#">BÀI CHỜ DUYỆT</a></li>
                    <li class="active"><a href="#">BÀI ĐÃ DUYỆT</a></li>
                    <li><a href="#">LỊCH ĐĂNG BÀI</a></li>
                    <li><a href="#">PHÂN TÍCH TƯƠNG TÁC</a></li>
                </ul>
            </nav>
            <button class="logout-btn">ĐĂNG XUẤT</button>
        </aside>

        <main class="content">
            <h1>ADMIN quản lý bài đăng</h1>
            <section class="approved-posts">
                <h3>Danh sách bài viết đã kiểm duyệt</h3>
                <table>
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
                        @for ($i = 1; $i <= 5; $i++)
                            <tr>
                                <td>Img {{ $i }}</td>
                                <td>Tiêu đề {{ $i }}</td>
                                <td>Đã đăng</td>
                                <td>19/03/2025</td>
                                <td>Theo dõi</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </section>

            <section class="classify">
                <h3>Phân loại bài viết</h3>
                <table>
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
                            <td>du lịch</td>
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
            </section>
        </main>
    </div>

    <script src="{{ asset('js/qlduyetbai.js') }}">
    </script>




</body>

</html>