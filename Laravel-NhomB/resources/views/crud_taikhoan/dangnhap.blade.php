<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dangnhap.css') }}">
    <style>
        .error-message {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-left">
            <div class="login-logo">
                <h1>Fite</h1>
                <p>Fite giúp bạn kết nối và chia sẻ với mọi người trong cuộc sống của bạn</p>
            </div>
        </div>
        <div class="login-right">
            <div class="login-form-container">
                <h2>Welcome</h2>
                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf
                    <div class="form-group">
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" id="password" placeholder="Mật khẩu" required>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-login">Đăng nhập</button>
                        <button type="button" class="btn btn-register"
                            onclick="window.location.href='{{ route('register') }}'">Đăng ký</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/dangnhap.js') }}"></script>
</body>

</html>