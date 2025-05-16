@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Chi tiết bài viết</h1>
        <a href="{{ route('admin.pending-posts') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

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

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h2>{{ $post->title }}</h2>
                    <p class="text-muted">
                        Đăng bởi: {{ $post->user->name }} | 
                        Ngày đăng: {{ $post->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group">
                        @if($post->status === 'pending')
                            <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Duyệt
                                </button>
                            </form>
                            <form action="{{ route('admin.posts.reject', $post) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times"></i> Từ chối
                                </button>
                            </form>
                        @endif
                        @if($post->status === 'approved')
                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            {!! nl2br(e($post->content)) !!}
                        </div>
                    </div>
                </div>
            </div>

            @if($post->media->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <h4>Hình ảnh đính kèm</h4>
                    <div class="row">
                        @foreach($post->media as $media)
                        <div class="col-md-4 mb-3">
                            <img src="{{ asset('storage/' . $media->file_path) }}" class="img-fluid rounded" alt="Media">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if($post->status === 'pending')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Yêu cầu chỉnh sửa</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.posts.request-edit', $post) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="edit_reason" class="form-label">Lý do cần chỉnh sửa</label>
                                    <textarea class="form-control" id="edit_reason" name="edit_reason" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Gửi yêu cầu chỉnh sửa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($post->comments->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <h4>Bình luận ({{ $post->comments->count() }})</h4>
                    @foreach($post->comments as $comment)
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="card-subtitle mb-2 text-muted">{{ $comment->user->name }}</h6>
                                <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <p class="card-text">{{ $comment->content }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 