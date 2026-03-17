@extends('layouts.app')

@section('title', 'Thanh Toán')

@section('extra-css')
<style>
    .breadcrumb-bar {
        background: #eaf4fb;
        border-bottom: 1px solid #d0e8f5;
        padding: 8px 0;
        font-size: 0.82rem;
    }
    .breadcrumb-bar a { color: #1a5276; text-decoration: none; }
    .breadcrumb-bar a:hover { text-decoration: underline; }
    .breadcrumb-bar span { color: #888; }
    .main-content { padding: 24px 0 40px; }

    .block-title {
        background: #1a5276;
        color: #fff;
        font-size: 0.88rem;
        font-weight: 700;
        padding: 9px 15px;
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
    }
    .form-block { border: 1px solid #ddd; background: #fff; margin-bottom: 18px; }
    .form-block-body { padding: 18px; }
    .form-label { font-size: 0.82rem; font-weight: 600; color: #444; margin-bottom: 4px; }
    .form-label .req { color: #e74c3c; }
    .form-control-custom {
        width: 100%;
        border: 1px solid #ddd;
        padding: 8px 12px;
        font-size: 0.85rem;
        color: #333;
        outline: none;
        transition: border-color 0.15s;
        border-radius: 0;
        background: #fff;
    }
    .form-control-custom:focus { border-color: #1a5276; }
    .form-control-custom.is-invalid { border-color: #e74c3c; }
    textarea.form-control-custom { resize: vertical; min-height: 80px; }

    /* ===== DANH SÁCH ĐỊA CHỈ ===== */
    .dia-chi-list { border-bottom: 1px dashed #ddd; padding-bottom: 14px; margin-bottom: 16px; }
    .dia-chi-label { font-size: 0.82rem; font-weight: 700; color: #444; margin-bottom: 10px; }
    .dia-chi-item {
        border: 2px solid #ddd;
        padding: 10px 14px;
        cursor: pointer;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        transition: all 0.15s;
        margin-bottom: 8px;
        background: #fff;
    }
    .dia-chi-item:hover { border-color: #1a5276; background: #f8fbfe; }
    .dia-chi-item.selected { border-color: #1a5276; background: #eaf4fb; }
    .dia-chi-item input[type="radio"] { accent-color: #1a5276; margin-top: 3px; flex-shrink: 0; }
    .dia-chi-item-name { font-size: 0.85rem; font-weight: 700; color: #222; }
    .dia-chi-item-detail { font-size: 0.78rem; color: #666; margin-top: 2px; line-height: 1.5; }
    .badge-mac-dinh {
        background: #1a5276; color: #fff;
        font-size: 0.65rem; font-weight: 700;
        padding: 1px 7px; margin-left: 6px; vertical-align: middle;
    }
    .btn-dia-chi-moi {
        background: none;
        border: 2px dashed #ddd;
        color: #888;
        font-size: 0.82rem;
        padding: 8px 14px;
        cursor: pointer;
        width: 100%;
        text-align: left;
        transition: all 0.15s;
        margin-top: 4px;
    }
    .btn-dia-chi-moi:hover { border-color: #1a5276; color: #1a5276; }

    /* ===== PHƯƠNG THỨC THANH TOÁN ===== */
    .payment-option {
        border: 2px solid #ddd; padding: 12px 16px; cursor: pointer;
        display: flex; align-items: center; gap: 12px;
        transition: all 0.15s; margin-bottom: 10px; background: #fff;
    }
    .payment-option:last-child { margin-bottom: 0; }
    .payment-option:hover { border-color: #1a5276; background: #f8fbfe; }
    .payment-option.selected { border-color: #1a5276; background: #eaf4fb; }
    .payment-option input[type="radio"] { accent-color: #1a5276; width: 16px; height: 16px; flex-shrink: 0; }
    .payment-option-icon { width: 36px; height: 36px; background: #1a5276; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
    .payment-option.cod .payment-option-icon { background: #27ae60; }
    .payment-option-info strong { font-size: 0.88rem; color: #222; display: block; }
    .payment-option-info span { font-size: 0.78rem; color: #888; }

    /* ===== MÃ GIẢM GIÁ ===== */
    .voucher-box { display: flex; gap: 8px; }
    .voucher-box input { flex: 1; border: 1px solid #ddd; padding: 8px 12px; font-size: 0.85rem; outline: none; text-transform: uppercase; }
    .voucher-box input:focus { border-color: #1a5276; }
    .btn-ap-ma { background: #1a5276; color: #fff; border: none; padding: 8px 18px; font-size: 0.82rem; font-weight: 600; cursor: pointer; white-space: nowrap; }
    .btn-ap-ma:hover { background: #154360; }
    .voucher-result { margin-top: 8px; font-size: 0.82rem; display: none; }
    .voucher-result.success { color: #27ae60; }
    .voucher-result.error { color: #e74c3c; }

    /* ===== TỔNG ĐƠN HÀNG ===== */
    .order-summary { border: 1px solid #ddd; background: #fff; }
    .order-summary-title { background: #1a5276; color: #fff; font-weight: 700; font-size: 0.88rem; padding: 9px 15px; text-transform: uppercase; }
    .order-summary-body { padding: 15px; }
    .order-item { display: flex; gap: 10px; padding: 10px 0; border-bottom: 1px dashed #eee; align-items: center; }
    .order-item:last-child { border-bottom: none; }
    .order-item img { width: 54px; height: 54px; object-fit: cover; border: 1px solid #ddd; flex-shrink: 0; }
    .order-item-name { font-size: 0.8rem; font-weight: 600; color: #222; line-height: 1.4; flex: 1; }
    .order-item-bienthe { font-size: 0.72rem; color: #888; margin-top: 2px; }
    .order-item-price { font-size: 0.82rem; font-weight: 700; color: #e74c3c; white-space: nowrap; }
    .order-item-qty { font-size: 0.75rem; color: #888; }
    .summary-row { display: flex; justify-content: space-between; padding: 7px 0; font-size: 0.85rem; border-bottom: 1px dashed #eee; }
    .summary-row:last-child { border-bottom: none; }
    .summary-row.discount { color: #27ae60; }
    .summary-row.total { font-weight: 700; font-size: 1rem; color: #e74c3c; border-top: 2px solid #ddd; border-bottom: none; padding-top: 10px; margin-top: 4px; }
    .btn-dat-hang { display: block; width: 100%; background: #e74c3c; color: #fff; border: none; padding: 13px; font-size: 0.95rem; font-weight: 700; text-align: center; cursor: pointer; margin-top: 14px; transition: background 0.2s; }
    .btn-dat-hang:hover { background: #c0392b; }
    .btn-dat-hang:disabled { background: #ccc; cursor: not-allowed; }
    .error-msg { color: #e74c3c; font-size: 0.75rem; margin-top: 3px; display: block; }

    #formDiaChiMoi { display: none; }
    #formDiaChiMoi.show { display: block; }
</style>
@endsection

@section('content')

<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Trang chủ</a>
        <span class="mx-2">›</span>
        <a href="{{ route('gio-hang') }}">Giỏ hàng</a>
        <span class="mx-2">›</span>
        <span>Thanh toán</span>
    </div>
</div>

<div class="main-content">
    <div class="container">
        <form action="{{ url('/thanh-toan') }}" method="POST" id="formThanhToan">
            @csrf
            <input type="hidden" name="magiamgia_id" id="magiamgiaId" value="">

            <div class="row">

                {{-- ===== CỘT TRÁI ===== --}}
                <div class="col-lg-7 mb-4">

                    <div class="form-block">
                        <div class="block-title">
                            <i class="fas fa-map-marker-alt"></i> Thông tin giao hàng
                        </div>
                        <div class="form-block-body">

                            {{-- Danh sách địa chỉ đã lưu --}}
                            @if($diaChis->count() > 0)
                            <div class="dia-chi-list">
                                <div class="dia-chi-label">
                                    <i class="fas fa-bookmark me-1" style="color:#1a5276;"></i>Địa chỉ đã lưu
                                </div>
                                @foreach($diaChis as $dc)
                                <div class="dia-chi-item {{ $dc->mac_dinh ? 'selected' : '' }}"
                                     onclick="chonDiaChi(this, {{ $dc->id }})">
                                    <input type="radio" name="_dia_chi_chon" value="{{ $dc->id }}"
                                           {{ $dc->mac_dinh ? 'checked' : '' }}>
                                    <div style="flex:1;">
                                        <div class="dia-chi-item-name">
                                            {{ $dc->ho_ten }}
                                            <span style="font-weight:400;color:#888;font-size:0.8rem;">— {{ $dc->so_dien_thoai }}</span>
                                            @if($dc->mac_dinh)
                                                <span class="badge-mac-dinh">Mặc định</span>
                                            @endif
                                        </div>
                                        <div class="dia-chi-item-detail">
                                            {{ $dc->dia_chi_chi_tiet }}, {{ $dc->phuong_xa }},
                                            {{ $dc->quan_huyen }}, {{ $dc->tinh_thanh }}
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <button type="button" class="btn-dia-chi-moi" onclick="toggleFormMoi()">
                                    <i class="fas fa-plus me-1"></i>Giao đến địa chỉ khác
                                </button>
                            </div>
                            @endif

                            {{-- Form nhập địa chỉ (luôn hiện nếu chưa có địa chỉ lưu) --}}
                            <div id="formDiaChiMoi" class="{{ $diaChis->count() === 0 ? 'show' : '' }}">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="form-label">Họ và tên <span class="req">*</span></label>
                                        <input type="text" name="ten_nguoi_nhan" id="ten_nguoi_nhan"
                                               class="form-control-custom @error('ten_nguoi_nhan') is-invalid @enderror"
                                               value="{{ old('ten_nguoi_nhan', $diaChiMacDinh?->ho_ten ?? Auth::user()?->ho_ten) }}"
                                               placeholder="Nguyễn Văn A">
                                        @error('ten_nguoi_nhan')<span class="error-msg">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Số điện thoại <span class="req">*</span></label>
                                        <input type="text" name="so_dien_thoai" id="so_dien_thoai"
                                               class="form-control-custom @error('so_dien_thoai') is-invalid @enderror"
                                               value="{{ old('so_dien_thoai', $diaChiMacDinh?->so_dien_thoai ?? Auth::user()?->so_dien_thoai) }}"
                                               placeholder="0901234567">
                                        @error('so_dien_thoai')<span class="error-msg">{{ $message }}</span>@enderror
                                    </div>

                                    {{-- Email — chỉ hiện cho khách chưa đăng nhập --}}
                                    @guest
                                    <div class="col-12">
                                        <label class="form-label">
                                            Email nhận xác nhận đơn
                                            <span style="font-weight:400;color:#888;font-size:0.78rem;">(không bắt buộc)</span>
                                        </label>
                                        <input type="email" name="email" id="email"
                                               class="form-control-custom @error('email') is-invalid @enderror"
                                               value="{{ old('email') }}"
                                               placeholder="example@email.com">
                                        @error('email')<span class="error-msg">{{ $message }}</span>@enderror
                                    </div>
                                    @endguest
                                    <div class="col-12">
                                        <label class="form-label">Địa chỉ chi tiết <span class="req">*</span></label>
                                        <input type="text" name="dia_chi_chi_tiet" id="dia_chi_chi_tiet"
                                               class="form-control-custom @error('dia_chi_chi_tiet') is-invalid @enderror"
                                               value="{{ old('dia_chi_chi_tiet', $diaChiMacDinh?->dia_chi_chi_tiet) }}"
                                               placeholder="Số nhà, tên đường, tổ/ấp...">
                                        @error('dia_chi_chi_tiet')<span class="error-msg">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label">Phường/Xã <span class="req">*</span></label>
                                        <input type="text" name="phuong_xa" id="phuong_xa"
                                               class="form-control-custom @error('phuong_xa') is-invalid @enderror"
                                               value="{{ old('phuong_xa', $diaChiMacDinh?->phuong_xa) }}"
                                               placeholder="Phường Mỹ Long">
                                        @error('phuong_xa')<span class="error-msg">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label">Quận/Huyện <span class="req">*</span></label>
                                        <input type="text" name="quan_huyen" id="quan_huyen"
                                               class="form-control-custom @error('quan_huyen') is-invalid @enderror"
                                               value="{{ old('quan_huyen', $diaChiMacDinh?->quan_huyen) }}"
                                               placeholder="TP. Long Xuyên">
                                        @error('quan_huyen')<span class="error-msg">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label">Tỉnh/Thành phố <span class="req">*</span></label>
                                        <input type="text" name="tinh_thanh" id="tinh_thanh"
                                               class="form-control-custom @error('tinh_thanh') is-invalid @enderror"
                                               value="{{ old('tinh_thanh', $diaChiMacDinh?->tinh_thanh) }}"
                                               placeholder="An Giang">
                                        @error('tinh_thanh')<span class="error-msg">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Phương thức thanh toán --}}
                    <div class="form-block">
                        <div class="block-title">
                            <i class="fas fa-credit-card"></i> Phương thức thanh toán
                        </div>
                        <div class="form-block-body">
                            <label class="payment-option cod selected" onclick="chonThanhToan(this)">
                                <input type="radio" name="phuong_thuc_thanhtoan" value="cod" checked>
                                <div class="payment-option-icon cod"><i class="fas fa-money-bill-wave"></i></div>
                                <div class="payment-option-info">
                                    <strong>Thanh toán khi nhận hàng (COD)</strong>
                                    <span>Thanh toán bằng tiền mặt khi nhận được hàng</span>
                                </div>
                            </label>
                            <label class="payment-option" onclick="chonThanhToan(this)">
                                <input type="radio" name="phuong_thuc_thanhtoan" value="chuyen_khoan">
                                <div class="payment-option-icon"><i class="fas fa-university"></i></div>
                                <div class="payment-option-info">
                                    <strong>Chuyển khoản ngân hàng</strong>
                                    <span>Vietcombank — 1234567890 — NGUYEN VAN A</span>
                                </div>
                            </label>
                            @error('phuong_thuc_thanhtoan')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    {{-- Mã giảm giá --}}
                    <div class="form-block">
                        <div class="block-title">
                            <i class="fas fa-tag"></i> Mã giảm giá
                        </div>
                        <div class="form-block-body">
                            <div class="voucher-box">
                                <input type="text" id="maCode" placeholder="Nhập mã giảm giá..." maxlength="50">
                                <button type="button" class="btn-ap-ma" onclick="apMaGiamGia()">
                                    <i class="fas fa-check me-1"></i>Áp dụng
                                </button>
                            </div>
                            <div class="voucher-result" id="voucherResult"></div>
                        </div>
                    </div>

                    {{-- Ghi chú --}}
                    <div class="form-block">
                        <div class="block-title">
                            <i class="fas fa-pen"></i> Ghi chú đơn hàng
                        </div>
                        <div class="form-block-body">
                            <textarea name="ghi_chu_khach" class="form-control-custom"
                                      placeholder="Ghi chú thêm về đơn hàng, ví dụ: giao giờ hành chính...">{{ old('ghi_chu_khach') }}</textarea>
                        </div>
                    </div>

                </div>

                {{-- ===== CỘT PHẢI: ĐƠN HÀNG ===== --}}
                <div class="col-lg-5">
                    <div class="order-summary">
                        <div class="order-summary-title">
                            <i class="fas fa-receipt me-2"></i>Đơn hàng ({{ $giohang->chitiets->count() }} sản phẩm)
                        </div>
                        <div class="order-summary-body">
                            @foreach($giohang->chitiets as $ct)
                            <div class="order-item">
                                <img src="{{ asset($ct->sanpham->anhChinh?->duong_dan_anh ?? 'images/no-image.png') }}"
                                     alt="{{ $ct->sanpham->ten_san_pham }}">
                                <div class="order-item-name flex-1">
                                    {{ $ct->sanpham->ten_san_pham }}
                                    @if($ct->bienthe)
                                        <div class="order-item-bienthe">{{ $ct->bienthe->ten_bienthe }}</div>
                                    @endif
                                    <div class="order-item-qty">x{{ $ct->so_luong }}</div>
                                </div>
                                <div class="order-item-price">{{ number_format($ct->thanh_tien) }}đ</div>
                            </div>
                            @endforeach

                            <div style="margin-top:12px;">
                                <div class="summary-row">
                                    <span>Tạm tính:</span>
                                    <span>{{ number_format($giohang->tong_tien) }}đ</span>
                                </div>
                                <div class="summary-row">
                                    <span>Phí vận chuyển:</span>
                                    <span style="color:#27ae60;">Miễn phí</span>
                                </div>
                                <div class="summary-row discount" id="rowGiam" style="display:none;">
                                    <span id="tenMaHienThi">Giảm giá:</span>
                                    <span id="soTienGiamHienThi">-0đ</span>
                                </div>
                                <div class="summary-row total">
                                    <span>Tổng thanh toán:</span>
                                    <span id="tongThanhToan">{{ number_format($giohang->tong_tien) }}đ</span>
                                </div>
                            </div>

                            <button type="submit" class="btn-dat-hang" id="btnDatHang">
                                <i class="fas fa-check-circle me-2"></i>ĐẶT HÀNG NGAY
                            </button>

                            <a href="{{ route('gio-hang') }}"
                               style="display:block;text-align:center;font-size:0.8rem;color:#888;margin-top:10px;text-decoration:none;">
                                <i class="fas fa-arrow-left me-1"></i>Quay lại giỏ hàng
                            </a>
                        </div>
                    </div>

                    <div style="background:#fff;border:1px solid #ddd;padding:12px 15px;margin-top:14px;font-size:0.78rem;color:#555;">
                        <div class="mb-2"><i class="fas fa-shield-alt text-danger me-2"></i>Thông tin được bảo mật tuyệt đối</div>
                        <div class="mb-2"><i class="fas fa-truck text-danger me-2"></i>Miễn phí ship toàn quốc</div>
                        <div><i class="fas fa-undo text-danger me-2"></i>Đổi trả trong 7 ngày nếu lỗi nhà sản xuất</div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection

@section('extra-js')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const tongTienHang = {{ $giohang->tong_tien }};
const diaChiData = @json($diaChis->keyBy('id'));

// Click vào địa chỉ đã lưu → tự điền form
function chonDiaChi(el, id) {
    document.querySelectorAll('.dia-chi-item').forEach(i => i.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input[type="radio"]').checked = true;

    const dc = diaChiData[id];
    if (!dc) return;

    document.getElementById('formDiaChiMoi').classList.add('show');
    document.getElementById('ten_nguoi_nhan').value   = dc.ho_ten;
    document.getElementById('so_dien_thoai').value    = dc.so_dien_thoai;
    document.getElementById('dia_chi_chi_tiet').value = dc.dia_chi_chi_tiet;
    document.getElementById('phuong_xa').value        = dc.phuong_xa;
    document.getElementById('quan_huyen').value       = dc.quan_huyen;
    document.getElementById('tinh_thanh').value       = dc.tinh_thanh;
}

// Nút "Giao đến địa chỉ khác" → bỏ chọn + xóa form
function toggleFormMoi() {
    document.querySelectorAll('.dia-chi-item').forEach(i => {
        i.classList.remove('selected');
        i.querySelector('input[type="radio"]').checked = false;
    });
    const form = document.getElementById('formDiaChiMoi');
    form.classList.add('show');
    ['ten_nguoi_nhan','so_dien_thoai','dia_chi_chi_tiet','phuong_xa','quan_huyen','tinh_thanh']
        .forEach(id => document.getElementById(id).value = '');
    document.getElementById('ten_nguoi_nhan').focus();
}

// Tự động click địa chỉ mặc định khi load trang
document.addEventListener('DOMContentLoaded', function () {
    const macDinh = document.querySelector('.dia-chi-item.selected');
    if (macDinh) {
        const id = macDinh.querySelector('input[type="radio"]').value;
        chonDiaChi(macDinh, id);
    }
});

function chonThanhToan(el) {
    document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
}

function apMaGiamGia() {
    const maCode = document.getElementById('maCode').value.trim();
    const resultEl = document.getElementById('voucherResult');

    if (!maCode) {
        resultEl.textContent = 'Vui lòng nhập mã giảm giá.';
        resultEl.className = 'voucher-result error';
        resultEl.style.display = 'block';
        return;
    }

    fetch('{{ url('/thanh-toan/ap-ma') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ ma_code: maCode })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('magiamgiaId').value = data.magiamgia_id;
            document.getElementById('rowGiam').style.display = 'flex';
            document.getElementById('tenMaHienThi').textContent = 'Mã ' + data.ten_ma + ':';
            document.getElementById('soTienGiamHienThi').textContent = '-' + data.so_tien_giam;
            document.getElementById('tongThanhToan').textContent = data.tong_thanh_toan;
            resultEl.innerHTML = '<i class="fas fa-check-circle me-1"></i>' + data.message;
            resultEl.className = 'voucher-result success';
        } else {
            document.getElementById('magiamgiaId').value = '';
            document.getElementById('rowGiam').style.display = 'none';
            document.getElementById('tongThanhToan').textContent = new Intl.NumberFormat('vi-VN').format(tongTienHang) + 'đ';
            resultEl.innerHTML = '<i class="fas fa-times-circle me-1"></i>' + data.message;
            resultEl.className = 'voucher-result error';
        }
        resultEl.style.display = 'block';
    });
}

document.getElementById('formThanhToan').addEventListener('submit', function () {
    const btn = document.getElementById('btnDatHang');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
});
</script>
@endsection