{{-- resources/views/pages/tra-cuu-don-hang.blade.php --}}

@extends('layouts.app')
@section('title', 'Tra cứu đơn hàng')

@section('content')
<div class="container py-5" style="max-width: 520px;">

    <div class="text-center mb-4">
        <h4 class="fw-semibold">Tra cứu đơn hàng</h4>
        <p class="text-muted small">Nhập số điện thoại và mã đơn hàng để kiểm tra trạng thái.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-3 p-4">
        <form action="{{ route('tra-cuu-don-hang.ket-qua') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-medium">Số điện thoại <span class="text-danger">*</span></label>
                <input
                    type="text"
                    name="so_dien_thoai"
                    class="form-control @error('so_dien_thoai') is-invalid @enderror"
                    placeholder="VD: 0901234567"
                    value="{{ old('so_dien_thoai') }}"
                    autofocus
                >
                @error('so_dien_thoai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Mã đơn hàng <span class="text-danger">*</span></label>
                <input
                    type="text"
                    name="ma_don_hang"
                    class="form-control @error('ma_don_hang') is-invalid @enderror"
                    placeholder="VD: DH000123"
                    value="{{ old('ma_don_hang') }}"
                >
                @error('ma_don_hang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Mã đơn có trong email xác nhận hoặc trang đặt hàng thành công.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Tra cứu đơn hàng</button>
        </form>
    </div>

    <div class="text-center mt-3">
        <span class="text-muted small">Đã có tài khoản?</span>
        <a href="{{ route('login') }}" class="small ms-1">Đăng nhập để xem toàn bộ đơn hàng</a>
    </div>

</div>
@endsection
