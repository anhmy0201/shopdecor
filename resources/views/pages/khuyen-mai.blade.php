@extends('layouts.app')
@section('title', 'Khuyến Mãi & Mã Giảm Giá')

@section('extra-css')
<style>
.ma-card {
    border: 2px dashed #1a5276;
    border-radius: 6px;
    background: #fff;
    transition: box-shadow 0.2s, transform 0.2s;
    position: relative;
    overflow: hidden;
}
.ma-card:hover {
    box-shadow: 0 6px 20px rgba(26,82,118,0.15);
    transform: translateY(-2px);
}
.ma-card-left {
    background: #1a5276;
    color: #fff;
    writing-mode: vertical-rl;
    text-orientation: mixed;
    transform: rotate(180deg);
    padding: 16px 10px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    min-width: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.ma-card-left.co-dinh { background: #e74c3c; }
.ma-code-text {
    font-family: monospace;
    font-size: 1.4rem;
    font-weight: 700;
    color: #1a5276;
    letter-spacing: 3px;
    cursor: pointer;
    user-select: all;
}
.ma-code-text:hover { color: #e74c3c; }
.dot-divider {
    width: 12px; height: 12px;
    background: #f5f5f5;
    border-radius: 50%;
    border: 2px dashed #1a5276;
    position: absolute;
    right: -6px; top: 50%;
    transform: translateY(-50%);
}
.dot-divider-left {
    left: -6px; right: auto;
}
.progress-bar-custom {
    height: 6px;
    background: #e9ecef;
    border-radius: 3px;
    overflow: hidden;
}
.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #1a5276, #2980b9);
    border-radius: 3px;
    transition: width 0.3s;
}
.progress-fill.warning { background: linear-gradient(90deg, #e67e22, #e74c3c); }
.badge-phan-tram { background: #1a5276; color: #fff; }
.badge-co-dinh   { background: #e74c3c; color: #fff; }
.btn-copy {
    background: #1a5276;
    color: #fff;
    border: none;
    padding: 6px 16px;
    font-size: 0.82rem;
    font-weight: 600;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-copy:hover { background: #154360; }
.btn-copy.copied { background: #27ae60; }
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
        <span class="text-muted">Khuyến Mãi</span>
    </div>
</div>

<div class="container py-4">

    {{-- Tiêu đề --}}
    <div class="text-center mb-4">
        <h4 class="fw-bold mb-1" style="color:#1a5276">
            <i class="fas fa-tags me-2 text-danger"></i>Mã Giảm Giá Đang Có
        </h4>
        <p class="text-muted small mb-0">
            Nhấn vào mã để sao chép, sau đó nhập vào trang thanh toán để được giảm giá
        </p>
    </div>

    @if($magiamgias->count() > 0)

    <div class="row g-3">
        @foreach($magiamgias as $ma)
        @php
            $phanTram = $ma->so_luong
                ? round($ma->da_su_dung / $ma->so_luong * 100)
                : 0;
            $ganHet = $ma->so_luong && ($ma->so_luong - $ma->da_su_dung) <= 5;
        @endphp
        <div class="col-md-6 col-lg-4">
            <div class="ma-card d-flex">

                {{-- Thanh dọc bên trái --}}
                <div class="ma-card-left {{ $ma->kieu_giam === 'co_dinh' ? 'co-dinh' : '' }}">
                    {{ $ma->kieu_giam === 'phan_tram' ? 'Phần trăm' : 'Cố định' }}
                </div>

                {{-- Nội dung --}}
                <div class="p-3 flex-grow-1">

                    {{-- Giá trị giảm --}}
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge {{ $ma->kieu_giam === 'phan_tram' ? 'badge-phan-tram' : 'badge-co-dinh' }} fs-6 px-3 py-2">
                                @if($ma->kieu_giam === 'phan_tram')
                                    GIẢM {{ $ma->gia_tri }}%
                                @else
                                    GIẢM {{ number_format($ma->gia_tri) }}đ
                                @endif
                            </span>
                        </div>
                        @if($ganHet)
                            <span class="badge bg-danger" style="font-size:0.65rem">Sắp hết</span>
                        @endif
                    </div>

                    {{-- Mã code --}}
                    <div class="d-flex align-items-center gap-2 my-2 p-2 bg-light rounded border">
                        <span class="ma-code-text flex-grow-1" id="ma-{{ $ma->id }}"
                              onclick="copyMa('{{ $ma->ma_code }}', {{ $ma->id }})">
                            {{ $ma->ma_code }}
                        </span>
                        <button class="btn-copy" id="btn-{{ $ma->id }}"
                                onclick="copyMa('{{ $ma->ma_code }}', {{ $ma->id }})">
                            <i class="fas fa-copy me-1"></i>Sao chép
                        </button>
                    </div>

                    {{-- Mô tả --}}
                    @if($ma->mo_ta)
                        <div class="text-muted small mb-2">{{ $ma->mo_ta }}</div>
                    @endif

                    {{-- Điều kiện --}}
                    <div class="small mb-2">
                        @if($ma->don_hang_toi_thieu > 0)
                            <div class="text-muted">
                                <i class="fas fa-info-circle me-1 text-primary"></i>
                                Đơn tối thiểu: <strong>{{ number_format($ma->don_hang_toi_thieu) }}đ</strong>
                            </div>
                        @endif
                        @if($ma->kieu_giam === 'phan_tram' && $ma->giam_toi_da)
                            <div class="text-muted">
                                <i class="fas fa-info-circle me-1 text-primary"></i>
                                Giảm tối đa: <strong>{{ number_format($ma->giam_toi_da) }}đ</strong>
                            </div>
                        @endif
                    </div>

                    {{-- Thanh tiến độ lượt dùng --}}
                    @if($ma->so_luong)
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Đã dùng {{ $ma->da_su_dung }}/{{ $ma->so_luong }}</span>
                                <span>{{ $ma->so_luong - $ma->da_su_dung }} còn lại</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill {{ $phanTram >= 80 ? 'warning' : '' }}"
                                     style="width:{{ $phanTram }}%"></div>
                            </div>
                        </div>
                    @else
                        <div class="text-muted small mb-2">
                            <i class="fas fa-infinity me-1"></i>Không giới hạn lượt dùng
                        </div>
                    @endif

                    {{-- Thời hạn --}}
                    <div class="small">
                        @if($ma->ket_thuc)
                            @php $conLai = now()->diffInDays($ma->ket_thuc, false); @endphp
                            @if($conLai <= 3)
                                <span class="text-danger fw-bold">
                                    <i class="fas fa-clock me-1"></i>Hết hạn trong {{ $conLai }} ngày!
                                </span>
                            @else
                                <span class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>HSD: {{ $ma->ket_thuc->format('d/m/Y') }}
                                </span>
                            @endif
                        @else
                            <span class="text-muted">
                                <i class="fas fa-calendar-check me-1"></i>Không giới hạn thời gian
                            </span>
                        @endif
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Hướng dẫn dùng --}}
    <div class="card border-0 bg-light mt-4">
        <div class="card-body py-3">
            <h6 class="fw-bold mb-3"><i class="fas fa-question-circle text-primary me-2"></i>Cách sử dụng mã giảm giá</h6>
            <div class="row g-3 small text-muted">
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary rounded-circle" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;flex-shrink:0">1</span>
                        <span>Nhấn <strong>Sao chép</strong> để sao chép mã giảm giá</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary rounded-circle" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;flex-shrink:0">2</span>
                        <span>Thêm sản phẩm vào giỏ hàng và tiến hành thanh toán</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary rounded-circle" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;flex-shrink:0">3</span>
                        <span>Dán mã vào ô <strong>Mã giảm giá</strong> ở trang thanh toán và nhấn Áp dụng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="text-center py-5 text-muted">
        <i class="fas fa-tags fa-3x mb-3 d-block text-secondary"></i>
        <h5>Hiện chưa có mã giảm giá nào</h5>
        <p class="small">Hãy quay lại sau để xem các chương trình khuyến mãi mới nhất!</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-2" style="background:#1a5276;border-color:#1a5276">
            <i class="fas fa-shopping-bag me-1"></i>Mua sắm ngay
        </a>
    </div>
    @endif

</div>

@endsection

@section('extra-js')
<script>
function copyMa(ma, id) {
    navigator.clipboard.writeText(ma).then(() => {
        const btn = document.getElementById('btn-' + id);
        btn.classList.add('copied');
        btn.innerHTML = '<i class="fas fa-check me-1"></i>Đã sao chép';
        setTimeout(() => {
            btn.classList.remove('copied');
            btn.innerHTML = '<i class="fas fa-copy me-1"></i>Sao chép';
        }, 2000);
    }).catch(() => {
        // Fallback cho trình duyệt cũ
        const el = document.getElementById('ma-' + id);
        const range = document.createRange();
        range.selectNode(el);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        document.execCommand('copy');
        window.getSelection().removeAllRanges();
        const btn = document.getElementById('btn-' + id);
        btn.classList.add('copied');
        btn.innerHTML = '<i class="fas fa-check me-1"></i>Đã sao chép';
        setTimeout(() => {
            btn.classList.remove('copied');
            btn.innerHTML = '<i class="fas fa-copy me-1"></i>Sao chép';
        }, 2000);
    });
}
</script>
@endsection
