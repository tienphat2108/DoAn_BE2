<!DOCTYPE html>
<html>
<head>
    <title>Yêu cầu chỉnh sửa bài viết</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }
        .content {
            padding: 20px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Yêu cầu chỉnh sửa bài viết</h2>
        </div>
        
        <div class="content">
            <p>Xin chào {{ $post->user->name }},</p>
            
            <p>Admin đã xem xét bài viết của bạn và yêu cầu một số chỉnh sửa trước khi có thể được duyệt.</p>
            
            <h3>Thông tin bài viết:</h3>
            <ul>
                <li>Tiêu đề: {{ $post->title }}</li>
                <li>Ngày đăng: {{ $post->created_at->format('d/m/Y H:i') }}</li>
            </ul>

            <h3>Lý do cần chỉnh sửa:</h3>
            <p>{{ $editReason }}</p>

            <p>Vui lòng đăng nhập vào hệ thống và chỉnh sửa bài viết theo yêu cầu trên.</p>
            
            <p>Trân trọng,<br>Admin Team</p>
        </div>

        <div class="footer">
            <p>Đây là email tự động, vui lòng không trả lời email này.</p>
        </div>
    </div>
</body>
</html> 