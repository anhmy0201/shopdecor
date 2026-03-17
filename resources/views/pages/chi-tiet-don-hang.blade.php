@extends('layouts.app')

@section('title', 'Chi Tiết Đơn #DH' . str_pad($donhang->id, 6, '0', STR_PAD_LEFT))

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
    .main-content { padding: 24px 0 50px; }

    /* ===== BLOCK ===== */
    .info-block { border: 1px solid #ddd; background: #fff; margin-bottom: 18px; }
    .info-block-title {
        background: #1a5276;
        color: #fff;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 9px 15px;
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
    }
    .info-block-body { padding: 16px; }

    /* ===== TIMELINE ===== */
    .timeline {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        position: relative;
        padding: 10px 0 6px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        top: 28px; left: 10%; right: 10%;
        height: 3px;
        background: #e0e0e0;
        z-index: 0;
    }
    .timeline-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
        z-index: 1;
        min-width: 60px;
    }
    .timeline-dot {
        width: 36px; height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        border: 3px solid #ddd;
        background: #fff;
        margin-bottom: 7px;
    }
    .timeline-dot.done    { background: #27ae60; border-color: #27ae60; color: #fff; }
    .timeline-dot.active  { background: #1a5276; border-color: #1a5276; color: #fff; }
    .timeline-dot.pending { color: #ccc; }
    .timeline-label { font-size: 0.72rem; font-weight: 600; color: #aaa; text-align: center; line-height: 1.3; }
    .timeline-label.done   { color: #27ae60; }
    .timeline-label.active { color: #1a5276; }

    /* ===== INFO ROW ===== */
    .info-row {
        display: flex; gap: 8px;
        padding: 7px 0; border-bottom: 1px dashed #eee; font-size: 0.85rem;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row-label { color: #888; min-width: 140px; flex-shrink: 0; }
    .info-row-val   { color: #222; font-weight: 600; }

    /* ===== BADGES ===== */
    .badge-tt {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; font-size: 0.75rem; font-weight: 700; border-radius: 2px;
    }
    .badge-moi      { background: #fdf6e3; color: #b7770d;  border: 1px solid #f0d080; }
    .badge-xu-ly    { background: #eaf4fb; color: #1a5276;  border: 1px solid #b8d9ed; }
    .badge-hoan-tat { background: #e8f8f0; color: #1e8449;  border: 1px solid #a9dfbf; }
    .badge-huy      { background: #fdf2f2; color: #c0392b;  border: 1px solid #f5b7b1; }
    .badge-cod      { background: #e8f8f0; color: #1e8449; }
    .badge-ck       { background: #eaf4fb; color: #1a5276; }
    .badge-chua_tt  { background: #fdf6e3; color: #b7770d; }
    .badge-da_tt    { background: #e8f8f0; color: #1e8449; }

    /* ===== BẢNG SẢN PHẨM ===== */
    .order-table { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
    .order-table th {
        background: #f5f5f5; border: 1px solid #ddd;
        padding: 8px 12px; font-weight: 700; color: #333; text-align: center;
    }
    .order-table td { border: 1px solid #eee; padding: 10px 12px; vertical-align: middle; }
    .order-table tbody tr:hover { background: #fafafa; }
    .item-img  { width: 52px; height: 52px; object-fit: cover; border: 1px solid #ddd; flex-shrink: 0; }
    .item-name { font-weight: 600; color: #222; line-height: 1.4; }
    .item-sub  { font-size: 0.75rem; color: #888; margin-top: 2px; }
    .item-gia  { color: #e74c3c; font-weight: 700; text-align: right; }

    /* ===== TỔNG ===== */
    .tong-row { display: flex; justify-content: space-between; padding: 7px 0; font-size: 0.85rem; border-bottom: 1px dashed #eee; }
    .tong-row:last-child { border-bottom: none; }
    .tong-row.giam  { color: #27ae60; }
    .tong-row.total {
        font-weight: 700; font-size: 1rem; color: #e74c3c;
        border-top: 2px solid #ddd; border-bottom: none; padding-top: 10px; margin-top: 4px;
    }

    /* ===== CHUYỂN KHOẢN ===== */
    .ck-info { background: #eaf4fb; border: 1px solid #b8d9ed; padding: 14px 16px; font-size: 0.83rem; }
    .ck-info strong { color: #1a5276; display: block; margin-bottom: 6px; }
    .ck-row { display: flex; gap: 6px; padding: 3px 0; }
    .ck-label { color: #666; min-width: 120px; }
    .ck-val   { font-weight: 700; color: #1a5276; }

    /* ===== KHU VỰC ĐÁNH GIÁ ===== */
    .review-block { border: 1px solid #ddd; background: #fff; margin-bottom: 18px; }
    .review-block-title {
        background: #f0a500;
        color: #fff; font-size: 0.85rem; font-weight: 700;
        padding: 9px 15px; display: flex; align-items: center; gap: 8px; text-transform: uppercase;
    }
    .review-item-card {
        border: 1px solid #eee; padding: 14px; margin-bottom: 12px; background: #fafafa;
    }
    .review-item-card:last-child { margin-bottom: 0; }

    .review-sp-header { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
    .review-sp-header img { width: 44px; height: 44px; object-fit: cover; border: 1px solid #ddd; flex-shrink: 0; }
    .review-sp-header .sp-name { font-weight: 700; font-size: 0.85rem; color: #222; }
    .review-sp-header .sp-bienthe { font-size: 0.75rem; color: #888; margin-top: 2px; }

    /* Chọn sao */
    .star-picker { display: flex; gap: 3px; margin-bottom: 10px; cursor: pointer; }
    .star-picker i { font-size: 1.4rem; color: #ddd; transition: color 0.1s; }
    .star-picker i.lit { color: #f0a500; }

    .review-textarea {
        width: 100%; border: 1px solid #ddd; padding: 8px 12px;
        font-size: 0.85rem; resize: vertical; min-height: 80px;
        outline: none; border-radius: 0; transition: border-color 0.15s;
    }
    .review-textarea:focus { border-color: #1a5276; }
    .btn-gui {
        background: #f0a500; color: #fff; border: none;
        padding: 7px 20px; font-size: 0.83rem; font-weight: 700;
        cursor: pointer; margin-top: 8px; transition: background 0.15s;
    }
    .btn-gui:hover { background: #d4910a; }

    /* Đã đánh giá */
    .done-card {
        border: 1px solid #a9dfbf; background: #e8f8f0;
        padding: 10px 14px; margin-bottom: 10px; font-size: 0.83rem;
    }
    .done-card:last-child { margin-bottom: 0; }
    .done-stars i { color: #f0a500; font-size: 0.82rem; }
    .done-stars i.off { color: #ddd; }

    /* ===== NÚT ===== */
    .btn-primary-custom {
        background: #1a5276; color: #fff; border: none;
        padding: 9px 22px; font-size: 0.85rem; font-weight: 600;
        text-decoration: none; display: inline-block;
        transition: background 0.2s; cursor: pointer;
    }
    .btn-primary-custom:hover { background: #154360; color: #fff; }
    .btn-outline-custom {
        background: #fff; color: #1a5276; border: 2px solid #1a5276;
        padding: 8px 20px; font-size: 0.85rem; font-weight: 600;
        text-decoration: none; display: inline-block; transition: all 0.2s;
    }
    .btn-outline-custom:hover { background: #1a5276; color: #fff; }
    .btn-danger-outline {
        background: #fff; color: #e74c3c; border: 1px solid #e74c3c;
        padding: 8px 18px; font-size: 0.85rem; font-weight: 600;
        cursor: pointer; transition: all 0.2s;
    }
    .btn-danger-outline:hover { background: #e74c3c; color: #fff; }

    /* ===== ALERT ===== */
    .alert-box {
        padding: 10px 14px; font-size: 0.85rem; margin-bottom: 14px;
        border-left: 4px solid; display: flex; align-items: center; gap: 8px;
    }
    .alert-ok  { background: #e8f8f0; border-color: #27ae60; color: #1e8449; }
    .alert-err { background: #fdf2f2; border-color: #e74c3c; color: #c0392b; }
</style>
@endsection

@section('content')

<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Trang chủ</a>
        <span class="mx-2">›</span>
        <a href="{{ url('/don-hang') }}">Đơn hàng</a>
        <span class="mx-2">›</span>
        <span>#DH{{ str_pad($donhang->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>
</div>

<div class="main-content">
    <div class="container">

        @if(session('success'))
            <div class="alert-box alert-ok"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-box alert-err"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        <div class="row">

            {{-- ============ CỘT TRÁI ============ --}}
            <div class="col-lg-7 mb-4">

                {{-- Timeline --}}
                @if($donhang->trang_thai !== \App\Models\Donhang::TRANG_THAI_HUY)
                @php
                    $tt = $donhang->trang_thai;
                    $steps = [
                        ['label' => "Đặt hàng\nthành công", 'icon' => 'fa-check',           'done' => $tt >= 0, 'active' => $tt === 0],
                        ['label' => "Đã xác\nnhận",         'icon' => 'fa-clipboard-check',  'done' => $tt >= 1, 'active' => $tt === 1],
                        ['label' => "Đang\nvận chuyển",     'icon' => 'fa-shipping-fast',    'done' => $tt >= 2, 'active' => false],
                        ['label' => "Đã giao\nhàng",        'icon' => 'fa-box-open',         'done' => $tt >= 2, 'active' => $tt === 2],
                    ];
                @endphp
                <div class="info-block">
                    <div class="info-block-title"><i class="fas fa-route"></i> Trạng thái đơn hàng</div>
                    <div class="info-block-body" style="padding: 22px 20px 16px;">
                        <div class="timeline">
                            @foreach($steps as $step)
                            <div class="timeline-step">
                                <div class="timeline-dot
                                    {{ $step['active'] ? 'active' : ($step['done'] ? 'done' : 'pending') }}">
                                    <i class="fas {{ $step['icon'] }}"></i>
                                </div>
                                <div class="timeline-label
                                    {{ $step['active'] ? 'active' : ($step['done'] ? 'done' : '') }}">
                                    {!! nl2br(e($step['label'])) !!}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            @if($tt === \App\Models\Donhang::TRANG_THAI_MOI)
                                <span class="badge-tt badge-moi"><i class="fas fa-clock me-1"></i>Chờ xác nhận</span>
                            @elseif($tt === \App\Models\Donhang::TRANG_THAI_XU_LY)
                                <span class="badge-tt badge-xu-ly"><i class="fas fa-sync-alt me-1"></i>Đang xử lý</span>
                            @else
                                <span class="badge-tt badge-hoan-tat"><i class="fas fa-check-circle me-1"></i>Đã giao — Hoàn tất</span>
                            @endif
                        </div>
                    </div>
                </div>
                @else
                <div class="info-block" style="border-top:3px solid #e74c3c;">
                    <div class="info-block-body" style="text-align:center;padding:22px;">
                        <div style="width:52px;height:52px;background:#e74c3c;border-radius:50%;
                                    display:flex;align-items:center;justify-content:center;
                                    margin:0 auto 10px;font-size:1.4rem;color:#fff;">
                            <i class="fas fa-times"></i>
                        </div>
                        <div style="font-weight:700;color:#c0392b;">Đơn hàng đã bị hủy</div>
                    </div>
                </div>
                @endif

                {{-- Thông tin giao hàng --}}
                <div class="info-block">
                    <div class="info-block-title"><i class="fas fa-map-marker-alt"></i> Thông tin giao hàng</div>
                    <div class="info-block-body">
                        <div class="info-row">
                            <span class="info-row-label">Mã đơn hàng:</span>
                            <span class="info-row-val" style="color:#1a5276;letter-spacing:.5px;">
                                #DH{{ str_pad($donhang->id, 6, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-label">Ngày đặt:</span>
                            <span class="info-row-val">{{ $donhang->ngay_dat->format('H:i — d/m/Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-label">Người nhận:</span>
                            <span class="info-row-val">{{ $donhang->ten_nguoi_nhan }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-label">Số điện thoại:</span>
                            <span class="info-row-val">{{ $donhang->so_dien_thoai }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-label">Địa chỉ:</span>
                            <span class="info-row-val">
                                {{ $donhang->dia_chi_chi_tiet }}, {{ $donhang->phuong_xa }},
                                {{ $donhang->quan_huyen }}, {{ $donhang->tinh_thanh }}
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-label">Thanh toán:</span>
                            <span class="info-row-val">
                                @if($donhang->phuong_thuc_thanhtoan === 'cod')
                                    <span class="badge-tt badge-cod"><i class="fas fa-money-bill-wave me-1"></i>COD</span>
                                @else
                                    <span class="badge-tt badge-ck"><i class="fas fa-university me-1"></i>Chuyển khoản</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-label">TT thanh toán:</span>
                            <span class="info-row-val">
                                @if($donhang->trang_thai_thanhtoan === 'da_thanh_toan')
                                    <span class="badge-tt badge-da_tt"><i class="fas fa-check me-1"></i>Đã thanh toán</span>
                                @else
                                    <span class="badge-tt badge-chua_tt"><i class="fas fa-hourglass-half me-1"></i>Chưa thanh toán</span>
                                @endif
                            </span>
                        </div>
                        @if($donhang->ghi_chu_khach)
                        <div class="info-row">
                            <span class="info-row-label">Ghi chú:</span>
                            <span class="info-row-val">{{ $donhang->ghi_chu_khach }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Thông tin CK nếu chưa thanh toán --}}
                @if($donhang->phuong_thuc_thanhtoan === 'chuyen_khoan'
                    && $donhang->trang_thai_thanhtoan !== 'da_thanh_toan')
                <div class="info-block">
                    <div class="info-block-title"><i class="fas fa-university"></i> Thông tin chuyển khoản</div>
                    <div class="info-block-body">
                        <p style="font-size:0.83rem;color:#555;margin-bottom:10px;">
                            Vui lòng chuyển khoản trong vòng <strong style="color:#e74c3c;">24 giờ</strong>.
                        </p>
                        <div class="ck-info">
                            <strong><i class="fas fa-piggy-bank me-1"></i>Thông tin tài khoản</strong>
                            <div class="ck-row"><span class="ck-label">Ngân hàng:</span><span class="ck-val">Vietcombank</span></div>
                            <div class="ck-row"><span class="ck-label">Số tài khoản:</span><span class="ck-val">1234567890</span></div>
                            <div class="ck-row"><span class="ck-label">Chủ tài khoản:</span><span class="ck-val">NGUYEN VAN A</span></div>
                            <div class="ck-row">
                                <span class="ck-label">Nội dung CK:</span>
                                <span class="ck-val">DH{{ str_pad($donhang->id, 6, '0', STR_PAD_LEFT) }} {{ $donhang->so_dien_thoai }}</span>
                            </div>
                            <div class="ck-row">
                                <span class="ck-label">Số tiền:</span>
                                <span class="ck-val" style="color:#e74c3c;">{{ number_format($donhang->tong_thanh_toan) }}đ</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Nút điều hướng --}}
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ url('/don-hang') }}" class="btn-outline-custom">
                        <i class="fas fa-arrow-left me-1"></i>Danh sách đơn
                    </a>
                    <a href="{{ url('/') }}" class="btn-primary-custom">
                        <i class="fas fa-shopping-bag me-1"></i>Tiếp tục mua sắm
                    </a>
                    @if($donhang->coTheHuy())
                        <form action="{{ url('/don-hang/' . $donhang->id . '/huy') }}" method="POST"
                              onsubmit="return confirm('Xác nhận hủy đơn hàng này?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-danger-outline">
                                <i class="fas fa-times me-1"></i>Hủy đơn hàng
                            </button>
                        </form>
                    @endif
                </div>

            </div>

            {{-- ============ CỘT PHẢI ============ --}}
            <div class="col-lg-5">

                {{-- Bảng sản phẩm --}}
                <div class="info-block">
                    <div class="info-block-title"><i class="fas fa-list-ul"></i> Sản phẩm đã đặt</div>
                    <div class="info-block-body" style="padding:0;">
                        <div style="overflow-x:auto;">
                            <table class="order-table">
                                <thead>
                                    <tr>
                                        <th style="text-align:left;width:52%;">Sản phẩm</th>
                                        <th style="width:12%;">SL</th>
                                        <th style="width:36%;">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($donhang->chitiets as $ct)
                                    <tr>
                                        <td>
                                            <div class="d-flex gap-2 align-items-start">
                                                <img src="{{ asset($ct->hinh_anh ?? 'images/no-image.png') }}"
                                                     class="item-img" alt="{{ $ct->ten_san_pham }}">
                                                <div>
                                                    <div class="item-name">{{ $ct->ten_san_pham }}</div>
                                                    @if($ct->ten_bienthe)
                                                        <div class="item-sub">{{ $ct->ten_bienthe }}</div>
                                                    @endif
                                                    <div class="item-sub">{{ number_format($ct->gia) }}đ / cái</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $ct->so_luong }}</td>
                                        <td class="item-gia">{{ number_format($ct->so_luong * $ct->gia) }}đ</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div style="padding:14px 16px;">
                            <div class="tong-row">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($donhang->tong_tien_hang) }}đ</span>
                            </div>
                            <div class="tong-row">
                                <span>Phí vận chuyển:</span>
                                <span style="color:#27ae60;">Miễn phí</span>
                            </div>
                            @if($donhang->so_tien_giam > 0)
                            <div class="tong-row giam">
                                <span>Giảm giá@if($donhang->magiamgia) ({{ $donhang->magiamgia->ma_code }})@endif:</span>
                                <span>-{{ number_format($donhang->so_tien_giam) }}đ</span>
                            </div>
                            @endif
                            <div class="tong-row total">
                                <span>Tổng thanh toán:</span>
                                <span>{{ number_format($donhang->tong_thanh_toan) }}đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ====== ĐÁNH GIÁ — chỉ khi hoàn tất ====== --}}
                @if($donhang->trang_thai === \App\Models\Donhang::TRANG_THAI_HOAN_TAT)

                    {{-- Chưa đánh giá --}}
                    @if($sanphamChuaDanhGia->count() > 0)
                    <div class="review-block">
                        <div class="review-block-title">
                            <i class="fas fa-star"></i>
                            Đánh giá sản phẩm
                            <span style="background:rgba(255,255,255,.25);padding:1px 8px;border-radius:10px;font-size:0.72rem;">
                                {{ $sanphamChuaDanhGia->count() }} sản phẩm
                            </span>
                        </div>
                        <div style="padding:14px;">
                            @foreach($sanphamChuaDanhGia as $idx => $ct)
                            <div class="review-item-card">

                                {{-- Tên sản phẩm --}}
                                <div class="review-sp-header">
                                    <img src="{{ asset($ct->hinh_anh ?? 'images/no-image.png') }}" alt="">
                                    <div>
                                        <div class="sp-name">{{ $ct->ten_san_pham }}</div>
                                        @if($ct->ten_bienthe)
                                            <div class="sp-bienthe">{{ $ct->ten_bienthe }}</div>
                                        @endif
                                    </div>
                                </div>

                                <form action="{{ url('/don-hang/' . $donhang->id . '/danh-gia') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="sanpham_id" value="{{ $ct->sanpham_id }}">

                                    {{-- Chọn sao --}}
                                    <div style="font-size:0.78rem;color:#555;margin-bottom:5px;font-weight:600;">Chất lượng sản phẩm:</div>
                                    <div class="star-picker" id="stars-{{ $idx }}">
                                        @for($s = 1; $s <= 5; $s++)
                                            <i class="fas fa-star lit"
                                               data-val="{{ $s }}"
                                               onmouseover="hoverSao({{ $idx }}, {{ $s }})"
                                               onmouseleave="resetSao({{ $idx }})"
                                               onclick="chonSao({{ $idx }}, {{ $s }})"></i>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="sao_danh_gia" id="sao-{{ $idx }}" value="5">

                                    <textarea name="noi_dung" class="review-textarea"
                                              placeholder="Sản phẩm có đúng mô tả không? Chất lượng thế nào?..." required></textarea>

                                    <button type="submit" class="btn-gui">
                                        <i class="fas fa-paper-plane me-1"></i>Gửi đánh giá
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Đã đánh giá --}}
                    @if($sanphamDaDanhGia->count() > 0)
                    <div class="review-block">
                        <div class="review-block-title" style="background:#27ae60;">
                            <i class="fas fa-check-circle"></i> Đánh giá của bạn
                        </div>
                        <div style="padding:14px;">
                            @foreach($sanphamDaDanhGia as $ct)
                            @php
                                $bl = \App\Models\Binhluan::where('user_id', Auth::id())
                                        ->where('sanpham_id', $ct->sanpham_id)->first();
                            @endphp
                            @if($bl)
                            <div class="done-card">
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                                    <img src="{{ asset($ct->hinh_anh ?? 'images/no-image.png') }}"
                                         style="width:38px;height:38px;object-fit:cover;border:1px solid #ddd;" alt="">
                                    <div>
                                        <div style="font-weight:700;font-size:0.83rem;color:#222;">{{ $ct->ten_san_pham }}</div>
                                        <div class="done-stars">
                                            @for($s = 1; $s <= 5; $s++)
                                                <i class="fas fa-star {{ $s <= $bl->sao_danh_gia ? '' : 'off' }}"></i>
                                            @endfor
                                            <small class="ms-1" style="color:#888;">
                                                {{ \Carbon\Carbon::parse($bl->ngay_dang)->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div style="font-size:0.82rem;color:#444;font-style:italic;padding-left:46px;">
                                    "{{ $bl->noi_dung }}"
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                @endif
                {{-- end hoàn tất --}}

            </div>
        </div>

    </div>
</div>

@endsection

@section('extra-js')
<script>
// Lưu giá trị sao đã chọn
const saoDaChon = {};

function chonSao(idx, val) {
    saoDaChon[idx] = val;
    document.getElementById('sao-' + idx).value = val;
    capNhatSao(idx, val, true);
}

function hoverSao(idx, val) {
    capNhatSao(idx, val, false);
}

function resetSao(idx) {
    const val = saoDaChon[idx] ?? 5;
    capNhatSao(idx, val, false);
}

function capNhatSao(idx, val, save) {
    document.querySelectorAll('#stars-' + idx + ' i').forEach((el, i) => {
        el.classList.toggle('lit', i < val);
    });
}

// Mặc định 5 sao khi load
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.star-picker').forEach(function (picker) {
        const idx = picker.id.replace('stars-', '');
        saoDaChon[idx] = 5;
    });
});
</script>
@endsection