@extends('layouts.app')

@section('title', 'Quên Mật Khẩu')

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
    .auth-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
        animation: slideUp 0.5s ease-out;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .auth-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 30px;
        text-align: center;
    }
    .auth-header i   { font-size: 3rem; margin-bottom: 15px; display: block; }
    .auth-header h2  { font-size: 1.8rem; font-weight: bold; margin: 0; }
    .auth-body       { padding: 40px; }
    .form-control {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        outline: none;
    }
    .form-control.is-invalid { border-color: #dc3545; }
    .btn-submit {
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
    .btn-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102,126,234,0.4);
    }
    .btn-submit:disabled { opacity: 0.7; cursor: not-allowed; }
    .alert { border-radius: 8px; border: none; margin-bottom: 20px; }
    .alert-success { background-color: #d4edda; color: #155724; }
    .alert-danger  { background-color: #f8d7da; color: #721c24; }
    .hint-box {
        background: rgba(102,126,234,0.08);
        border-left: 4px solid #667eea;
        border-radius: 8px;
        padding: 14px 16px;
        margin-bottom: 24px;
        font-size: .9rem;
        color: #555;
    }
</style>
@endsection

@section('content')
<div class="gradient-bg">
    <div class="w-100" style="max-width: 450px; padding: 0 20px;">
        <div class="auth-card">

            <div class="auth-header">
                <i class="fas fa-key"></i>
                <h2>Quên Mật Khẩu</h2>
                <p class="mb-0 mt-2 opacity-75">Nhập email để nhận link đặt lại mật khẩu</p>
            </div>

            <div class="auth-body">

                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="hint-box">
                    <i class="fas fa-info-circle me-2" style="color:#667eea"></i>
                    Nhập địa chỉ email bạn đã đăng ký. Chúng tôi sẽ gửi link đặt lại mật khẩu có hiệu lực trong <strong>60 phút</strong>.
                </div>

                <form action="{{ route('password.forgot.send') }}" method="POST" id="forgotForm">
                    @csrf

                    <div class="form-group mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-envelope me-2" style="color:#667eea"></i>Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Nhập email của bạn"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                        @error('email')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit" id="btnSubmit">
                        <i class="fas fa-paper-plane me-2"></i>
                        <span id="btnText">Gửi Link Đặt Lại</span>
                    </button>
                </form>

                <hr class="my-4">

                <div class="text-center">
                    <a href="{{ route('login') }}" style="color:#667eea; font-weight:600; text-decoration:none;">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại đăng nhập
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script>
document.getElementById('forgotForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    document.getElementById('btnText').innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang gửi...';
});
</script>
@endsection
