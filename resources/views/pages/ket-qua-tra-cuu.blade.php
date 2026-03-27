{{-- resources/views/pages/ket-qua-tra-cuu.blade.php --}}

@extends('layouts.app')
@section('title', 'Kết quả tra cứu đơn hàng')

@section('content')
<style>
    .tc-wrap { max-width: 780px; margin: 0 auto; padding: 2.5rem 1rem 4rem; }

    /* Status badge */
    .tc-status { display:inline-flex;align-items:center;gap:7px;padding:6px 16px;border-radius:999px;font-size:.8125rem;font-weight:500; }
    .tc-status .dot { width:7px;height:7px;border-radius:50%;flex-shrink:0; }
    .tc-status-0 { background:#fef9ec;color:#92400e; } .tc-status-0 .dot { background:#f59e0b; }
    .tc-status-1 { background:#eff6ff;color:#1d4ed8; } .tc-status-1 .dot { background:#3b82f6; }
    .tc-status-2 { background:#f0fdf4;color:#166534; } .tc-status-2 .dot { background:#22c55e; }
    .tc-status-3 { background:#fef2f2;color:#991b1b; } .tc-status-3 .dot { background:#ef4444; }

    /* Stepper */
    .stepper { display:flex;align-items:flex-start;margin-bottom:2rem; }
    .step-item { flex:1;display:flex;flex-direction:column;align-items:center;position:relative; }
    .step-item:not(:last-child)::after { content:'';position:absolute;top:13px;left:calc(50% + 15px);right:calc(-50% + 15px);height:1.5px;background:#e5e7eb;z-index:0; }
    .step-item.done:not(:last-child)::after { background:#22c55e; }
    .step-circle { width:27px;height:27px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:600;z-index:1;position:relative;border:1.5px solid #e5e7eb;background:#fff;color:#9ca3af; }
    .step-item.active .step-circle { background:#0d6efd;border-color:#0d6efd;color:#fff;box-shadow:0 0 0 4px rgba(13,110,253,.12); }
    .step-item.done .step-circle   { background:#f0fdf4;border-color:#22c55e;color:#16a34a; }
    .step-label { font-size:.6875rem;color:#9ca3af;margin-top:5px;text-align:center;line-height:1.3; }
    .step-item.active .step-label { color:#0d6efd;font-weight:500; }
    .step-item.done .step-label   { color:#16a34a; }

    /* Cards */
    .tc-card { background:#fff;border:1px solid #f0f0f0 !important;border-radius:14px !important;box-shadow:none !important; }
    .tc-card-title { font-size:.6875rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:14px; }

    /* Product thumb */
    .product-thumb {
        width:66px; height:66px;
        border-radius:10px;
        object-fit:cover;
        border:1px solid #f0f0f0;
        flex-shrink:0;
        background:#f9fafb;
    }
    .product-thumb-placeholder {
        width:66px; height:66px;
        border-radius:10px;
        border:1px solid #f0f0f0;
        flex-shrink:0;
        background:#f3f4f6;
        display:flex; align-items:center; justify-content:center;
    }
    .product-variant { display:inline-block;font-size:.6875rem;padding:2px 8px;background:#f3f4f6;color:#6b7280;border-radius:4px; }

    /* Payment */
    .pay-total-val { font-size:1.1rem;font-weight:700;color:#0d6efd; }
    .pay-free { color:#16a34a;font-weight:500; }
    .pay-discount { color:#16a34a;font-weight:500; }
    .order-code-box { background:#f9fafb;border-radius:8px;padding:10px 12px;margin-top:12px; }

    /* Buttons */
    .btn-tc { border-radius:9px;font-size:.8125rem;font-weight:500;padding:9px 18px; }
</style>

<div class="tc-wrap">

    {{-- Header --}}
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h5 class="fw-semibold mb-1">
                Đơn hàng #DH{{ str_pad($donhang->id, 6, '0', STR_PAD_LEFT) }}
            </h5>
            <span class="text-muted" style="font-size:.8125rem">
                Đặt ngày {{ $donhang->ngay_dat->format('d/m/Y') }} lúc {{ $donhang->ngay_dat->format('H:i') }}
            </span>
        </div>
        @php
            $statusMap = [
                0 => ['label' => 'Chờ xác nhận', 'cls' => 'tc-status-0'],
                1 => ['label' => 'Đang xử lý',   'cls' => 'tc-status-1'],
                2 => ['label' => 'Hoàn tất',      'cls' => 'tc-status-2'],
                3 => ['label' => 'Đã hủy',        'cls' => 'tc-status-3'],
            ];
            $st = $statusMap[$donhang->trang_thai] ?? ['label' => 'Không rõ', 'cls' => 'tc-status-0'];
        @endphp
        <span class="tc-status {{ $st['cls'] }}">
            <span class="dot"></span>{{ $st['label'] }}
        </span>
    </div>

    {{-- Stepper (ẩn khi đã hủy) --}}
    @if ($donhang->trang_thai != 3)
        @php $step = $donhang->trang_thai; @endphp
        <div class="stepper">
            @foreach (['Chờ xác nhận', 'Đang xử lý', 'Đang giao', 'Hoàn tất'] as $i => $label)
                <div class="step-item {{ $i < $step ? 'done' : ($i == $step ? 'active' : '') }}">
                    <div class="step-circle">
                        @if ($i < $step)
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                <path d="M2 6L4.5 8.5L10 3" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                    <span class="step-label">{{ $label }}</span>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Grid --}}
    <div class="row g-3">

        {{-- Cột trái --}}
        <div class="col-md-7 d-flex flex-column gap-3">

            {{-- Sản phẩm --}}
            <div class="tc-card card p-3">
                <p class="tc-card-title">Sản phẩm</p>
                @foreach ($donhang->chitiets as $ct)
                    <div class="d-flex gap-3 {{ !$loop->first ? 'pt-3 mt-3 border-top' : '' }}">

                        {{--
                            ĐÃ SỬA: dùng asset($ct->hinh_anh) — KHÔNG có 'storage/'
                            vì duong_dan_anh được lưu dạng "sanpham/ten-anh.jpg"
                            và asset() đã trỏ đúng vào public/storage/ thông qua symlink
                            Xem cách dùng trong san-pham.blade.php dòng 81:
                            asset($sanpham->anhChinh?->duong_dan_anh ?? 'images/no-image.png')
                        --}}
                        @if ($ct->hinh_anh)
                            <img
                                src="{{ asset($ct->hinh_anh) }}"
                                alt="{{ $ct->ten_san_pham }}"
                                class="product-thumb"
                                onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                            >
                            <div class="product-thumb-placeholder" style="display:none">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="3" width="18" height="18" rx="3" stroke="#d1d5db" stroke-width="1.5"/>
                                    <circle cx="8.5" cy="8.5" r="1.5" fill="#d1d5db"/>
                                    <path d="M3 15l5-4 4 3 3-2.5 6 5.5" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        @else
                            <div class="product-thumb-placeholder">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="3" width="18" height="18" rx="3" stroke="#d1d5db" stroke-width="1.5"/>
                                    <circle cx="8.5" cy="8.5" r="1.5" fill="#d1d5db"/>
                                    <path d="M3 15l5-4 4 3 3-2.5 6 5.5" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        @endif

                        <div class="flex-grow-1 min-w-0">
                            <p class="mb-1 fw-medium text-truncate" style="font-size:.875rem">{{ $ct->ten_san_pham }}</p>
                            @if ($ct->ten_bienthe)
                                <span class="product-variant">{{ $ct->ten_bienthe }}</span>
                            @endif
                            <p class="mb-0 text-muted mt-1" style="font-size:.75rem">x{{ $ct->so_luong }}</p>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <p class="mb-0 fw-semibold" style="font-size:.875rem">{{ number_format($ct->gia * $ct->so_luong) }}đ</p>
                            <p class="mb-0 text-muted" style="font-size:.75rem">{{ number_format($ct->gia) }}đ / cái</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Địa chỉ --}}
            <div class="tc-card card p-3">
                <p class="tc-card-title">Giao đến</p>
                <p class="mb-1 fw-medium" style="font-size:.9375rem">{{ $donhang->ten_nguoi_nhan }}</p>
                <p class="mb-1 text-muted" style="font-size:.8125rem">{{ $donhang->so_dien_thoai }}</p>
                <p class="mb-0 text-muted" style="font-size:.8125rem;line-height:1.55">
                    {{ $donhang->dia_chi_chi_tiet }},
                    {{ $donhang->phuong_xa }},
                    {{ $donhang->quan_huyen }},
                    {{ $donhang->tinh_thanh }}
                </p>
            </div>
        </div>

        {{-- Cột phải: Thanh toán --}}
        <div class="col-md-5">
            <div class="tc-card card p-3 h-100">
                <p class="tc-card-title">Chi tiết thanh toán</p>

                <div class="d-flex justify-content-between mb-2" style="font-size:.8125rem">
                    <span class="text-muted">Tiền hàng</span>
                    <span class="fw-medium">{{ number_format($donhang->tong_tien_hang) }}đ</span>
                </div>

                @if ($donhang->so_tien_giam > 0)
                    <div class="d-flex justify-content-between mb-2" style="font-size:.8125rem">
                        <span class="text-muted">
                            Giảm giá
                            @if ($donhang->magiamgia)
                                <span class="product-variant ms-1" style="background:#f0fdf4;color:#16a34a">
                                    {{ $donhang->magiamgia->ma_code }}
                                </span>
                            @endif
                        </span>
                        <span class="pay-discount">-{{ number_format($donhang->so_tien_giam) }}đ</span>
                    </div>
                @endif

                <div class="d-flex justify-content-between mb-2" style="font-size:.8125rem">
                    <span class="text-muted">Phí vận chuyển</span>
                    @if ($donhang->phi_ship > 0)
                        <span class="fw-medium">{{ number_format($donhang->phi_ship) }}đ</span>
                    @else
                        <span class="pay-free">Miễn phí</span>
                    @endif
                </div>

                <hr class="my-2">

                <div class="d-flex justify-content-between align-items-baseline">
                    <span class="fw-semibold" style="font-size:.9375rem">Tổng thanh toán</span>
                    <span class="pay-total-val">{{ number_format($donhang->tong_thanh_toan) }}đ</span>
                </div>

                <div class="d-flex justify-content-between mt-2 pt-2 border-top" style="font-size:.75rem;color:#9ca3af">
                    <span>Thanh toán</span>
                    <span>{{ $donhang->phuong_thuc_thanhtoan === 'cod' ? 'Tiền mặt (COD)' : 'Chuyển khoản' }}</span>
                </div>

                <div class="order-code-box">
                    <p class="mb-1" style="font-size:.6875rem;color:#9ca3af">Mã đơn hàng</p>
                    <p class="mb-0 fw-semibold" style="font-size:.875rem;letter-spacing:.04em">
                        #DH{{ str_pad($donhang->id, 6, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Footer --}}
    <div class="mt-4 d-flex flex-wrap gap-2 justify-content-between align-items-center">
        <a href="{{ route('tra-cuu-don-hang') }}" class="btn btn-outline-secondary btn-tc">
            ← Tra cứu đơn khác
        </a>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('lien-he') }}" class="btn btn-outline-primary btn-tc">
                Liên hệ hỗ trợ
            </a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-tc">
                Tạo tài khoản quản lý đơn
            </a>
        </div>
    </div>

</div>
@endsection