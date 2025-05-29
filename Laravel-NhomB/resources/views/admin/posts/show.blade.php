@extends('layouts.admin_custom')

@section('content')
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Chi tiết bài viết</h3>
        </div>
        <div class="card-body">
            <h4>{{ $post->title }}</h4>
            <p class="text-muted">Người đăng: <strong>{{ $post->user->name }}</strong></p>
            @if($post->media && $post->media->count() > 0)
                <div class="mt-3">
                    <strong>Media đính kèm:</strong>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        @foreach($post->media as $media)
                            @if(str_contains($media->file_type, 'image'))
                                <img src="{{ asset('storage/' . $media->file_url) }}" style="max-width: 200px; border-radius: 8px;">
                            @elseif(str_contains($media->file_type, 'video'))
                                <video src="{{ asset('storage/' . $media->file_url) }}" style="max-width: 200px; border-radius: 8px;" controls></video>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="mt-4">
                @if($post->status === 'pending')
                    <form action="{{ route('admin.posts.approve', $post) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success">Duyệt</button>
                    </form>
                @endif
                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" style="display:inline-block; margin-left: 10px;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
                <a href="{{ url()->previous() }}" class="btn btn-secondary" style="margin-left: 10px;">Quay lại</a>
            </div>
        </div>
    </div>
</div>
@endsection 