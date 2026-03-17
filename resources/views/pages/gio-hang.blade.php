@extends('layouts.app')

@section('title', 'Giỏ Hàng')

@section('extra-css')
<style>
    /* ===== BREADCRUMB ===== */
    .breadcrumb-bar {
        background: #eaf4fb;
        border-bottom: 1px solid #d0e8f5;
        padding: 8px 0;
        font-size: 0.82rem;
    }
    .breadcrumb-bar a { color: #1a5276; text-decoration: none; }
    .breadcrumb-bar a:hover { text-decoration: underline; }
    .breadcrumb-bar span { color: #888; }

    /* ===== SECTION TITLE ===== */
    .section-title {
        background: #1a5276;
        color: #fff;
        font-size: 0.95rem;
        font-weight: 700;
        padding: 8px 15px;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ===== BẢNG GIỎ HÀNG ===== */
    .cart-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
        background: #fff;
    }
    .cart-table th {
        background: #f5f5f5;
        border: 1px solid #ddd;
        padding: 10px 12px;
        font-weight: 700;
        color: #333;
        text-align: center;
    }
    .cart-table td {
        border: 1px solid #eee;
        padding: 12px;
        vertical-align: middle;
    }
    .cart-table tbody tr:hover { background: #fafafa; }

    .sp-img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border: 1px solid #ddd;
        flex-shrink: 0;
    }
    .sp-name {
        font-weight: 600;
        color: #222;
        text-decoration: none;
        font-size: 0.85rem;
        line-height: 1.4;
    }
    .sp-name:hover { color: #e74c3c; }
    .sp-bienthe {
        font-size: 0.75rem;
        color: #888;
        margin-top: 3px;
    }
    .sp-gia { color: #e74c3c; font-weight: 700; text-align: center; }
    .sp-thanhtien { color: #e74c3c; font-weight: 700; text-align: center; font-size: 0.95rem; }

    /* Số lượng */
    .qty-box {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
    }
    .qty-btn {
        width: 30px;
        height: 30px;
        border: 1px solid #ddd;
        background: #f5f5f5;
        cursor: pointer;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .qty-btn:hover { background: #ddd; }
    .qty-input {
        width: 45px;
        height: 30px;
        border: 1px solid #ddd;
        border-left: none;
        border-right: none;
        text-align: center;
        font-size: 0.85rem;
        font-weight: 600;
        outline: none;
    }

    /* Nút xóa */
    .btn-xoa {
        background: none;
        border: none;
        color: #e74c3c;
        cursor: pointer;
        font-size: 1rem;
        padding: 5px 8px;
        transition: transform 0.15s;
    }
    .btn-xoa:hover { transform: scale(1.2); }

    /* ===== TỔNG TIỀN ===== */
    .cart-summary {
        background: #fff;
        border: 1px solid #ddd;
    }
    .cart-summary-title {
        background: #1a5276;
        color: #fff;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 10px 15px;
        text-transform: uppercase;
    }
    .cart-summary-body { padding: 15px; }
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed #eee;
        font-size: 0.88rem;
    }
    .summary-row:last-child { border-bottom: none; }
    .summary-row.total {
        font-weight: 700;
        font-size: 1rem;
        color: #e74c3c;
        border-top: 2px solid #ddd;
        border-bottom: none;
        padding-top: 12px;
        margin-top: 4px;
    }
    .btn-thanhtoan {
        display: block;
        width: 100%;
        background: #e74c3c;
        color: #fff;
        border: none;
        padding: 12px;
        font-size: 0.95rem;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        margin-top: 12px;
        transition: background 0.2s;
    }
    .btn-thanhtoan:hover { background: #c0392b; color: #fff; }
    .btn-tieptuc {
        display: block;
        width: 100%;
        background: #fff;
        color: #1a5276;
        border: 2px solid #1a5276;
        padding: 10px;
        font-size: 0.88rem;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        margin-top: 8px;
        transition: all 0.2s;
    }
    .btn-tieptuc:hover { background: #1a5276; color: #fff; }

    /* ===== EMPTY ===== */
    .empty-cart {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border: 1px solid #ddd;
    }
    .empty-cart i { font-size: 4rem; color: #ddd; margin-bottom: 15px; }
    .empty-cart h5 { color: #555; margin-bottom: 8px; }
    .empty-cart p { color: #999; font-size: 0.88rem; }

    .main-content { padding: 20px 0; }
</style>
@endsection

@section('content')

{{-- BREADCRUMB --}}
<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Trang chủ</a>
        <span class="mx-2">›</span>
        <span>Giỏ hàng</span>
    </div>
</div>

<div class="main-content">
    <div class="container">

        @if($giohang->chitiets->count() > 0)
        <div class="row align-items-start">

            {{-- BẢNG SẢN PHẨM --}}
            <div class="col-lg-8 mb-4">
                <div class="section-title mb-0">
                    <i class="fas fa-shopping-cart"></i>
                    GIỎ HÀNG ({{ $giohang->chitiets->count() }} sản phẩm)
                </div>

                <div style="overflow-x:auto;">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th style="width:40%;">Sản phẩm</th>
                                <th style="width:15%;">Đơn giá</th>
                                <th style="width:20%;">Số lượng</th>
                                <th style="width:18%;">Thành tiền</th>
                                <th style="width:7%;"></th>
                            </tr>
                        </thead>
                        <tbody id="cartBody">
                            @foreach($giohang->chitiets as $ct)
                            <tr id="row-{{ $ct->id }}">
                                {{-- Sản phẩm --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset($ct->sanpham->anhChinh?->duong_dan_anh ?? 'images/no-image.png') }}"
                                             class="sp-img" alt="{{ $ct->sanpham->ten_san_pham }}">
                                        <div>
                                            <a href="{{ url('/san-pham/' . $ct->sanpham->slug) }}" class="sp-name">
                                                {{ $ct->sanpham->ten_san_pham }}
                                            </a>
                                            @if($ct->bienthe)
                                                <div class="sp-bienthe">
                                                    <i class="fas fa-tag me-1"></i>{{ $ct->bienthe->ten_bienthe }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Đơn giá --}}
                                <td class="sp-gia">{{ number_format($ct->gia) }}đ</td>

                                {{-- Số lượng --}}
                                <td>
                                    <div class="qty-box">
                                        <button class="qty-btn" onclick="doiSoLuong({{ $ct->id }}, -1)">−</button>
                                        <input type="number" class="qty-input"
                                               id="qty-{{ $ct->id }}"
                                               value="{{ $ct->so_luong }}"
                                               min="1" max="99"
                                               onchange="capNhatSoLuong({{ $ct->id }})">
                                        <button class="qty-btn" onclick="doiSoLuong({{ $ct->id }}, 1)">+</button>
                                    </div>
                                </td>

                                {{-- Thành tiền --}}
                                <td class="sp-thanhtien" id="thanhtien-{{ $ct->id }}">
                                    {{ number_format($ct->thanh_tien) }}đ
                                </td>

                                {{-- Xóa --}}
                                <td class="text-center">
                                    <button class="btn-xoa" onclick="xoaSanPham({{ $ct->id }})" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Nút xóa tất cả --}}
                <div class="mt-2 text-end">
                    <form action="{{ url('/gio-hang/xoa-tat') }}" method="POST"
                          onsubmit="return confirm('Xóa toàn bộ giỏ hàng?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:none;border:none;color:#e74c3c;font-size:0.82rem;cursor:pointer;">
                            <i class="fas fa-trash me-1"></i>Xóa tất cả
                        </button>
                    </form>
                </div>
            </div>

            {{-- TỔNG TIỀN --}}
            <div class="col-lg-4">
                <div class="cart-summary">
                    <div class="cart-summary-title">
                        <i class="fas fa-receipt me-2"></i>TỔNG ĐƠN HÀNG
                    </div>
                    <div class="cart-summary-body">
                        <div class="summary-row">
                            <span>Tạm tính:</span>
                            <span id="tamTinh">{{ number_format($giohang->tong_tien) }}đ</span>
                        </div>
                        <div class="summary-row">
                            <span>Phí vận chuyển:</span>
                            <span style="color:#27ae60;">Miễn phí</span>
                        </div>
                        <div class="summary-row total">
                            <span>Tổng cộng:</span>
                            <span id="tongCong">{{ number_format($giohang->tong_tien) }}đ</span>
                        </div>

                        <a href="{{ url('/thanh-toan') }}" class="btn-thanhtoan">
                            <i class="fas fa-credit-card me-2"></i>TIẾN HÀNH THANH TOÁN
                        </a>
                        <a href="{{ url('/') }}" class="btn-tieptuc">
                            <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                        </a>
                    </div>
                </div>

                {{-- Chính sách --}}
                <div style="background:#fff;border:1px solid #ddd;padding:12px 15px;margin-top:15px;font-size:0.8rem;color:#555;">
                    <div class="mb-2"><i class="fas fa-truck text-danger me-2"></i>Miễn phí ship đơn từ 500.000đ</div>
                    <div class="mb-2"><i class="fas fa-undo text-danger me-2"></i>Đổi trả trong 7 ngày</div>
                    <div><i class="fas fa-shield-alt text-danger me-2"></i>Bảo hành 12 tháng</div>
                </div>
            </div>

        </div>

        @else
        {{-- GIỎ HÀNG TRỐNG --}}
        <div class="empty-cart">
            <i class="fas fa-shopping-cart d-block"></i>
            <h5>Giỏ hàng của bạn đang trống</h5>
            <p>Hãy thêm sản phẩm vào giỏ hàng để tiến hành mua sắm.</p>
            <a href="{{ url('/') }}"
               style="background:#1a5276;color:#fff;padding:10px 28px;text-decoration:none;display:inline-block;margin-top:15px;font-weight:600;">
                <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
            </a>
        </div>
        @endif

    </div>
</div>

@endsection

@section('extra-js')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// Đổi số lượng bằng nút +/-
function doiSoLuong(id, delta) {
    const input = document.getElementById('qty-' + id);
    const val = parseInt(input.value) + delta;
    if (val >= 1 && val <= 99) {
        input.value = val;
        capNhatSoLuong(id);
    }
}

// Gọi API cập nhật
function capNhatSoLuong(id) {
    const soLuong = parseInt(document.getElementById('qty-' + id).value);
    if (soLuong < 1) return;

    fetch(`/gio-hang/cap-nhat/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ so_luong: soLuong })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('thanhtien-' + id).textContent = data.thanh_tien;
            document.getElementById('tamTinh').textContent = data.tong_tien;
            document.getElementById('tongCong').textContent = data.tong_tien;
        }
    });
}

// Xóa 1 sản phẩm
function xoaSanPham(id) {
    if (!confirm('Xóa sản phẩm này khỏi giỏ hàng?')) return;

    fetch(`/gio-hang/xoa/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('row-' + id).remove();
            // Nếu hết hàng thì reload
            if (document.querySelectorAll('#cartBody tr').length === 0) {
                location.reload();
            }
        }
    });
}
</script>
@endsection