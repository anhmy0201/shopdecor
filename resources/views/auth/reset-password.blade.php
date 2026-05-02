@extends('layouts.app')

@section('title', 'Đặt Lại Mật Khẩu')

@section('extra-css')
<style>
    .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center; padding: 40px 0; }
    .auth-card { background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); overflow: hidden; animation: slideUp 0.5s ease-out; }
    @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .auth-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 30px; text-align: center; }
    .auth-header i { font-size: 3rem; margin-bottom: 15px; display: block; }
    .auth-header h2 { font-size: 1.8rem; font-weight: bold; margin: 0; }
    .auth-body { padding: 40px; }
    .form-control { border: 2px solid #e0e0e0; border-radius: 8px; padding: 12px 15px; font-size: 1rem; transition: all 0.3s ease; }
    .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.1); outline: none; }
    .form-control.is-invalid { border-color: #dc3545; }
    .password-field { position: relative; }
    .password-toggle { position: absolute; right: 15px; top: 38px; cursor: pointer; color: #667eea; }
    .btn-submit { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white; padding: 12px 30px; font-size: 1rem; font-weight: 600; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; width: 100%; }
    .btn-submit:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(102,126,234,0.4); }
    .btn-submit:disabled { opacity: 0.7; cursor: not-allowed; }
    .alert { border-radius: 8px; border: none; margin-bottom: 20px; }
    .alert-danger { background-color: #f8d7da; color: #721c24; }
    .strength-bar { height: 4px; border-radius: 2px; margin-top: 6px; transition: all 0.3s; }
    .strength-text { font-size: .78rem; margin-top: 4px; }
</style>
@endsection

@section('content')
<div class="gradient-bg">
    <div class="w-100" style="max-width: 450px; padding: 0 20px;">
        <div class="auth-card">
            <div class="auth-header">
                <i class="fas fa-lock-open"></i>
                <h2>Đặt Lại Mật Khẩu</h2>
                <p class="mb-0 mt-2 opacity-75">Nhập mật khẩu mới cho tài khoản của bạn</p>
            </div>
            <div class="auth-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('password.reset') }}" method="POST" id="resetForm">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="form-group mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-lock me-2" style="color:#667eea"></i>Mật Khẩu Mới
                        </label>
                        <div class="password-field">
                            <input type="password" name="mat_khau" id="mat_khau"
                                class="form-control @error('mat_khau') is-invalid @enderror"
                                placeholder="Ít nhất 6 ký tự" required autofocus
                                oninput="checkStrength(this.value)">
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('mat_khau', this)"></i>
                        </div>
                        <div class="strength-bar w-0" id="strengthBar"></div>
                        <div class="strength-text text-muted" id="strengthText"></div>
                        @error('mat_khau')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-lock me-2" style="color:#667eea"></i>Xác Nhận Mật Khẩu
                        </label>
                        <div class="password-field">
                            <input type="password" name="mat_khau_confirmation" id="mat_khau_confirmation"
                                class="form-control @error('mat_khau_confirmation') is-invalid @enderror"
                                placeholder="Nhập lại mật khẩu mới" required>
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('mat_khau_confirmation', this)"></i>
                        </div>
                        @error('mat_khau_confirmation')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit" id="btnSubmit">
                        <i class="fas fa-check me-2"></i>
                        <span id="btnText">Đặt Lại Mật Khẩu</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script>
function togglePassword(id, icon) {
    const f = document.getElementById(id);
    if (f.type === 'password') { f.type = 'text'; icon.classList.replace('fa-eye','fa-eye-slash'); }
    else { f.type = 'password'; icon.classList.replace('fa-eye-slash','fa-eye'); }
}
function checkStrength(val) {
    const bar = document.getElementById('strengthBar');
    const txt = document.getElementById('strengthText');
    if (!val) { bar.style.width='0'; txt.textContent=''; return; }
    let s = 0;
    if (val.length >= 6) s++;
    if (val.length >= 10) s++;
    if (/[A-Z]/.test(val)) s++;
    if (/[0-9]/.test(val)) s++;
    if (/[^A-Za-z0-9]/.test(val)) s++;
    const lv = [
        {w:'20%',c:'#e74c3c',l:'Rất yếu'},
        {w:'40%',c:'#e67e22',l:'Yếu'},
        {w:'60%',c:'#f1c40f',l:'Trung bình'},
        {w:'80%',c:'#2ecc71',l:'Mạnh'},
        {w:'100%',c:'#27ae60',l:'Rất mạnh'},
    ][Math.max(0, Math.min(s-1, 4))];
    bar.style.width = lv.w; bar.style.background = lv.c;
    txt.textContent = 'Độ mạnh: ' + lv.l; txt.style.color = lv.c;
}
document.getElementById('resetForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    document.getElementById('btnText').innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
});
</script>
@endsection
