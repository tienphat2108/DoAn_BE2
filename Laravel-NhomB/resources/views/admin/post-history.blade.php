@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Lịch sử đăng bài</h1>
    <form action="{{ route('admin.post-history.filter') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="action">Hành động:</label>
                <select name="action" id="action" class="form-control">
                    <option value="">Tất cả</option>
                    <option value="create">Tạo mới</option>
                    <option value="edit">Chỉnh sửa</option>
                    <option value="delete">Xóa</option>
                    <option value="approve">Duyệt</option>
                    <option value="reject">Từ chối</option>
                    <option value="request_edit">Yêu cầu chỉnh sửa</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="user_id">Người dùng:</label>
                <input type="number" name="user_id" id="user_id" class="form-control" placeholder="ID người dùng">
            </div>
            <div class="col-md-3">
                <label for="post_id">Bài viết:</label>
                <input type="number" name="post_id" id="post_id" class="form-control" placeholder="ID bài viết">
            </div>
            <div class="col-md-3">
                <label for="start_date">Từ ngày:</label>
                <input type="date" name="start_date" id="start_date" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="end_date">Đến ngày:</label>
                <input type="date" name="end_date" id="end_date" class="form-control">
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary form-control">Lọc</button>
            </div>
        </div>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Bài viết</th>
                <th>Người dùng</th>
                <th>Hành động</th>
                <th>Chi tiết</th>
                <th>Thời gian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($histories as $history)
                <tr>
                    <td>{{ $history->id }}</td>
                    <td>{{ $history->post->title ?? 'N/A' }}</td>
                    <td>{{ $history->user->name ?? 'N/A' }}</td>
                    <td>{{ $history->action }}</td>
                    <td>{{ $history->details }}</td>
                    <td>{{ $history->created_at->format('d/m/Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $histories->links() }}
</div>
@endsection 