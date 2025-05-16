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
                    <div class="btn-group" role="group" style="display: flex; flex-direction: column; align-items: center; gap: 6px;">
                        <a href="{{ route('admin.posts.show', $post) }}" class="btn-outline-delete" style="width: 220px; text-align: center; justify-content: center;">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                        <div style="display: flex; gap: 8px; margin-top: 6px;">
                            <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="d-inline" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" style="background:#28a745; color:#fff; border:none; padding:8px 18px; border-radius:6px; font-size:15px; display:inline-flex; align-items:center; gap:6px;">
                                    <span style="font-weight:bold; font-size:18px;">&#10003;</span> Duyệt
                                </button>
                            </form>
                            <form action="{{ route('admin.posts.reject', $post) }}" method="POST" class="d-inline" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" style="background:#dc3545; color:#fff; border:none; padding:8px 18px; border-radius:6px; font-size:15px; display:inline-flex; align-items:center; gap:6px;">
                                    <span style="font-weight:bold; font-size:18px;">&#10007;</span> Từ chối
                                </button>
                            </form>
                        </div>
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