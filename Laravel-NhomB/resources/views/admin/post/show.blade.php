@extends('layouts.app')

@section('content')
    <div style="padding: 20px;">
        <h2>Chi tiết bài viết</h2>
        <hr>

        <p><strong>Tiêu đề:</strong> {{ $post->title }}</p>

        <p><strong>Nội dung:</strong></p>
        <div style="border: 1px solid #ccc; padding: 10px; background: #f9f9f9;">
            {{ $post->content }}
        </div>

        <p><strong>Trạng thái:</strong> {{ $post->status }}</p>

        <p><strong>Ghi chú từ Admin:</strong> {{ $post->admin_note ?? 'Không có' }}</p>

        <p><strong>Ngày tạo:</strong> {{ $post->created_at->format('d/m/Y H:i') }}</p>

        @if ($post->image)
            <p><strong>Hình ảnh:</strong></p>
            <img src="{{ asset('uploads/' . $post->image) }}" alt="Hình ảnh bài viết" width="300">
        @endif

        <br><br>
        <a href="{{ route('admin.posts.pending') }}" style="padding: 8px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;">← Quay lại danh sách</a>
    </div>
@endsection
