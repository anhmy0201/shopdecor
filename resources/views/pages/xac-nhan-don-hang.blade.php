@extends('layouts.app')

@section('title', 'Đặt Hàng Thành Công')

@section('extra-css')
<style>
    .breadcrumb-bar {
        background: #eaf4fb;
        border-bottom: 1px solid #d0e8f5;
        padding: 8px 0;
        font-size: 0.82rem;
    }
    .breadcrumb-bar a { color: #1a5276; text-decoration: none; }
    .breadcrumb-bar span { color: #888; }

    .main-content { padding: 30px 0 50px; }

    /* ===== BANNER THÀNH CÔNG ===== */
    .success-banner {
        background: #fff;
        border: 1px solid #ddd;
        border-top: 4px solid #27ae60;
        text-align: center;
        padding: 30px 20px;
        margin-bottom: 24px;
    }
    .success-icon {
        width: 64px; height: 64px;
        background: #27ae60;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px;
        font-size: 1.8rem;
        color: #fff;
    }
    .success-banner h4 { color: #27ae60; font-weight: 700; font-size: 1.1rem; margin-bottom: 6px; }
    .success-banner p  { color: #666; font-size: 0.85rem; margin: 0; }
    .order-code {
        display: inline-block;
        background: #eaf4fb;
        border: 1px solid #b8d9ed;
        color: #1a5276;
        font-weight: 700;
        font-size: 1rem;
        padding: 6px 18px;
        margin-top: 12px;
        letter-spacing: 1px;
    }

    /* ===== BLOCK ===== */
    .info-block {
        border: 1px solid #ddd;
        background: #fff;
        margin-bottom: 18px;
    }
    .info-block-title {
        background: #1a5276;
        color: #fff;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 8px 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
    }
    .info-block-body { padding: 16px; }

    /* ===== THÔNG TIN GIAO HÀNG ===== */
    .info-row {
        display: flex;
        gap: 8px;
        padding: 6px 0;
        border-bottom: 1px dashed #eee;
        font-size: 0.85rem;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row-label { color: #888; min-width: 140px; flex-shrink: 0; }
    .info-row-val { color: #222; font-weight: 600; }

    /* ===== BADGE TRẠNG THÁI ===== */
    .badge-tt {
        display: inline-block;
        padding: 3px 10px;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 2px;
    }
    .badge-cod      { background: #e8f8f0; color: #1e8449; }
    .badge-ck       { background: #eaf4fb; color: #1a5276; }
    .badge-chua_tt  { background: #fdf6e3; color: #b7770d; }
    .badge-da_tt    { background: #e8f8f0; color: #1e8449; }

    /* ===== BẢNG SẢN PHẨM ===== */
    .order-table { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
    .order-table th {
        background: #f5f5f5;
        border: 1px solid #ddd;
        padding: 8px 12px;
        font-weight: 700;
        color: #333;
        text-align: center;
    }
    .order-table td {
        border: 1px solid #eee;
        padding: 10px 12px;
        vertical-align: middle;
    }
    .order-table tbody tr:hover { background: #fafafa; }

    .item-img {
        width: 54px; height: 54px;
        object-fit: cover;
        border: 1px solid #ddd;
        flex-shrink: 0;
    }
    .item-name { font-weight: 600; color: #222; line-height: 1.4; }
    .item-bienthe { font-size: 0.75rem; color: #888; margin-top: 2px; }
    .item-gia { color: #e74c3c; font-weight: 700; text-align: right; }

    /* ===== TỔNG ===== */
    .tong-row {
        display: flex;
        justify-content: space-between;
        padding: 7px 0;
        font-size: 0.85rem;
        border-bottom: 1px dashed #eee;
    }
    .tong-row:last-child { border-bottom: none; }
    .tong-row.giam { color: #27ae60; }
    .tong-row.total {
        font-weight: 700;
        font-size: 1rem;
        color: #e74c3c;
        border-top: 2px solid #ddd;
        border-bottom: none;
        padding-top: 10px;
        margin-top: 4px;
    }

    /* ===== NÚT ===== */
    .btn-primary-custom {
        background: #1a5276;
        color: #fff;
        border: none;
        padding: 10px 24px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-primary-custom:hover { background: #154360; color: #fff; }
    .btn-outline-custom {
        background: #fff;
        color: #1a5276;
        border: 2px solid #1a5276;
        padding: 9px 22px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s;
    }
    .btn-outline-custom:hover { background: #1a5276; color: #fff; }

    /* Chuyển khoản info box */
    .ck-info {
        background: #eaf4fb;
        border: 1px solid #b8d9ed;
        padding: 14px 16px;
        margin-top: 14px;
        font-size: 0.83rem;
    }
    .ck-info strong { color: #1a5276; display: block; margin-bottom: 6px; font-size: 0.85rem; }
    .ck-info-row { display: flex; gap: 6px; padding: 3px 0; }
    .ck-info-label { color: #666; min-width: 110px; }
    .ck-info-val { font-weight: 700; color: #1a5276; }
</style>
@endsection

@section('content')

<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Trang chủ</a>
        <span class="mx-2">›</span>
        <span>Xác nhận đơn hàng</span>
    </div>
</div>

<div class="main-content">
    <div class="container">

        {{-- Banner thành công --}}
        <div class="success-banner">
            <div class="success-icon"><i class="fas fa-check"></i></div>
            <h4>Đặt hàng thành công!</h4>
            <p>Cảm ơn bạn đã mua sắm tại cửa hàng. Chúng tôi sẽ liên hệ xác nhận sớm nhất.</p>
            <div class="order-code">#DH{{ str_pad($donhang->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <div class="row">

            {{-- CỘT TRÁI --}}
            <div class="col-lg-7 mb-4">

                {{-- Thông tin giao hàng --}}
                <div class="info-block mb-3">
                    <div class="info-block-title"><i class="fas fa-map-marker-alt"></i> Thông tin giao hàng</div>
                    <div class="info-block-body">
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
                            <span class="info-row-label">Ngày đặt:</span>
                            <span class="info-row-val">{{ $donhang->ngay_dat->format('H:i d/m/Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-label">Thanh toán:</span>
                            <span class="info-row-val">
                                @if($donhang->phuong_thuc_thanhtoan === 'cod')
                                    <span class="badge-tt badge-cod"><i class="fas fa-money-bill-wave me-1"></i>COD - Tiền mặt khi nhận</span>
                                @else
                                    <span class="badge-tt badge-ck"><i class="fas fa-university me-1"></i>Chuyển khoản</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-label">Trạng thái:</span>
                            <span class="info-row-val">
                                <span class="badge-tt badge-chua_tt">
                                    <i class="fas fa-clock me-1"></i>Chờ xác nhận
                                </span>
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

                {{-- Thông tin chuyển khoản nếu chọn CK --}}
                @if($donhang->phuong_thuc_thanhtoan === 'chuyen_khoan')
                <div class="info-block mb-3">
                    <div class="info-block-title"><i class="fas fa-university"></i> Thông tin chuyển khoản</div>
                    <div class="info-block-body">
                        <p style="font-size:0.83rem;color:#555;margin-bottom:10px;">
                            Vui lòng chuyển khoản trong vòng <strong style="color:#e74c3c;">24 giờ</strong>
                            để đơn hàng được xử lý nhanh nhất.
                        </p>
                        <div class="ck-info">
                            <strong><i class="fas fa-piggy-bank me-1"></i>Thông tin tài khoản</strong>
                            <div class="ck-info-row"><span class="ck-info-label">Ngân hàng:</span><span class="ck-info-val">Vietcombank</span></div>
                            <div class="ck-info-row"><span class="ck-info-label">Số tài khoản:</span><span class="ck-info-val">1234567890</span></div>
                            <div class="ck-info-row"><span class="ck-info-label">Chủ tài khoản:</span><span class="ck-info-val">NGUYEN VAN A</span></div>
                            <div class="ck-info-row">
                                <span class="ck-info-label">Nội dung CK:</span>
                                <span class="ck-info-val">DH{{ str_pad($donhang->id, 6, '0', STR_PAD_LEFT) }} {{ Auth::user()->so_dien_thoai }}</span>
                            </div>
                            <div class="ck-info-row">
                                <span class="ck-info-label">Số tiền:</span>
                                <span class="ck-info-val" style="color:#e74c3c;">{{ number_format($donhang->tong_thanh_toan) }}đ</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Nút điều hướng --}}
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ url('/') }}" class="btn-primary-custom">
                        <i class="fas fa-home me-2"></i>Về trang chủ
                    </a>
                    <a href="{{ url('/san-pham') }}" class="btn-outline-custom">
                        <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>

            </div>

            {{-- CỘT PHẢI: CHI TIẾT ĐƠN --}}
            <div class="col-lg-5">
                <div class="info-block">
                    <div class="info-block-title">
                        <i class="fas fa-list-ul"></i> Chi tiết đơn hàng
                    </div>
                    <div class="info-block-body" style="padding:0;">
                        <div style="overflow-x:auto;">
                            <table class="order-table">
                                <thead>
                                    <tr>
                                        <th style="width:50%;text-align:left;">Sản phẩm</th>
                                        <th style="width:15%;">SL</th>
                                        <th style="width:35%;">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($donhang->chitiets as $ct)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($ct->hinh_anh)
                                                    <img src="{{ asset($ct->hinh_anh) }}" class="item-img" alt="{{ $ct->ten_san_pham }}">
                                                @endif
                                                <div>
                                                    <div class="item-name">{{ $ct->ten_san_pham }}</div>
                                                    @if($ct->ten_bienthe)
                                                        <div class="item-bienthe">{{ $ct->ten_bienthe }}</div>
                                                    @endif
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
                                <span>
                                    Giảm giá
                                    @if($donhang->magiamgia)
                                        ({{ $donhang->magiamgia->ma_code }}):
                                    @endif
                                </span>
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
            </div>

        </div>
    </div>
</div>

@endsection