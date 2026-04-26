@extends('layouts.app')
@section('title', 'Đặt Hàng Thành Công')

@php
    $maDonHang = 'DH' . str_pad($donhang->id, 6, '0', STR_PAD_LEFT);
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
                            {{ $donhang->tinh_thanh }}
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
                                <span class="badge text-white" style="background:#0F6E56">
                                    <i class="fas fa-university me-1"></i>Thanh toán qua ngân hàng
                                </span>
                            @endif
                        </div>

                        <div class="col-4 text-muted">Trạng thái:</div>
                        <div class="col-8">
                            @if($donhang->trang_thai_thanhtoan === 'da_thanh_toan')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Đã thanh toán
                                </span>
                            @elseif($donhang->phuong_thuc_thanhtoan === 'cod')
                                <span class="badge bg-secondary">
                                    <i class="fas fa-hand-holding-usd me-1"></i>Thanh toán khi nhận hàng
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock me-1"></i>Chờ thanh toán
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

            {{-- Nút điều hướng --}}
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ url('/') }}" class="btn btn-primary px-4">
                    <i class="fas fa-home me-2"></i>Về trang chủ
                </a>
                <a href="{{ url('/san-pham') }}" class="btn btn-outline-primary px-4">
                    <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                </a>
                @if($donhang->phuong_thuc_thanhtoan === 'payos' && $donhang->trang_thai_thanhtoan !== 'da_thanh_toan')
                <a href="{{ route('payos.checkout', $donhang->id) }}" class="btn btn-warning px-4 fw-bold">
                    <i class="fas fa-university me-2"></i>Thanh toán ngay
                </a>
                @endif
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

@endsection

@section('extra-js')
@endsection