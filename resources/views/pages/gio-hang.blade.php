@extends('layouts.app')
@section('title', 'Giỏ Hàng')

@section('extra-css')
<style>
    /* Bảng giỏ hàng */
    .cart-table { width:100%; border-collapse:collapse; font-size:0.88rem; background:#fff; }
    .cart-table th { background:#f5f5f5; border:1px solid #ddd; padding:10px 12px; font-weight:700; color:#333; text-align:center; }
    .cart-table td { border:1px solid #eee; padding:12px; vertical-align:middle; }
    .cart-table tbody tr:hover { background:#fafafa; }

    /* Số lượng */
    .qty-box { display:flex; align-items:center; justify-content:center; }
    .qty-btn { width:30px; height:30px; border:1px solid #ddd; background:#f5f5f5; cursor:pointer; font-size:0.9rem; display:flex; align-items:center; justify-content:center; }
    .qty-btn:hover { background:#ddd; }
    .qty-input { width:45px; height:30px; border:1px solid #ddd; border-left:none; border-right:none; text-align:center; font-size:0.85rem; font-weight:600; outline:none; }
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
<div style="background:#eaf4fb;border-bottom:1px solid #d0e8f5;padding:8px 0;font-size:0.82rem;">
    <div class="container">
        <a href="{{ url('/') }}" class="text-decoration-none" style="color:#1a5276">
            <i class="fas fa-home me-1"></i>Trang chủ
        </a>
        <span class="mx-2 text-muted">›</span>
        <span class="text-muted">Giỏ hàng</span>
    </div>
</div>

<div class="container py-4">

    @if($giohang->chitiets->count() > 0)
    <div class="row align-items-start g-4">

        {{-- BẢNG SẢN PHẨM --}}
        <div class="col-lg-8">
            <div class="fw-bold py-2 px-3 mb-0 text-white" style="background:#1a5276;font-size:0.95rem">
                <i class="fas fa-shopping-cart me-2"></i>GIỎ HÀNG ({{ $giohang->chitiets->count() }} sản phẩm)
            </div>
            <div style="overflow-x:auto;">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th style="width:40%;text-align:left">Sản phẩm</th>
                            <th style="width:15%">Đơn giá</th>
                            <th style="width:20%">Số lượng</th>
                            <th style="width:18%">Thành tiền</th>
                            <th style="width:7%"></th>
                        </tr>
                    </thead>
                    <tbody id="cartBody">
                        @foreach($giohang->chitiets as $ct)
                        <tr id="row-{{ $ct->id }}">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset($ct->sanpham->anhChinh?->duong_dan_anh ?? 'images/no-image.png') }}"
                                         width="70" height="70"
                                         class="border flex-shrink-0"
                                         style="object-fit:cover">
                                    <div>
                                        <a href="{{ url('/san-pham/' . $ct->sanpham->slug) }}"
                                           class="fw-bold text-decoration-none text-dark"
                                           style="font-size:0.85rem;line-height:1.4">
                                            {{ $ct->sanpham->ten_san_pham }}
                                        </a>
                                        @if($ct->bienthe)
                                            <div class="text-muted small mt-1">
                                                <i class="fas fa-tag me-1"></i>{{ $ct->bienthe->ten_bienthe }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center fw-bold text-danger">{{ number_format($ct->gia) }}đ</td>
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
                            <td class="text-center fw-bold text-danger" id="thanhtien-{{ $ct->id }}" style="font-size:0.95rem">
                                {{ number_format($ct->thanh_tien) }}đ
                            </td>
                            <td class="text-center">
                                <button class="btn btn-link text-danger p-1" onclick="xoaSanPham({{ $ct->id }})" title="Xóa">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-2 text-end">
                <form action="{{ url('/gio-hang/xoa-tat') }}" method="POST"
                      onsubmit="return confirm('Xóa toàn bộ giỏ hàng?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-link text-danger p-0" style="font-size:0.82rem">
                        <i class="fas fa-trash me-1"></i>Xóa tất cả
                    </button>
                </form>
            </div>
        </div>

        {{-- TỔNG TIỀN --}}
        <div class="col-lg-4">
            <div class="border">
                <div class="fw-bold py-2 px-3 text-white text-uppercase" style="background:#1a5276;font-size:0.9rem">
                    <i class="fas fa-receipt me-2"></i>Tổng Đơn Hàng
                </div>
                <div class="p-3">
                    <div class="d-flex justify-content-between py-2 border-bottom small">
                        <span class="text-muted">Tạm tính</span>
                        <span id="tamTinh">{{ number_format($giohang->tong_tien) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom small">
                        <span class="text-muted">Phí vận chuyển</span>
                        <span class="text-success fw-bold">Miễn phí</span>
                    </div>
                    <div class="d-flex justify-content-between pt-3 fw-bold text-danger" style="font-size:1rem">
                        <span>Tổng cộng</span>
                        <span id="tongCong">{{ number_format($giohang->tong_tien) }}đ</span>
                    </div>

                    <a href="{{ url('/thanh-toan') }}"
                       class="btn btn-danger d-block mt-3 fw-bold py-2">
                        <i class="fas fa-credit-card me-2"></i>TIẾN HÀNH THANH TOÁN
                    </a>
                    <a href="{{ url('/') }}"
                       class="btn btn-outline-secondary d-block mt-2 fw-bold py-2"
                       style="color:#1a5276;border-color:#1a5276">
                        <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            </div>

            {{-- Chính sách --}}
            <div class="border mt-3 p-3 bg-white small text-muted">
                <div class="mb-2"><i class="fas fa-truck text-danger me-2"></i>Miễn phí ship đơn từ 500.000đ</div>
                <div class="mb-2"><i class="fas fa-undo text-danger me-2"></i>Đổi trả trong 7 ngày</div>
                <div><i class="fas fa-shield-alt text-danger me-2"></i>Bảo hành 12 tháng</div>
            </div>
        </div>

    </div>

    @else
    <div class="text-center py-5 bg-white border">
        <i class="fas fa-shopping-cart fa-4x text-secondary mb-3 d-block"></i>
        <h5 class="text-muted">Giỏ hàng của bạn đang trống</h5>
        <p class="text-muted small">Hãy thêm sản phẩm vào giỏ hàng để tiến hành mua sắm.</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-3 fw-bold px-4"
           style="background:#1a5276;border-color:#1a5276">
            <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
        </a>
    </div>
    @endif

</div>

@endsection

@section('extra-js')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function doiSoLuong(id, delta) {
    const input = document.getElementById('qty-' + id);
    const val = parseInt(input.value) + delta;
    if (val >= 1 && val <= 99) {
        input.value = val;
        capNhatSoLuong(id);
    }
}

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
            if (document.querySelectorAll('#cartBody tr').length === 0) location.reload();
        }
    });
}
</script>
@endsection