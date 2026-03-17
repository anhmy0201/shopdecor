@extends('layouts.app')
@section('title', $bai->tieu_de)
 
@section('content')
<div class="container py-4">
<div class="row">
 
    {{-- NỘI DUNG CHÍNH --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
 
                {{-- Breadcrumb --}}
                <nav class="mb-3">
                    <small class="text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-decoration-none">Trang chủ</a>
                        &rsaquo;
                        <a href="{{ route('tin-tuc.index') }}" class="text-muted text-decoration-none">Tin tức</a>
                        &rsaquo;
                        <span>{{ \Illuminate\Support\Str::limit($bai->tieu_de, 40) }}</span>
                    </small>
                </nav>
 
                {{-- Tiêu đề --}}
                <h3 class="fw-bold mb-3">{{ $bai->tieu_de }}</h3>
 
                {{-- Meta --}}
                <div class="d-flex gap-3 text-muted small mb-4 pb-3 border-bottom">
                    <span><i class="fas fa-user me-1"></i>{{ $bai->tacGia?->ho_ten ?? 'ShopDecor' }}</span>
                    <span><i class="fas fa-calendar me-1"></i>{{ $bai->ngay_dang?->format('d/m/Y') }}</span>
                    <span><i class="fas fa-eye me-1"></i>{{ number_format($bai->luot_xem) }} lượt xem</span>
                </div>
 
                {{-- Ảnh đại diện --}}
                @if($bai->anh_dai_dien)
                <img src="{{ asset($bai->anh_dai_dien) }}"
                     class="img-fluid rounded mb-4 w-100"
                     style="max-height:400px;object-fit:cover"
                     alt="{{ $bai->tieu_de }}">
                @endif
 
                {{-- Mô tả ngắn --}}
                @if($bai->mo_ta_ngan)
                <div class="p-3 bg-light rounded mb-4 fst-italic text-muted border-start border-danger border-3">
                    {{ $bai->mo_ta_ngan }}
                </div>
                @endif
 
                {{-- Nội dung --}}
                @if($bai->noi_dung)
                <div class="bai-viet-content" style="line-height:1.8">
                    {!! $bai->noi_dung !!}
                </div>
                @endif
 
                {{-- Ảnh Gallery trong bài --}}
                @if($bai->hinhanhs->count() > 0)
                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-images me-2 text-danger"></i>Hình Ảnh Minh Hoạ
                    </h6>
                    <div class="row g-3">
                        @foreach($bai->hinhanhs as $anh)
                        <div class="col-{{ $bai->hinhanhs->count() === 1 ? '12' : ($bai->hinhanhs->count() === 2 ? '6' : '4') }}">
                            <img src="{{ asset($anh->duong_dan_anh) }}"
                                 class="img-fluid rounded w-100"
                                 style="height:220px;object-fit:cover"
                                 alt="{{ $anh->chu_thich ?? $bai->tieu_de }}">
                            @if($anh->chu_thich)
                                <div class="text-center text-muted small mt-1 fst-italic">
                                    {{ $anh->chu_thich }}
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
 
            </div>
        </div>
    </div>
 
    {{-- SIDEBAR --}}
    <div class="col-lg-4">
 
        {{-- Bài liên quan --}}
        @if($lienQuan->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold border-bottom">
                <i class="fas fa-bookmark text-danger me-2"></i>Bài Viết Khác
            </div>
            <div class="card-body p-0">
                @foreach($lienQuan as $lr)
                <a href="{{ route('tin-tuc.show', $lr->slug) }}"
                   class="d-flex gap-3 p-3 text-decoration-none text-dark {{ !$loop->last ? 'border-bottom' : '' }}">
                    @if($lr->anh_dai_dien)
                        <img src="{{ asset($lr->anh_dai_dien) }}"
                             width="70" height="55"
                             class="rounded flex-shrink-0"
                             style="object-fit:cover">
                    @else
                        <div class="rounded bg-light d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:70px;height:55px">
                            <i class="fas fa-newspaper text-muted"></i>
                        </div>
                    @endif
                    <div>
                        <div class="small fw-bold" style="line-height:1.3">
                            {{ \Illuminate\Support\Str::limit($lr->tieu_de, 60) }}
                        </div>
                        <div class="text-muted" style="font-size:0.72rem;margin-top:4px">
                            {{ $lr->ngay_dang?->format('d/m/Y') }}
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
 
    </div>
 
</div>
</div>
@endsection