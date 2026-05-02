@extends('layouts.app')

@section('title', $sanpham->ten_san_pham)

@section('extra-css')
<style>
    .breadcrumb-bar { background:#eaf4fb; border-bottom:1px solid #d0e8f5; font-size:.82rem; }
    .breadcrumb-bar a { color:#1a5276; text-decoration:none; }
    .breadcrumb-bar a:hover { text-decoration:underline; }

    .gallery-main { border:1px solid #ddd; background:#f9f9f9; aspect-ratio:1; overflow:hidden; }
    .gallery-main img { width:100%; height:100%; object-fit:contain; cursor:zoom-in; transition:transform .3s; }
    .gallery-main img:hover { transform:scale(1.05); }
    .gallery-thumb { width:80px; height:88px; border:2px solid #ddd; background:#f9f9f9; cursor:pointer; overflow:hidden; flex-shrink:0; transition:border-color .2s; }
    .gallery-thumb img { width:100%; height:72px; object-fit:cover; }
    .gallery-thumb:hover, .gallery-thumb.active { border-color:#e74c3c; }
    .gallery-thumb-label { font-size:9px; text-align:center; color:#555; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; padding:2px 2px 0; }

    .bienthe-btn { border:2px solid #ddd; background:#fff; padding:6px 14px; font-size:.82rem; cursor:pointer; transition:all .2s; display:inline-flex; align-items:center; gap:6px; }
    .bienthe-btn img { width:28px; height:28px; object-fit:cover; border:1px solid #eee; }
    .bienthe-btn:hover { border-color:#1a5276; color:#1a5276; }
    .bienthe-btn.active { border-color:#e74c3c; color:#e74c3c; font-weight:700; }
    .bienthe-btn.het-hang { opacity:.4; cursor:not-allowed; text-decoration:line-through; }

    .qty-btn { width:36px; height:36px; border:1px solid #ddd; background:#f5f5f5; cursor:pointer; transition:background .15s; }
    .qty-btn:hover { background:#e0e0e0; }
    .qty-input { width:55px; height:36px; border:1px solid #ddd; border-left:none; border-right:none; text-align:center; font-size:.9rem; font-weight:600; outline:none; }

    .tab-nav { border-bottom:2px solid #1a5276; }
    .tab-btn { background:#f0f0f0; border:1px solid #ddd; border-bottom:none; padding:9px 20px; font-size:.85rem; font-weight:600; cursor:pointer; color:#555; transition:all .15s; }
    .tab-btn.active { background:#1a5276; color:#fff; border-color:#1a5276; }
    .tab-btn:hover:not(.active) { background:#e0e0e0; }
    .tab-content { border:1px solid #ddd; border-top:none; padding:20px; background:#fff; font-size:.88rem; line-height:1.8; color:#333; display:none; }
    .tab-content.active { display:block; }

    .review-item { border-bottom:1px dashed #eee; padding:15px 0; }
    .review-item:last-child { border-bottom:none; }

    .section-title { background:#1a5276; color:#fff; font-size:.95rem; font-weight:700; padding:8px 15px; position:relative; }
    .section-title::after { content:''; position:absolute; right:-1px; top:0; width:0; height:100%; border-style:solid; border-width:18px 0 18px 12px; border-color:transparent transparent transparent #1a5276; }

    .product-card { background:#fff; border:1px solid #ddd; transition:box-shadow .2s; height:100%; }
    .product-card:hover { box-shadow:0 4px 15px rgba(0,0,0,.1); }
    .product-card-img { position:relative; overflow:hidden; padding-top:75%; background:#f9f9f9; }
    .product-card-img img { position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; transition:transform .3s; }
    .product-card:hover .product-card-img img { transform:scale(1.05); }
    .product-card-name { font-size:.83rem; font-weight:600; color:#222; line-height:1.4; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; text-decoration:none; }
    .product-card-name:hover { color:#e74c3c; }
</style>
@endsection

@section('content')

<div class="breadcrumb-bar py-2">
    <div class="container">
        <a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Trang chủ</a>
        <span class="mx-2 text-muted">›</span>
        <a href="{{ url('/danh-muc/' . $sanpham->loai->slug) }}">{{ $sanpham->loai->ten_loai }}</a>
        <span class="mx-2 text-muted">›</span>
        <span class="text-muted">{{ Str::limit($sanpham->ten_san_pham, 50) }}</span>
    </div>
</div>

<div class="container py-4">

    <div class="row mb-4">

        <div class="col-lg-5 mb-4 mb-lg-0">
            <div class="gallery-main mb-2" id="galleryMain">
                <img src="{{ asset($sanpham->anhChinh?->duong_dan_anh ?? 'images/no-image.png') }}"
                     alt="{{ $sanpham->ten_san_pham }}" id="mainImg">
            </div>
            <div class="d-flex gap-2 mt-2" id="galleryThumbs"
                 style="overflow-x:auto; flex-wrap:nowrap; padding-bottom:6px; scrollbar-width:thin;">

                @foreach($sanpham->hinhanhs->sortBy('thu_tu') as $anh)
                <div class="gallery-thumb {{ $anh->la_anh_chinh ? 'active' : '' }}"
                     onclick="doiAnh('{{ asset($anh->duong_dan_anh) }}', this)">
                    <img src="{{ asset($anh->duong_dan_anh) }}" alt="">
                </div>
                @endforeach

                @if($sanpham->co_bien_the)
                    @foreach($sanpham->bienthesActive as $bt)
                        @if($bt->hinh_anh)
                        <div class="gallery-thumb"
                             data-bienthe-id="{{ $bt->id }}"
                             onclick="doiAnh('{{ asset($bt->hinh_anh) }}', this)">
                            <img src="{{ asset($bt->hinh_anh) }}" alt="{{ $bt->ten_bienthe }}">
                            <div class="gallery-thumb-label">{{ $bt->ten_bienthe }}</div>
                        </div>
                        @endif
                    @endforeach
                @endif

            </div>
        </div>

        <div class="col-lg-7">

            <h1 class="fs-5 fw-bold text-dark lh-sm mb-2">{{ $sanpham->ten_san_pham }}</h1>

            <div class="d-flex flex-wrap gap-3 small text-muted mb-3 pb-3" style="border-bottom:1px dashed #ddd">
                <span><i class="fas fa-tag me-1" style="color:#1a5276"></i>Mã SP: <strong>{{ $sanpham->id }}</strong></span>
                <span><i class="fas fa-eye me-1" style="color:#1a5276"></i>Lượt xem: <strong>{{ number_format($sanpham->luot_xem) }}</strong></span>
                <span><i class="fas fa-star me-1" style="color:#f0a500"></i>{{ $sanpham->binhluans->count() }} đánh giá</span>
            </div>

            <div class="mb-3 d-flex align-items-center flex-wrap gap-2">
                <span class="fs-4 fw-bold text-danger" id="giaHienThi">{{ number_format($sanpham->gia) }}đ</span>
                @if($sanpham->gia_cu && $sanpham->gia_cu > $sanpham->gia)
                    <span class="text-muted text-decoration-line-through" style="font-size:.95rem">{{ number_format($sanpham->gia_cu) }}đ</span>
                    <span class="badge bg-danger" style="font-size:.72rem">
                        -{{ round(($sanpham->gia_cu - $sanpham->gia) / $sanpham->gia_cu * 100) }}%
                    </span>
                @endif
            </div>

            <div class="mb-3 d-flex align-items-center flex-wrap gap-2">
                @if($sanpham->con_hang)
                    <span class="badge fw-semibold" id="badgeTonKho"
                          style="background:#e8f8f0;color:#27ae60;border:1px solid #a9dfbf;font-size:.8rem">
                        <i class="fas fa-check-circle me-1"></i>Còn hàng
                    </span>
                    @if(!$sanpham->co_bien_the)
                        <span class="text-muted" id="slHienThi" style="font-size:.8rem">
                            SL: <strong>{{ $sanpham->so_luong }}</strong>
                        </span>
                    @else
                        <span class="text-muted d-none" id="slHienThi" style="font-size:.8rem"></span>
                    @endif
                @else
                    <span class="badge fw-semibold" id="badgeTonKho"
                          style="background:#fdf2f2;color:#e74c3c;border:1px solid #f5c6c6;font-size:.8rem">
                        <i class="fas fa-times-circle me-1"></i>Hết hàng
                    </span>
                    <span class="d-none" id="slHienThi"></span>
                @endif
            </div>

            @if($sanpham->co_bien_the && $sanpham->bienthesActive->count() > 0)
            <div class="mb-3">
                <div class="fw-bold small mb-2">
                    Lựa chọn: <span id="tenBienThe" class="text-danger fw-normal">— chưa chọn —</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($sanpham->bienthesActive as $bt)
                    <button class="bienthe-btn {{ !$bt->con_hang ? 'het-hang' : '' }}"
                            data-id="{{ $bt->id }}" data-gia="{{ $bt->gia }}"
                            data-ten="{{ $bt->ten_bienthe }}" data-ton="{{ $bt->so_luong }}"
                            data-anh="{{ $bt->hinh_anh ? asset($bt->hinh_anh) : '' }}"
                            {{ !$bt->con_hang ? 'disabled' : '' }}
                            onclick="chonBienThe(this)">
                        @if($bt->hinh_anh)<img src="{{ asset($bt->hinh_anh) }}" alt="">@endif
                        {{ $bt->ten_bienthe }}
                        @if(!$bt->con_hang)<small>(Hết)</small>@endif
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mb-3">
                <div class="fw-bold small mb-2">Số lượng:</div>
                <div class="d-flex align-items-center">
                    <button class="qty-btn" onclick="doiSoLuong(-1)">−</button>
                    <input type="number" class="qty-input" id="soLuong" value="1" min="1" max="99">
                    <button class="qty-btn" onclick="doiSoLuong(1)">+</button>
                </div>
            </div>

            <div class="d-flex gap-2 flex-wrap mb-3">
                <button class="btn fw-bold rounded-0 text-white"
                        style="background:#1a5276;padding:11px 24px"
                        onclick="themGioHang({{ $sanpham->id }})">
                    <i class="fas fa-cart-plus me-2"></i>THÊM VÀO GIỎ
                </button>
                <button class="btn btn-danger fw-bold rounded-0 text-white"
                        style="padding:11px 24px"
                        onclick="muaNgay({{ $sanpham->id }})">
                    <i class="fas fa-bolt me-2"></i>MUA NGAY
                </button>
            </div>

            <div class="border bg-light p-3 small" style="font-size:.82rem">
                <div class="d-flex align-items-center gap-2 py-1 border-bottom">
                    <i class="fas fa-truck text-danger"></i>
                    <span>Giao hàng toàn quốc, miễn phí đơn từ 500.000đ</span>
                </div>
                <div class="d-flex align-items-center gap-2 py-1 border-bottom">
                    <i class="fas fa-undo text-danger"></i>
                    <span>Đổi trả miễn phí trong 7 ngày nếu lỗi nhà sản xuất</span>
                </div>
                <div class="d-flex align-items-center gap-2 py-1 border-bottom">
                    <i class="fas fa-shield-alt text-danger"></i>
                    <span>Bảo hành 12 tháng, hỗ trợ 24/7</span>
                </div>
                <div class="d-flex align-items-center gap-2 py-1">
                    <i class="fas fa-lock text-danger"></i>
                    <span>Thanh toán an toàn, bảo mật thông tin</span>
                </div>
            </div>

        </div>
    </div>

    <div class="mb-5">
        <div class="tab-nav d-flex">
            <button class="tab-btn active" onclick="doiTab('mo-ta', this)">
                <i class="fas fa-align-left me-1"></i>Mô Tả Sản Phẩm
            </button>
            <button class="tab-btn" onclick="doiTab('danh-gia', this)">
                <i class="fas fa-star me-1"></i>Đánh Giá ({{ $sanpham->binhluans->count() }})
            </button>
        </div>

        <div class="tab-content active" id="tab-mo-ta">
            @if($sanpham->mo_ta)
                {!! nl2br(e($sanpham->mo_ta)) !!}
            @else
                <p class="text-muted mb-0">Chưa có mô tả cho sản phẩm này.</p>
            @endif
        </div>

        <div class="tab-content" id="tab-danh-gia">
            @forelse($sanpham->binhluans->sortByDesc('ngay_dang') as $bl)
            <div class="review-item">
                <div>
                    <span class="fw-bold small">{{ $bl->user->ho_ten }}</span>
                    <span class="text-muted ms-2" style="font-size:.75rem">
                        {{ \Carbon\Carbon::parse($bl->ngay_dang)->format('d/m/Y') }}
                    </span>
                </div>
                <div class="small my-1" style="color:#f0a500">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $bl->sao_danh_gia ? '' : 'text-muted' }}"></i>
                    @endfor
                </div>
                <div class="small text-secondary">{{ $bl->noi_dung }}</div>
            </div>
            @empty
                <p class="text-muted mb-0">Chưa có đánh giá nào.</p>
            @endforelse
        </div>
    </div>

    @if($lienQuan->count() > 0)
    <div class="section-title d-flex align-items-center gap-2 mb-3">
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
                <div class="p-2">
                    <a href="{{ url('/san-pham/' . $sp->slug) }}" class="product-card-name d-block mb-1">
                        {{ $sp->ten_san_pham }}
                    </a>
                    <div class="fw-bold text-danger" style="font-size:.9rem">{{ number_format($sp->gia) }}đ</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>

@endsection

@section('extra-js')
<script>
function doiAnh(src, el) {
    document.getElementById('mainImg').src = src;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}

function chonBienThe(el) {
    if (el.disabled) return;
    document.querySelectorAll('.bienthe-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('giaHienThi').textContent = new Intl.NumberFormat('vi-VN').format(el.dataset.gia) + 'đ';
    document.getElementById('tenBienThe').textContent = el.dataset.ten;
    document.getElementById('soLuong').max = el.dataset.ton;

    const slHienThi = document.getElementById('slHienThi');
    if (slHienThi) {
        slHienThi.classList.remove('d-none');
        slHienThi.innerHTML = 'SL: <strong>' + el.dataset.ton + '</strong>';
    }

    if (el.dataset.anh) {
        document.getElementById('mainImg').src = el.dataset.anh;
        document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
        const matchThumb = document.querySelector(`.gallery-thumb[data-bienthe-id="${el.dataset.id}"]`);
        if (matchThumb) matchThumb.classList.add('active');
    }
}

function doiSoLuong(delta) {
    const input = document.getElementById('soLuong');
    const val = parseInt(input.value) + delta;
    const max = parseInt(input.max) || 99;
    if (val >= 1 && val <= max) input.value = val;
}

function doiTab(id, el) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('active');
    el.classList.add('active');
}

function themGioHang(sanPhamId) {
    const bientheActive = document.querySelector('.bienthe-btn.active');
    const soLuong = parseInt(document.getElementById('soLuong').value);
    const bientheId = bientheActive ? bientheActive.dataset.id : null;

    @if($sanpham->co_bien_the)
    if (!bientheId) { alert('Vui lòng chọn phân loại sản phẩm!'); return; }
    @endif

    fetch('{{ url('/gio-hang/them') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ san_pham_id: sanPhamId, bienthe_id: bientheId, so_luong: soLuong })
    })
    .then(r => r.json())
    .then(data => { if (data.success) alert('Đã thêm vào giỏ hàng!'); });
}

function muaNgay(sanPhamId) {
    const bientheActive = document.querySelector('.bienthe-btn.active');
    const soLuong = parseInt(document.getElementById('soLuong').value);
    const bientheId = bientheActive ? bientheActive.dataset.id : null;

    @if($sanpham->co_bien_the)
    if (!bientheId) { alert('Vui lòng chọn phân loại sản phẩm!'); return; }
    @endif

    // Tạo form POST ẩn để gửi lên /gio-hang/mua-ngay — dùng session, không đụng giỏ hàng
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ url('/gio-hang/mua-ngay') }}';

    const addHidden = (name, value) => {
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = name;
        input.value = value ?? '';
        form.appendChild(input);
    };

    addHidden('_token', document.querySelector('meta[name="csrf-token"]').content);
    addHidden('san_pham_id', sanPhamId);
    addHidden('so_luong', soLuong);
    if (bientheId) addHidden('bienthe_id', bientheId);

    document.body.appendChild(form);
    form.submit();
}

function dangKyEcho() {
    if (window.Echo) {
        window.Echo.channel('san-pham-{{ $sanpham->id }}')
            .listen('.ton-kho-cap-nhat', function (data) {
                const badge     = document.getElementById('badgeTonKho');
                const slHienThi = document.getElementById('slHienThi');

                if (badge) {
                    if (data.con_hang) {
                        badge.style.cssText = 'background:#e8f8f0;color:#27ae60;border:1px solid #a9dfbf;font-size:.8rem';
                        badge.innerHTML = '<i class="fas fa-check-circle me-1"></i>Còn hàng';
                    } else {
                        badge.style.cssText = 'background:#fdf2f2;color:#e74c3c;border:1px solid #f5c6c6;font-size:.8rem';
                        badge.innerHTML = '<i class="fas fa-times-circle me-1"></i>Hết hàng';
                    }
                }

                if (!data.bienthe_id) {
                    const input = document.getElementById('soLuong');
                    if (input) input.max = data.so_luong;
                    if (slHienThi) {
                        if (data.con_hang) {
                            slHienThi.classList.remove('d-none');
                            slHienThi.innerHTML = 'SL: <strong>' + data.so_luong + '</strong>';
                        } else {
                            slHienThi.classList.add('d-none');
                        }
                    }
                }

                if (data.bienthe_id) {
                    const btn = document.querySelector(`.bienthe-btn[data-id="${data.bienthe_id}"]`);
                    if (btn) {
                        btn.dataset.ton = data.bienthe_so_luong;

                        if (data.bienthe_so_luong <= 0 && !btn.classList.contains('het-hang')) {
                            btn.classList.add('het-hang');
                            btn.disabled = true;
                            if (!btn.querySelector('small')) {
                                btn.innerHTML += ' <small>(Hết)</small>';
                            }
                        } else if (data.bienthe_so_luong > 0 && btn.classList.contains('het-hang')) {
                            btn.classList.remove('het-hang');
                            btn.disabled = false;
                            btn.querySelectorAll('small').forEach(s => s.remove());
                        }

                        if (btn.classList.contains('active')) {
                            const input = document.getElementById('soLuong');
                            if (input) input.max = data.bienthe_so_luong;
                            if (slHienThi) {
                                slHienThi.classList.remove('d-none');
                                slHienThi.innerHTML = 'SL: <strong>' + data.bienthe_so_luong + '</strong>';
                            }
                        }
                    }
                }
            });
    } else {
        setTimeout(dangKyEcho, 200);
    }
}

document.addEventListener('DOMContentLoaded', dangKyEcho);
</script>
@endsection