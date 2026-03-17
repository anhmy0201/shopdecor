@extends('layouts.app')
@section('title', 'Tin Tức')
 
@section('content')
<div class="container py-4">
 
    <h4 class="fw-bold mb-4">
        <i class="fas fa-newspaper text-danger me-2"></i>Tin Tức & Cẩm Nang Decor
    </h4>
 
    <div class="row g-4">
        @forelse($tintuc as $bai)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                {{-- Ảnh đại diện --}}
                <a href="{{ route('tin-tuc.show', $bai->slug) }}">
                    @if($bai->anh_dai_dien)
                        <img src="{{ asset($bai->anh_dai_dien) }}"
                             class="card-img-top"
                             style="height:200px;object-fit:cover"
                             alt="{{ $bai->tieu_de }}">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center"
                             style="height:200px">
                            <i class="fas fa-newspaper fa-3x text-muted"></i>
                        </div>
                    @endif
                </a>
 
                <div class="card-body d-flex flex-column">
                    <h6 class="fw-bold mb-2">
                        <a href="{{ route('tin-tuc.show', $bai->slug) }}"
                           class="text-dark text-decoration-none">
                            {{ $bai->tieu_de }}
                        </a>
                    </h6>
                    @if($bai->mo_ta_ngan)
                        <p class="text-muted small mb-3">
                            {{ \Illuminate\Support\Str::limit($bai->mo_ta_ngan, 100) }}
                        </p>
                    @endif
                    <div class="mt-auto d-flex justify-content-between align-items-center small text-muted">
                        <span>
                            <i class="fas fa-user me-1"></i>{{ $bai->tacGia?->ho_ten ?? 'ShopDecor' }}
                        </span>
                        <span>
                            <i class="fas fa-eye me-1"></i>{{ number_format($bai->luot_xem) }}
                            &nbsp;·&nbsp;
                            {{ $bai->ngay_dang?->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-5">
            <i class="fas fa-newspaper fa-3x mb-3 d-block"></i>
            Chưa có bài viết nào.
        </div>
        @endforelse
    </div>
 
    {{-- Pagination --}}
    @if($tintuc->hasPages())
    <div class="mt-4">{{ $tintuc->links() }}</div>
    @endif
 
</div>
@endsection