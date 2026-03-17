@extends('layouts.app')

@section('title', $sanpham->ten_san_pham)

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

    /* ===== GALLERY ===== */
    .gallery-main {
        border: 1px solid #ddd;
        background: #f9f9f9;
        overflow: hidden;
        margin-bottom: 10px;
        aspect-ratio: 1;
    }
    .gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        cursor: zoom-in;
        transition: transform 0.3s;
    }
    .gallery-main img:hover { transform: scale(1.05); }
    .gallery-thumbs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .gallery-thumb {
        width: 70px;
        height: 70px;
        border: 2px solid #ddd;
        background: #f9f9f9;
        cursor: pointer;
        overflow: hidden;
        transition: border-color 0.2s;
        flex-shrink: 0;
    }
    .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .gallery-thumb:hover,
    .gallery-thumb.active { border-color: #e74c3c; }

    /* ===== PRODUCT INFO ===== */
    .product-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1.4;
        margin-bottom: 10px;
    }
    .product-meta {
        font-size: 0.82rem;
        color: #888;
        margin-bottom: 12px;
        border-bottom: 1px dashed #ddd;
        padding-bottom: 12px;
    }
    .product-meta span { margin-right: 15px; }
    .product-meta i { color: #1a5276; margin-right: 4px; }
    .product-price-main {
        font-size: 1.6rem;
        font-weight: 700;
        color: #e74c3c;
        margin-bottom: 4px;
    }
    .product-price-old {
        font-size: 0.95rem;
        color: #999;
        text-decoration: line-through;
        margin-left: 10px;
    }
    .product-price-save {
        background: #e74c3c;
        color: #fff;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 8px;
        margin-left: 8px;
        vertical-align: middle;
    }
    .stock-badge {
        display: inline-block;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 3px 10px;
        margin-bottom: 15px;
    }
    .stock-badge.con-hang { background: #e8f8f0; color: #27ae60; border: 1px solid #a9dfbf; }
    .stock-badge.het-hang { background: #fdf2f2; color: #e74c3c; border: 1px solid #f5c6c6; }

    /* ===== BIẾN THỂ ===== */
    .bienthe-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
    }
    .bienthe-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 15px;
    }
    .bienthe-btn {
        border: 2px solid #ddd;
        background: #fff;
        padding: 6px 14px;
        font-size: 0.82rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .bienthe-btn img {
        width: 28px;
        height: 28px;
        object-fit: cover;
        border: 1px solid #eee;
    }
    .bienthe-btn:hover { border-color: #1a5276; color: #1a5276; }
    .bienthe-btn.active { border-color: #e74c3c; color: #e74c3c; font-weight: 700; }
    .bienthe-btn.het-hang { opacity: 0.4; cursor: not-allowed; text-decoration: line-through; }

    /* ===== SỐ LƯỢNG + NÚT ===== */
    .qty-box {
        display: flex;
        align-items: center;
        gap: 0;
        margin-bottom: 15px;
    }
    .qty-btn {
        width: 36px;
        height: 36px;
        border: 1px solid #ddd;
        background: #f5f5f5;
        font-size: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s;
    }
    .qty-btn:hover { background: #e0e0e0; }
    .qty-input {
        width: 55px;
        height: 36px;
        border: 1px solid #ddd;
        border-left: none;
        border-right: none;
        text-align: center;
        font-size: 0.9rem;
        font-weight: 600;
        outline: none;
    }
    .btn-them-gio {
        background: #1a5276;
        color: #fff;
        border: none;
        padding: 11px 24px;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-them-gio:hover { background: #154360; }
    .btn-mua-ngay {
        background: #e74c3c;
        color: #fff;
        border: none;
        padding: 11px 24px;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-mua-ngay:hover { background: #c0392b; color: #fff; }

    /* ===== POLICY ICONS ===== */
    .policy-list {
        border: 1px solid #e0e0e0;
        background: #fafafa;
        padding: 12px 15px;
        margin-top: 18px;
    }
    .policy-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.82rem;
        color: #444;
        padding: 5px 0;
        border-bottom: 1px dashed #eee;
    }
    .policy-item:last-child { border-bottom: none; }
    .policy-item i { color: #e74c3c; font-size: 1rem; width: 20px; text-align: center; }

    /* ===== TABS ===== */
    .detail-tabs {
        margin-top: 30px;
    }
    .tab-nav {
        display: flex;
        border-bottom: 2px solid #1a5276;
        gap: 0;
    }
    .tab-btn {
        background: #f0f0f0;
        border: 1px solid #ddd;
        border-bottom: none;
        padding: 9px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        color: #555;
        transition: all 0.15s;
    }
    .tab-btn.active {
        background: #1a5276;
        color: #fff;
        border-color: #1a5276;
    }
    .tab-btn:hover:not(.active) { background: #e0e0e0; }
    .tab-content {
        border: 1px solid #ddd;
        border-top: none;
        padding: 20px;
        background: #fff;
        font-size: 0.88rem;
        line-height: 1.8;
        color: #333;
        display: none;
    }
    .tab-content.active { display: block; }

    /* ===== BÌNH LUẬN ===== */
    .review-item {
        border-bottom: 1px dashed #eee;
        padding: 15px 0;
    }
    .review-item:last-child { border-bottom: none; }
    .review-user { font-weight: 700; font-size: 0.85rem; color: #222; }
    .review-date { font-size: 0.75rem; color: #999; margin-left: 10px; }
    .review-stars { color: #f0a500; font-size: 0.82rem; margin: 4px 0; }
    .review-text { font-size: 0.85rem; color: #444; }
    .review-form textarea {
        width: 100%;
        border: 1px solid #ddd;
        padding: 10px;
        font-size: 0.85rem;
        resize: vertical;
        outline: none;
        min-height: 80px;
    }
    .review-form textarea:focus { border-color: #1a5276; }
    .star-select { display: flex; gap: 5px; margin-bottom: 10px; }
    .star-select i {
        font-size: 1.3rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.15s;
    }
    .star-select i.active,
    .star-select i:hover { color: #f0a500; }

    /* ===== SẢN PHẨM LIÊN QUAN ===== */
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
        text-decoration: none;
        display: block;
    }
    .product-card-name:hover { color: #e74c3c; }
    .product-card-price { color: #e74c3c; font-weight: 700; font-size: 0.9rem; }

    .main-content { padding: 20px 0; }
</style>
@endsection

@section('content')

{{-- BREADCRUMB --}}
<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Trang chủ</a>
        <span class="mx-2">›</span>
        <a href="{{ url('/danh-muc/' . $sanpham->loai->slug) }}">{{ $sanpham->loai->ten_loai }}</a>
        <span class="mx-2">›</span>
        <span>{{ Str::limit($sanpham->ten_san_pham, 50) }}</span>
    </div>
</div>

<div class="main-content">
    <div class="container">

        {{-- ===== CHI TIẾT SẢN PHẨM ===== --}}
        <div class="row mb-4">

            {{-- GALLERY ẢNH --}}
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="gallery-main" id="galleryMain">
                    <img src="{{ asset($sanpham->anhChinh?->duong_dan_anh ?? 'images/no-image.png') }}"
                         alt="{{ $sanpham->ten_san_pham }}" id="mainImg">
                </div>
                <div class="gallery-thumbs" id="galleryThumbs">
                    @foreach($sanpham->hinhanhs->sortBy('thu_tu') as $anh)
                    <div class="gallery-thumb {{ $anh->la_anh_chinh ? 'active' : '' }}"
                         onclick="doiAnh('{{ asset($anh->duong_dan_anh) }}', this)">
                        <img src="{{ asset($anh->duong_dan_anh) }}" alt="">
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- THÔNG TIN SẢN PHẨM --}}
            <div class="col-lg-7">

                <h1 class="product-title">{{ $sanpham->ten_san_pham }}</h1>

                <div class="product-meta">
                    <span><i class="fas fa-tag"></i>Mã SP: <strong>{{ $sanpham->id }}</strong></span>
                    <span><i class="fas fa-eye"></i>Lượt xem: <strong>{{ number_format($sanpham->luot_xem) }}</strong></span>
                    <span><i class="fas fa-star" style="color:#f0a500;"></i>
                        {{ $sanpham->binhluans->count() }} đánh giá
                    </span>
                </div>

                {{-- GIÁ --}}
                <div class="mb-3">
                    <span class="product-price-main" id="giaHienThi">
                        {{ number_format($sanpham->gia) }}đ
                    </span>
                    @if($sanpham->gia_cu && $sanpham->gia_cu > $sanpham->gia)
                        <span class="product-price-old">{{ number_format($sanpham->gia_cu) }}đ</span>
                        <span class="product-price-save">
                            -{{ round(($sanpham->gia_cu - $sanpham->gia) / $sanpham->gia_cu * 100) }}%
                        </span>
                    @endif
                </div>

                {{-- TỒN KHO --}}
                <div>
                    @if($sanpham->con_hang)
                        <span class="stock-badge con-hang"><i class="fas fa-check-circle me-1"></i>Còn hàng</span>
                    @else
                        <span class="stock-badge het-hang"><i class="fas fa-times-circle me-1"></i>Hết hàng</span>
                    @endif
                </div>

                {{-- BIẾN THỂ --}}
                @if($sanpham->co_bien_the && $sanpham->bienthesActive->count() > 0)
                <div class="mb-3">
                    <div class="bienthe-label">
                        Lựa chọn: <span id="tenBienThe" style="color:#e74c3c;font-weight:400;">— chưa chọn —</span>
                    </div>
                    <div class="bienthe-list">
                        @foreach($sanpham->bienthesActive as $bt)
                        <button class="bienthe-btn {{ !$bt->con_hang ? 'het-hang' : '' }}"
                                data-id="{{ $bt->id }}"
                                data-gia="{{ $bt->gia }}"
                                data-ten="{{ $bt->ten_bienthe }}"
                                data-ton="{{ $bt->so_luong }}"
                                {{ !$bt->con_hang ? 'disabled' : '' }}
                                onclick="chonBienThe(this)">
                            @if($bt->hinh_anh)
                                <img src="{{ asset($bt->hinh_anh) }}" alt="">
                            @endif
                            {{ $bt->ten_bienthe }}
                            @if(!$bt->con_hang)
                                <small>(Hết)</small>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- SỐ LƯỢNG --}}
                <div class="mb-3">
                    <div class="bienthe-label mb-2">Số lượng:</div>
                    <div class="qty-box">
                        <button class="qty-btn" onclick="doiSoLuong(-1)">−</button>
                        <input type="number" class="qty-input" id="soLuong" value="1" min="1" max="99">
                        <button class="qty-btn" onclick="doiSoLuong(1)">+</button>
                    </div>
                </div>

                {{-- NÚT MUA --}}
                <div class="d-flex gap-2 flex-wrap mb-3">
                    <button class="btn-them-gio" onclick="themGioHang({{ $sanpham->id }})">
                        <i class="fas fa-cart-plus"></i> THÊM VÀO GIỎ
                    </button>
                    <a href="{{ url('/gio-hang') }}" class="btn-mua-ngay">
                        <i class="fas fa-bolt"></i> MUA NGAY
                    </a>
                </div>

                {{-- CHÍNH SÁCH --}}
                <div class="policy-list">
                    <div class="policy-item">
                        <i class="fas fa-truck"></i>
                        <span>Giao hàng toàn quốc, miễn phí đơn từ 500.000đ</span>
                    </div>
                    <div class="policy-item">
                        <i class="fas fa-undo"></i>
                        <span>Đổi trả miễn phí trong 7 ngày nếu lỗi nhà sản xuất</span>
                    </div>
                    <div class="policy-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Bảo hành 12 tháng, hỗ trợ 24/7</span>
                    </div>
                    <div class="policy-item">
                        <i class="fas fa-lock"></i>
                        <span>Thanh toán an toàn, bảo mật thông tin</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- ===== TABS MÔ TẢ + ĐÁNH GIÁ ===== --}}
        <div class="detail-tabs mb-5">
            <div class="tab-nav">
                <button class="tab-btn active" onclick="doiTab('mo-ta', this)">
                    <i class="fas fa-align-left me-1"></i>Mô Tả Sản Phẩm
                </button>
                <button class="tab-btn" onclick="doiTab('danh-gia', this)">
                    <i class="fas fa-star me-1"></i>Đánh Giá ({{ $sanpham->binhluans->count() }})
                </button>
            </div>

            {{-- Mô tả --}}
            <div class="tab-content active" id="tab-mo-ta">
                @if($sanpham->mo_ta)
                    {!! nl2br(e($sanpham->mo_ta)) !!}
                @else
                    <p style="color:#999;">Chưa có mô tả cho sản phẩm này.</p>
                @endif
            </div>

            {{-- Đánh giá --}}
            <div class="tab-content" id="tab-danh-gia">
                @if($sanpham->binhluans->count() > 0)
                    @foreach($sanpham->binhluans->sortByDesc('ngay_dang') as $bl)
                    <div class="review-item">
                        <div>
                            <span class="review-user">{{ $bl->user->ho_ten }}</span>
                            <span class="review-date">{{ \Carbon\Carbon::parse($bl->ngay_dang)->format('d/m/Y') }}</span>
                        </div>
                        <div class="review-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $bl->sao_danh_gia ? '' : 'text-muted' }}"></i>
                            @endfor
                        </div>
                        <div class="review-text">{{ $bl->noi_dung }}</div>
                    </div>
                    @endforeach
                @else
                    <p style="color:#999;">Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá!</p>
                @endif

                {{-- Form đánh giá --}}
                @auth
                <div class="mt-4">
                    <h6 style="font-weight:700;color:#1a5276;margin-bottom:12px;">Viết đánh giá của bạn</h6>
                    <form action="{{ url('/binh-luan/' . $sanpham->id) }}" method="POST" class="review-form">
                        @csrf
                        <div class="star-select mb-2" id="starSelect">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star" data-val="{{ $i }}" onclick="chonSao({{ $i }})"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="sao_danh_gia" id="saoDanhGia" value="5">
                        <textarea name="noi_dung" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..." required></textarea>
                        <button type="submit" style="background:#1a5276;color:#fff;border:none;padding:8px 20px;font-weight:600;margin-top:8px;cursor:pointer;">
                            <i class="fas fa-paper-plane me-1"></i>Gửi đánh giá
                        </button>
                    </form>
                </div>
                @else
                <p style="font-size:0.85rem;color:#888;margin-top:15px;">
                    <a href="{{ route('login') }}" style="color:#1a5276;font-weight:600;">Đăng nhập</a> để viết đánh giá.
                </p>
                @endauth
            </div>
        </div>

        {{-- ===== SẢN PHẨM LIÊN QUAN ===== --}}
        @if($lienQuan->count() > 0)
        <div class="section-title">
            <i class="fas fa-th-large"></i> SẢN PHẨM LIÊN QUAN
        </div>
        <div class="row row-cols-2 row-cols-md-4 g-3">
            @foreach($lienQuan as $sp)
            <div class="col">
                <div class="product-card">
                    <div class="product-card-img">
                        <img src="{{ asset($sp->anhChinh?->duong_dan_anh ?? 'images/no-image.png') }}"
                             alt="{{ $sp->ten_san_pham }}" loading="lazy">
                    </div>
                    <div class="product-card-body">
                        <a href="{{ url('/san-pham/' . $sp->slug) }}" class="product-card-name">
                            {{ $sp->ten_san_pham }}
                        </a>
                        <div class="product-card-price">{{ number_format($sp->gia) }}đ</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>

@endsection

@section('extra-js')
<script>
// Đổi ảnh chính
function doiAnh(src, el) {
    document.getElementById('mainImg').src = src;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}

// Chọn biến thể
function chonBienThe(el) {
    if (el.disabled) return;
    document.querySelectorAll('.bienthe-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('giaHienThi').textContent = new Intl.NumberFormat('vi-VN').format(el.dataset.gia) + 'đ';
    document.getElementById('tenBienThe').textContent = el.dataset.ten;
    document.getElementById('soLuong').max = el.dataset.ton;
}

// Đổi số lượng
function doiSoLuong(delta) {
    const input = document.getElementById('soLuong');
    const val = parseInt(input.value) + delta;
    const max = parseInt(input.max) || 99;
    if (val >= 1 && val <= max) input.value = val;
}

// Đổi tab
function doiTab(id, el) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('active');
    el.classList.add('active');
}

// Chọn sao đánh giá
function chonSao(val) {
    document.getElementById('saoDanhGia').value = val;
    document.querySelectorAll('#starSelect i').forEach((el, i) => {
        el.classList.toggle('active', i < val);
    });
}
// Mặc định 5 sao
chonSao(5);

// Thêm giỏ hàng
function themGioHang(sanPhamId) {
    const bientheActive = document.querySelector('.bienthe-btn.active');
    const soLuong = parseInt(document.getElementById('soLuong').value);
    const bientheId = bientheActive ? bientheActive.dataset.id : null;

    @if($sanpham->co_bien_the)
    if (!bientheId) {
        alert('Vui lòng chọn phân loại sản phẩm!');
        return;
    }
    @endif

    fetch('{{ url('/gio-hang/them') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            san_pham_id: sanPhamId,
            bienthe_id: bientheId,
            so_luong: soLuong
        })
    })
    .then(r => r.json())
    .then(data => { if (data.success) alert('Đã thêm vào giỏ hàng!'); });
}
</script>
@endsection