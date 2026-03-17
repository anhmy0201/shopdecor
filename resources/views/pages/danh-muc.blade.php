@extends('layouts.app')

@section('title', $danhMuc->ten_loai)

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
    .sidebar-cat-item:hover,
    .sidebar-cat-item.active {
        background: #eaf4fb;
        color: #1a5276;
        padding-left: 20px;
        font-weight: 600;
    }
    .sidebar-cat-item.active { border-left: 3px solid #e74c3c; }
    .sidebar-cat-item i { color: #1a5276; font-size: 0.7rem; margin-right: 6px; }
    .sidebar-cat-count {
        background: #1a5276;
        color: #fff;
        font-size: 0.68rem;
        padding: 1px 6px;
        border-radius: 10px;
    }
    .sidebar-cat-item.active .sidebar-cat-count { background: #e74c3c; }

    /* ===== SECTION TITLE ===== */
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
        right: -1px; top: 0;
        width: 0; height: 100%;
        border-style: solid;
        border-width: 18px 0 18px 12px;
        border-color: transparent transparent transparent #1a5276;
    }

    /* ===== TOOLBAR ===== */
    .toolbar {
        background: #f9f9f9;
        border: 1px solid #ddd;
        padding: 8px 14px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.85rem;
        flex-wrap: wrap;
        gap: 8px;
    }
    .toolbar .sp-count { color: #666; }
    .toolbar .sp-count strong { color: #e74c3c; }
    .sort-select {
        border: 1px solid #ddd;
        padding: 4px 10px;
        font-size: 0.82rem;
        cursor: pointer;
        outline: none;
        color: #333;
    }
    .sort-select:focus { border-color: #1a5276; }

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
    .badge-sale {
        position: absolute;
        top: 8px; right: 8px;
        background: #f0a500;
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

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }
    .empty-state i { font-size: 3rem; margin-bottom: 15px; color: #ddd; }

    /* ===== PAGINATION ===== */
    .pagination .page-link {
        color: #1a5276;
        border-color: #ddd;
        font-size: 0.85rem;
    }
    .pagination .page-item.active .page-link {
        background: #1a5276;
        border-color: #1a5276;
        color: #fff;
    }
    .pagination .page-link:hover { background: #eaf4fb; color: #1a5276; }

    .main-content { padding: 20px 0; }
</style>
@endsection

@section('content')

{{-- BREADCRUMB --}}
<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Trang chủ</a>
        <span class="mx-2">›</span>
        <a href="{{ url('/san-pham') }}">Sản phẩm</a>
        <span class="mx-2">›</span>
        <span>{{ $danhMuc->ten_loai }}</span>
    </div>
</div>

<div class="main-content">
    <div class="container">
        <div class="row">

            {{-- SIDEBAR --}}
            <div class="col-lg-3 d-none d-lg-block">
                <div class="sidebar-cat">
                    <div class="sidebar-cat-title">
                        <i class="fas fa-bars"></i> DANH MỤC SẢN PHẨM
                    </div>
                    @foreach($danhMucs as $dm)
                    <a href="{{ url('/danh-muc/' . $dm->slug) }}"
                       class="sidebar-cat-item {{ $dm->slug === $danhMuc->slug ? 'active' : '' }}">
                        <span><i class="fas fa-chevron-right"></i>{{ $dm->ten_loai }}</span>
                        <span class="sidebar-cat-count">{{ $dm->sanphams_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- NỘI DUNG CHÍNH --}}
            <div class="col-lg-9">

                {{-- Section title --}}
                <div class="section-title">
                    <i class="fas fa-th-large"></i> {{ strtoupper($danhMuc->ten_loai) }}
                </div>

                {{-- Toolbar --}}
                <div class="toolbar">
                    <div class="sp-count">
                        Tìm thấy <strong>{{ $sanphams->total() }}</strong> sản phẩm
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span style="font-size:0.82rem;color:#666;">Sắp xếp:</span>
                        <select class="sort-select" onchange="window.location.href=this.value">
                            <option value="?sort=moi-nhat" {{ $sapXep === 'moi-nhat' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="?sort=ban-chay" {{ $sapXep === 'ban-chay' ? 'selected' : '' }}>Bán chạy</option>
                            <option value="?sort=gia-tang" {{ $sapXep === 'gia-tang' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="?sort=gia-giam" {{ $sapXep === 'gia-giam' ? 'selected' : '' }}>Giá giảm dần</option>
                        </select>
                    </div>
                </div>

                {{-- Danh sách sản phẩm --}}
                @if($sanphams->count() > 0)
                    <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
                        @foreach($sanphams as $sp)
                        <div class="col">
                            <div class="product-card">
                                <div class="product-card-img">
                                    <img src="{{ asset($sp->anhChinh?->duong_dan_anh ?? 'images/no-image.png') }}"
                                         alt="{{ $sp->ten_san_pham }}" loading="lazy">
                                    <span class="badge-new">MỚI</span>
                                    @if($sp->gia_cu && $sp->gia_cu > $sp->gia)
                                        <span class="badge-sale">
                                            -{{ round(($sp->gia_cu - $sp->gia) / $sp->gia_cu * 100) }}%
                                        </span>
                                    @endif
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
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-3">
                        {{ $sanphams->links() }}
                    </div>

                @else
                    <div class="empty-state">
                        <i class="fas fa-box-open d-block"></i>
                        <h5 style="color:#555;">Chưa có sản phẩm trong danh mục này</h5>
                        <p>Vui lòng quay lại sau hoặc xem các danh mục khác.</p>
                        <a href="{{ url('/san-pham') }}"
                           style="background:#1a5276;color:#fff;padding:8px 24px;text-decoration:none;display:inline-block;margin-top:10px;">
                            Xem tất cả sản phẩm
                        </a>
                    </div>
                @endif

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
    .then(data => { 
        if (data.success) alert('Đã thêm vào giỏ hàng!'); 
    });
}
</script>
@endsection