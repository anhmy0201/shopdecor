@extends('layouts.app')

@section('title', 'Tìm kiếm: ' . $q)

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

    .main-content { padding: 20px 0 50px; }

    /* ===== KẾT QUẢ HEADER ===== */
    .result-header {
        background: #fff;
        border: 1px solid #ddd;
        padding: 12px 16px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .result-keyword {
        font-size: 0.9rem;
        color: #333;
    }
    .result-keyword strong {
        color: #e74c3c;
        font-size: 1rem;
    }
    .result-count {
        font-size: 0.82rem;
        color: #888;
    }
    .result-count span { color: #1a5276; font-weight: 700; }

    /* ===== SIDEBAR ===== */
    .filter-block {
        border: 1px solid #ddd;
        background: #fff;
        margin-bottom: 16px;
    }
    .filter-block-title {
        background: #1a5276;
        color: #fff;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 9px 14px;
        display: flex;
        align-items: center;
        gap: 7px;
        text-transform: uppercase;
    }
    .filter-block-body { padding: 12px 14px; }

    /* Danh mục filter */
    .cat-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 0;
        border-bottom: 1px dashed #f0f0f0;
        text-decoration: none;
        color: #333;
        font-size: 0.83rem;
        transition: all 0.15s;
    }
    .cat-item:last-child { border-bottom: none; }
    .cat-item:hover, .cat-item.active { color: #1a5276; padding-left: 6px; }
    .cat-item.active { font-weight: 700; color: #e74c3c; }
    .cat-count {
        font-size: 0.7rem;
        background: #eee;
        color: #666;
        padding: 1px 6px;
        border-radius: 10px;
    }
    .cat-item.active .cat-count { background: #e74c3c; color: #fff; }

    /* Giá filter */
    .price-inputs {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .price-input {
        flex: 1;
        border: 1px solid #ddd;
        padding: 6px 8px;
        font-size: 0.82rem;
        outline: none;
        border-radius: 0;
        transition: border-color 0.15s;
    }
    .price-input:focus { border-color: #1a5276; }
    .price-sep { color: #888; font-size: 0.8rem; flex-shrink: 0; }
    .btn-loc {
        width: 100%;
        background: #1a5276;
        color: #fff;
        border: none;
        padding: 7px;
        font-size: 0.83rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: 10px;
        transition: background 0.15s;
    }
    .btn-loc:hover { background: #154360; }
    .btn-xoa-loc {
        display: block;
        text-align: center;
        font-size: 0.78rem;
        color: #e74c3c;
        text-decoration: none;
        margin-top: 7px;
    }
    .btn-xoa-loc:hover { text-decoration: underline; }

    /* ===== TOOLBAR SẮP XẾP ===== */
    .toolbar {
        background: #f9f9f9;
        border: 1px solid #ddd;
        padding: 8px 14px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.83rem;
        flex-wrap: wrap;
        gap: 8px;
    }
    .sort-select {
        border: 1px solid #ddd;
        padding: 5px 10px;
        font-size: 0.82rem;
        cursor: pointer;
        outline: none;
        color: #333;
        border-radius: 0;
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
    .btn-giohang:hover { background: #154360; }

    .product-card-body { padding: 10px; }
    .product-card-name {
        font-size: 0.83rem;
        font-weight: 600;
        color: #222;
        line-height: 1.4;
        margin-bottom: 5px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.4em;
        text-decoration: none;
    }
    .product-card-name:hover { color: #e74c3c; }

    /* Highlight từ khóa trong tên */
    .product-card-name mark {
        background: #fff3cd;
        color: #e74c3c;
        font-weight: 700;
        padding: 0;
    }

    .product-card-price { color: #e74c3c; font-weight: 700; font-size: 0.92rem; }
    .product-card-price-old {
        color: #999; font-size: 0.75rem;
        text-decoration: line-through; margin-left: 5px;
    }

    /* ===== EMPTY ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border: 1px solid #ddd;
    }
    .empty-state i { font-size: 3rem; color: #ddd; margin-bottom: 15px; }
    .empty-state h5 { color: #555; }
    .empty-state p  { color: #999; font-size: 0.85rem; }

    /* ===== GỢI Ý TÌM KIẾM ===== */
    .search-suggest a {
        display: inline-block;
        background: #eaf4fb;
        color: #1a5276;
        border: 1px solid #b8d9ed;
        padding: 4px 12px;
        font-size: 0.8rem;
        text-decoration: none;
        margin: 3px;
        transition: all 0.15s;
    }
    .search-suggest a:hover { background: #1a5276; color: #fff; }

    /* ===== PAGINATION ===== */
    .pagination .page-link { color: #1a5276; border-color: #ddd; font-size: 0.85rem; }
    .pagination .page-item.active .page-link { background: #1a5276; border-color: #1a5276; }
    .pagination .page-link:hover { background: #eaf4fb; }
</style>
@endsection

@section('content')

<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Trang chủ</a>
        <span class="mx-2">›</span>
        <span>Tìm kiếm: "{{ $q }}"</span>
    </div>
</div>

<div class="main-content">
    <div class="container">

        {{-- Header kết quả --}}
        <div class="result-header">
            <div class="result-keyword">
                <i class="fas fa-search me-2" style="color:#1a5276;"></i>
                Kết quả cho: <strong>"{{ $q }}"</strong>
            </div>
            <div class="result-count">
                Tìm thấy <span>{{ $sanphams->total() }}</span> sản phẩm
            </div>
        </div>

        <div class="row">

            {{-- ===== SIDEBAR ===== --}}
            <div class="col-lg-3 d-none d-lg-block">

                {{-- Lọc danh mục --}}
                <div class="filter-block">
                    <div class="filter-block-title">
                        <i class="fas fa-th-large"></i> Danh mục
                    </div>
                    <div class="filter-block-body">
                        <a href="{{ url('/tim-kiem') }}?q={{ urlencode($q) }}&sort={{ $sapXep }}"
                           class="cat-item {{ !$loaiId ? 'active' : '' }}">
                            <span>Tất cả danh mục</span>
                            <span class="cat-count">{{ $sanphams->total() }}</span>
                        </a>
                        @foreach($danhMucs as $dm)
                        @if($dm->sanphams_count > 0)
                        <a href="{{ url('/tim-kiem') }}?q={{ urlencode($q) }}&sort={{ $sapXep }}&loai={{ $dm->id }}"
                           class="cat-item {{ $loaiId == $dm->id ? 'active' : '' }}">
                            <span><i class="fas fa-chevron-right me-1" style="font-size:0.65rem;"></i>{{ $dm->ten_loai }}</span>
                            <span class="cat-count">{{ $dm->sanphams_count }}</span>
                        </a>
                        @endif
                        @endforeach
                    </div>
                </div>

                {{-- Lọc giá --}}
                <div class="filter-block">
                    <div class="filter-block-title">
                        <i class="fas fa-tag"></i> Lọc theo giá
                    </div>
                    <div class="filter-block-body">
                        <form action="{{ url('/tim-kiem') }}" method="GET">
                            <input type="hidden" name="q" value="{{ $q }}">
                            <input type="hidden" name="sort" value="{{ $sapXep }}">
                            @if($loaiId)
                                <input type="hidden" name="loai" value="{{ $loaiId }}">
                            @endif

                            <div style="font-size:0.78rem;color:#888;margin-bottom:8px;">Khoảng giá (đ):</div>
                            <div class="price-inputs">
                                <input type="number" name="gia_min" class="price-input"
                                       placeholder="Từ" value="{{ $giaMin }}" min="0">
                                <span class="price-sep">—</span>
                                <input type="number" name="gia_max" class="price-input"
                                       placeholder="Đến" value="{{ $giaMax }}" min="0">
                            </div>

                            {{-- Gợi ý khoảng giá nhanh --}}
                            <div style="margin-top:10px;display:flex;flex-wrap:wrap;gap:4px;">
                                @foreach([
                                    ['label'=>'Dưới 100k', 'min'=>'', 'max'=>100000],
                                    ['label'=>'100k–300k', 'min'=>100000, 'max'=>300000],
                                    ['label'=>'300k–500k', 'min'=>300000, 'max'=>500000],
                                    ['label'=>'Trên 500k', 'min'=>500000, 'max'=>''],
                                ] as $range)
                                <a href="{{ url('/tim-kiem') }}?q={{ urlencode($q) }}&sort={{ $sapXep }}&gia_min={{ $range['min'] }}&gia_max={{ $range['max'] }}{{ $loaiId ? '&loai='.$loaiId : '' }}"
                                   style="font-size:0.72rem;background:#f5f5f5;border:1px solid #ddd;
                                          padding:3px 8px;text-decoration:none;color:#555;
                                          transition:all .15s;"
                                   onmouseover="this.style.background='#1a5276';this.style.color='#fff';"
                                   onmouseout="this.style.background='#f5f5f5';this.style.color='#555';">
                                    {{ $range['label'] }}
                                </a>
                                @endforeach
                            </div>

                            <button type="submit" class="btn-loc">
                                <i class="fas fa-filter me-1"></i>Lọc giá
                            </button>
                        </form>

                        @if($giaMin || $giaMax)
                            <a href="{{ url('/tim-kiem') }}?q={{ urlencode($q) }}&sort={{ $sapXep }}{{ $loaiId ? '&loai='.$loaiId : '' }}"
                               class="btn-xoa-loc">
                                <i class="fas fa-times me-1"></i>Xóa lọc giá
                            </a>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ===== NỘI DUNG CHÍNH ===== --}}
            <div class="col-lg-9">

                @if($sanphams->count() > 0)

                    {{-- Toolbar sắp xếp --}}
                    <div class="toolbar">
                        <div style="color:#666;">
                            Hiển thị <strong>{{ $sanphams->firstItem() }}–{{ $sanphams->lastItem() }}</strong>
                            / {{ $sanphams->total() }} sản phẩm
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span style="color:#666;">Sắp xếp:</span>
                            <select class="sort-select" onchange="doiSapXep(this.value)">
                                <option value="lien-quan" {{ $sapXep === 'lien-quan' ? 'selected' : '' }}>Liên quan nhất</option>
                                <option value="moi-nhat"  {{ $sapXep === 'moi-nhat'  ? 'selected' : '' }}>Mới nhất</option>
                                <option value="ban-chay"  {{ $sapXep === 'ban-chay'  ? 'selected' : '' }}>Bán chạy</option>
                                <option value="gia-tang"  {{ $sapXep === 'gia-tang'  ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="gia-giam"  {{ $sapXep === 'gia-giam'  ? 'selected' : '' }}>Giá giảm dần</option>
                            </select>
                        </div>
                    </div>

                    {{-- Danh sách sản phẩm --}}
                    <div class="row row-cols-2 row-cols-md-3 g-3 mb-4">
                        @foreach($sanphams as $sp)
                        <div class="col">
                            <div class="product-card">
                                <div class="product-card-img">
                                    <img src="{{ asset($sp->anhChinh?->duong_dan_anh ?? 'images/no-image.png') }}"
                                         alt="{{ $sp->ten_san_pham }}" loading="lazy">
                                    @if($sp->gia_cu && $sp->gia_cu > $sp->gia)
                                        <span style="position:absolute;top:8px;right:8px;background:#e74c3c;
                                                     color:#fff;font-size:0.65rem;font-weight:700;padding:2px 7px;">
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
                                        {!! preg_replace('/(' . preg_quote($q, '/') . ')/iu',
                                            '<mark>$1</mark>',
                                            e($sp->ten_san_pham)) !!}
                                    </a>
                                    <div>
                                        <span class="product-card-price">{{ number_format($sp->gia) }}đ</span>
                                        @if($sp->gia_cu && $sp->gia_cu > $sp->gia)
                                            <span class="product-card-price-old">{{ number_format($sp->gia_cu) }}đ</span>
                                        @endif
                                    </div>
                                    @if($sp->loai)
                                        <div style="font-size:0.72rem;color:#999;margin-top:3px;">
                                            <i class="fas fa-tag me-1"></i>{{ $sp->loai->ten_loai }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center">
                        {{ $sanphams->links() }}
                    </div>

                @else
                    {{-- Không tìm thấy --}}
                    <div class="empty-state">
                        <i class="fas fa-search d-block"></i>
                        <h5>Không tìm thấy sản phẩm nào</h5>
                        <p>Không có kết quả cho từ khóa <strong>"{{ $q }}"</strong>.</p>

                        {{-- Gợi ý tìm kiếm --}}
                        <div class="mt-3 mb-3">
                            <div style="font-size:0.82rem;color:#888;margin-bottom:8px;">Gợi ý tìm kiếm:</div>
                            <div class="search-suggest">
                                @foreach(['tượng', 'đèn decor', 'cây mini', 'desk mat', 'bút', 'kệ'] as $gợiY)
                                    <a href="{{ url('/tim-kiem') }}?q={{ urlencode($gợiY) }}">{{ $gợiY }}</a>
                                @endforeach
                            </div>
                        </div>

                        <a href="{{ url('/san-pham') }}"
                           style="background:#1a5276;color:#fff;padding:8px 22px;
                                  text-decoration:none;display:inline-block;font-weight:600;font-size:0.85rem;">
                            <i class="fas fa-shopping-bag me-2"></i>Xem tất cả sản phẩm
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
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// Đổi sắp xếp — giữ nguyên các query param khác
function doiSapXep(val) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', val);
    url.searchParams.delete('page'); // reset về trang 1
    window.location.href = url.toString();
}

// Thêm vào giỏ hàng
function themGioHang(sanPhamId) {
    fetch('{{ url('/gio-hang/them') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF
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
