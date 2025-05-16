@extends('layouts.app')

@section('content')
<div style="display: flex;">
    <!-- Sidebar -->
    <div style="width: 250px; background-color: #fff; padding: 20px; border-right: 1px solid #ccc;">
        <h2><img src="#" alt="Logo" style="height: 20px;"> Fite</h2>
        <ul style="list-style: none; padding: 0;">
            <li><a href="#">Trang chủ</a></li>
            <li><a href="#">Quản lý bài viết của người dùng</a></li>
            <li><strong style="color: black;">Bài chờ duyệt</strong></li>
            <li><a href="#">Bài đã duyệt</a></li>
            <li><a href="#">Liên hệ Admin</a></li>
            <li><a href="#">Phân tích tương tác</a></li>
        </ul>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" style="margin-top: 20px; width: 100%; background: black; color: white;">Đăng xuất</button>
        </form>
    </div>

    <!-- Main content -->
    <div style="flex: 1; padding: 20px;">
        <h1>Fite hệ thống ADMIN</h1>
        <div style="background-color: black; color: white; padding: 10px; margin: 20px 0;">
            <strong>ADMIN quản lý bài đăng</strong>
        </div>

        <h3>Bài chờ duyệt</h3>
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
                @foreach ($posts as $post)
                    <tr>
                        <td>Img {{ $loop->iteration }}</td>
                        <td>{{ Str::limit($post->title, 10) }}</td>
                        <td>{{ ucfirst($post->status) }}</td>
                        <td>{{ $post->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.posts.show', $post->id) }}">Xem</a>
                            <form action="{{ route('admin.posts.approve', $post->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button style="background-color: lightgreen;">Duyệt</button>
                            </form>
                            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button style="background-color: pink;">Xoá</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 10px;">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
