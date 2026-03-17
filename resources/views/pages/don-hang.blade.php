@extends('layouts.app')
@section('title', 'Đơn Hàng Của Tôi')

@section('extra-css')
<style>
.order-item-name {
    font-size:0.83rem; font-weight:600; color:#222; line-height:1.4;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
}
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
        <span class="text-muted">Đơn hàng của tôi</span>
    </div>
</div>

<div class="container py-4">

    {{-- Tiêu đề --}}
    <div class="fw-bold py-2 px-3 mb-3 text-white" style="background:#1a5276">
        <i class="fas fa-box me-2"></i>ĐƠN HÀNG CỦA TÔI
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabs --}}
    @php
        $tabs = [
            ['key' => 'tat-ca',       'label' => 'Tất cả',        'icon' => 'fa-list'],
            ['key' => 'cho-xac-nhan', 'label' => 'Chờ xác nhận', 'icon' => 'fa-clock'],
            ['key' => 'dang-xu-ly',   'label' => 'Đang xử lý',   'icon' => 'fa-sync-alt'],
            ['key' => 'hoan-tat',     'label' => 'Hoàn tất',     'icon' => 'fa-check-circle'],
            ['key' => 'da-huy',       'label' => 'Đã hủy',       'icon' => 'fa-times-circle'],
        ];
    @endphp
    <div class="d-flex border-bottom mb-3" style="overflow-x:auto;flex-wrap:nowrap;border-bottom:2px solid #1a5276!important">
        @foreach($tabs as $tab)
        <a href="{{ url('/don-hang') }}?trang_thai={{ $tab['key'] }}"
           class="d-inline-flex align-items-center gap-2 px-3 py-2 text-decoration-none border border-bottom-0 flex-shrink-0 small fw-bold
                  {{ $trangThai === $tab['key'] ? 'text-white' : 'text-muted' }}"
           style="{{ $trangThai === $tab['key'] ? 'background:#1a5276;border-color:#1a5276' : 'background:#f8f8f8' }}">
            <i class="fas {{ $tab['icon'] }}"></i>
            {{ $tab['label'] }}
            @if($dem[$tab['key']] > 0)
                <span class="badge {{ $trangThai === $tab['key'] ? 'bg-white text-dark' : 'bg-secondary' }}">
                    {{ $dem[$tab['key']] }}
                </span>
            @endif
        </a>
        @endforeach
    </div>

    {{-- Danh sách đơn hàng --}}
    @if($donhangs->count() > 0)

        @foreach($donhangs as $dh)
        @php
            $maDH    = '#DH' . str_pad($dh->id, 6, '0', STR_PAD_LEFT);
            $preview = $dh->chitiets->take(2);
            $conLai  = $dh->chitiets->count() - 2;
        @endphp
        <div class="card border shadow-sm mb-3">

            {{-- Header --}}
            <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="fw-bold" style="color:#1a5276">
                        <i class="fas fa-receipt me-1"></i>{{ $maDH }}
                    </span>
                    <span class="text-muted small">
                        <i class="fas fa-calendar-alt me-1"></i>{{ $dh->ngay_dat->format('H:i — d/m/Y') }}
                    </span>
                </div>
                @php
                    $badge = match($dh->trang_thai) {
                        \App\Models\Donhang::TRANG_THAI_MOI      => ['warning', 'text-dark', 'fa-clock',        'Chờ xác nhận'],
                        \App\Models\Donhang::TRANG_THAI_XU_LY   => ['info',    'text-dark', 'fa-sync-alt',     'Đang xử lý'],
                        \App\Models\Donhang::TRANG_THAI_HOAN_TAT => ['success', 'text-white','fa-check-circle', 'Hoàn tất'],
                        default                                   => ['danger',  'text-white','fa-times-circle', 'Đã hủy'],
                    };
                @endphp
                <span class="badge bg-{{ $badge[0] }} {{ $badge[1] }} fs-6">
                    <i class="fas {{ $badge[2] }} me-1"></i>{{ $badge[3] }}
                </span>
            </div>

            {{-- Body: preview sản phẩm --}}
            <div class="card-body py-2 px-3">
                @foreach($preview as $ct)
                <div class="d-flex gap-2 align-items-start py-2 {{ !$loop->last ? 'border-bottom border-dashed' : '' }}">
                    <img src="{{ asset($ct->hinh_anh ?? 'images/no-image.png') }}"
                         width="54" height="54"
                         class="border flex-shrink-0"
                         style="object-fit:cover">
                    <div class="flex-grow-1">
                        <div class="order-item-name">{{ $ct->ten_san_pham }}</div>
                        <div class="text-muted small mt-1">
                            @if($ct->ten_bienthe)
                                <span>{{ $ct->ten_bienthe }}</span> ·
                            @endif
                            <span>x{{ $ct->so_luong }}</span>
                            <span class="ms-2 fw-bold text-danger">{{ number_format($ct->gia) }}đ</span>
                        </div>
                    </div>
                </div>
                @endforeach
                @if($conLai > 0)
                    <div class="text-muted small fst-italic pt-2">
                        <i class="fas fa-ellipsis-h me-1"></i>và {{ $conLai }} sản phẩm khác
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="card-footer bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="small text-muted">
                    {{ $dh->chitiets->count() }} sản phẩm &nbsp;·&nbsp; Tổng:
                    <strong class="text-danger fs-6">{{ number_format($dh->tong_thanh_toan) }}đ</strong>
                </div>
                <div class="d-flex gap-2">
                    @if($dh->coTheHuy())
                    <form action="{{ url('/don-hang/' . $dh->id . '/huy') }}" method="POST"
                          onsubmit="return confirm('Xác nhận hủy đơn {{ $maDH }}?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-outline-danger fw-bold">
                            <i class="fas fa-times me-1"></i>Hủy đơn
                        </button>
                    </form>
                    @endif
                    <a href="{{ url('/don-hang/' . $dh->id) }}"
                       class="btn btn-sm btn-primary fw-bold"
                       style="background:#1a5276;border-color:#1a5276">
                        <i class="fas fa-eye me-1"></i>Xem chi tiết
                    </a>
                </div>
            </div>

        </div>
        @endforeach

        <div class="d-flex justify-content-center mt-2">
            {{ $donhangs->links() }}
        </div>

    @else
        <div class="text-center py-5 border bg-white">
            <i class="fas fa-box-open fa-3x text-secondary mb-3 d-block"></i>
            <h5 class="text-muted">
                @if($trangThai !== 'tat-ca') Không có đơn hàng nào trong mục này
                @else Bạn chưa có đơn hàng nào
                @endif
            </h5>
            <p class="text-muted small">Hãy khám phá sản phẩm và đặt hàng ngay!</p>
            <a href="{{ url('/') }}" class="btn btn-primary mt-2 fw-bold"
               style="background:#1a5276;border-color:#1a5276">
                <i class="fas fa-shopping-bag me-2"></i>Mua sắm ngay
            </a>
        </div>
    @endif
</div>

@endsection