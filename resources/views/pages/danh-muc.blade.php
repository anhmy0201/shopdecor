@extends('layouts.app')
@section('title', $danhMuc->ten_loai)

@section('extra-css')
<style>
.product-card { background:#fff; border:1px solid #ddd; transition:box-shadow 0.2s; height:100%; }
.product-card:hover { box-shadow:0 4px 15px rgba(0,0,0,0.1); }
.product-card-img { position:relative; overflow:hidden; padding-top:75%; background:#f9f9f9; }
.product-card-img img { position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; transition:transform 0.3s; }
.product-card:hover .product-card-img img { transform:scale(1.05); }
.product-card-actions {
    position:absolute; bottom:0; left:0; right:0;
    display:flex; gap:4px; padding:8px;
    background:rgba(0,0,0,0.5);
    transform:translateY(100%); transition:transform 0.25s;
}
.product-card:hover .product-card-actions { transform:translateY(0); }
.product-card-name {
    font-size:0.83rem; font-weight:600; color:#222; line-height:1.4;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;
    overflow:hidden; min-height:2.4em; text-decoration:none;
}
.product-card-name:hover { color:#e74c3c; }
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
        @if(is_null($danhMuc->slug))
            <span class="text-muted">Tất Cả Sản Phẩm</span>
        @else
            <a href="{{ url('/san-pham') }}" class="text-decoration-none" style="color:#1a5276">Sản phẩm</a>
            <span class="mx-2 text-muted">›</span>
            <span class="text-muted">{{ $danhMuc->ten_loai }}</span>
        @endif
    </div>
</div>

<div class="container py-4">
    <div class="row g-3">

        {{-- SIDEBAR --}}
        <div class="col-lg-3 d-none d-lg-block">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background:#1a5276;color:#fff;">
                    <i class="fas fa-bars me-2"></i>DANH MỤC SẢN PHẨM
                </div>
                <div class="list-group list-group-flush">

                    {{-- Tất cả sản phẩm --}}
                    <a href="{{ url('/san-pham') }}"
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center small
                              {{ is_null($danhMuc->slug) ? 'active' : '' }}"
                       style="{{ is_null($danhMuc->slug) ? 'background:#1a5276;border-color:#1a5276;color:#fff;' : '' }}">
                        <span>
                            <i class="fas fa-th me-1" style="font-size:0.65rem"></i>
                            Tất Cả Sản Phẩm
                        </span>
                        <span class="badge {{ is_null($danhMuc->slug) ? 'bg-danger' : 'bg-secondary' }}">
                            {{ $danhMucs->sum('sanphams_count') }}
                        </span>
                    </a>

                    @foreach($danhMucs as $dm)
                    <a href="{{ url('/danh-muc/' . $dm->slug) }}"
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center small
                              {{ $dm->slug === $danhMuc->slug ? 'active' : '' }}"
                       style="{{ $dm->slug === $danhMuc->slug ? 'background:#1a5276;border-color:#1a5276;' : '' }}">
                        <span>
                            <i class="fas fa-chevron-right me-1" style="font-size:0.65rem"></i>
                            {{ $dm->ten_loai }}
                        </span>
                        <span class="badge {{ $dm->slug === $danhMuc->slug ? 'bg-danger' : 'bg-secondary' }}">
                            {{ $dm->sanphams_count }}
                        </span>
                    </a>
                    @endforeach

                </div>
            </div>
        </div>

        {{-- NỘI DUNG --}}
        <div class="col-lg-9">

            {{-- Tiêu đề danh mục --}}
            <div class="fw-bold py-2 px-3 mb-3 text-white" style="background:#1a5276;font-size:0.95rem">
                <i class="fas fa-th-large me-2"></i>{{ strtoupper($danhMuc->ten_loai) }}
            </div>

            {{-- Toolbar --}}
            <div class="d-flex justify-content-between align-items-center bg-light border px-3 py-2 mb-3 small">
                <span class="text-muted">
                    Tìm thấy <strong class="text-danger">{{ $sanphams->total() }}</strong> sản phẩm
                </span>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted">Sắp xếp:</span>
                    <select class="form-select form-select-sm" style="width:auto"
                            onchange="window.location.href=this.value">
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
                            <span class="badge bg-danger position-absolute" style="top:8px;left:8px;font-size:0.65rem">MỚI</span>
                            @if($sp->gia_cu && $sp->gia_cu > $sp->gia)
                                <span class="badge bg-warning text-dark position-absolute" style="top:8px;right:8px;font-size:0.65rem">
                                    -{{ round(($sp->gia_cu - $sp->gia) / $sp->gia_cu * 100) }}%
                                </span>
                            @endif
                            <div class="product-card-actions">
                                <a href="{{ url('/san-pham/' . $sp->slug) }}"
                                   class="btn btn-sm btn-warning fw-bold flex-fill" style="font-size:0.75rem">
                                    <i class="fas fa-eye me-1"></i>Chi tiết
                                </a>
                                <button class="btn btn-sm fw-bold flex-fill text-white"
                                        style="background:#1a5276;font-size:0.75rem"
                                        onclick="themGioHang({{ $sp->id }})">
                                    <i class="fas fa-cart-plus me-1"></i>Thêm giỏ
                                </button>
                            </div>
                        </div>
                        <div class="p-2">
                            <a href="{{ url('/san-pham/' . $sp->slug) }}" class="product-card-name d-block mb-1">
                                {{ $sp->ten_san_pham }}
                            </a>
                            <span class="fw-bold text-danger" style="font-size:0.92rem">
                                {{ number_format($sp->gia) }}đ
                            </span>
                            @if($sp->gia_cu && $sp->gia_cu > $sp->gia)
                                <span class="text-muted text-decoration-line-through ms-1" style="font-size:0.78rem">
                                    {{ number_format($sp->gia_cu) }}đ
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center">
                {{ $sanphams->links() }}
            </div>

            @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-box-open fa-3x mb-3 d-block text-secondary"></i>
                <h5>Chưa có sản phẩm trong danh mục này</h5>
                <p>Vui lòng quay lại sau hoặc xem các danh mục khác.</p>
                <a href="{{ url('/san-pham') }}" class="btn btn-primary mt-2"
                   style="background:#1a5276;border-color:#1a5276">
                    Xem tất cả sản phẩm
                </a>
            </div>
            @endif

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