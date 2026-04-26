<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: Arial, sans-serif; background:#f0f4f8; padding:24px 12px; }

.wrap { max-width:600px; margin:0 auto; background:#fff; border-radius:4px; overflow:hidden; border:1px solid #dde3ea; }

/* Header */
.header { background:#1a5276; padding:24px 28px; text-align:center; }
.header .icon { width:52px; height:52px; background:#27ae60; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:12px; }
.header .icon svg { width:26px; height:26px; fill:#fff; }
.header h1 { color:#fff; font-size:1.1rem; font-weight:700; margin-bottom:4px; }
.header p { color:#aac9e0; font-size:.82rem; }
.order-code { display:inline-block; background:rgba(255,255,255,.15); color:#fff; font-weight:700; font-size:.95rem; letter-spacing:1.5px; padding:5px 18px; margin-top:12px; border-radius:2px; }

/* Section */
.section { padding:20px 28px; border-bottom:1px solid #eef0f3; }
.section:last-child { border-bottom:none; }
.section-title { font-size:.72rem; font-weight:700; color:#1a5276; text-transform:uppercase; letter-spacing:.8px; margin-bottom:12px; padding-bottom:7px; border-bottom:2px solid #eaf4fb; }

/* Info rows */
.info-row { display:flex; padding:6px 0; font-size:.84rem; }
.info-label { width:140px; color:#888; flex-shrink:0; }
.info-value { color:#222; font-weight:600; flex:1; }

/* Badge */
.badge { display:inline-block; font-size:.72rem; font-weight:700; padding:2px 10px; border-radius:20px; }
.badge-success { background:#d4edda; color:#155724; }
.badge-warning { background:#fff3cd; color:#856404; }
.badge-secondary { background:#e2e3e5; color:#383d41; }
.badge-blue { background:#cce5ff; color:#004085; }

/* Product table */
table { width:100%; border-collapse:collapse; font-size:.83rem; }
thead tr { background:#f4f7fb; }
th { padding:8px 10px; text-align:left; font-size:.72rem; color:#888; text-transform:uppercase; letter-spacing:.5px; }
th:last-child { text-align:right; }
td { padding:10px; border-bottom:1px solid #f0f0f0; vertical-align:middle; }
td:last-child { text-align:right; font-weight:700; color:#c0392b; }
.product-name { font-weight:600; color:#222; line-height:1.4; }
.product-variant { font-size:.75rem; color:#999; margin-top:2px; }
.product-qty { color:#666; font-size:.78rem; }

/* Totals */
.totals { padding:14px 28px; background:#fafbfc; }
.total-row { display:flex; justify-content:space-between; font-size:.84rem; padding:5px 0; color:#555; }
.total-row.discount { color:#27ae60; }
.total-row.grand { padding-top:10px; margin-top:6px; border-top:2px solid #e0e0e0; font-size:1rem; font-weight:700; color:#c0392b; }

/* Note */
.note-box { background:#fff8e1; border-left:3px solid #f0a500; padding:10px 14px; font-size:.82rem; color:#555; line-height:1.6; margin-top:10px; }

/* Footer */
.footer { background:#1a5276; padding:18px 28px; text-align:center; }
.footer p { color:#aac9e0; font-size:.75rem; line-height:1.7; }
.footer a { color:#7fc8f0; text-decoration:none; }

/* PayOS CTA */
.cta-box { background:#fff3cd; border:1px solid #ffc107; padding:14px 20px; text-align:center; margin:16px 0 0; border-radius:3px; }
.cta-box p { font-size:.83rem; color:#856404; margin-bottom:10px; }
.btn-pay { display:inline-block; background:#f0a500; color:#fff; font-weight:700; font-size:.85rem; padding:9px 24px; border-radius:3px; text-decoration:none; letter-spacing:.3px; }
</style>
</head>
<body>

@php
    $maDon = 'DH' . str_pad($donhang->id, 6, '0', STR_PAD_LEFT);
@endphp

<div class="wrap">

    {{-- Header --}}
    <div class="header">
        <div class="icon">
            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
        </div>
        <h1>Đặt hàng thành công!</h1>
        <p>Cảm ơn bạn đã mua sắm tại ShopDecor</p>
        <div class="order-code">#{{ $maDon }}</div>
    </div>

    {{-- Thông tin giao hàng --}}
    <div class="section">
        <div class="section-title">Thông tin giao hàng</div>

        <div class="info-row">
            <span class="info-label">Người nhận</span>
            <span class="info-value">{{ $donhang->ten_nguoi_nhan }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Số điện thoại</span>
            <span class="info-value">{{ $donhang->so_dien_thoai }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Địa chỉ</span>
            <span class="info-value">
                {{ $donhang->dia_chi_chi_tiet }}, {{ $donhang->phuong_xa }}, {{ $donhang->tinh_thanh }}
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Ngày đặt</span>
            <span class="info-value">{{ ($donhang->ngay_dat ?? $donhang->created_at)?->format('H:i — d/m/Y') ?? '—' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Thanh toán</span>
            <span class="info-value">
                @if($donhang->phuong_thuc_thanhtoan === 'cod')
                    <span class="badge badge-success">COD — Tiền mặt khi nhận</span>
                @else
                    <span class="badge badge-blue">Chuyển khoản ngân hàng</span>
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Trạng thái TT</span>
            <span class="info-value">
                @if($donhang->trang_thai_thanhtoan === 'da_thanh_toan')
                    <span class="badge badge-success">Đã thanh toán</span>
                @elseif($donhang->phuong_thuc_thanhtoan === 'cod')
                    <span class="badge badge-secondary">Thanh toán khi nhận hàng</span>
                @else
                    <span class="badge badge-warning">Chờ thanh toán</span>
                @endif
            </span>
        </div>

        @if($donhang->ghi_chu_khach)
        <div class="note-box">
            <strong>Ghi chú:</strong> {{ $donhang->ghi_chu_khach }}
        </div>
        @endif

        {{-- Nút thanh toán PayOS nếu chưa trả --}}
        @if($donhang->phuong_thuc_thanhtoan === 'payos' && $donhang->trang_thai_thanhtoan !== 'da_thanh_toan')
        <div class="cta-box">
            <p>Đơn hàng của bạn đang chờ thanh toán. Vui lòng hoàn tất để được xử lý sớm nhất.</p>
            <a href="{{ route('payos.checkout', $donhang->id) }}" class="btn-pay">Thanh toán ngay</a>
        </div>
        @endif
    </div>

    {{-- Sản phẩm --}}
    <div class="section">
        <div class="section-title">Sản phẩm đã đặt</div>
        <table>
            <thead>
                <tr>
                    <th style="width:55%">Sản phẩm</th>
                    <th style="width:10%;text-align:center">SL</th>
                    <th style="width:35%">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donhang->chitiets as $ct)
                <tr>
                    <td>
                        <div class="product-name">{{ $ct->ten_san_pham }}</div>
                        @if($ct->ten_bienthe)
                            <div class="product-variant">{{ $ct->ten_bienthe }}</div>
                        @endif
                        <div class="product-qty">{{ number_format($ct->gia) }}đ / cái</div>
                    </td>
                    <td style="text-align:center;font-weight:700">{{ $ct->so_luong }}</td>
                    <td>{{ number_format($ct->so_luong * $ct->gia) }}đ</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Tổng tiền --}}
    <div class="totals">
        <div class="total-row">
            <span>Tạm tính</span>
            <span>{{ number_format($donhang->tong_tien_hang) }}đ</span>
        </div>
        <div class="total-row">
            <span>Phí vận chuyển</span>
            <span style="color:#27ae60;font-weight:700">Miễn phí</span>
        </div>
        @if($donhang->so_tien_giam > 0)
        <div class="total-row discount">
            <span>Giảm giá @if($donhang->magiamgia)({{ $donhang->magiamgia->ma_code }})@endif</span>
            <span>-{{ number_format($donhang->so_tien_giam) }}đ</span>
        </div>
        @endif
        <div class="total-row grand">
            <span>Tổng thanh toán</span>
            <span>{{ number_format($donhang->tong_thanh_toan) }}đ</span>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>
            Nếu có thắc mắc, vui lòng liên hệ chúng tôi qua email hoặc hotline.<br>
            Đây là email tự động — vui lòng không reply trực tiếp.<br>
            <a href="{{ url('/') }}">ShopDecor</a> &nbsp;·&nbsp; Cảm ơn bạn đã tin tưởng mua sắm!
        </p>
    </div>

</div>

</body>
</html>