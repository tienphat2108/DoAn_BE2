<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết bài viết</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f7f7f7;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 32px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 32px 28px;
        }
        h2 {
            margin-top: 0;
            color: #222;
            font-size: 2rem;
        }
        .info-row {
            margin-bottom: 12px;
            font-size: 1.1rem;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 1rem;
            background: #e0e0e0;
            color: #222;
        }
        .status.approved { background: #d1e7dd; color: #0f5132; }
        .status.pending { background: #fff3cd; color: #664d03; }
        .status.published { background: #cfe2ff; color: #084298; }
        .section-title {
            margin-top: 32px;
            font-size: 1.3rem;
            font-weight: bold;
            color: #1976d2;
            border-bottom: 2px solid #1976d2;
            padding-bottom: 6px;
        }
        .evaluations-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        .evaluations-table th, .evaluations-table td {
            border: 1px solid #e0e0e0;
            padding: 10px 8px;
            text-align: center;
        }
        .evaluations-table th {
            background: #f7f7f7;
            font-weight: bold;
        }
        .star {
            color: #ffd700;
            font-size: 1.1em;
        }
        .no-eval {
            color: #888;
            margin: 18px 0;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 18px;
            color: #1976d2;
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .content-box {
            background: #f9f9f9;
            border-radius: 6px;
            padding: 14px 18px;
            margin-bottom: 18px;
            font-size: 1.08rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('admin.baidaduyet') }}" class="back-link"><i class="fa fa-arrow-left"></i> Quay lại danh sách</a>
        <h2>Chi tiết bài viết: {{ $post->title }}</h2>
        <div class="info-row"><span class="label">ID:</span> {{ $post->id }}</div>
        <div class="info-row">
            <span class="label">Trạng thái:</span>
            <span class="status {{ $post->status }}">{{ $post->status }}</span>
        </div>
        <div class="info-row">
            <span class="label">Nội dung:</span>
            <div class="content-box">{!! nl2br(e($post->content ?? '')) !!}</div>
        </div>

        <div class="section-title"><i class="fa fa-star"></i> Đánh giá chất lượng nội dung</div>
        @if($post->evaluations->count())
            <table class="evaluations-table">
                <thead>
                    <tr>
                        <th>Người đánh giá</th>
                        <th>Số sao</th>
                        <th>Nhận xét</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($post->evaluations as $eval)
                        <tr>
                            <td>{{ $eval->user->name ?? 'Admin' }}</td>
                            <td>
                                @for($i=1; $i<=$eval->rating; $i++)
                                    <span class="star"><i class="fa fa-star"></i></span>
                                @endfor
                            </td>
                            <td style="text-align:left;">{{ $eval->comment }}</td>
                            <td>{{ $eval->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-eval">Chưa có đánh giá nào cho bài viết này.</div>
        @endif
    </div>
</body>
</html>
