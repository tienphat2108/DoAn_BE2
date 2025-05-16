@extends('layouts.admin_custom')

@section('content')
<h2>Danh sách bài viết chờ duyệt</h2>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="posts-table">
    <table class="table-users">
        <thead>
            <tr>
                <th>ID</th>
                <th>Người đăng</th>
                <th>Tiêu đề</th>
                <th>Nội dung</th>
                <th>Ngày đăng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->user->name }}</td>
                <td>{{ Str::limit($post->title, 50) }}</td>
                <td>{{ Str::limit($post->content, 100) }}</td>
                <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                        <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i> Duyệt
                            </button>
                        </form>
                        <form action="{{ route('admin.posts.reject', $post) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-times"></i> Từ chối
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>
@endsection 