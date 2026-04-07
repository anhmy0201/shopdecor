@extends('layouts.app')
@section('title', 'Tin Tức')

@section('content')

{{-- ===== HEADER TIN TỨC ===== --}}
<div class="tintuc-header">
    <div class="container">
        <div class="tintuc-header__inner">
            <span class="tintuc-header__label">TIN TỨC</span>
        </div>
    </div>
</div>

{{-- ===== MAIN: SIDEBAR + NỘI DUNG ===== --}}
<div class="py-4">
    <div class="container">
        <div class="row">

            {{-- ============================================================ --}}
            {{-- SIDEBAR TRÁI (copy từ home) --}}
            {{-- ============================================================ --}}
            <div class="col-lg-3 d-none d-lg-block">

                {{-- Danh mục sản phẩm --}}
                <div class="border mb-3">
                    <div class="px-3 py-2 fw-bold text-white" style="background:#1a5276">
                        <i class="fas fa-bars me-2"></i>DANH MỤC SẢN PHẨM
                    </div>
                    @foreach([
                        ['/danh-muc/tuong-figurine', 'Tượng & Figurine', 'tuong'],
                        ['/danh-muc/den-decor',      'Đèn Decor',        'den'],
                        ['/danh-muc/cay-xanh-mini',  'Cây Xanh Mini',    'cay'],
                        ['/danh-muc/van-phong-pham', 'Văn Phòng Phẩm',   'vanphong'],
                        ['/danh-muc/to-chuc-ban',    'Tổ Chức Bàn',      'tochuc'],
                        ['/danh-muc/desk-mat',       'Desk Mat',         'deskmat'],
                    ] as [$url, $label, $key])
                    <a href="{{ url($url) }}"
                       class="d-flex justify-content-between px-3 py-2 border-bottom text-decoration-none text-dark small">
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

            </div>
            {{-- /SIDEBAR --}}

            {{-- ============================================================ --}}
            {{-- NỘI DUNG CHÍNH: DANH SÁCH TIN TỨC --}}
            {{-- ============================================================ --}}
            <div class="col-lg-9">

                <div class="tintuc-list">

                    @forelse($tintuc as $bai)
                    <div class="tintuc-item">

                        {{-- Meta row --}}
                        <div class="tintuc-item__meta">
                            <span>
                                <i class="fas fa-folder-open me-1"></i>Tin tức
                            </span>
                            <span>
                                <i class="fas fa-comment me-1"></i>0 comment
                            </span>
                            <span>
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $bai->ngay_dang?->translatedFormat('F-j-Y')
                                    ?? $bai->created_at?->translatedFormat('F-j-Y') }}
                            </span>
                        </div>
                        <div class="tintuc-item__body">

                            @if($bai->anh_dai_dien)
                            <a href="{{ route('tin-tuc.show', $bai->slug) }}"
                               class="tintuc-item__thumb-wrap">
                                <img src="{{ asset($bai->anh_dai_dien) }}"
                                     class="tintuc-item__thumb"
                                     alt="{{ $bai->tieu_de }}">
                            </a>
                            @endif

                            <div class="tintuc-item__content {{ !$bai->anh_dai_dien ? 'tintuc-item__content--full' : '' }}">
                                <h2 class="tintuc-item__title">
                                    <a href="{{ route('tin-tuc.show', $bai->slug) }}"
                                       class="tintuc-item__title-link">
                                        {{ $bai->tieu_de }}
                                    </a>
                                </h2>

                                @if($bai->mo_ta_ngan)
                                <p class="tintuc-item__desc">
                                    {{ \Illuminate\Support\Str::limit($bai->mo_ta_ngan, 200) }}
                                </p>
                                @endif

                                <a href="{{ route('tin-tuc.show', $bai->slug) }}"
                                   class="tintuc-item__btn">
                                    Xem thêm
                                </a>
                            </div>

                        </div>
                    </div>
                    @empty
                    <div class="tintuc-empty">
                        <i class="fas fa-newspaper fa-3x d-block mb-3"></i>
                        Chưa có bài viết nào.
                    </div>
                    @endforelse

                </div>

                {{-- Pagination --}}
                @if($tintuc->hasPages())
                <div class="mt-4">
                    {{ $tintuc->links() }}
                </div>
                @endif

            </div>
            {{-- /NỘI DUNG --}}

        </div>
    </div>
</div>

{{-- ===== CSS ===== --}}
<style>
/* ---------- Header "TIN TỨC" ---------- */
.tintuc-header {
    background: #e8e8e8;
}
.tintuc-header__inner {
    display: flex;
    align-items: stretch;
}
.tintuc-header__label {
    display: inline-block;
    background: #1a5fa8;
    color: #fff;
    font-size: .85rem;
    font-weight: 700;
    letter-spacing: 1px;
    padding: 10px 44px 10px 28px;
    clip-path: polygon(0 0, calc(100% - 16px) 0, 100% 50%, calc(100% - 16px) 100%, 0 100%);
}

/* ---------- List wrapper ---------- */
.tintuc-list {
    border: 1px solid #e0e0e0;
    background: #fff;
}

/* ---------- Mỗi bài viết ---------- */
.tintuc-item {
    padding: 14px 16px 20px;
    border-bottom: 1px solid #e4e4e4;
}
.tintuc-item:last-child { border-bottom: none; }

/* Meta */
.tintuc-item__meta {
    display: flex;
    align-items: center;
    gap: 16px;
    font-size: .78rem;
    color: #777;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

/* Body */
.tintuc-item__body {
    display: flex;
    gap: 18px;
    align-items: flex-start;
}

/* Thumbnail */
.tintuc-item__thumb-wrap { flex-shrink: 0; display: block; }
.tintuc-item__thumb {
    width: 160px;
    height: 120px;
    object-fit: cover;
    display: block;
    border-radius: 2px;
}

/* Content text */
.tintuc-item__content { flex: 1; min-width: 0; }
.tintuc-item__content--full { width: 100%; }

.tintuc-item__title {
    font-size: 1rem;
    font-weight: 700;
    margin: 0 0 10px;
    line-height: 1.4;
}
.tintuc-item__title-link { color: #222; text-decoration: none; }
.tintuc-item__title-link:hover { color: #1a5fa8; text-decoration: underline; }

.tintuc-item__desc {
    font-size: .875rem;
    color: #555;
    line-height: 1.65;
    margin-bottom: 14px;
}

/* Nút Xem thêm */
.tintuc-item__btn {
    display: inline-block;
    background: #1a5fa8;
    color: #fff;
    font-size: .8rem;
    font-weight: 600;
    padding: 7px 22px;
    border-radius: 3px;
    text-decoration: none;
    transition: background .18s;
}
.tintuc-item__btn:hover { background: #154e8f; color: #fff; }

/* Empty */
.tintuc-empty {
    text-align: center;
    color: #aaa;
    padding: 60px 20px;
}

/* Responsive */
@media (max-width: 576px) {
    .tintuc-item__body { flex-direction: column; }
    .tintuc-item__thumb { width: 100%; height: 180px; }
}
</style>

@endsection