@extends('layouts.admin')
@section('title', 'Sửa Người Dùng')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Sửa: {{ $nguoidung->ho_ten }}</h5>
        <small class="text-muted">
            <a href="{{ route('admin.nguoidung.show', $nguoidung) }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i>Quay lại chi tiết
            </a>
        </small>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-lg-7">

<form action="{{ route('admin.nguoidung.update', $nguoidung) }}" method="POST">
    @csrf @method('PUT')

    <div class="card mb-3">
        <div class="card-header"><i class="fas fa-user me-2"></i>Thông Tin Cá Nhân</div>
        <div class="card-body p-4">

            <div class="mb-3">
                <label class="form-label fw-bold">Họ Tên <span class="text-danger">*</span></label>
                <input type="text" name="ho_ten"
                       class="form-control @error('ho_ten') is-invalid @enderror"
                       value="{{ old('ho_ten', $nguoidung->ho_ten) }}" autofocus>
                @error('ho_ten')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $nguoidung->email) }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Số Điện Thoại</label>
                    <input type="text" name="so_dien_thoai" class="form-control"
                           value="{{ old('so_dien_thoai', $nguoidung->so_dien_thoai) }}">
                </div>
            </div>

            <div class="mb-3 p-3 bg-light rounded small text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Tên đăng nhập: <code>{{ $nguoidung->ten_dang_nhap }}</code>
                (không thể thay đổi)
            </div>

        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header"><i class="fas fa-shield-alt me-2"></i>Quyền Hạn & Trạng Thái</div>
        <div class="card-body p-4">

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Quyền Hạn <span class="text-danger">*</span></label>
                    <select name="quyen_han" class="form-select @error('quyen_han') is-invalid @enderror">
                        <option value="0" {{ old('quyen_han', $nguoidung->quyen_han) == 0 ? 'selected' : '' }}>
                            Khách hàng
                        </option>
                        <option value="1" {{ old('quyen_han', $nguoidung->quyen_han) == 1 ? 'selected' : '' }}>
                            Nhân viên
                        </option>
                        <option value="2" {{ old('quyen_han', $nguoidung->quyen_han) == 2 ? 'selected' : '' }}>
                            Admin
                        </option>
                    </select>
                    @error('quyen_han')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="kich_hoat"
                               id="kichHoat" value="1"
                               {{ old('kich_hoat', $nguoidung->kich_hoat) ? 'checked' : '' }}
                               {{ $nguoidung->id === auth()->id() ? 'disabled' : '' }}>
                        <label class="form-check-label fw-bold" for="kichHoat">Kích hoạt tài khoản</label>
                    </div>
                    @if($nguoidung->id === auth()->id())
                        <input type="hidden" name="kich_hoat" value="1">
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-key me-2"></i>Đổi Mật Khẩu</div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Mật Khẩu Mới</label>
                    <input type="password" name="mat_khau"
                           class="form-control @error('mat_khau') is-invalid @enderror"
                           placeholder="Để trống nếu không đổi">
                    @error('mat_khau')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Xác Nhận Mật Khẩu</label>
                    <input type="password" name="mat_khau_confirmation"
                           class="form-control" placeholder="Nhập lại mật khẩu mới">
                </div>
            </div>
            <div class="form-text mt-2">Để trống nếu không muốn thay đổi mật khẩu.</div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>Cập Nhật
        </button>
        <a href="{{ route('admin.nguoidung.show', $nguoidung) }}" class="btn btn-outline-secondary">Huỷ</a>
    </div>

</form>
</div>
</div>

@endsection