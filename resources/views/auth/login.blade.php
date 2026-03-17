@extends('layouts.app')

@section('title', 'Đăng Nhập')

@section('extra-css')
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 0;
    }

    .login-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 30px;
        text-align: center;
    }

    .login-header i {
        font-size: 3rem;
        margin-bottom: 15px;
        display: block;
    }

    .login-header h2 {
        font-size: 1.8rem;
        font-weight: bold;
        margin: 0;
    }

    .login-body {
        padding: 40px;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .form-control {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
        display: block;
    }

    .btn-login {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 12px 30px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-login:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-login:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .password-toggle {
        position: absolute;
        right: 15px;
        top: 38px;
        cursor: pointer;
        color: #667eea;
    }

    .password-field {
        position: relative;
    }

    .text-center.mt-4 a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
    }

    .text-center.mt-4 a:hover {
        text-decoration: underline;
    }

    .alert {
        border-radius: 8px;
        border: none;
        margin-bottom: 20px;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .demo-box {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-left: 4px solid #667eea;
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
    }

    .demo-box h6 {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .demo-account {
        margin-bottom: 10px;
    }

    .demo-account strong {
        color: #333;
        display: block;
        margin-bottom: 5px;
    }

    .demo-account code {
        background: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        color: #667eea;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="gradient-bg">
    <div class="w-100" style="max-width: 450px; padding: 0 20px;">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <i class="fas fa-sign-in-alt"></i>
                <h2>Đăng Nhập</h2>
                <p class="mb-0 mt-2 opacity-75">Chào mừng bạn quay trở lại!</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <!-- Thông báo lỗi -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Lỗi!</strong>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- Thông báo thành công -->
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('login') }}" method="POST" id="loginForm">
                    @csrf

                    <!-- Tên đăng nhập -->
                    <div class="form-group mb-4">
                        <label for="ten_dang_nhap" class="form-label">
                            <i class="fas fa-user me-2" style="color: #667eea;"></i>Tên Đăng Nhập
                        </label>
                        <input 
                            type="text" 
                            name="ten_dang_nhap"
                            id="ten_dang_nhap"
                            class="form-control @error('ten_dang_nhap') is-invalid @enderror"
                            placeholder="Nhập tên đăng nhập"
                            value="{{ old('ten_dang_nhap') }}"
                            required
                            autofocus
                        >
                        @error('ten_dang_nhap')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Mật khẩu -->
                    <div class="form-group mb-4">
                        <label for="mat_khau" class="form-label">
                            <i class="fas fa-lock me-2" style="color: #667eea;"></i>Mật Khẩu
                        </label>
                        <div class="password-field">
                            <input 
                                type="password" 
                                name="mat_khau"
                                id="mat_khau"
                                class="form-control @error('mat_khau') is-invalid @enderror"
                                placeholder="Nhập mật khẩu"
                                required
                            >
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('mat_khau')"></i>
                        </div>
                        @error('mat_khau')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nhớ tôi -->
                    <div class="form-group mb-4">
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="remember" 
                                name="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="remember">
                                Nhớ tôi
                            </label>
                        </div>
                    </div>

                    <!-- Nút đăng nhập -->
                    <button type="submit" class="btn-login" id="btnLogin">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        <span id="btnText">Đăng Nhập</span>
                    </button>
                </form>
                <!-- Google Login Button -->
                <div class="mt-4">
                    <a href="{{ route('auth.google') }}" class="btn btn-outline-danger w-100">
                        <i class="fab fa-google me-2"></i>
                        Đăng nhập bằng Google
                    </a>
                </div>

                <hr class="my-3">

                <!-- Link đăng ký -->
                <div class="text-center mt-4">
                    <p class="text-muted">
                        Chưa có tài khoản? 
                        <a href="{{ route('register') }}">Đăng ký ngay</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = event.target;
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnLogin');
    const btnText = document.getElementById('btnText');
    btn.disabled = true;
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
});
</script>
@endsection