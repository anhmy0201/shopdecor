@extends('layouts.app')
@section('title', 'Đặt Hàng Thành Công')

@php
    $vcbSoTK       = '1031487289';
    $vcbChuTK      = 'TRUONG ANH MY';
    $maDonHang     = 'DH' . str_pad($donhang->id, 6, '0', STR_PAD_LEFT);
    $noiDungCK     = $maDonHang . ' ' . $donhang->so_dien_thoai;
    $soTienRaw     = (int) $donhang->tong_thanh_toan;

    $qrUrl = 'https://img.vietqr.io/image/VCB-' . $vcbSoTK
           . '-compact2.png'
           . '?amount=' . $soTienRaw
           . '&addInfo=' . urlencode($noiDungCK)
           . '&accountName=' . urlencode($vcbChuTK);
@endphp

@section('extra-css')
<style>
    .order-code {
        display: inline-block;
        background: #eaf4fb;
        border: 1px solid #b8d9ed;
        color: #1a5276;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 5px 18px;
        margin-top: 12px;
        letter-spacing: 1px;
    }
    .nav-account .nav-link.active {
        background: #1a5276;
        color: #fff !important;
    }

    /* ---- Card chuyển khoản ---- */
    .ck-card {
        border: 2px solid #006934 !important;
        border-radius: 12px;
    }
    .ck-header {
        background: linear-gradient(135deg, #006934 0%, #00a651 100%);
        border-radius: 10px 10px 0 0;
        color: #fff;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* QR box */
    .qr-wrapper {
        background: #fff;
        border: 2px solid #006934;
        border-radius: 10px;
        padding: 12px;
        display: inline-block;
        position: relative;
    }
    .qr-wrapper img { display: block; width: 180px; height: 180px; }
    .qr-badge {
        position: absolute;
        bottom: -12px;
        left: 50%;
        transform: translateX(-50%);
        background: #006934;
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 12px;
        border-radius: 20px;
        white-space: nowrap;
        letter-spacing: 0.5px;
    }

    /* Thông tin tài khoản */
    .ck-info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 9px 0;
        border-bottom: 1px dashed #dee2e6;
        font-size: 0.875rem;
    }
    .ck-info-row:last-child { border-bottom: none; }
    .ck-label { color: #6c757d; flex-shrink: 0; width: 130px; }
    .ck-value { font-weight: 600; text-align: right; }
    .ck-value.highlight { color: #dc3545; font-size: 1rem; }

    /* Nút copy */
    .btn-copy {
        background: none;
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 2px 8px;
        font-size: 0.75rem;
        color: #495057;
        cursor: pointer;
        transition: all .15s;
        margin-left: 6px;
        white-space: nowrap;
    }
    .btn-copy:hover { background: #006934; color: #fff; border-color: #006934; }
    .btn-copy.copied { background: #006934; color: #fff; border-color: #006934; }

    /* Countdown */
    .countdown-bar {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 0.82rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    #countdown-timer { font-weight: 700; color: #dc3545; font-size: 1rem; }

    /* Steps */
    .ck-steps { counter-reset: step; list-style: none; padding: 0; margin: 0; }
    .ck-steps li {
        counter-increment: step;
        padding: 6px 0 6px 36px;
        position: relative;
        font-size: 0.82rem;
        color: #495057;
    }
    .ck-steps li::before {
        content: counter(step);
        position: absolute;
        left: 0; top: 5px;
        width: 22px; height: 22px;
        background: #006934;
        color: #fff;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.72rem; font-weight: 700;
    }

    /* ---- Nút PayOS ---- */
    .payos-btn {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #00b14f 0%, #009d47 100%);
        color: #fff !important;
        border: none;
        border-radius: 10px;
        padding: 12px 32px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: opacity .2s, transform .15s;
        box-shadow: 0 3px 10px rgba(0,177,79,.25);
    }
    .payos-btn:hover {
        opacity: .92;
        transform: translateY(-1px);
        color: #fff !important;
    }
    .payos-btn small {
        font-size: 0.68rem;
        font-weight: 400;
        opacity: .85;
        margin-top: 2px;
    }

    /* Divider "hoặc" */
    .or-divider {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #adb5bd;
        font-size: 0.78rem;
        margin: 18px 0;
    }
    .or-divider::before,
    .or-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #dee2e6;
    }
</style>
@endsection

@section('content')

<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Xác nhận đơn hàng</li>
        </ol>
    </nav>

    {{-- Alert thông báo (success / warning / info) --}}
    @foreach(['success','warning','info','error'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-{{ $type === 'success' ? 'check-circle' : ($type === 'warning' ? 'exclamation-triangle' : 'info-circle') }} me-2"></i>
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    {{-- Banner thành công --}}
    <div class="card border-0 shadow-sm text-center p-4 mb-4" style="border-top: 4px solid #27ae60 !important;">
        <div class="mx-auto mb-3 rounded-circle bg-success d-flex align-items-center justify-content-center"
             style="width:64px; height:64px; font-size:1.8rem; color:#fff;">
            <i class="fas fa-check"></i>
        </div>
        <h5 class="fw-bold text-success mb-1">Đặt hàng thành công!</h5>
        <p class="text-muted small mb-0">Cảm ơn bạn đã mua sắm tại cửa hàng. Chúng tôi sẽ liên hệ xác nhận sớm nhất.</p>
        <div class="order-code">#{{ $maDonHang }}</div>
    </div>

    <div class="row g-4">

        {{-- CỘT TRÁI --}}
        <div class="col-lg-7">

            {{-- Thông tin giao hàng --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="fas fa-map-marker-alt text-primary"></i>
                    <span class="fw-bold">Thông tin giao hàng</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-2 small">
                        <div class="col-4 text-muted">Người nhận:</div>
                        <div class="col-8 fw-semibold">{{ $donhang->ten_nguoi_nhan }}</div>

                        <div class="col-4 text-muted">Số điện thoại:</div>
                        <div class="col-8 fw-semibold">{{ $donhang->so_dien_thoai }}</div>

                        <div class="col-4 text-muted">Địa chỉ:</div>
                        <div class="col-8 fw-semibold">
                            {{ $donhang->dia_chi_chi_tiet }}, {{ $donhang->phuong_xa }},
                            {{ $donhang->quan_huyen }}, {{ $donhang->tinh_thanh }}
                        </div>

                        <div class="col-4 text-muted">Ngày đặt:</div>
                        <div class="col-8 fw-semibold">{{ $donhang->ngay_dat->format('H:i d/m/Y') }}</div>

                        <div class="col-4 text-muted">Thanh toán:</div>
                        <div class="col-8">
                            @if($donhang->phuong_thuc_thanhtoan === 'cod')
                                <span class="badge bg-success">
                                    <i class="fas fa-money-bill-wave me-1"></i>COD - Tiền mặt khi nhận
                                </span>
                            @else
                                <span class="badge text-white" style="background:#006934">
                                    <i class="fas fa-university me-1"></i>Chuyển khoản ngân hàng
                                </span>
                            @endif
                        </div>

                        <div class="col-4 text-muted">Trạng thái:</div>
                        <div class="col-8">
                            @if($donhang->trang_thai_thanhtoan === 'da_thanh_toan')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Đã thanh toán
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock me-1"></i>Chờ xác nhận
                                </span>
                            @endif
                        </div>

                        @if($donhang->ghi_chu_khach)
                        <div class="col-4 text-muted">Ghi chú:</div>
                        <div class="col-8 fw-semibold">{{ $donhang->ghi_chu_khach }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- =============================================== --}}
            {{-- BLOCK CHUYỂN KHOẢN — chỉ hiện khi CK           --}}
            {{-- =============================================== --}}
            @if($donhang->phuong_thuc_thanhtoan === 'chuyen_khoan' && $donhang->trang_thai_thanhtoan !== 'da_thanh_toan')
            <div class="card border-0 shadow-sm ck-card mb-4">

                {{-- Header xanh VCB --}}
                <div class="ck-header">
                    <i class="fas fa-university fa-lg"></i>
                    <div>
                        <div class="fw-bold" style="font-size:0.95rem">Thanh toán chuyển khoản</div>
                        <div style="font-size:0.75rem; opacity:.85">Vietcombank (VCB)</div>
                    </div>
                </div>

                <div class="ck-body p-4">

                    {{-- Đếm ngược 24h --}}
                    <div class="countdown-bar mb-4">
                        <i class="fas fa-hourglass-half text-warning"></i>
                        <span>Vui lòng chuyển khoản trong:</span>
                        <span id="countdown-timer">23:59:59</span>
                        <span class="text-muted">để đơn được xử lý</span>
                    </div>

                    <div class="row g-4">

                        {{-- QR Code --}}
                        <div class="col-12 col-sm-auto d-flex flex-column align-items-center">
                            <div class="mb-3 text-center" style="font-size:0.8rem; color:#6c757d;">
                                <i class="fas fa-qrcode me-1"></i>Quét mã bằng app ngân hàng
                            </div>
                            <div class="qr-wrapper mb-4">
                                <img src="{{ $qrUrl }}"
                                     alt="QR chuyển khoản VCB"
                                     onerror="this.src='https://placehold.co/180x180?text=QR+Error'">
                                <span class="qr-badge">VietQR · VCB</span>
                            </div>
                            <div class="text-center mt-3" style="font-size:0.75rem; color:#6c757d; max-width:180px">
                                Mã QR đã điền sẵn số tiền &amp; nội dung
                            </div>
                        </div>

                        {{-- Thông tin tài khoản --}}
                        <div class="col">
                            <div style="font-size:0.8rem; color:#6c757d; margin-bottom:8px;">
                                <i class="fas fa-info-circle me-1"></i>Hoặc chuyển khoản thủ công
                            </div>

                            <div class="ck-info-row">
                                <span class="ck-label">Ngân hàng</span>
                                <span class="ck-value">
                                    <span class="badge text-white me-1" style="background:#006934; font-size:.8rem">VCB</span>
                                    Vietcombank
                                </span>
                            </div>

                            <div class="ck-info-row">
                                <span class="ck-label">Số tài khoản</span>
                                <span class="ck-value d-flex align-items-center justify-content-end">
                                    <span id="stk-val">{{ $vcbSoTK }}</span>
                                    <button class="btn-copy" onclick="copyText('stk-val', this)" title="Sao chép">
                                        <i class="fas fa-copy me-1"></i>Copy
                                    </button>
                                </span>
                            </div>

                            <div class="ck-info-row">
                                <span class="ck-label">Chủ tài khoản</span>
                                <span class="ck-value">{{ $vcbChuTK }}</span>
                            </div>

                            <div class="ck-info-row">
                                <span class="ck-label">Số tiền</span>
                                <span class="ck-value highlight d-flex align-items-center justify-content-end">
                                    <span id="sotien-val">{{ number_format($soTienRaw) }}</span>đ
                                    <button class="btn-copy" onclick="copyText('sotien-val', this, true)" title="Sao chép">
                                        <i class="fas fa-copy me-1"></i>Copy
                                    </button>
                                </span>
                            </div>

                            <div class="ck-info-row">
                                <span class="ck-label">Nội dung CK</span>
                                <span class="ck-value d-flex align-items-center justify-content-end">
                                    <span id="noidung-val">{{ $noiDungCK }}</span>
                                    <button class="btn-copy" onclick="copyText('noidung-val', this)" title="Sao chép">
                                        <i class="fas fa-copy me-1"></i>Copy
                                    </button>
                                </span>
                            </div>

                            {{-- Cảnh báo nội dung --}}
                            <div class="alert alert-warning py-2 px-3 mb-0 mt-3 small">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Lưu ý:</strong> Nhập <strong>đúng nội dung</strong>
                                <code>{{ $noiDungCK }}</code> để đơn hàng được xác nhận tự động.
                            </div>
                        </div>
                    </div>

                    {{-- Hướng dẫn từng bước --}}
                    <hr class="my-3">
                    <div class="fw-bold small mb-2">
                        <i class="fas fa-list-ol me-1 text-success"></i>Hướng dẫn chuyển khoản nhanh
                    </div>
                    <ol class="ck-steps">
                        <li>Mở app <strong>VCB Digibank</strong> (hoặc app ngân hàng bất kỳ hỗ trợ VietQR)</li>
                        <li>Chọn <strong>Quét mã QR</strong> → quét mã bên trên — số tiền &amp; nội dung tự điền sẵn</li>
                        <li>Kiểm tra lại thông tin rồi xác nhận chuyển khoản</li>
                        <li>Chụp màn hình xác nhận &amp; gửi cho shop qua Zalo/FB nếu cần xử lý nhanh</li>
                    </ol>

                    {{-- ===== DIVIDER + NÚT PAYOS ===== --}}
                    <div class="or-divider">hoặc thanh toán tự động qua</div>

                    <div class="text-center">
                        <a href="{{ route('payos.checkout', $donhang->id) }}" class="payos-btn">
                            <span>
                                <i class="fas fa-bolt me-2"></i>Thanh toán qua PayOS
                            </span>
                            <small>MoMo · ZaloPay · ATM · Visa · QR ngân hàng — xác nhận tự động</small>
                        </a>
                        <div class="mt-2" style="font-size:0.72rem; color:#6c757d;">
                            <i class="fas fa-shield-alt me-1"></i>Bảo mật SSL · Không lưu thông tin thẻ
                        </div>
                    </div>
                    {{-- ================================ --}}

                </div>
            </div>

            {{-- Thông báo nếu đã thanh toán qua PayOS --}}
            @elseif($donhang->phuong_thuc_thanhtoan === 'chuyen_khoan' && $donhang->trang_thai_thanhtoan === 'da_thanh_toan')
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                <i class="fas fa-check-circle fa-lg"></i>
                <div>
                    <strong>Đã thanh toán thành công!</strong><br>
                    <span class="small">Đơn hàng của bạn đã được xác nhận và đang được xử lý.</span>
                </div>
            </div>
            @endif
            {{-- =============================================== --}}

            {{-- Nút điều hướng --}}
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ url('/') }}" class="btn btn-primary px-4">
                    <i class="fas fa-home me-2"></i>Về trang chủ
                </a>
                <a href="{{ url('/san-pham') }}" class="btn btn-outline-primary px-4">
                    <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                </a>
            </div>

        </div>

        {{-- CỘT PHẢI: CHI TIẾT ĐƠN --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="fas fa-list-ul text-primary"></i>
                    <span class="fw-bold">Chi tiết đơn hàng</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:55%">Sản phẩm</th>
                                    <th class="text-center" style="width:10%">SL</th>
                                    <th class="text-end" style="width:35%">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donhang->chitiets as $ct)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($ct->hinh_anh)
                                                <img src="{{ asset($ct->hinh_anh) }}"
                                                     width="50" height="50"
                                                     class="border rounded flex-shrink-0"
                                                     style="object-fit:cover"
                                                     alt="{{ $ct->ten_san_pham }}">
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $ct->ten_san_pham }}</div>
                                                @if($ct->ten_bienthe)
                                                    <div class="text-muted small">{{ $ct->ten_bienthe }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $ct->so_luong }}</td>
                                    <td class="text-end fw-bold text-danger">
                                        {{ number_format($ct->so_luong * $ct->gia) }}đ
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 border-top small">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Tạm tính:</span>
                            <span>{{ number_format($donhang->tong_tien_hang) }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Phí vận chuyển:</span>
                            <span class="text-success fw-bold">Miễn phí</span>
                        </div>
                        @if($donhang->so_tien_giam > 0)
                        <div class="d-flex justify-content-between py-2 border-bottom text-success">
                            <span>
                                Giảm giá
                                @if($donhang->magiamgia)({{ $donhang->magiamgia->ma_code }}):@endif
                            </span>
                            <span>-{{ number_format($donhang->so_tien_giam) }}đ</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between pt-3 fw-bold text-danger fs-6">
                            <span>Tổng thanh toán:</span>
                            <span>{{ number_format($donhang->tong_thanh_toan) }}đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
// ---- Copy to clipboard ----
function copyText(elId, btn, isNumber = false) {
    let text = document.getElementById(elId).innerText.trim();
    if (isNumber) text = text.replace(/\./g, '');
    navigator.clipboard.writeText(text).then(() => {
        btn.innerHTML = '<i class="fas fa-check me-1"></i>Đã copy';
        btn.classList.add('copied');
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-copy me-1"></i>Copy';
            btn.classList.remove('copied');
        }, 2000);
    });
}

// ---- Đếm ngược 24h ----
(function () {
    const key = 'ck_deadline_{{ $donhang->id }}';
    let deadline = localStorage.getItem(key);
    if (!deadline) {
        deadline = Date.now() + 24 * 60 * 60 * 1000;
        localStorage.setItem(key, deadline);
    }
    const el = document.getElementById('countdown-timer');
    if (!el) return;
    function tick() {
        const diff = parseInt(deadline) - Date.now();
        if (diff <= 0) { el.textContent = '00:00:00'; return; }
        const h = String(Math.floor(diff / 3600000)).padStart(2, '0');
        const m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
        const s = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        el.textContent = `${h}:${m}:${s}`;
        setTimeout(tick, 1000);
    }
    tick();
})();
</script>
@endpush

@endsection