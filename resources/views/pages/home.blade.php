@extends('layouts.app')

@section('title', 'Trang Chủ')

@section('extra-css')
<style>
/* ===== HERO SLIDER ===== */
.hero-slider{
position:relative;
height:400px;
overflow:hidden;
background:#1a5276;
width:100%
}
.hero-slide{
position:absolute;
inset:0;
opacity:0;
display:flex;
align-items:center;
transition:opacity .5s
}
.hero-slide.active{opacity:1}
.slide-bg{
position:absolute;
inset:0;
width:100%;
height:100%;
object-fit:cover
}
.slide-overlay{
position:absolute;
inset:0;
background:rgba(0,0,0,.4)
}
.slide-body{
position:relative;
z-index:2;
padding:0 50px;
max-width:580px
}

/* ===== SLIDER CONTROLS ===== */
.slider-arrow{
position:absolute;
top:50%;
transform:translateY(-50%);
width:40px;
height:40px;
border:none;
background:rgba(0,0,0,.3);
color:#fff;
cursor:pointer
}
.prev{left:0}
.next{right:0}
.slider-dots{
position:absolute;
bottom:14px;
left:50%;
transform:translateX(-50%);
display:flex;
gap:6px
}
.slider-dot{
width:10px;
height:10px;
border-radius:50%;
border:none;
background:rgba(255,255,255,.5);
cursor:pointer;
padding:0
}
.slider-dot.active{background:#fff}
.slider-progress{
position:absolute;
bottom:0;
left:0;
height:3px;
background:#e74c3c;
width:0
}

/* ===== QUICK CATEGORY ===== */
.quick-cat-circle{
width:70px;
height:70px;
border-radius:50%;
margin:0 auto 8px;
background:#fff;
border:1px solid #ddd;
display:flex;
align-items:center;
justify-content:center;
font-size:1.5rem
}
.quick-cat-item{
text-align:center;
text-decoration:none;
color:#333;
display:block
}
.quick-cat-item:hover{color:#f07b05}

/* ===== SECTION TITLE – DecoPro style (clip-path góc chéo) ===== */
.section-title{
background:#1a5276;
color:#fff;
padding:8px 36px 8px 12px;
margin-bottom:15px;
display:flex;
align-items:center;
gap:8px;
font-weight:700;
clip-path:polygon(0 0,calc(100% - 20px) 0,100% 50%,calc(100% - 20px) 100%,0 100%)
}
.section-title a{
margin-left:auto;
color:#cde;
font-size:.8rem;
text-decoration:none
}
.section-title a:hover{color:#fff}

/* ===== PRODUCT CARD ===== */
.product-card-img{
position:relative;
padding-top:100%;
background:#f9f9f9;
overflow:hidden
}
.product-card-img img{
position:absolute;
inset:0;
width:100%;
height:100%;
object-fit:cover;
transition:transform .35s
}
/* hover card */
.border.bg-white{transition:box-shadow .2s,transform .2s}
.border.bg-white:hover{box-shadow:0 4px 16px rgba(0,0,0,.12);transform:translateY(-2px)}
.border.bg-white:hover .product-card-img img{transform:scale(1.05)}

/* badge MỚI – DecoPro: xanh lá, góc trái, sát viền */
.badge-moi{
position:absolute;
top:0;
left:0;
background:#27ae60;
color:#fff;
font-size:.68rem;
font-weight:700;
padding:3px 8px;
letter-spacing:.5px;
z-index:2
}

/* action buttons – hiện khi hover */
.product-card-actions{
position:absolute;
bottom:0;
left:0;
right:0;
display:flex;
gap:2px;
padding:0;
opacity:0;
transform:translateY(6px);
transition:opacity .25s,transform .25s
}
.border.bg-white:hover .product-card-actions{
opacity:1;
transform:translateY(0)
}
.btn-chitiet,
.btn-giohang{
flex:1;
border:none;
font-size:.75rem;
font-weight:700;
padding:8px 4px;
cursor:pointer;
text-align:center;
text-decoration:none;
color:#fff;
display:flex;
align-items:center;
justify-content:center;
gap:4px
}
/* cam = Chi tiết, navy = Thêm giỏ – giống DecoPro */
.btn-chitiet{background:#f07b05}
.btn-chitiet:hover{background:#d96e00;color:#fff}
.btn-giohang{background:#1a5276}
.btn-giohang:hover{background:#154360;color:#fff}
</style>
@endsection

@section('content')

<div class="hero-slider" id="heroSlider">
    @forelse($banners as $i => $banner)
    <div class="hero-slide {{ $i===0?'active':'' }}">
        <img class="slide-bg" src="{{ asset($banner->duong_dan_anh) }}" alt="{{ $banner->tieu_de ?? 'Banner '.($i+1) }}">
        <div class="slide-overlay"></div>
        @if($banner->tieu_de || $banner->mo_ta)
        <div class="slide-body">
            @if($banner->tieu_de)
            <div class="fs-3 fw-bold text-white mb-2">{{ $banner->tieu_de }}</div>
            @endif
            @if($banner->mo_ta)
            <p class="text-light small mb-3">{{ $banner->mo_ta }}</p>
            @endif
            @if($banner->url_lien_ket)
            <a href="{{ url($banner->url_lien_ket) }}" class="btn btn-danger fw-bold">
                <i class="fas fa-arrow-right me-1"></i>Xem ngay
            </a>
            @endif
        </div>
        @endif
    </div>
    @empty
    <div class="hero-slide active">
        <div class="slide-body text-center">
            <div class="fs-3 fw-bold text-white">Chào mừng đến ShopDecor</div>
        </div>
    </div>
    @endforelse

    @if($banners->count() > 1)
    <button class="slider-arrow prev" onclick="heroSlide(-1)"><i class="fas fa-chevron-left"></i></button>
    <button class="slider-arrow next" onclick="heroSlide(1)"><i class="fas fa-chevron-right"></i></button>
    <div class="slider-dots">
        @foreach($banners as $i => $_)
            <button class="slider-dot {{ $i===0?'active':'' }}" onclick="heroGo({{ $i }})"></button>
        @endforeach
    </div>
    <div class="slider-progress" id="sliderProgress"></div>
    @endif
</div>
</div>

{{-- ===== DANH MỤC NHANH ===== --}}
<div class="bg-warning bg-opacity-10 py-3 border-bottom">
    <div class="container">
        <div class="row row-cols-3 row-cols-md-6 g-2">
            @foreach([
                ['/danh-muc/tuong-figurine','🏆','Tượng & Figurine'],
                ['/danh-muc/den-decor',     '💡','Đèn Decor'],
                ['/danh-muc/cay-xanh-mini', '🌿','Cây Xanh Mini'],
                ['/danh-muc/van-phong-pham','✒️','Văn Phòng Phẩm'],
                ['/danh-muc/to-chuc-ban',   '📦','Tổ Chức Bàn'],
                ['/danh-muc/desk-mat',      '🖱️','Desk Mat'],
            ] as [$url,$icon,$name])
            <div class="col">
                <a href="{{ url($url) }}" class="quick-cat-item">
                    <div class="quick-cat-circle">{{ $icon }}</div>
                    <div class="small">{{ $name }}</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ===== MAIN CONTENT ===== --}}
<div class="py-4">
    <div class="container">
        <div class="row">

            {{-- SIDEBAR --}}
            <div class="col-lg-3 d-none d-lg-block">

                {{-- Danh mục --}}
                <div class="border mb-3">
                    <div class="px-3 py-2 fw-bold text-white" style="background:#1a5276">
                        <i class="fas fa-bars me-2"></i>DANH MỤC SẢN PHẨM
                    </div>
                    @foreach([
                        ['/danh-muc/tuong-figurine','Tượng & Figurine','tuong'],
                        ['/danh-muc/den-decor',     'Đèn Decor',       'den'],
                        ['/danh-muc/cay-xanh-mini', 'Cây Xanh Mini',   'cay'],
                        ['/danh-muc/van-phong-pham','Văn Phòng Phẩm',  'vanphong'],
                        ['/danh-muc/to-chuc-ban',   'Tổ Chức Bàn',     'tochuc'],
                        ['/danh-muc/desk-mat',      'Desk Mat',        'deskmat'],
                    ] as [$url,$label,$key])
                    <a href="{{ url($url) }}" class="d-flex justify-content-between px-3 py-2 border-bottom text-decoration-none text-dark small">
                        <span><i class="fas fa-chevron-right me-1 text-muted small"></i>{{ $label }}</span>
                        <span class="text-muted">{{ $soLuong[$key] ?? 0 }}</span>
                    </a>
                    @endforeach
                </div>

                {{-- Khuyến mãi --}}
                <div class="bg-danger text-white text-center p-3 mb-3">
                    <h6 class="mb-1">🎉 ƯU ĐÃI HÔM NAY</h6>
                    <p class="small mb-2">Giảm 10% đơn hàng đầu tiên. Dùng mã:</p>
                    <span class="bg-white text-danger fw-bold px-3 py-1 d-inline-block">WELCOME10</span>
                </div>

                {{-- Bán chạy --}}
                <div class="border">
                    <div class="px-3 py-2 fw-bold text-white bg-danger">
                        <i class="fas fa-fire me-2"></i>BÁN CHẠY NHẤT
                    </div>
                    @isset($banChay)
                        @foreach($banChay->take(3) as $sp)
                        <a href="{{ url('/san-pham/'.$sp->slug) }}" class="d-flex gap-2 p-2 border-bottom text-decoration-none text-dark">
                            <img src="{{ asset($sp->anhChinh?->duong_dan_anh ?? 'images/no-image.png') }}"
                                 alt="{{ $sp->ten_san_pham }}"
                                 style="width:60px;height:60px;object-fit:cover;border:1px solid #ddd">
                            <div>
                                <div class="small">{{ Str::limit($sp->ten_san_pham, 45) }}</div>
                                <div class="text-danger fw-bold small">{{ number_format($sp->gia) }}đ</div>
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
                            <div class="border bg-white h-100">
                                <div class="product-card-img">
                                    <img src="{{ asset($sp->anhChinh?->duong_dan_anh ?? 'images/no-image.png') }}"
                                         alt="{{ $sp->ten_san_pham }}"
                                         loading="lazy">
                                    <span class="badge-moi">MỚI</span>
                                    <div class="product-card-actions">
                                        <a href="{{ url('/san-pham/'.$sp->slug) }}" class="btn-chitiet">
                                            <i class="fas fa-eye"></i>Chi tiết
                                        </a>
                                        <button class="btn-giohang" onclick="themGioHangQuick({{ $sp->id }}, {{ $sp->co_bien_the ? 'true' : 'false' }}, '{{ $sp->slug }}')">
                                            <i class="fas fa-cart-plus"></i>Thêm giỏ
                                        </button>
                                    </div>
                                </div>
                                <div class="p-2">
                                    <a href="{{ url('/san-pham/'.$sp->slug) }}" class="d-block small fw-semibold text-dark text-decoration-none mb-1">
                                        {{ $sp->ten_san_pham }}
                                    </a>
                                    <span class="text-danger fw-bold">{{ number_format($sp->gia) }}đ</span>
                                    @if($sp->gia_cu && $sp->gia_cu > $sp->gia)
                                        <span class="text-muted small text-decoration-line-through ms-1">{{ number_format($sp->gia_cu) }}đ</span>
                                    @endif
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
                            <div class="border bg-white h-100">
                                <div class="product-card-img">
                                    <img src="{{ asset($sp->anhChinh?->duong_dan_anh ?? 'images/no-image.png') }}"
                                         alt="{{ $sp->ten_san_pham }}"
                                         loading="lazy">
                                    <div class="product-card-actions">
                                        <a href="{{ url('/san-pham/'.$sp->slug) }}" class="btn-chitiet">
                                            <i class="fas fa-eye"></i>Chi tiết
                                        </a>
                                        <button class="btn-giohang" onclick="themGioHangQuick({{ $sp->id }}, {{ $sp->co_bien_the ? 'true' : 'false' }}, '{{ $sp->slug }}')">
                                            <i class="fas fa-cart-plus"></i>Thêm giỏ
                                        </button>
                                    </div>
                                </div>
                                <div class="p-2">
                                    <a href="{{ url('/san-pham/'.$sp->slug) }}" class="d-block small fw-semibold text-dark text-decoration-none mb-1">
                                        {{ $sp->ten_san_pham }}
                                    </a>
                                    <span class="text-danger fw-bold">{{ number_format($sp->gia) }}đ</span>
                                    @if($sp->gia_cu && $sp->gia_cu > $sp->gia)
                                        <span class="text-muted small text-decoration-line-through ms-1">{{ number_format($sp->gia_cu) }}đ</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endisset
                </div>

                <div class="text-center mb-3">
                    <a href="{{ url('/san-pham') }}" class="btn btn-danger fw-bold px-4">
                        <i class="fas fa-arrow-down me-2"></i>XEM THÊM SẢN PHẨM
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ===== WHY US ===== --}}
<div class="bg-white border-top border-3 py-4" style="border-color:#1a5276!important">
    <div class="container">
        <div class="row text-center g-3">
            @foreach([
                ['fa-truck',    'GIAO HÀNG TOÀN QUỐC',  'Miễn phí vận chuyển'],
                ['fa-handshake','UY TÍN - CHUYÊN NGHIỆP','Tư vấn, hỗ trợ tận tâm'],
                ['fa-medal',    'CAM KẾT CHẤT LƯỢNG',   'Nhập khẩu chính hãng'],
                ['fa-tag',      'GIÁ TỐT NHẤT',         'Cam kết giá cạnh tranh'],
            ] as [$icon,$title,$sub])
            <div class="col-6 col-md-3">
                <i class="fas {{ $icon }} fs-3 text-danger mb-2 d-block"></i>
                <h6 class="fw-bold small">{{ $title }}</h6>
                <p class="text-muted small mb-0">{{ $sub }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script>
(function () {
    const SPEED  = 5000;
    const slides = document.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.slider-dot');
    const bar    = document.getElementById('sliderProgress');
    if (!slides.length) return;
    let cur = 0, timer;

    function show(idx) {
        slides[cur].classList.remove('active');
        dots[cur]?.classList.remove('active');
        cur = (idx + slides.length) % slides.length;
        slides[cur].classList.add('active');
        dots[cur]?.classList.add('active');
        if (bar) {
            bar.style.transition = 'none';
            bar.style.width = '0%';
            bar.offsetWidth;
            bar.style.transition = `width ${SPEED}ms linear`;
            bar.style.width = '100%';
        }
    }
    function reset() {
        clearInterval(timer);
        timer = setInterval(() => show(cur + 1), SPEED);
    }
    window.heroSlide = d => { show(cur + d); reset(); };
    window.heroGo    = i => { show(i); reset(); };
    document.getElementById('heroSlider')?.addEventListener('mouseenter', () => clearInterval(timer));
    document.getElementById('heroSlider')?.addEventListener('mouseleave', reset);
    show(0); reset();
})();

function themGioHangQuick(id, coBienThe, slug) {
    // Nếu sản phẩm có biến thể, chuyển sang trang chi tiết để chọn biến thể
    if (coBienThe) {
        window.location.href = '{{ url('/san-pham') }}/' + slug + '?them_gio_hang=1';
        return;
    }
    fetch('{{ url('/gio-hang/them') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ san_pham_id: id, so_luong: 1 })
    }).then(r => r.json()).then(d => {
        if (d.success) {
            // Cập nhật số lượng giỏ hàng trên header nếu có
            const badge = document.querySelector('.cart-count, #cartCount, .badge-cart');
            if (badge && d.tong_so_luong !== undefined) badge.textContent = d.tong_so_luong;
            alert('Đã thêm vào giỏ hàng!');
        }
    });
}
</script>
@endsection