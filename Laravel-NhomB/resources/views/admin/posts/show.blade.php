@extends('layouts.admin_custom')

@section('content')
<h2>Chi tiết bài viết</h2>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<div class="card mb-4">
    <div class="card-body">
        <h3>{{ $post->title }}</h3>
        <p class="text-muted">Đăng bởi: {{ $post->user->name }} | Ngày đăng: {{ $post->created_at->format('d/m/Y H:i') }}</p>
        <div>{!! nl2br(e($post->content)) !!}</div>
        @if($post->media->count() > 0)
            <div class="mt-3">
                <h4>Hình ảnh đính kèm</h4>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @foreach($post->media as $media)
                        @if(str_contains($media->file_type, 'image'))
                            <img src="{{ asset('storage/' . $media->file_path) }}" style="max-width: 150px; border-radius: 8px;">
                        @elseif(str_contains($media->file_type, 'video'))
                            <video src="{{ asset('storage/' . $media->file_path) }}" style="max-width: 150px; border-radius: 8px;" controls></video>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
        <div class="mt-3">
            @if($post->status === 'pending' || $post->status === 'waiting')
                <form action="{{ route('admin.posts.approve', $post) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-success" style="background:#28a745; color:#fff; border:none; padding:8px 18px; border-radius:6px; font-size:16px; display:inline-flex; align-items:center; gap:6px;">
                        <span style="font-weight:bold; font-size:18px;">&#10003;</span> Duyệt
                    </button>
                </form>
                <form action="{{ route('admin.posts.reject', $post) }}" method="POST" style="display:inline-block; margin-left:8px;">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="background:#dc3545; color:#fff; border:none; padding:8px 18px; border-radius:6px; font-size:16px; display:inline-flex; align-items:center; gap:6px;">
                        <span style="font-weight:bold; font-size:18px;">&#10007;</span> Từ chối
                    </button>
                </form>
                <a href="{{ url()->previous() }}" class="btn btn-secondary" style="display:inline-block; margin-left: 10px;">Trở về</a>
            @endif
            @if($post->status === 'approved')
                <form action="{{ route('admin.deletePost', $post) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            @endif
        </div>
    </div>
</div>
@if($post->comments->count() > 0)
    <div class="card mt-4">
        <div class="card-body">
            <h4>Bình luận ({{ $post->comments->count() }})</h4>
            @foreach($post->comments as $comment)
                <div class="border-bottom mb-2 pb-2">
                    <strong>{{ $comment->user->name }}</strong> <span class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                    <div>{{ $comment->content }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection 