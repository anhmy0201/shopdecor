@extends('layouts.app')

@section('title', 'Chi Tiết Đơn #DH' . str_pad($donhang->id, 6, '0', STR_PAD_LEFT))

@section('extra-css')
<style>
    /* Timeline */
    .timeline { display:flex; align-items:flex-start; justify-content:space-between; position:relative; padding:10px 0 6px; }
    .timeline::before { content:''; position:absolute; top:28px; left:10%; right:10%; height:3px; background:#e0e0e0; z-index:0; }
    .timeline-step { display:flex; flex-direction:column; align-items:center; flex:1; position:relative; z-index:1; }
    .timeline-dot { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.9rem; border:3px solid #ddd; background:#fff; margin-bottom:7px; }
    .timeline-dot.done   { background:#27ae60; border-color:#27ae60; color:#fff; }
    .timeline-dot.active { background:#1a5276; border-color:#1a5276; color:#fff; }
    .timeline-dot.pending { color:#ccc; }
    .timeline-label { font-size:0.72rem; font-weight:600; color:#aaa; text-align:center; line-height:1.3; }
    .timeline-label.done   { color:#27ae60; }
    .timeline-label.active { color:#1a5276; }

    /* Star picker */
    .star-picker { display:flex; gap:3px; cursor:pointer; }
    .star-picker i { font-size:1.4rem; color:#ddd; transition:color 0.1s; }
    .star-picker i.lit { color:#f0a500; }
    .done-stars i { color:#f0a500; font-size:0.82rem; }
    .done-stars i.off { color:#ddd; }
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
        <a href="{{ url('/don-hang') }}" class="text-decoration-none" style="color:#1a5276">Đơn hàng</a>
        <span class="mx-2 text-muted">›</span>
        <span class="text-muted">#DH{{ str_pad($donhang->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>
</div>

<div class="container py-4">

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

    <div class="row g-4">

        {{-- CỘT TRÁI --}}
        <div class="col-lg-7">

            {{-- Timeline trạng thái --}}
            @if($donhang->trang_thai !== \App\Models\Donhang::TRANG_THAI_HUY)
            @php
                $tt = $donhang->trang_thai;
                $steps = [
                    ['label' => "Đặt hàng\nthành công", 'icon' => 'fa-check',          'done' => $tt >= 0, 'active' => $tt === 0],
                    ['label' => "Đã xác\nnhận",         'icon' => 'fa-clipboard-check', 'done' => $tt >= 1, 'active' => $tt === 1],
                    ['label' => "Đang\nvận chuyển",     'icon' => 'fa-shipping-fast',   'done' => $tt >= 2, 'active' => false],
                    ['label' => "Đã giao\nhàng",        'icon' => 'fa-box-open',        'done' => $tt >= 2, 'active' => $tt === 2],
                ];
            @endphp
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header" style="background:#1a5276;color:#fff;">
                    <i class="fas fa-route me-2"></i>Trạng Thái Đơn Hàng
                </div>
                <div class="card-body py-4">
                    <div class="timeline">
                        @foreach($steps as $step)
                        <div class="timeline-step">
                            <div class="timeline-dot {{ $step['active'] ? 'active' : ($step['done'] ? 'done' : 'pending') }}">
                                <i class="fas {{ $step['icon'] }}"></i>
                            </div>
                            <div class="timeline-label {{ $step['active'] ? 'active' : ($step['done'] ? 'done' : '') }}">
                                {!! nl2br(e($step['label'])) !!}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        @if($tt === \App\Models\Donhang::TRANG_THAI_MOI)
                            <span class="badge bg-warning text-dark fs-6">
                                <i class="fas fa-clock me-1"></i>Chờ xác nhận
                            </span>
                        @elseif($tt === \App\Models\Donhang::TRANG_THAI_XU_LY)
                            <span class="badge bg-info text-dark fs-6">
                                <i class="fas fa-sync-alt me-1"></i>Đang xử lý
                            </span>
                        @else
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-check-circle me-1"></i>Đã giao — Hoàn tất
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="card border-danger border-2 shadow-sm mb-3 text-center py-4">
                <div class="card-body">
                    <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center text-white mx-auto mb-3"
                         style="width:52px;height:52px;font-size:1.4rem">
                        <i class="fas fa-times"></i>
                    </div>
                    <div class="fw-bold text-danger">Đơn hàng đã bị hủy</div>
                </div>
            </div>
            @endif

            {{-- Thông tin giao hàng --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header" style="background:#1a5276;color:#fff;">
                    <i class="fas fa-map-marker-alt me-2"></i>Thông Tin Giao Hàng
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0 small" style="font-size:0.85rem">
                        <tr class="border-bottom">
                            <td class="text-muted ps-3" width="140">Mã đơn hàng</td>
                            <td class="fw-bold" style="color:#1a5276">
                                #DH{{ str_pad($donhang->id, 6, '0', STR_PAD_LEFT) }}
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted ps-3">Ngày đặt</td>
                            <td class="fw-bold">{{ $donhang->ngay_dat->format('H:i — d/m/Y') }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted ps-3">Người nhận</td>
                            <td class="fw-bold">{{ $donhang->ten_nguoi_nhan }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted ps-3">Số điện thoại</td>
                            <td class="fw-bold">{{ $donhang->so_dien_thoai }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted ps-3">Địa chỉ</td>
                            <td class="fw-bold">
                                {{ $donhang->dia_chi_chi_tiet }}, {{ $donhang->phuong_xa }},
                                {{ $donhang->quan_huyen }}, {{ $donhang->tinh_thanh }}
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted ps-3">Thanh toán</td>
                            <td>
                                @if($donhang->phuong_thuc_thanhtoan === 'cod')
                                    <span class="badge bg-success">COD</span>
                                @else
                                    <span class="badge bg-primary">Chuyển khoản</span>
                                @endif
                            </td>
                        </tr>
                        <tr class="{{ $donhang->ghi_chu_khach ? 'border-bottom' : '' }}">
                            <td class="text-muted ps-3">Trạng thái TT</td>
                            <td>
                                @if($donhang->trang_thai_thanhtoan === 'da_thanh_toan')
                                    <span class="badge bg-success">Đã thanh toán</span>
                                @else
                                    <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                                @endif
                            </td>
                        </tr>
                        @if($donhang->ghi_chu_khach)
                        <tr>
                            <td class="text-muted ps-3">Ghi chú</td>
                            <td class="fw-bold">{{ $donhang->ghi_chu_khach }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Thông tin CK --}}
            @if($donhang->phuong_thuc_thanhtoan === 'chuyen_khoan'
                && $donhang->trang_thai_thanhtoan !== 'da_thanh_toan')
            <div class="card border-primary shadow-sm mb-3">
                <div class="card-header" style="background:#1a5276;color:#fff;">
                    <i class="fas fa-university me-2"></i>Thông Tin Chuyển Khoản
                </div>
                <div class="card-body small">
                    <p class="text-muted mb-3">
                        Vui lòng chuyển khoản trong vòng <strong class="text-danger">24 giờ</strong>.
                    </p>
                    <div class="bg-light p-3 rounded border">
                        <div class="fw-bold text-primary mb-2"><i class="fas fa-piggy-bank me-1"></i>Thông tin tài khoản</div>
                        <div class="row g-1">
                            <div class="col-5 text-muted">Ngân hàng</div>
                            <div class="col-7 fw-bold">Vietcombank</div>
                            <div class="col-5 text-muted">Số tài khoản</div>
                            <div class="col-7 fw-bold">1234567890</div>
                            <div class="col-5 text-muted">Chủ tài khoản</div>
                            <div class="col-7 fw-bold">NGUYEN VAN A</div>
                            <div class="col-5 text-muted">Nội dung CK</div>
                            <div class="col-7 fw-bold text-primary">
                                DH{{ str_pad($donhang->id, 6, '0', STR_PAD_LEFT) }} {{ $donhang->so_dien_thoai }}
                            </div>
                            <div class="col-5 text-muted">Số tiền</div>
                            <div class="col-7 fw-bold text-danger">{{ number_format($donhang->tong_thanh_toan) }}đ</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Nút --}}
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ url('/don-hang') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Danh sách đơn
                </a>
                <a href="{{ url('/') }}" class="btn btn-primary" style="background:#1a5276;border-color:#1a5276">
                    <i class="fas fa-shopping-bag me-1"></i>Tiếp tục mua sắm
                </a>
                @if($donhang->coTheHuy())
                <form action="{{ url('/don-hang/' . $donhang->id . '/huy') }}" method="POST"
                      onsubmit="return confirm('Xác nhận hủy đơn hàng này?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-times me-1"></i>Hủy đơn hàng
                    </button>
                </form>
                @endif
            </div>

        </div>

        {{-- CỘT PHẢI --}}
        <div class="col-lg-5">

            {{-- Bảng sản phẩm --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header" style="background:#1a5276;color:#fff;">
                    <i class="fas fa-list-ul me-2"></i>Sản Phẩm Đã Đặt
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">SL</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donhang->chitiets as $ct)
                            <tr>
                                <td>
                                    <div class="d-flex gap-2 align-items-start">
                                        <img src="{{ asset($ct->hinh_anh ?? 'images/no-image.png') }}"
                                             width="48" height="48"
                                             class="rounded border flex-shrink-0"
                                             style="object-fit:cover">
                                        <div>
                                            <div class="fw-bold" style="line-height:1.3">{{ $ct->ten_san_pham }}</div>
                                            @if($ct->ten_bienthe)
                                                <div class="text-muted small">{{ $ct->ten_bienthe }}</div>
                                            @endif
                                            <div class="text-muted small">{{ number_format($ct->gia) }}đ/cái</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-bold">{{ $ct->so_luong }}</td>
                                <td class="text-end fw-bold text-danger">
                                    {{ number_format($ct->so_luong * $ct->gia) }}đ
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-body pt-2 pb-3">
                    <div class="d-flex justify-content-between small py-1 border-bottom">
                        <span class="text-muted">Tạm tính</span>
                        <span>{{ number_format($donhang->tong_tien_hang) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between small py-1 border-bottom">
                        <span class="text-muted">Phí vận chuyển</span>
                        <span class="text-success">Miễn phí</span>
                    </div>
                    @if($donhang->so_tien_giam > 0)
                    <div class="d-flex justify-content-between small py-1 border-bottom text-success">
                        <span>Giảm giá @if($donhang->magiamgia)({{ $donhang->magiamgia->ma_code }})@endif</span>
                        <span>-{{ number_format($donhang->so_tien_giam) }}đ</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between fw-bold text-danger pt-2" style="font-size:1rem">
                        <span>Tổng thanh toán</span>
                        <span>{{ number_format($donhang->tong_thanh_toan) }}đ</span>
                    </div>
                </div>
            </div>

            {{-- Đánh giá --}}
            @if($donhang->trang_thai === \App\Models\Donhang::TRANG_THAI_HOAN_TAT)

                {{-- Chưa đánh giá --}}
                @if($sanphamChuaDanhGia->count() > 0)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header" style="background:#f0a500;color:#fff;">
                        <i class="fas fa-star me-2"></i>Đánh Giá Sản Phẩm
                        <span class="badge bg-white text-warning ms-1">{{ $sanphamChuaDanhGia->count() }}</span>
                    </div>
                    <div class="card-body">
                        @foreach($sanphamChuaDanhGia as $idx => $ct)
                        <div class="border rounded p-3 mb-3 bg-light {{ $loop->last ? 'mb-0' : '' }}">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <img src="{{ asset($ct->hinh_anh ?? 'images/no-image.png') }}"
                                     width="44" height="44"
                                     class="rounded border flex-shrink-0"
                                     style="object-fit:cover">
                                <div>
                                    <div class="fw-bold small">{{ $ct->ten_san_pham }}</div>
                                    @if($ct->ten_bienthe)
                                        <div class="text-muted" style="font-size:0.75rem">{{ $ct->ten_bienthe }}</div>
                                    @endif
                                </div>
                            </div>

                            <form action="{{ url('/don-hang/' . $donhang->id . '/danh-gia') }}" method="POST">
                                @csrf
                                <input type="hidden" name="sanpham_id" value="{{ $ct->sanpham_id }}">

                                <div class="small text-muted mb-1 fw-bold">Chất lượng sản phẩm:</div>
                                <div class="star-picker mb-2" id="stars-{{ $idx }}">
                                    @for($s = 1; $s <= 5; $s++)
                                        <i class="fas fa-star lit"
                                           data-val="{{ $s }}"
                                           onmouseover="hoverSao({{ $idx }}, {{ $s }})"
                                           onmouseleave="resetSao({{ $idx }})"
                                           onclick="chonSao({{ $idx }}, {{ $s }})"></i>
                                    @endfor
                                </div>
                                <input type="hidden" name="sao_danh_gia" id="sao-{{ $idx }}" value="5">

                                <textarea name="noi_dung" class="form-control form-control-sm mb-2"
                                          rows="3"
                                          placeholder="Sản phẩm có đúng mô tả không? Chất lượng thế nào?..."
                                          required></textarea>

                                <button type="submit" class="btn btn-sm btn-warning fw-bold">
                                    <i class="fas fa-paper-plane me-1"></i>Gửi đánh giá
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Đã đánh giá --}}
                @if($sanphamDaDanhGia->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-check-circle me-2"></i>Đánh Giá Của Bạn
                    </div>
                    <div class="card-body">
                        @foreach($sanphamDaDanhGia as $ct)
                        @php
                            $bl = \App\Models\Binhluan::where('user_id', Auth::id())
                                    ->where('sanpham_id', $ct->sanpham_id)->first();
                        @endphp
                        @if($bl)
                        <div class="border rounded p-3 bg-light mb-2 {{ $loop->last ? 'mb-0' : '' }}">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <img src="{{ asset($ct->hinh_anh ?? 'images/no-image.png') }}"
                                     width="38" height="38"
                                     class="rounded border flex-shrink-0"
                                     style="object-fit:cover">
                                <div>
                                    <div class="fw-bold small">{{ $ct->ten_san_pham }}</div>
                                    <div class="done-stars">
                                        @for($s = 1; $s <= 5; $s++)
                                            <i class="fas fa-star {{ $s <= $bl->sao_danh_gia ? '' : 'off' }}"></i>
                                        @endfor
                                        <small class="ms-1 text-muted">
                                            {{ \Carbon\Carbon::parse($bl->ngay_dang)->format('d/m/Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="small text-muted fst-italic ps-1">"{{ $bl->noi_dung }}"</div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif

            @endif

        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script>
const saoDaChon = {};

function chonSao(idx, val) {
    saoDaChon[idx] = val;
    document.getElementById('sao-' + idx).value = val;
    capNhatSao(idx, val);
}
function hoverSao(idx, val) { capNhatSao(idx, val); }
function resetSao(idx) { capNhatSao(idx, saoDaChon[idx] ?? 5); }
function capNhatSao(idx, val) {
    document.querySelectorAll('#stars-' + idx + ' i').forEach((el, i) => {
        el.classList.toggle('lit', i < val);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.star-picker').forEach(function (picker) {
        const idx = picker.id.replace('stars-', '');
        saoDaChon[idx] = 5;
    });
});
</script>
@endsection