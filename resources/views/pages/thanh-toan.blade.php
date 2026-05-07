@extends('layouts.app')

@section('title', 'Thanh Toán')

@section('extra-css')
<style>
@import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap');
/* Override global Arial từ layout */
.tt-page, .tt-page *:not(i):not(.fas):not(.far):not(.fab):not([class*="fa-"]) {
    font-family: 'Be Vietnam Pro', Arial, sans-serif !important;
}
/* Đảm bảo Font Awesome icon không bị ghi đè font */
.tt-page i, .tt-page .fas, .tt-page .far, .tt-page .fab,
.tt-page [class*="fa-"] {
    font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
}

.breadcrumb-bar { background:#eaf4fb; border-bottom:1px solid #d0e8f5; font-size:.82rem; }
.breadcrumb-bar a { color:#1a5276; text-decoration:none; }

.block-card { background:#fff; border-radius:10px; box-shadow:0 1px 8px rgba(0,0,0,0.07); margin-bottom:14px; overflow:hidden; }
.block-title { background:linear-gradient(90deg,#1a5276,#154360); color:#fff; font-size:.85rem; font-weight:700; padding:10px 16px; display:flex; align-items:center; gap:8px; overflow:visible; line-height:1.4; }

.dia-chi-item, .payment-option {
    border:2px solid #e9ecef; border-radius:8px; cursor:pointer; transition:all .15s;
}
.dia-chi-item:hover, .payment-option:hover { border-color:#1a5276; background:#f8fbfe; }
.dia-chi-item.selected, .payment-option.selected { border-color:#1a5276; background:#eaf4fb; }
input[type="radio"] { accent-color:#1a5276; }
.badge-mac-dinh { background:#1a5276; color:#fff; font-size:.62rem; font-weight:700; padding:1px 7px; border-radius:30px; }

.btn-dia-chi-moi { border:2px dashed #dee2e6; color:#6c757d; font-size:.8rem; width:100%; text-align:left; transition:all .15s; border-radius:8px; background:#fafafa; }
.btn-dia-chi-moi:hover { border-color:#1a5276; color:#1a5276; background:#f0f6fb; }

.payment-option-icon { width:40px; height:40px; border-radius:8px; color:#fff !important; font-size:1.2rem; flex-shrink:0; display:flex; align-items:center; justify-content:center; overflow:visible; line-height:1; }

/* ===== VOUCHER SECTION ===== */
.voucher-trigger {
    display:flex; align-items:center; gap:12px; padding:14px 16px;
    cursor:pointer; transition:background .15s;
}
.voucher-trigger:hover { background:#f8fbfe; }
.voucher-trigger-icon { width:38px; height:38px; background:#eaf4fb; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#1a5276 !important; font-size:1.1rem; flex-shrink:0; overflow:visible; line-height:1; }
.voucher-trigger-text { flex:1; }
.voucher-trigger-text .title { font-size:.85rem; font-weight:700; color:#1a5276; }
.voucher-trigger-text .sub { font-size:.76rem; color:#888; }
.voucher-trigger-tag { display:flex; align-items:center; gap:6px; }
.voucher-applied-badge { background:#eafaf1; color:#1e8449; border:1px solid #aed6c0; border-radius:20px; padding:3px 10px; font-size:.74rem; font-weight:700; }
.voucher-arrow { color:#aaa; font-size:.9rem; }

/* ===== POPUP CHỌN MÃ ===== */
.vc-overlay {
    position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:9998;
    display:flex; align-items:flex-end; justify-content:center;
    visibility:hidden; opacity:0; pointer-events:none;
    transition:opacity 0.2s, visibility 0.2s;
}
.vc-overlay.show { visibility:visible; opacity:1; pointer-events:all; }
@media(min-width:768px){ .vc-overlay { align-items:center; } }

.vc-drawer {
    background:#fff; width:100%; max-width:520px; border-radius:16px 16px 0 0;
    max-height:90vh; display:flex; flex-direction:column;
    animation:slideUp .25s ease;
}
@media(min-width:768px){ .vc-drawer { border-radius:14px; max-height:80vh; } }
@keyframes slideUp { from{transform:translateY(40px);opacity:0} to{transform:translateY(0);opacity:1} }

.vc-drawer-head {
    padding:16px 20px 12px; border-bottom:1px solid #f0f0f0;
    display:flex; align-items:center; justify-content:space-between; flex-shrink:0;
}
.vc-drawer-head h6 { font-weight:800; color:#1a5276; margin:0; font-size:.95rem; }
.vc-drawer-close { width:32px; height:32px; border-radius:50%; border:none; background:#f5f5f5; color:#666; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:.9rem; transition:background .15s; }
.vc-drawer-close:hover { background:#e9ecef; color:#333; }

/* Input nhập tay */
.vc-input-row { padding:12px 20px; border-bottom:1px solid #f0f0f0; display:flex; gap:8px; flex-shrink:0; }
.vc-input-row input { flex:1; border:1.5px solid #dee2e6; border-radius:6px; padding:8px 12px; font-size:.85rem; font-family:inherit; outline:none; text-transform:uppercase; }
.vc-input-row input:focus { border-color:#1a5276; }
.vc-input-row button { background:#1a5276; color:#fff; border:none; border-radius:6px; padding:8px 16px; font-size:.82rem; font-weight:700; cursor:pointer; transition:background .15s; white-space:nowrap; }
.vc-input-row button:hover { background:#154360; }

/* Danh sách voucher trong popup */
.vc-list { flex:1; overflow-y:auto; padding:12px 16px; }

.vc-item {
    display:flex; border-radius:10px; border:2px solid #e9ecef;
    margin-bottom:10px; overflow:visible; cursor:pointer; transition:all .15s; position:relative;
}
.vc-item:hover { border-color:#1a5276; box-shadow:0 4px 14px rgba(26,82,118,0.12); }
.vc-item.selected-item { border-color:#1a5276; background:#f0f6fb; }
.vc-item.disabled-item { opacity:.5; cursor:not-allowed; }
.vc-item.disabled-item:hover { border-color:#e9ecef; box-shadow:none; }

.vc-item-left {
    width:72px; flex-shrink:0; display:flex; flex-direction:column;
    align-items:center; justify-content:center; padding:10px 4px; gap:3px;
}
.vc-item-left.pt { background:linear-gradient(160deg,#1a5276,#2980b9); }
.vc-item-left.cd { background:linear-gradient(160deg,#c0392b,#e74c3c); }
.vc-item-left .ic { font-size:.85rem; color:rgba(255,255,255,.8); }
.vc-item-left .vl { font-size:1rem; font-weight:800; color:#fff; line-height:1; text-align:center; }
.vc-item-left .vl small { font-size:.58rem; display:block; opacity:.9; }
.vc-item-left .vt { font-size:.55rem; font-weight:700; color:rgba(255,255,255,.8); text-transform:uppercase; }

.vc-item-right { flex:1; padding:10px 12px; min-width:0; position:relative; }
.vc-item-code { font-family:'Courier New',monospace; font-size:.88rem; font-weight:700; color:#1a5276; letter-spacing:1.5px; margin-bottom:3px; }
.vc-item-left.cd ~ .vc-item-right .vc-item-code { color:#c0392b; }
.vc-item-desc { font-size:.74rem; color:#555; margin-bottom:5px; line-height:1.4; }
.vc-item-meta { display:flex; flex-wrap:wrap; gap:6px; margin-bottom:4px; }
.vc-item-badge { font-size:.65rem; font-weight:600; padding:1px 7px; border-radius:20px; }
.vc-item-badge.blue{background:#eaf4fb;color:#1a5276;} .vc-item-badge.green{background:#eafaf1;color:#1e8449;} .vc-item-badge.orange{background:#fef9ec;color:#d68910;}
.vc-item-expire { font-size:.68rem; color:#999; }
.vc-item-expire.urgent { color:#e74c3c; font-weight:600; }

.vc-item-prog { margin-top:5px; }
.vc-item-prog-bar { height:4px; background:#e9ecef; border-radius:99px; overflow:hidden; }
.vc-item-prog-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,#1a5276,#3498db); }
.vc-item-prog-fill.w { background:linear-gradient(90deg,#e67e22,#e74c3c); }

.vc-item-check { position:absolute; top:10px; right:10px; width:22px; height:22px; border-radius:50%; border:2px solid #dee2e6; display:flex; align-items:center; justify-content:center; font-size:.7rem; color:#fff; transition:all .15s; background:#fff; }
.vc-item.selected-item .vc-item-check { background:#1a5276; border-color:#1a5276; }

.vc-item-disabled-reason { position:absolute; bottom:8px; right:10px; font-size:.65rem; color:#e74c3c; font-weight:600; }

/* Nút áp dụng dưới drawer */
.vc-drawer-foot { padding:14px 20px; border-top:1px solid #f0f0f0; flex-shrink:0; }
.btn-apply-vc { width:100%; background:#1a5276; color:#fff; border:none; border-radius:8px; padding:11px; font-size:.9rem; font-weight:700; cursor:pointer; transition:background .15s; }
.btn-apply-vc:hover { background:#154360; }
.btn-apply-vc:disabled { background:#aaa; cursor:not-allowed; }

/* ===== TỔNG KẾT ĐƠN ===== */
.summary-total { font-weight:800; font-size:1.05rem; color:#dc3545; border-top:2px solid #dee2e6; }
#rowGiam { display:none; }
#rowGiam.show { display:flex; }

.form-control, .form-select { border-radius:6px !important; }
textarea.form-control { resize:vertical; min-height:80px; }
#formDiaChiMoi { display:none; }
#formDiaChiMoi.show { display:block; }

/* ===== TOAST THÔNG BÁO ===== */
.tt-toast {
    position:fixed; bottom:24px; left:50%; transform:translateX(-50%) translateY(20px);
    background:#222; color:#fff; padding:10px 20px; border-radius:8px;
    font-size:.82rem; font-weight:600; z-index:99999;
    opacity:0; transition:all 0.3s; pointer-events:none; white-space:nowrap;
    display:flex; align-items:center; gap:8px; min-width:220px; justify-content:center;
    box-shadow:0 4px 20px rgba(0,0,0,0.25);
}
.tt-toast.show { opacity:1; transform:translateX(-50%) translateY(0); }
.tt-toast.success { background:#1e8449; }
.tt-toast.error   { background:#c0392b; }

</style>
@endsection

@section('content')
<div class="tt-page">

<div class="breadcrumb-bar py-2">
  <div class="container">
    <a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Trang chủ</a>
    <span class="mx-2 text-muted">›</span>
    <a href="{{ route('gio-hang') }}">Giỏ hàng</a>
    <span class="mx-2 text-muted">›</span>
    <span class="text-muted">Thanh toán</span>
  </div>
</div>

<div class="container py-4">
  <form action="{{ url('/thanh-toan') }}" method="POST" id="formThanhToan">
    @csrf
    <input type="hidden" name="magiamgia_id" id="magiamgiaId" value="">

    <div class="row">

      {{-- ===== CỘT TRÁI ===== --}}
      <div class="col-lg-7 mb-4">

        {{-- Địa chỉ giao hàng --}}
        <div class="block-card">
          <div class="block-title"><i class="fas fa-map-marker-alt"></i> Thông tin giao hàng</div>
          <div class="p-3">
            @if($diaChis->count() > 0)
            <div class="pb-3 mb-3" style="border-bottom:1px dashed #dee2e6">
              <p class="fw-bold small mb-2"><i class="fas fa-bookmark me-1" style="color:#1a5276"></i>Địa chỉ đã lưu</p>
              @foreach($diaChis as $dc)
              <div class="dia-chi-item d-flex align-items-start gap-2 p-2 mb-2 {{ $dc->mac_dinh ? 'selected' : '' }}"
                   onclick="chonDiaChi(this, {{ $dc->id }})">
                <input type="radio" name="_dia_chi_chon" value="{{ $dc->id }}" class="mt-1 flex-shrink-0" {{ $dc->mac_dinh ? 'checked' : '' }}>
                <div class="flex-grow-1">
                  <div class="fw-bold" style="font-size:.85rem">
                    {{ $dc->ho_ten }}
                    <span class="fw-normal text-muted" style="font-size:.8rem">— {{ $dc->so_dien_thoai }}</span>
                    @if($dc->mac_dinh)<span class="badge-mac-dinh ms-1">Mặc định</span>@endif
                  </div>
                  <div class="text-muted" style="font-size:.78rem;line-height:1.5">
                    {{ $dc->dia_chi_chi_tiet }}, {{ $dc->phuong_xa }}, {{ $dc->tinh_thanh }}
                  </div>
                </div>
              </div>
              @endforeach
              <button type="button" class="btn btn-light btn-sm btn-dia-chi-moi mt-1 p-2" onclick="toggleFormMoi()">
                <i class="fas fa-plus me-1"></i>Giao đến địa chỉ khác
              </button>
            </div>
            @endif

            <div id="formDiaChiMoi" class="{{ $diaChis->count() === 0 ? 'show' : '' }}">
              <div class="row g-3">
                <div class="col-sm-6">
                  <label class="form-label small fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                  <input type="text" name="ten_nguoi_nhan" id="ten_nguoi_nhan"
                         class="form-control form-control-sm @error('ten_nguoi_nhan') is-invalid @enderror"
                         value="{{ old('ten_nguoi_nhan', $diaChiMacDinh?->ho_ten ?? Auth::user()?->ho_ten) }}"
                         placeholder="Nguyễn Văn A">
                  @error('ten_nguoi_nhan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                  <label class="form-label small fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                  <input type="text" name="so_dien_thoai" id="so_dien_thoai"
                         class="form-control form-control-sm @error('so_dien_thoai') is-invalid @enderror"
                         value="{{ old('so_dien_thoai', $diaChiMacDinh?->so_dien_thoai ?? Auth::user()?->so_dien_thoai) }}"
                         placeholder="0901234567">
                  @error('so_dien_thoai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @guest
                <div class="col-12">
                  <label class="form-label small fw-semibold">
                    Email
                    <span class="text-muted fw-normal" style="font-size:.75rem">(không bắt buộc — để nhận xác nhận đơn hàng)</span>
                  </label>
                  <input type="email" name="email" id="email"
                         class="form-control form-control-sm @error('email') is-invalid @enderror"
                         value="{{ old('email') }}" placeholder="example@email.com">
                  @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @endguest
                <div class="col-12">
                  <label class="form-label small fw-semibold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                  <input type="text" name="dia_chi_chi_tiet" id="dia_chi_chi_tiet"
                         class="form-control form-control-sm @error('dia_chi_chi_tiet') is-invalid @enderror"
                         value="{{ old('dia_chi_chi_tiet', $diaChiMacDinh?->dia_chi_chi_tiet) }}"
                         placeholder="Số nhà, tên đường, tổ/ấp...">
                  @error('dia_chi_chi_tiet')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                  <label class="form-label small fw-semibold">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                  <select name="tinh_thanh" id="tinh_thanh" class="form-select form-select-sm @error('tinh_thanh') is-invalid @enderror">
                    <option value="">-- Chọn tỉnh/thành --</option>
                  </select>
                  @error('tinh_thanh')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                  <label class="form-label small fw-semibold">Xã/Phường <span class="text-danger">*</span></label>
                  <select name="phuong_xa" id="phuong_xa" class="form-select form-select-sm @error('phuong_xa') is-invalid @enderror" disabled>
                    <option value="">-- Chọn xã/phường --</option>
                  </select>
                  @error('phuong_xa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  <input type="hidden" id="_old_tinh_thanh" value="{{ old('tinh_thanh', $diaChiMacDinh?->tinh_thanh) }}">
                  <input type="hidden" id="_old_phuong_xa"  value="{{ old('phuong_xa',  $diaChiMacDinh?->phuong_xa) }}">
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Phương thức thanh toán --}}
        <div class="block-card">
          <div class="block-title"><i class="fas fa-credit-card"></i> Phương thức thanh toán</div>
          <div class="p-3">
            <label class="payment-option d-flex align-items-center gap-3 p-3 mb-2 selected" onclick="chonThanhToan(this)">
              <input type="radio" name="phuong_thuc_thanhtoan" value="cod" checked>
              <div class="payment-option-icon" style="background:#198754"><i class="fas fa-money-bill-wave"></i></div>
              <div>
                <strong class="d-block" style="font-size:.88rem">Thanh toán khi nhận hàng (COD)</strong>
                <span class="text-muted" style="font-size:.76rem">Thanh toán bằng tiền mặt khi nhận được hàng</span>
              </div>
            </label>
            <label class="payment-option d-flex align-items-center gap-3 p-3 mb-2" onclick="chonThanhToan(this)">
              <input type="radio" name="phuong_thuc_thanhtoan" value="payos">
              <div class="payment-option-icon" style="background:#0F6E56"><i class="fas fa-university"></i></div>
              <div>
                <strong class="d-block" style="font-size:.88rem">Thanh toán qua ngân hàng</strong>
                <span class="text-muted" style="font-size:.76rem">Mở app ngân hàng / quét QR — xác nhận tự động</span>
              </div>
            </label>
            @error('phuong_thuc_thanhtoan')<span class="text-danger small d-block mt-1">{{ $message }}</span>@enderror
          </div>
        </div>

        {{-- ===== MÃ GIẢM GIÁ — SHOPEE STYLE ===== --}}
        <div class="block-card">
          <div class="block-title"><i class="fas fa-ticket-alt"></i> Mã giảm giá</div>

          {{-- Trigger mở popup --}}
          <div class="voucher-trigger" onclick="moPopupVoucher()">
            <div class="voucher-trigger-icon"><i class="fas fa-tag"></i></div>
            <div class="voucher-trigger-text">
              <div class="title" id="voucherTriggerTitle">Chọn mã giảm giá</div>
              <div class="sub" id="voucherTriggerSub">Nhấn để chọn hoặc nhập mã</div>
            </div>
            <div class="voucher-trigger-tag">
              <span class="voucher-applied-badge" id="voucherAppliedBadge" style="display:none">
                <i class="fas fa-check me-1"></i><span id="voucherAppliedText">Đã áp dụng</span>
              </span>
              <button type="button" class="btn btn-sm btn-link text-danger p-0" id="btnXoaMa" style="display:none;font-size:.75rem" onclick="xoaMa(event)">
                <i class="fas fa-times me-1"></i>Bỏ mã
              </button>
              <i class="fas fa-chevron-right voucher-arrow ms-1"></i>
            </div>
          </div>
        </div>

        {{-- Ghi chú --}}
        <div class="block-card">
          <div class="block-title"><i class="fas fa-pen"></i> Ghi chú đơn hàng</div>
          <div class="p-3">
            <textarea name="ghi_chu_khach" class="form-control form-control-sm"
                      placeholder="Ghi chú thêm về đơn hàng, ví dụ: giao giờ hành chính...">{{ old('ghi_chu_khach') }}</textarea>
          </div>
        </div>

      </div>

      {{-- ===== CỘT PHẢI: ĐƠN HÀNG ===== --}}
      <div class="col-lg-5">
        <div class="block-card">
          <div class="block-title"><i class="fas fa-receipt"></i>Đơn hàng ({{ $giohang->chitiets->count() }} sản phẩm)</div>
          <div class="p-3">

            @foreach($giohang->chitiets as $ct)
            <div class="d-flex align-items-center gap-2 py-2 border-bottom">
              <img src="{{ asset($ct->sanpham->anhChinh?->duong_dan_anh ?? 'images/no-image.png') }}"
                   alt="{{ $ct->sanpham->ten_san_pham }}"
                   style="width:54px;height:54px;object-fit:cover;border:1px solid #ddd;border-radius:6px;flex-shrink:0">
              <div class="flex-grow-1">
                <div class="fw-semibold lh-sm" style="font-size:.8rem">{{ $ct->sanpham->ten_san_pham }}</div>
                @if($ct->bienthe)<div class="text-muted" style="font-size:.72rem">{{ $ct->bienthe->ten_bienthe }}</div>@endif
                <div class="text-muted" style="font-size:.75rem">x{{ $ct->so_luong }}</div>
              </div>
              <div class="fw-bold text-danger text-nowrap" style="font-size:.82rem">{{ number_format($ct->thanh_tien) }}đ</div>
            </div>
            @endforeach

            <div class="mt-3">
              <div class="d-flex justify-content-between py-2 border-bottom small">
                <span>Tạm tính:</span>
                <span>{{ number_format($giohang->tong_tien) }}đ</span>
              </div>
              <div class="d-flex justify-content-between py-2 border-bottom small">
                <span>Phí vận chuyển:</span>
                <span class="text-success fw-semibold">Miễn phí</span>
              </div>
              <div class="justify-content-between py-2 border-bottom small text-success fw-semibold" id="rowGiam">
                <span id="tenMaHienThi">Giảm giá:</span>
                <span id="soTienGiamHienThi">-0đ</span>
              </div>
              <div class="summary-total d-flex justify-content-between pt-2 mt-1">
                <span>Tổng thanh toán:</span>
                <span id="tongThanhToan">{{ number_format($giohang->tong_tien) }}đ</span>
              </div>
            </div>

            <button type="submit" id="btnDatHang"
                    class="btn btn-danger w-100 py-2 mt-3 fw-bold text-uppercase"
                    style="border-radius:8px;font-size:.9rem">
              <i class="fas fa-check-circle me-2"></i>Đặt hàng ngay
            </button>
            <a href="{{ route('gio-hang') }}" class="d-block text-center text-muted mt-2 small text-decoration-none">
              <i class="fas fa-arrow-left me-1"></i>Quay lại giỏ hàng
            </a>
          </div>
        </div>

        <div class="block-card p-3 small text-muted">
          <div class="mb-2"><i class="fas fa-shield-alt text-danger me-2"></i>Thông tin được bảo mật tuyệt đối</div>
          <div class="mb-2"><i class="fas fa-truck text-danger me-2"></i>Miễn phí ship toàn quốc</div>
          <div><i class="fas fa-undo text-danger me-2"></i>Đổi trả trong 7 ngày nếu lỗi nhà sản xuất</div>
        </div>
      </div>

    </div>
  </form>
</div>

{{-- ===== POPUP CHỌN VOUCHER ===== --}}
<div class="vc-overlay" id="vcOverlay" onclick="dongPopup(event)">
  <div class="vc-drawer" onclick="event.stopPropagation()">

    <div class="vc-drawer-head">
      <h6><i class="fas fa-ticket-alt me-2" style="color:#1a5276"></i>Chọn mã giảm giá</h6>
      <button type="button" class="vc-drawer-close" onclick="dongPopupBtn()"><i class="fas fa-times"></i></button>
    </div>

    <div class="vc-input-row">
      <input type="text" id="vcNhapTay" placeholder="Nhập mã giảm giá..." maxlength="50">
      <button type="button" onclick="apNhapTay()"><i class="fas fa-search me-1"></i>Áp dụng</button>
    </div>

    <div class="vc-list" id="vcList">
      {{-- Danh sách sẽ render bằng JS --}}
      <div class="text-center text-muted py-4" id="vcLoading">
        <i class="fas fa-spinner fa-spin me-1"></i>Đang tải mã giảm giá...
      </div>
    </div>

    <div class="vc-drawer-foot">
      <button type="button" class="btn-apply-vc" id="btnApplyPopup" onclick="apDungPopup()" disabled>
        Áp dụng mã đã chọn
      </button>
    </div>

  </div>
</div>


<div class="tt-toast" id="ttToast"></div>
</div>
@endsection

@section('extra-js')
<script>

/* ===== TOAST ===== */
function showToast(msg, type='error') {
    const t = document.getElementById('ttToast');
    const icon = type === 'success' ? '✓' : '✕';
    t.innerHTML = `<span>${icon}</span><span>${msg}</span>`;
    t.className = `tt-toast ${type} show`;
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => { t.classList.remove('show'); }, 3200);
}
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const tongTienHang = {{ $giohang->tong_tien }};
const diaChiData = @json($diaChis->keyBy('id'));

/* ========== ĐỊACHỈ ========== */
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
    setTinhThanh(dc.tinh_thanh, dc.phuong_xa);
}
function toggleFormMoi() {
    document.querySelectorAll('.dia-chi-item').forEach(i => {
        i.classList.remove('selected');
        i.querySelector('input[type="radio"]').checked = false;
    });
    document.getElementById('formDiaChiMoi').classList.add('show');
    ['ten_nguoi_nhan','so_dien_thoai','dia_chi_chi_tiet'].forEach(id => { const el = document.getElementById(id); if(el) el.value=''; });
    const selTinh = document.getElementById('tinh_thanh');
    const selXa   = document.getElementById('phuong_xa');
    selTinh.value = '';
    selXa.innerHTML = '<option value="">-- Chọn xã/phường --</option>';
    selXa.disabled = true;
    document.getElementById('ten_nguoi_nhan').focus();
}

const API_BASE = 'https://provinces.open-api.vn/api/v2';
async function loadTinhs() {
    try {
        const res = await fetch(`${API_BASE}/p/`);
        const data = await res.json();
        const sel = document.getElementById('tinh_thanh');
        data.forEach(t => {
            const opt = document.createElement('option');
            opt.value = t.name; opt.dataset.code = t.code; opt.textContent = t.name;
            sel.appendChild(opt);
        });
        const oldTinh = document.getElementById('_old_tinh_thanh').value;
        const oldXa   = document.getElementById('_old_phuong_xa').value;
        if (oldTinh) await setTinhThanh(oldTinh, oldXa);
    } catch(e) { console.warn(e); }
}
async function loadXas(tinhCode, selectVal='') {
    const selXa = document.getElementById('phuong_xa');
    selXa.innerHTML = '<option value="">-- Đang tải... --</option>';
    selXa.disabled = true;
    try {
        const res  = await fetch(`${API_BASE}/p/${tinhCode}?depth=2`);
        const data = await res.json();
        selXa.innerHTML = '<option value="">-- Chọn xã/phường --</option>';
        (data.wards || []).forEach(w => {
            const opt = document.createElement('option');
            opt.value = w.name; opt.textContent = w.name;
            if (w.name === selectVal) opt.selected = true;
            selXa.appendChild(opt);
        });
        selXa.disabled = (data.wards||[]).length === 0;
    } catch(e) { selXa.innerHTML = '<option value="">-- Không tải được --</option>'; }
}
async function setTinhThanh(tinhName, xaName='') {
    const selTinh = document.getElementById('tinh_thanh');
    const opt = [...selTinh.options].find(o => o.value === tinhName);
    if (opt) { selTinh.value = tinhName; await loadXas(opt.dataset.code, xaName); }
}
document.getElementById('tinh_thanh').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (opt && opt.dataset.code) loadXas(opt.dataset.code);
    else { const s = document.getElementById('phuong_xa'); s.innerHTML='<option value="">-- Chọn xã/phường --</option>'; s.disabled=true; }
});
document.addEventListener('DOMContentLoaded', function() {
    loadTinhs();
    const mac = document.querySelector('.dia-chi-item.selected');
    if (mac) chonDiaChi(mac, mac.querySelector('input[type="radio"]').value);
});
function chonThanhToan(el) {
    document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
}

/* ========== VOUCHER POPUP ========== */
// Data mã giảm giá lấy thẳng từ blade — không cần AJAX
const danhSachVoucher = {!! json_encode($danhSachMa->values()->map(function($ma) {
    return [
        'id'                 => $ma->id,
        'ma_code'            => $ma->ma_code,
        'mo_ta'              => $ma->mo_ta,
        'kieu_giam'          => $ma->kieu_giam,
        'gia_tri'            => $ma->gia_tri,
        'don_hang_toi_thieu' => $ma->don_hang_toi_thieu,
        'giam_toi_da'        => $ma->giam_toi_da,
        'so_luong'           => $ma->so_luong,
        'da_su_dung'         => $ma->da_su_dung,
        'ket_thuc'           => $ma->ket_thuc ? $ma->ket_thuc->toDateString() : null,
    ];
})) !!};
let maChonTam = null;
let maApDung  = null;

function moPopupVoucher() {
    document.getElementById('vcOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
    maChonTam = maApDung; // giữ trạng thái đã chọn
    renderVoucherList(danhSachVoucher);
    document.getElementById('vcNhapTay').value = '';
}
function dongPopupBtn() {
    document.getElementById('vcOverlay').classList.remove('show');
    document.body.style.overflow = '';
}
function dongPopup(e) {
    if (e.target === document.getElementById('vcOverlay')) dongPopupBtn();
}

function renderVoucherList(list) {
    const container = document.getElementById('vcList');
    if (!list.length) {
        container.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-tags fa-2x mb-3 d-block"></i>Không có mã giảm giá nào</div>';
        return;
    }
    let html = '';
    list.forEach(ma => {
        const isPt = ma.kieu_giam === 'phan_tram';
        const kieuCls = isPt ? 'pt' : 'cd';
        const val = isPt ? `${ma.gia_tri}%` : (ma.gia_tri >= 1000 ? `${Math.floor(ma.gia_tri/1000)}K` : `${ma.gia_tri}`);
        const isSelected = maChonTam == ma.id;
        const conLai = ma.ket_thuc ? diffDays(ma.ket_thuc) : null;

        // Kiểm tra điều kiện đơn hàng
        const duDieu = tongTienHang >= (ma.don_hang_toi_thieu || 0);
        const disabled = !duDieu;

        // Progress bar
        let progHtml = '';
        if (ma.so_luong) {
            const pct = Math.round(ma.da_su_dung / ma.so_luong * 100);
            progHtml = `<div class="vc-item-prog">
                <div class="vc-item-prog-bar"><div class="vc-item-prog-fill ${pct>=80?'w':''}" style="width:${pct}%"></div></div>
            </div>`;
        }

        // Badge điều kiện
        const dieuKienBadge = ma.don_hang_toi_thieu > 0
            ? `<span class="vc-item-badge blue">Đơn từ ${fmt(ma.don_hang_toi_thieu)}đ</span>`
            : `<span class="vc-item-badge green">Không giới hạn đơn</span>`;
        const maxBadge = (isPt && ma.giam_toi_da)
            ? `<span class="vc-item-badge orange">Tối đa ${fmt(ma.giam_toi_da)}đ</span>` : '';

        const expireHtml = ma.ket_thuc
            ? (conLai <= 3
                ? `<span class="vc-item-expire urgent"><i class="fas fa-clock me-1"></i>Còn ${conLai} ngày!</span>`
                : `<span class="vc-item-expire"><i class="fas fa-calendar me-1"></i>HSD: ${formatDate(ma.ket_thuc)}</span>`)
            : `<span class="vc-item-expire"><i class="fas fa-calendar-check me-1"></i>Vĩnh viễn</span>`;

        const reasonHtml = disabled
            ? `<div class="vc-item-disabled-reason"><i class="fas fa-info-circle me-1"></i>Đơn tối thiểu ${fmt(ma.don_hang_toi_thieu)}đ</div>` : '';

        html += `
        <div class="vc-item ${isSelected?'selected-item':''} ${disabled?'disabled-item':''}"
             onclick="${disabled ? '' : `chonVoucherItem(${ma.id}, this)`}"
             data-id="${ma.id}">
          <div class="vc-item-left ${kieuCls}">
            <span style="font-size:1rem;color:rgba(255,255,255,0.9);display:block;text-align:center">${isPt ? '%' : '₫'}</span>
            <div class="vl">${val}<small>GIẢM</small></div>
            <div class="vt">${isPt?'%':'Cố định'}</div>
          </div>
          <div class="vc-item-right">
            <div class="vc-item-code">${ma.ma_code}</div>
            ${ma.mo_ta ? `<div class="vc-item-desc">${ma.mo_ta}</div>` : ''}
            <div class="vc-item-meta">${dieuKienBadge}${maxBadge}</div>
            ${expireHtml}
            ${progHtml}
            ${reasonHtml}
            <div class="vc-item-check">${isSelected ? '<i class="fas fa-check"></i>' : ''}</div>
          </div>
        </div>`;
    });
    container.innerHTML = html;
    capNhatNutApDung();
}

function chonVoucherItem(id, el) {
    // Toggle
    if (maChonTam == id) {
        maChonTam = null;
    } else {
        maChonTam = id;
    }
    // Re-render
    document.querySelectorAll('.vc-item').forEach(item => {
        const iid = parseInt(item.dataset.id);
        const check = item.querySelector('.vc-item-check');
        if (iid === maChonTam) {
            item.classList.add('selected-item');
            check.innerHTML = '<i class="fas fa-check"></i>';
        } else {
            item.classList.remove('selected-item');
            check.innerHTML = '';
        }
    });
    capNhatNutApDung();
}

function capNhatNutApDung() {
    const btn = document.getElementById('btnApplyPopup');
    btn.disabled = maChonTam === null;
    btn.textContent = maChonTam ? 'Áp dụng mã đã chọn' : 'Chọn một mã để áp dụng';
}

function apDungPopup() {
    if (maChonTam === null) return;
    const ma = danhSachVoucher.find(m => m.id == maChonTam);
    if (!ma) return;
    apMaTheoCode(ma.ma_code, () => {
        maApDung = maChonTam;
        dongPopupBtn();
    });
}

function apNhapTay() {
    const code = document.getElementById('vcNhapTay').value.trim().toUpperCase();
    if (!code) return;
    apMaTheoCode(code, () => {
        const ma = danhSachVoucher.find(m => m.ma_code === code);
        if (ma) { maApDung = ma.id; maChonTam = ma.id; }
        dongPopupBtn();
    });
}

function apMaTheoCode(maCode, onSuccess) {
    fetch('{{ url("/thanh-toan/ap-ma") }}', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({ ma_code: maCode })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('magiamgiaId').value = data.magiamgia_id;
            document.getElementById('rowGiam').classList.add('show');
            document.getElementById('rowGiam').style.display = 'flex';
            document.getElementById('tenMaHienThi').textContent  = 'Mã ' + data.ten_ma + ':';
            document.getElementById('soTienGiamHienThi').textContent = '-' + data.so_tien_giam;
            document.getElementById('tongThanhToan').textContent = data.tong_thanh_toan;
            // Cập nhật trigger
            document.getElementById('voucherTriggerTitle').textContent = 'Mã: ' + data.ten_ma;
            document.getElementById('voucherTriggerSub').textContent   = 'Giảm ' + data.so_tien_giam;
            document.getElementById('voucherAppliedBadge').style.display = '';
            document.getElementById('voucherAppliedText').textContent = data.ten_ma;
            document.getElementById('btnXoaMa').style.display = '';
            if (onSuccess) onSuccess();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('Lỗi kết nối, vui lòng thử lại!', 'error'));
}

function xoaMa(e) {
    e.stopPropagation();
    document.getElementById('magiamgiaId').value = '';
    document.getElementById('rowGiam').classList.remove('show');
    document.getElementById('rowGiam').style.display = 'none';
    document.getElementById('tongThanhToan').textContent = new Intl.NumberFormat('vi-VN').format(tongTienHang) + 'đ';
    document.getElementById('voucherTriggerTitle').textContent = 'Chọn mã giảm giá';
    document.getElementById('voucherTriggerSub').textContent   = 'Nhấn để chọn hoặc nhập mã';
    document.getElementById('voucherAppliedBadge').style.display = 'none';
    document.getElementById('btnXoaMa').style.display = 'none';
    maApDung = null; maChonTam = null;
}

/* Helpers */
function fmt(n) { return new Intl.NumberFormat('vi-VN').format(n); }
function diffDays(dateStr) {
    const d = new Date(dateStr); const now = new Date();
    return Math.ceil((d - now) / (1000*60*60*24));
}
function formatDate(dateStr) {
    const d = new Date(dateStr);
    return `${String(d.getDate()).padStart(2,'0')}/${String(d.getMonth()+1).padStart(2,'0')}/${d.getFullYear()}`;
}

document.getElementById('formThanhToan').addEventListener('submit', function() {
    const btn = document.getElementById('btnDatHang');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
});
</script>
@endsection