@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">TIÊU ĐỀ: Kiểm tra Bài viết</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.posts.review', $post) }}" method="POST">
                        @csrf
                        
                        <!-- Dropdown for selecting posts -->
                        <div class="form-group mb-4">
                            <label for="postSelect">Chọn bài viết cần kiểm tra:</label>
                            <select class="form-control" id="postSelect" name="post_id">
                                <option value="">-- Chọn bài viết --</option>
                                @foreach($pendingPosts as $pendingPost)
                                    <option value="{{ $pendingPost->id }}" {{ $post->id == $pendingPost->id ? 'selected' : '' }}>
                                        {{ $pendingPost->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Current post being reviewed -->
                        <div class="form-group mb-4">
                            <label>Bài viết đang kiểm tra:</label>
                            <div class="alert alert-info">
                                {{ $post->title }} (ID: {{ $post->id }})
                            </div>
                        </div>

                        <!-- Post content -->
                        <div class="form-group mb-4">
                            <label>Nội dung bài viết:</label>
                            <div class="content-review-box p-3 border rounded" style="min-height: 200px; max-height: 400px; overflow-y: auto;">
                                {!! $post->content !!}
                            </div>
                        </div>

                        <!-- Review feedback -->
                        <div class="form-group mb-4">
                            <label for="reviewFeedback">Phản hồi kiểm tra:</label>
                            <textarea class="form-control" id="reviewFeedback" name="review_feedback" rows="4" 
                                    placeholder="Nhập nhận xét hoặc đề xuất sửa đổi..."></textarea>
                        </div>

                        <!-- Review status -->
                        <div class="form-group mb-4">
                            <label>Trạng thái kiểm tra:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="review_status" id="statusApproved" value="approved">
                                <label class="form-check-label" for="statusApproved">
                                    Đạt yêu cầu
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="review_status" id="statusNeedsEdit" value="needs_edit">
                                <label class="form-check-label" for="statusNeedsEdit">
                                    Cần sửa đổi
                                </label>
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="form-group d-flex gap-2">
                            <button type="submit" name="action" value="approve" class="btn btn-success">
                                Duyệt Bài
                            </button>
                            <button type="submit" name="action" value="request_edit" class="btn btn-warning">
                                Yêu cầu sửa đổi
                            </button>
                            <a href="{{ route('admin.baidaduyet') }}" class="btn btn-secondary">
                                Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content-review-box {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
}

.form-group {
    margin-bottom: 1.5rem;
}

.btn {
    padding: 0.5rem 1.5rem;
}

.form-check {
    margin-bottom: 0.5rem;
}
</style>

<script>
document.getElementById('postSelect').addEventListener('change', function() {
    if (this.value) {
        window.location.href = "{{ route('admin.posts.review', '') }}/" + this.value;
    }
});
</script>
@endsection 