@extends('layouts.app')

@section('title', 'Đơn Hàng Của Tôi')

@section('extra-css')
<style>
    .breadcrumb-bar {
        background: #eaf4fb;
        border-bottom: 1px solid #d0e8f5;
        padding: 8px 0;
        font-size: 0.82rem;
    }
    .breadcrumb-bar a { color: #1a5276; text-decoration: none; }
    .breadcrumb-bar a:hover { text-decoration: underline; }
    .breadcrumb-bar span { color: #888; }
    .main-content { padding: 24px 0 50px; }

    /* ===== PAGE TITLE ===== */
    .page-title {
        background: #1a5276;
        color: #fff;
        font-size: 0.95rem;
        font-weight: 700;
        padding: 10px 15px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ===== TABS ===== */
    .status-tabs {
        display: flex;
        border-bottom: 2px solid #1a5276;
        margin-bottom: 18px;
        overflow-x: auto;
        flex-wrap: nowrap;
        gap: 0;
    }
    .status-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 16px;
        font-size: 0.83rem;
        font-weight: 600;
        color: #555;
        text-decoration: none;
        border: 1px solid #ddd;
        border-bottom: none;
        background: #f8f8f8;
        white-space: nowrap;
        transition: all 0.15s;
        flex-shrink: 0;
    }
    .status-tab:hover { background: #eaf4fb; color: #1a5276; }
    .status-tab.active { background: #1a5276; color: #fff; border-color: #1a5276; }
    .tab-count {
        font-size: 0.68rem;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 10px;
        min-width: 18px;
        text-align: center;
    }
    .status-tab.active     .tab-count { background: rgba(255,255,255,0.25); color: #fff; }
    .status-tab:not(.active) .tab-count { background: #1a5276; color: #fff; }
    .status-tab.tab-cho:not(.active)   .tab-count { background: #e67e22; }
    .status-tab.tab-hoan:not(.active)  .tab-count { background: #27ae60; }
    .status-tab.tab-huy:not(.active)   .tab-count { background: #e74c3c; }

    /* ===== CARD ===== */
    .order-card {
        background: #fff;
        border: 1px solid #ddd;
        margin-bottom: 14px;
        transition: box-shadow 0.2s;
    }
    .order-card:hover { box-shadow: 0 3px 12px rgba(0,0,0,0.07); }

    .order-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        background: #f9f9f9;
        border-bottom: 1px solid #eee;
        flex-wrap: wrap;
        gap: 8px;
    }
    .order-code { font-weight: 700; color: #1a5276; font-size: 0.88rem; }
    .order-date { font-size: 0.78rem; color: #999; }

    /* ===== BADGE TRẠNG THÁI ===== */
    .badge-tt {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 2px;
    }
    .badge-moi      { background: #fdf6e3; color: #b7770d;  border: 1px solid #f0d080; }
    .badge-xu-ly    { background: #eaf4fb; color: #1a5276;  border: 1px solid #b8d9ed; }
    .badge-hoan-tat { background: #e8f8f0; color: #1e8449;  border: 1px solid #a9dfbf; }
    .badge-huy      { background: #fdf2f2; color: #c0392b;  border: 1px solid #f5b7b1; }

    /* ===== BODY ===== */
    .order-card-body { padding: 12px 15px; }
    .order-item-row {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        padding: 6px 0;
        border-bottom: 1px dashed #f0f0f0;
    }
    .order-item-row:last-of-type { border-bottom: none; }
    .order-item-thumb {
        width: 54px; height: 54px;
        object-fit: cover;
        border: 1px solid #ddd;
        flex-shrink: 0;
    }
    .order-item-name {
        font-size: 0.83rem;
        font-weight: 600;
        color: #222;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .order-item-sub { font-size: 0.75rem; color: #999; margin-top: 3px; }
    .order-more { font-size: 0.78rem; color: #888; padding-top: 6px; font-style: italic; }

    /* ===== FOOTER ===== */
    .order-card-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        border-top: 1px solid #eee;
        flex-wrap: wrap;
        gap: 8px;
        background: #fafafa;
    }
    .order-total { font-size: 0.85rem; color: #555; }
    .order-total strong { color: #e74c3c; font-size: 0.95rem; }
    .order-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

    .btn-chitiet {
        background: #1a5276;
        color: #fff;
        border: none;
        padding: 6px 16px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.15s;
        display: inline-block;
    }
    .btn-chitiet:hover { background: #154360; color: #fff; }
    .btn-huy {
        background: #fff;
        color: #e74c3c;
        border: 1px solid #e74c3c;
        padding: 5px 14px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-huy:hover { background: #e74c3c; color: #fff; }

    /* ===== EMPTY ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border: 1px solid #ddd;
    }
    .empty-state i { font-size: 3.5rem; color: #ddd; margin-bottom: 14px; }
    .empty-state h5 { color: #555; margin-bottom: 8px; }
    .empty-state p { color: #999; font-size: 0.85rem; }

    /* ===== PAGINATION ===== */
    .pagination .page-link { color: #1a5276; border-color: #ddd; font-size: 0.85rem; }
    .pagination .page-item.active .page-link { background: #1a5276; border-color: #1a5276; }
    .pagination .page-link:hover { background: #eaf4fb; }

    /* ===== ALERT ===== */
    .alert-box {
        padding: 10px 14px;
        font-size: 0.85rem;
        margin-bottom: 14px;
        border-left: 4px solid;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .alert-ok  { background: #e8f8f0; border-color: #27ae60; color: #1e8449; }
    .alert-err { background: #fdf2f2; border-color: #e74c3c; color: #c0392b; }
</style>
@endsection

@section('content')

<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Trang chủ</a>
        <span class="mx-2">›</span>
        <span>Đơn hàng của tôi</span>
    </div>
</div>

<div class="main-content">
    <div class="container">

        <div class="page-title">
            <i class="fas fa-box"></i> ĐƠN HÀNG CỦA TÔI
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="alert-box alert-ok">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-box alert-err">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Tabs --}}
        <div class="status-tabs">
            @php
                $tabs = [
                    ['key' => 'tat-ca',       'label' => 'Tất cả',        'icon' => 'fa-list',         'cls' => ''],
                    ['key' => 'cho-xac-nhan', 'label' => 'Chờ xác nhận', 'icon' => 'fa-clock',        'cls' => 'tab-cho'],
                    ['key' => 'dang-xu-ly',   'label' => 'Đang xử lý',   'icon' => 'fa-sync-alt',     'cls' => ''],
                    ['key' => 'hoan-tat',     'label' => 'Hoàn tất',     'icon' => 'fa-check-circle', 'cls' => 'tab-hoan'],
                    ['key' => 'da-huy',       'label' => 'Đã hủy',       'icon' => 'fa-times-circle', 'cls' => 'tab-huy'],
                ];
            @endphp
            @foreach($tabs as $tab)
                <a href="{{ url('/don-hang') }}?trang_thai={{ $tab['key'] }}"
                   class="status-tab {{ $tab['cls'] }} {{ $trangThai === $tab['key'] ? 'active' : '' }}">
                    <i class="fas {{ $tab['icon'] }}"></i>
                    {{ $tab['label'] }}
                    @if($dem[$tab['key']] > 0)
                        <span class="tab-count">{{ $dem[$tab['key']] }}</span>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- Danh sách --}}
        @if($donhangs->count() > 0)

            @foreach($donhangs as $dh)
            @php
                $maDH     = '#DH' . str_pad($dh->id, 6, '0', STR_PAD_LEFT);
                $preview  = $dh->chitiets->take(2);
                $conLai   = $dh->chitiets->count() - 2;
            @endphp
            <div class="order-card">

                {{-- Head --}}
                <div class="order-card-head">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <span class="order-code"><i class="fas fa-receipt me-1"></i>{{ $maDH }}</span>
                        <span class="order-date">
                            <i class="fas fa-calendar-alt me-1"></i>{{ $dh->ngay_dat->format('H:i — d/m/Y') }}
                        </span>
                    </div>
                    @if($dh->trang_thai === \App\Models\Donhang::TRANG_THAI_MOI)
                        <span class="badge-tt badge-moi"><i class="fas fa-clock"></i>Chờ xác nhận</span>
                    @elseif($dh->trang_thai === \App\Models\Donhang::TRANG_THAI_XU_LY)
                        <span class="badge-tt badge-xu-ly"><i class="fas fa-sync-alt"></i>Đang xử lý</span>
                    @elseif($dh->trang_thai === \App\Models\Donhang::TRANG_THAI_HOAN_TAT)
                        <span class="badge-tt badge-hoan-tat"><i class="fas fa-check-circle"></i>Hoàn tất</span>
                    @else
                        <span class="badge-tt badge-huy"><i class="fas fa-times-circle"></i>Đã hủy</span>
                    @endif
                </div>

                {{-- Body: preview sản phẩm --}}
                <div class="order-card-body">
                    @foreach($preview as $ct)
                    <div class="order-item-row">
                        <img src="{{ asset($ct->hinh_anh ?? 'images/no-image.png') }}"
                             class="order-item-thumb" alt="{{ $ct->ten_san_pham }}">
                        <div style="flex:1;">
                            <div class="order-item-name">{{ $ct->ten_san_pham }}</div>
                            <div class="order-item-sub">
                                @if($ct->ten_bienthe)
                                    <span>{{ $ct->ten_bienthe }}</span> ·
                                @endif
                                <span>x{{ $ct->so_luong }}</span>
                                <span class="ms-2" style="color:#e74c3c;font-weight:600;">
                                    {{ number_format($ct->gia) }}đ
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if($conLai > 0)
                        <div class="order-more">
                            <i class="fas fa-ellipsis-h me-1"></i>và {{ $conLai }} sản phẩm khác
                        </div>
                    @endif
                </div>

                {{-- Foot --}}
                <div class="order-card-foot">
                    <div class="order-total">
                        {{ $dh->chitiets->count() }} sản phẩm &nbsp;·&nbsp; Tổng:
                        <strong>{{ number_format($dh->tong_thanh_toan) }}đ</strong>
                    </div>
                    <div class="order-actions">
                        @if($dh->coTheHuy())
                            <form action="{{ url('/don-hang/' . $dh->id . '/huy') }}" method="POST"
                                  onsubmit="return confirm('Xác nhận hủy đơn hàng {{ $maDH }}?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-huy">
                                    <i class="fas fa-times me-1"></i>Hủy đơn
                                </button>
                            </form>
                        @endif
                        <a href="{{ url('/don-hang/' . $dh->id) }}" class="btn-chitiet">
                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                        </a>
                    </div>
                </div>

            </div>
            @endforeach

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $donhangs->links() }}
            </div>

        @else
            <div class="empty-state">
                <i class="fas fa-box-open d-block"></i>
                <h5>
                    @if($trangThai !== 'tat-ca') Không có đơn hàng nào trong mục này
                    @else Bạn chưa có đơn hàng nào
                    @endif
                </h5>
                <p>Hãy khám phá sản phẩm và đặt hàng ngay!</p>
                <a href="{{ url('/') }}"
                   style="background:#1a5276;color:#fff;padding:9px 24px;text-decoration:none;
                          display:inline-block;margin-top:14px;font-weight:600;font-size:0.88rem;">
                    <i class="fas fa-shopping-bag me-2"></i>Mua sắm ngay
                </a>
            </div>
        @endif

    </div>
</div>

@endsection