@extends('layouts.app')

@section('title', 'Trang Chủ')

@section('extra-css')
<style>
    /* ===== DANH MỤC NHANH ===== */
    .quick-cats {
        background: #fff8e1;
        padding: 18px 0;
        border-bottom: 1px solid #e0e0e0;
    }
    .quick-cat-item {
        text-align: center;
        text-decoration: none;
        display: block;
        color: #333;
    }
    .quick-cat-item:hover { color: #e74c3c; }
    .quick-cat-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        margin: 0 auto 8px;
        background: #fff;
        border: 2px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        transition: border-color 0.2s;
    }
    .quick-cat-item:hover .quick-cat-circle { border-color: #e74c3c; }
    .quick-cat-name { font-size: 0.78rem; font-weight: 600; }

    /* ===== SECTION TITLE (kiểu decopro) ===== */
    .section-title {
        background: #1a5276;
        color: #fff;
        font-size: 0.95rem;
        font-weight: 700;
        padding: 8px 15px;
        margin-bottom: 15px;
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title::after {
        content: '';
        position: absolute;
        right: -1px;
        top: 0;
        width: 0;
        height: 100%;
        border-style: solid;
        border-width: 18px 0 18px 12px;
        border-color: transparent transparent transparent #1a5276;
    }
    .section-title a {
        margin-left: auto;
        color: rgba(255,255,255,0.8);
        font-size: 0.78rem;
        text-decoration: none;
        font-weight: 400;
        padding-right: 18px;
    }
    .section-title a:hover { color: #f0a500; }

    /* ===== SIDEBAR ===== */
    .sidebar-cat {
        border: 1px solid #ddd;
        background: #fff;
        margin-bottom: 20px;
    }
    .sidebar-cat-title {
        background: #1a5276;
        color: #fff;
        font-size: 0.88rem;
        font-weight: 700;
        padding: 9px 14px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sidebar-cat-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 14px;
        border-bottom: 1px solid #eee;
        text-decoration: none;
        color: #333;
        font-size: 0.85rem;
        transition: all 0.15s;
    }
    .sidebar-cat-item:last-child { border-bottom: none; }
    .sidebar-cat-item:hover { background: #eaf4fb; color: #1a5276; padding-left: 20px; }
    .sidebar-cat-item i { color: #1a5276; font-size: 0.7rem; margin-right: 6px; }
    .sidebar-cat-count {
        background: #1a5276;
        color: #fff;
        font-size: 0.68rem;
        padding: 1px 6px;
        border-radius: 10px;
    }

    /* ===== PROMO BANNER ===== */
    .promo-banner {
        background: #e74c3c;
        color: #fff;
        padding: 16px;
        text-align: center;
        margin-bottom: 20px;
        border: 1px solid #c0392b;
    }
    .promo-banner h6 { font-weight: 700; margin-bottom: 4px; }
    .promo-banner p { font-size: 0.8rem; margin-bottom: 8px; opacity: 0.9; }
    .promo-code {
        background: #fff;
        color: #e74c3c;
        font-weight: 800;
        font-size: 1.1rem;
        letter-spacing: 2px;
        padding: 4px 14px;
        display: inline-block;
    }

    /* ===== BÁN CHẠY SIDEBAR ===== */
    .sidebar-sp {
        border: 1px solid #ddd;
        background: #fff;
        margin-bottom: 20px;
    }
    .sidebar-sp-title {
        background: #e74c3c;
        color: #fff;
        font-size: 0.88rem;
        font-weight: 700;
        padding: 9px 14px;
        text-transform: uppercase;
    }
    .sidebar-sp-item {
        display: flex;
        gap: 10px;
        padding: 10px 12px;
        border-bottom: 1px solid #eee;
        text-decoration: none;
        color: #333;
        transition: background 0.15s;
    }
    .sidebar-sp-item:last-child { border-bottom: none; }
    .sidebar-sp-item:hover { background: #fef9e7; }
    .sidebar-sp-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border: 1px solid #ddd;
        flex-shrink: 0;
    }
    .sidebar-sp-name { font-size: 0.78rem; font-weight: 600; color: #222; line-height: 1.4; }
    .sidebar-sp-price { color: #e74c3c; font-weight: 700; font-size: 0.82rem; margin-top: 3px; }

    /* ===== PRODUCT CARD ===== */
    .product-card {
        background: #fff;
        border: 1px solid #ddd;
        transition: box-shadow 0.2s;
        height: 100%;
    }
    .product-card:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .product-card-img {
        position: relative;
        overflow: hidden;
        padding-top: 75%;
        background: #f9f9f9;
    }
    .product-card-img img {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }
    .product-card:hover .product-card-img img { transform: scale(1.05); }
    .product-card-actions {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        display: flex;
        gap: 4px;
        padding: 8px;
        background: rgba(0,0,0,0.5);
        transform: translateY(100%);
        transition: transform 0.25s;
    }
    .product-card:hover .product-card-actions { transform: translateY(0); }
    .btn-chitiet {
        flex: 1;
        background: #f0a500;
        color: #fff;
        border: none;
        padding: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-chitiet:hover { background: #d4910a; color: #fff; }
    .btn-giohang {
        flex: 1;
        background: #1a5276;
        color: #fff;
        border: none;
        padding: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
        cursor: pointer;
    }
    .btn-giohang:hover { background: #154360; color: #fff; }
    .badge-new {
        position: absolute;
        top: 8px; left: 8px;
        background: #e74c3c;
        color: #fff;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 2px 7px;
        z-index: 1;
    }
    .product-card-body { padding: 10px; }
    .product-card-name {
        font-size: 0.83rem;
        font-weight: 600;
        color: #222;
        line-height: 1.4;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.4em;
        text-decoration: none;
    }
    .product-card-name:hover { color: #e74c3c; }
    .product-card-price { color: #e74c3c; font-weight: 700; font-size: 0.92rem; }
    .product-card-price-old {
        color: #999;
        font-size: 0.78rem;
        text-decoration: line-through;
        margin-left: 6px;
    }

    /* ===== WHY US ===== */
    .why-us {
        background: #fff;
        border-top: 3px solid #1a5276;
        border-bottom: 1px solid #ddd;
        padding: 20px 0;
        margin-top: 30px;
    }
    .why-item { text-align: center; padding: 10px; }
    .why-item i { font-size: 1.8rem; color: #e74c3c; margin-bottom: 8px; }
    .why-item h6 { font-weight: 700; font-size: 0.88rem; color: #1a5276; margin-bottom: 3px; }
    .why-item p { font-size: 0.78rem; color: #666; margin: 0; }

    .main-content { padding: 20px 0; }
</style>
@endsection

@section('content')

{{-- DANH MỤC NHANH --}}
<div class="quick-cats">
    <div class="container">
        <div class="row row-cols-3 row-cols-md-6 g-2">
            <div class="col">
                <a href="{{ url('/danh-muc/tuong-figurine') }}" class="quick-cat-item">
                    <div class="quick-cat-circle">🏆</div>
                    <div class="quick-cat-name">Tượng & Figurine</div>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('/danh-muc/den-decor') }}" class="quick-cat-item">
                    <div class="quick-cat-circle">💡</div>
                    <div class="quick-cat-name">Đèn Decor</div>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('/danh-muc/cay-xanh-mini') }}" class="quick-cat-item">
                    <div class="quick-cat-circle">🌿</div>
                    <div class="quick-cat-name">Cây Xanh Mini</div>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('/danh-muc/van-phong-pham') }}" class="quick-cat-item">
                    <div class="quick-cat-circle">✒️</div>
                    <div class="quick-cat-name">Văn Phòng Phẩm</div>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('/danh-muc/to-chuc-ban') }}" class="quick-cat-item">
                    <div class="quick-cat-circle">📦</div>
                    <div class="quick-cat-name">Tổ Chức Bàn</div>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('/danh-muc/desk-mat') }}" class="quick-cat-item">
                    <div class="quick-cat-circle">🖱️</div>
                    <div class="quick-cat-name">Desk Mat</div>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- MAIN CONTENT --}}
<div class="main-content">
    <div class="container">
        <div class="row">

            {{-- SIDEBAR --}}
            <div class="col-lg-3 d-none d-lg-block">

                {{-- Danh mục --}}
                <div class="sidebar-cat">
                    <div class="sidebar-cat-title">
                        <i class="fas fa-bars"></i> DANH MỤC SẢN PHẨM
                    </div>
                    <a href="{{ url('/danh-muc/tuong-figurine') }}" class="sidebar-cat-item">
                        <span><i class="fas fa-chevron-right"></i>Tượng & Figurine</span>
                        <span class="sidebar-cat-count">{{ $soLuong['tuong'] ?? 0 }}</span>
                    </a>
                    <a href="{{ url('/danh-muc/den-decor') }}" class="sidebar-cat-item">
                        <span><i class="fas fa-chevron-right"></i>Đèn Decor</span>
                        <span class="sidebar-cat-count">{{ $soLuong['den'] ?? 0 }}</span>
                    </a>
                    <a href="{{ url('/danh-muc/cay-xanh-mini') }}" class="sidebar-cat-item">
                        <span><i class="fas fa-chevron-right"></i>Cây Xanh Mini</span>
                        <span class="sidebar-cat-count">{{ $soLuong['cay'] ?? 0 }}</span>
                    </a>
                    <a href="{{ url('/danh-muc/van-phong-pham') }}" class="sidebar-cat-item">
                        <span><i class="fas fa-chevron-right"></i>Văn Phòng Phẩm</span>
                        <span class="sidebar-cat-count">{{ $soLuong['vanphong'] ?? 0 }}</span>
                    </a>
                    <a href="{{ url('/danh-muc/to-chuc-ban') }}" class="sidebar-cat-item">
                        <span><i class="fas fa-chevron-right"></i>Tổ Chức Bàn</span>
                        <span class="sidebar-cat-count">{{ $soLuong['tochuc'] ?? 0 }}</span>
                    </a>
                    <a href="{{ url('/danh-muc/desk-mat') }}" class="sidebar-cat-item">
                        <span><i class="fas fa-chevron-right"></i>Desk Mat</span>
                        <span class="sidebar-cat-count">{{ $soLuong['deskmat'] ?? 0 }}</span>
                    </a>
                </div>

                {{-- Khuyến mãi --}}
                <div class="promo-banner">
                    <h6>🎉 ƯU ĐÃI HÔM NAY</h6>
                    <p>Giảm 10% đơn hàng đầu tiên. Dùng mã:</p>
                    <div class="promo-code">WELCOME10</div>
                </div>

                {{-- Bán chạy --}}
                <div class="sidebar-sp">
                    <div class="sidebar-sp-title">
                        <i class="fas fa-fire me-2"></i>BÁN CHẠY NHẤT
                    </div>
                    @isset($banChay)
                        @foreach($banChay->take(3) as $sp)
                        <a href="{{ url('/san-pham/' . $sp->slug) }}" class="sidebar-sp-item">
                            <img src="{{ asset($sp->anhChinh?->duong_dan_anh ?? 'images/no-image.png') }}"
                                 alt="{{ $sp->ten_san_pham }}">
                            <div>
                                <div class="sidebar-sp-name">{{ Str::limit($sp->ten_san_pham, 45) }}</div>
                                <div class="sidebar-sp-price">{{ number_format($sp->gia) }}đ</div>
                            </div>
                        </a>
                        @endforeach
                    @endisset
                </div>

            </div>

            {{-- NỘI DUNG CHÍNH --}}
            <div class="col-lg-9">

                {{-- SẢN PHẨM NỔI BẬT --}}
                <div class="section-title">
                    <i class="fas fa-star"></i> SẢN PHẨM NỔI BẬT
                    <a href="{{ url('/san-pham') }}">Xem tất cả »</a>
                </div>

                <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
                    @isset($noiBat)
                        @foreach($noiBat as $sp)
                        <div class="col">
                            <div class="product-card">
                                <div class="product-card-img">
                                    <img src="{{ asset($sp->anhChinh?->duong_dan_anh ?? 'images/no-image.png') }}"
                                         alt="{{ $sp->ten_san_pham }}" loading="lazy">
                                    <span class="badge-new">MỚI</span>
                                    <div class="product-card-actions">
                                        <a href="{{ url('/san-pham/' . $sp->slug) }}" class="btn-chitiet">
                                            <i class="fas fa-eye me-1"></i>Chi tiết
                                        </a>
                                        <button class="btn-giohang" onclick="themGioHang({{ $sp->id }})">
                                            <i class="fas fa-cart-plus me-1"></i>Thêm giỏ
                                        </button>
                                    </div>
                                </div>
                                <div class="product-card-body">
                                    <a href="{{ url('/san-pham/' . $sp->slug) }}" class="product-card-name d-block">
                                        {{ $sp->ten_san_pham }}
                                    </a>
                                    <div>
                                        <span class="product-card-price">{{ number_format($sp->gia) }}đ</span>
                                        @if($sp->gia_cu && $sp->gia_cu > $sp->gia)
                                            <span class="product-card-price-old">{{ number_format($sp->gia_cu) }}đ</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endisset
                </div>

                {{-- TẤT CẢ SẢN PHẨM --}}
                <div class="section-title">
                    <i class="fas fa-th-large"></i> TẤT CẢ SẢN PHẨM
                    <a href="{{ url('/san-pham') }}">Xem thêm »</a>
                </div>

                <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
                    @isset($tatCa)
                        @foreach($tatCa as $sp)
                        <div class="col">
                            <div class="product-card">
                                <div class="product-card-img">
                                    <img src="{{ asset($sp->anhChinh?->duong_dan_anh ?? 'images/no-image.png') }}"
                                         alt="{{ $sp->ten_san_pham }}" loading="lazy">
                                    <div class="product-card-actions">
                                        <a href="{{ url('/san-pham/' . $sp->slug) }}" class="btn-chitiet">
                                            <i class="fas fa-eye me-1"></i>Chi tiết
                                        </a>
                                        <button class="btn-giohang" onclick="themGioHang({{ $sp->id }})">
                                            <i class="fas fa-cart-plus me-1"></i>Thêm giỏ
                                        </button>
                                    </div>
                                </div>
                                <div class="product-card-body">
                                    <a href="{{ url('/san-pham/' . $sp->slug) }}" class="product-card-name d-block">
                                        {{ $sp->ten_san_pham }}
                                    </a>
                                    <div>
                                        <span class="product-card-price">{{ number_format($sp->gia) }}đ</span>
                                        @if($sp->gia_cu && $sp->gia_cu > $sp->gia)
                                            <span class="product-card-price-old">{{ number_format($sp->gia_cu) }}đ</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endisset
                </div>

                <div class="text-center mt-2 mb-3">
                    <a href="{{ url('/san-pham') }}"
                       style="background:#e74c3c;color:#fff;padding:10px 30px;font-weight:700;text-decoration:none;display:inline-block;">
                        <i class="fas fa-arrow-down me-2"></i>XEM THÊM SẢN PHẨM
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- WHY US --}}
<div class="why-us">
    <div class="container">
        <div class="row text-center">
            <div class="col-6 col-md-3">
                <div class="why-item">
                    <i class="fas fa-truck"></i>
                    <h6>GIAO HÀNG TOÀN QUỐC</h6>
                    <p>Miễn phí vận chuyển</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="why-item">
                    <i class="fas fa-handshake"></i>
                    <h6>UY TÍN - CHUYÊN NGHIỆP</h6>
                    <p>Tư vấn, hỗ trợ tận tâm</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="why-item">
                    <i class="fas fa-medal"></i>
                    <h6>CAM KẾT CHẤT LƯỢNG</h6>
                    <p>Nhập khẩu chính hãng</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="why-item">
                    <i class="fas fa-tag"></i>
                    <h6>GIÁ TỐT NHẤT</h6>
                    <p>Cam kết giá cạnh tranh</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script>
function themGioHang(sanPhamId) {
    fetch('{{ url('/gio-hang/them') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ san_pham_id: sanPhamId, so_luong: 1 })
    })
    .then(r => r.json())
    .then(data => { if (data.success) alert('Đã thêm vào giỏ hàng!'); });
}
</script>
@endsection