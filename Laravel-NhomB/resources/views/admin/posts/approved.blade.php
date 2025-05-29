@extends('layouts.admin_custom')

@section('content')
<div class="container">
    <h2 class="mb-4">Danh sách bài đã duyệt</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Người đăng</th>
                <th>Tiêu đề</th>
                <th>Nội dung</th>
                <th>Ngày đăng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->user->name ?? 'Không rõ' }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ Str::limit($post->content, 100) }}</td>
                <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
