@extends('layouts.app')

@section('title', 'Đăng Ký')

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

    .register-card {
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

    .register-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 30px;
        text-align: center;
    }

    .register-header i {
        font-size: 3rem;
        margin-bottom: 15px;
        display: block;
    }

    .register-header h2 {
        font-size: 1.8rem;
        font-weight: bold;
        margin: 0;
    }

    .register-body {
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

    .btn-register {
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

    .btn-register:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-register:disabled {
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

    .text-center a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
    }

    .text-center a:hover {
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

    .form-text {
        font-size: 0.8rem;
        color: #888;
        margin-top: 4px;
    }
</style>
@endsection

@section('content')
<div class="gradient-bg">
    <div class="w-100" style="max-width: 520px; padding: 0 20px;">
        <div class="register-card">

            {{-- Header --}}
            <div class="register-header">
                <i class="fas fa-user-plus"></i>
                <h2>Đăng Ký Tài Khoản</h2>
                <p class="mb-0 mt-2 opacity-75">Tạo tài khoản để mua sắm dễ dàng hơn!</p>
            </div>

            {{-- Body --}}
            <div class="register-body">

                {{-- Thông báo lỗi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Lỗi!</strong>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('register') }}" method="POST" id="registerForm">
                    @csrf

                    {{-- Họ tên --}}
                    <div class="form-group mb-4">
                        <label for="ho_ten" class="form-label">
                            <i class="fas fa-id-card me-2" style="color: #667eea;"></i>Họ và Tên <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="ho_ten"
                            id="ho_ten"
                            class="form-control @error('ho_ten') is-invalid @enderror"
                            placeholder="Nguyễn Văn A"
                            value="{{ old('ho_ten') }}"
                            required
                            autofocus
                        >
                        @error('ho_ten')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tên đăng nhập --}}
                    <div class="form-group mb-4">
                        <label for="ten_dang_nhap" class="form-label">
                            <i class="fas fa-user me-2" style="color: #667eea;"></i>Tên Đăng Nhập <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="ten_dang_nhap"
                            id="ten_dang_nhap"
                            class="form-control @error('ten_dang_nhap') is-invalid @enderror"
                            placeholder="vd: nguyen_van_a"
                            value="{{ old('ten_dang_nhap') }}"
                            required
                        >
                        <div class="form-text">Chỉ gồm chữ, số và dấu gạch ngang. Không dùng khoảng trắng.</div>
                        @error('ten_dang_nhap')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group mb-4">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2" style="color: #667eea;"></i>Email <span class="text-danger">*</span>
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="email@example.com"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Số điện thoại --}}
                    <div class="form-group mb-4">
                        <label for="so_dien_thoai" class="form-label">
                            <i class="fas fa-phone me-2" style="color: #667eea;"></i>Số Điện Thoại
                        </label>
                        <input
                            type="text"
                            name="so_dien_thoai"
                            id="so_dien_thoai"
                            class="form-control @error('so_dien_thoai') is-invalid @enderror"
                            placeholder="0901234567"
                            value="{{ old('so_dien_thoai') }}"
                        >
                        @error('so_dien_thoai')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Mật khẩu --}}
                    <div class="form-group mb-4">
                        <label for="mat_khau" class="form-label">
                            <i class="fas fa-lock me-2" style="color: #667eea;"></i>Mật Khẩu <span class="text-danger">*</span>
                        </label>
                        <div class="password-field">
                            <input
                                type="password"
                                name="mat_khau"
                                id="mat_khau"
                                class="form-control @error('mat_khau') is-invalid @enderror"
                                placeholder="Tối thiểu 6 ký tự"
                                required
                            >
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('mat_khau')"></i>
                        </div>
                        @error('mat_khau')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Xác nhận mật khẩu --}}
                    <div class="form-group mb-4">
                        <label for="mat_khau_confirmation" class="form-label">
                            <i class="fas fa-lock me-2" style="color: #667eea;"></i>Xác Nhận Mật Khẩu <span class="text-danger">*</span>
                        </label>
                        <div class="password-field">
                            <input
                                type="password"
                                name="mat_khau_confirmation"
                                id="mat_khau_confirmation"
                                class="form-control"
                                placeholder="Nhập lại mật khẩu"
                                required
                            >
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('mat_khau_confirmation')"></i>
                        </div>
                    </div>

                    {{-- Nút đăng ký --}}
                    <button type="submit" class="btn-register" id="btnRegister">
                        <i class="fas fa-user-plus me-2"></i>
                        <span id="btnText">Tạo Tài Khoản</span>
                    </button>
                </form>

                {{-- Google --}}
                <div class="mt-4">
                    <a href="{{ route('auth.google') }}" class="btn btn-outline-danger w-100">
                        <i class="fab fa-google me-2"></i>
                        Đăng ký bằng Google
                    </a>
                </div>

                <hr class="my-3">

                {{-- Link đăng nhập --}}
                <div class="text-center">
                    <p class="text-muted">
                        Đã có tài khoản?
                        <a href="{{ route('login') }}">Đăng nhập ngay</a>
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

document.getElementById('registerForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnRegister');
    const btnText = document.getElementById('btnText');
    btn.disabled = true;
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
});
</script>
@endsection
