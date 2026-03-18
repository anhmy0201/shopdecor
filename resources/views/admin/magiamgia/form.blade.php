@extends('layouts.admin')
@section('title', $magiamgia ? 'Sửa Mã Giảm Giá' : 'Tạo Mã Giảm Giá')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">{{ $magiamgia ? 'Sửa Mã: ' . $magiamgia->ma_code : 'Tạo Mã Giảm Giá' }}</h5>
        <small class="text-muted">
            <a href="{{ route('admin.magiamgia.index') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
            </a>
        </small>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">

<form action="{{ $magiamgia ? route('admin.magiamgia.update', $magiamgia->id) : route('admin.magiamgia.store') }}"
      method="POST">
    @csrf
    @if($magiamgia) @method('PUT') @endif

    {{-- Thông tin cơ bản --}}
    <div class="card mb-3">
        <div class="card-header"><i class="fas fa-tag me-2"></i>Thông Tin Mã</div>
        <div class="card-body p-4">

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Mã Code <span class="text-danger">*</span></label>
                    <input type="text" name="ma_code"
                           class="form-control @error('ma_code') is-invalid @enderror text-uppercase"
                           value="{{ old('ma_code', $magiamgia?->ma_code) }}"
                           placeholder="VD: SALE20, FREESHIP..."
                           style="font-family:monospace;font-size:1rem;letter-spacing:2px"
                           oninput="this.value=this.value.toUpperCase()"
                           autofocus>
                    @error('ma_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Chỉ dùng chữ hoa, số và dấu gạch ngang.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Mô Tả</label>
                    <input type="text" name="mo_ta" class="form-control"
                           value="{{ old('mo_ta', $magiamgia?->mo_ta) }}"
                           placeholder="Giảm 20% cho đơn trên 500k...">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Trạng Thái</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="kich_hoat"
                           id="kichHoat" value="1"
                           {{ old('kich_hoat', $magiamgia?->kich_hoat ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="kichHoat">Kích hoạt ngay</label>
                </div>
            </div>

        </div>
    </div>

    {{-- Kiểu & Giá trị giảm --}}
    <div class="card mb-3">
        <div class="card-header"><i class="fas fa-percent me-2"></i>Kiểu & Giá Trị Giảm</div>
        <div class="card-body p-4">

            <div class="mb-3">
                <label class="form-label fw-bold">Kiểu Giảm <span class="text-danger">*</span></label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="kieu_giam"
                               id="phanTram" value="phan_tram"
                               {{ old('kieu_giam', $magiamgia?->kieu_giam ?? 'phan_tram') === 'phan_tram' ? 'checked' : '' }}
                               onchange="toggleKieu()">
                        <label class="form-check-label" for="phanTram">
                            <span class="badge bg-info text-dark">%</span> Phần trăm
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="kieu_giam"
                               id="coDinh" value="co_dinh"
                               {{ old('kieu_giam', $magiamgia?->kieu_giam) === 'co_dinh' ? 'checked' : '' }}
                               onchange="toggleKieu()">
                        <label class="form-check-label" for="coDinh">
                            <span class="badge bg-warning text-dark">đ</span> Số tiền cố định
                        </label>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        Giá Trị Giảm <span class="text-danger">*</span>
                        <span id="don-vi-label" class="text-muted fw-normal">(%)</span>
                    </label>
                    <div class="input-group">
                        <input type="number" name="gia_tri"
                               class="form-control @error('gia_tri') is-invalid @enderror"
                               value="{{ old('gia_tri', $magiamgia?->gia_tri) }}"
                               min="0" step="0.01">
                        <span class="input-group-text" id="don-vi-suffix">%</span>
                    </div>
                    @error('gia_tri')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4" id="wrap-giam-toi-da">
                    <label class="form-label fw-bold">Giảm Tối Đa <span class="text-muted fw-normal">(đ)</span></label>
                    <div class="input-group">
                        <input type="number" name="giam_toi_da" class="form-control"
                               value="{{ old('giam_toi_da', $magiamgia?->giam_toi_da) }}"
                               min="0" step="1000" placeholder="Không giới hạn">
                        <span class="input-group-text">đ</span>
                    </div>
                    <div class="form-text">Để trống = không giới hạn mức giảm.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Đơn Hàng Tối Thiểu</label>
                    <div class="input-group">
                        <input type="number" name="don_hang_toi_thieu" class="form-control"
                               value="{{ old('don_hang_toi_thieu', $magiamgia?->don_hang_toi_thieu ?? 0) }}"
                               min="0" step="1000">
                        <span class="input-group-text">đ</span>
                    </div>
                    <div class="form-text">0 = áp dụng cho mọi đơn.</div>
                </div>
            </div>

        </div>
    </div>

    {{-- Giới hạn & Thời gian --}}
    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-clock me-2"></i>Giới Hạn & Thời Gian</div>
        <div class="card-body p-4">

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Số Lượt Dùng</label>
                    <input type="number" name="so_luong" class="form-control"
                           value="{{ old('so_luong', $magiamgia?->so_luong) }}"
                           min="1" placeholder="Không giới hạn">
                    <div class="form-text">Để trống = không giới hạn.</div>
                    @if($magiamgia)
                        <div class="text-muted small mt-1">
                            Đã dùng: <strong>{{ $magiamgia->da_su_dung }}</strong> lượt
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Ngày Bắt Đầu</label>
                    <input type="datetime-local" name="bat_dau" class="form-control"
                           value="{{ old('bat_dau', $magiamgia?->bat_dau?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Ngày Kết Thúc</label>
                    <input type="datetime-local" name="ket_thuc"
                           class="form-control @error('ket_thuc') is-invalid @enderror"
                           value="{{ old('ket_thuc', $magiamgia?->ket_thuc?->format('Y-m-d\TH:i')) }}">
                    @error('ket_thuc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>{{ $magiamgia ? 'Cập Nhật' : 'Tạo Mã' }}
        </button>
        <a href="{{ route('admin.magiamgia.index') }}" class="btn btn-outline-secondary">Huỷ</a>
    </div>

</form>
</div>
</div>

@endsection

@section('extra-js')
<script>
function toggleKieu() {
    const isPhanTram = document.getElementById('phanTram').checked;
    document.getElementById('don-vi-label').textContent = isPhanTram ? '(%)' : '(đ)';
    document.getElementById('don-vi-suffix').textContent = isPhanTram ? '%' : 'đ';
    document.getElementById('wrap-giam-toi-da').style.display = isPhanTram ? '' : 'none';
}
// Chạy lần đầu
toggleKieu();
</script>
@endsection