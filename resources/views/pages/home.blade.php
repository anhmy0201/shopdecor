@extends('layouts.app')

@section('title', 'Trang Chủ')

@section('extra-css')
<style>
/* ===== HERO SLIDER ===== */
.hero-slider{
position:relative;
height:400px;
overflow:hidden;
background:#1a5276
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
.quick-cat-item:hover{color:#e74c3c}

/* ===== SECTION TITLE ===== */
.section-title{
background:#1a5276;
color:#fff;
padding:8px 12px;
margin-bottom:15px;
display:flex;
align-items:center;
gap:8px;
font-weight:700
}
.section-title a{
margin-left:auto;
color:#eee;
font-size:.8rem;
text-decoration:none
}

/* ===== PRODUCT CARD ===== */
.product-card-img{
position:relative;
padding-top:75%;
background:#f9f9f9;
overflow:hidden
}
.product-card-img img{
position:absolute;
inset:0;
width:100%;
height:100%;
object-fit:cover
}
.product-card-actions{
position:absolute;
bottom:0;
left:0;
right:0;
display:flex;
gap:4px;
padding:6px;
background:rgba(0,0,0,.5)
}
.btn-chitiet,
.btn-giohang{
flex:1;
border:none;
font-size:.75rem;
font-weight:600;
padding:6px;
cursor:pointer;
text-align:center;
text-decoration:none;
color:#fff
}
.btn-chitiet{background:#f0a500}
.btn-giohang{background:#1a5276}
</style>
@endsection

@section('content')

{{-- ===== HERO SLIDER ===== --}}
@php
    $bannerDir  = public_path('storage/banner');
    $extensions = ['jpg','jpeg','png','webp','gif'];
    $banners    = [];
    if (is_dir($bannerDir)) {
        foreach (scandir($bannerDir) as $file) {
            if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $extensions))
                $banners[] = $file;
        }
        sort($banners);
    }
    $slideTexts = [
        ['tag'=>'✨ Bộ sưu tập mới 2025','title'=>'Decor Bàn Làm Việc<br><span class="text-warning">Tinh Tế & Sang Trọng</span>','desc'=>'Hàng trăm mẫu tượng phong thủy, đèn decor và phụ kiện cao cấp.','btn'=>['url'=>'/san-pham','icon'=>'fa-shopping-bag','label'=>'Mua sắm ngay']],
        ['tag'=>'🔥 Ưu đãi đặc biệt',   'title'=>'Giảm Đến <span class="text-warning">30%</span><br>Đơn Hàng Đầu Tiên','desc'=>'Dùng mã <strong class="text-warning">WELCOME10</strong> khi thanh toán.','btn'=>['url'=>'/san-pham','icon'=>'fa-tag','label'=>'Săn ưu đãi ngay']],
        ['tag'=>'🌿 Mới về tuần này',    'title'=>'Cây Xanh Mini &<br><span class="text-warning">Đèn Decor</span> Tinh Tế','desc'=>'Thêm sức sống, ánh sáng ấm áp cho góc làm việc mỗi ngày.','btn'=>['url'=>'/danh-muc/cay-xanh-mini','icon'=>'fa-leaf','label'=>'Khám phá ngay']],
    ];
@endphp

<div class="bg-light py-3 border-bottom">
    <div class="container">
        <div class="hero-slider" id="heroSlider">
            @foreach($banners as $i => $file)
            @php $t = $slideTexts[$i] ?? $slideTexts[0]; @endphp
            <div class="hero-slide {{ $i===0?'active':'' }}">
                <img class="slide-bg" src="{{ asset('storage/banner/'.$file) }}" alt="Banner {{ $i+1 }}">
                <div class="slide-overlay"></div>
                <div class="slide-body">
                    <span class="badge bg-danger mb-2">{{ $t['tag'] }}</span>
                    <div class="fs-3 fw-bold text-white mb-2">{!! $t['title'] !!}</div>
                    <p class="text-light small mb-3">{!! $t['desc'] !!}</p>
                    <a href="{{ url($t['btn']['url']) }}" class="btn btn-danger fw-bold">
                        <i class="fas {{ $t['btn']['icon'] }} me-1"></i>{{ $t['btn']['label'] }}
                    </a>
                </div>
            </div>
            @endforeach

            @if(count($banners) > 1)
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
                                    <span class="badge bg-success position-absolute top-0 start-0 m-1">MỚI</span>
                                    <div class="product-card-actions">
                                        <a href="{{ url('/san-pham/'.$sp->slug) }}" class="btn-chitiet">
                                            <i class="fas fa-eye me-1"></i>Chi tiết
                                        </a>
                                        <button class="btn-giohang" onclick="themGioHang({{ $sp->id }})">
                                            <i class="fas fa-cart-plus me-1"></i>Thêm giỏ
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
                                            <i class="fas fa-eye me-1"></i>Chi tiết
                                        </a>
                                        <button class="btn-giohang" onclick="themGioHang({{ $sp->id }})">
                                            <i class="fas fa-cart-plus me-1"></i>Thêm giỏ
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

function themGioHang(id) {
    fetch('{{ url('/gio-hang/them') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ san_pham_id: id, so_luong: 1 })
    }).then(r => r.json()).then(d => {
        if (d.success) alert('Đã thêm vào giỏ hàng!');
    });
}
</script>
@endsection