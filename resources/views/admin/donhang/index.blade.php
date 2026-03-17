@extends('layouts.admin')
@section('title', 'Đơn Hàng')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Đơn Hàng</h5>
        <small class="text-muted">Quản lý tất cả đơn hàng</small>
    </div>
</div>

{{-- TABS TRẠNG THÁI --}}
<div class="card mb-3">
    <div class="card-body py-2 px-3">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.donhang.index') }}"
               class="btn btn-sm {{ !request('trang_thai') && !request()->except(['page']) ? 'btn-dark' : 'btn-outline-secondary' }}">
                Tất Cả <span class="badge bg-secondary ms-1">{{ $demTrangThai['tat_ca'] }}</span>
            </a>
            <a href="{{ route('admin.donhang.index', ['trang_thai' => 0]) }}"
               class="btn btn-sm {{ request('trang_thai') === '0' ? 'btn-warning' : 'btn-outline-warning' }}">
                Mới <span class="badge bg-warning text-dark ms-1">{{ $demTrangThai['moi'] }}</span>
            </a>
            <a href="{{ route('admin.donhang.index', ['trang_thai' => 1]) }}"
               class="btn btn-sm {{ request('trang_thai') === '1' ? 'btn-info' : 'btn-outline-info' }}">
                Đang Xử Lý <span class="badge bg-info text-dark ms-1">{{ $demTrangThai['xu_ly'] }}</span>
            </a>
            <a href="{{ route('admin.donhang.index', ['trang_thai' => 2]) }}"
               class="btn btn-sm {{ request('trang_thai') === '2' ? 'btn-success' : 'btn-outline-success' }}">
                Hoàn Tất <span class="badge bg-success ms-1">{{ $demTrangThai['hoan_tat'] }}</span>
            </a>
            <a href="{{ route('admin.donhang.index', ['trang_thai' => 3]) }}"
               class="btn btn-sm {{ request('trang_thai') === '3' ? 'btn-danger' : 'btn-outline-danger' }}">
                Đã Hủy <span class="badge bg-danger ms-1">{{ $demTrangThai['huy'] }}</span>
            </a>
        </div>
    </div>
</div>

{{-- BỘ LỌC --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            @if(request('trang_thai') !== null)
                <input type="hidden" name="trang_thai" value="{{ request('trang_thai') }}">
            @endif
            <div class="col-md-3">
                <input type="text" name="q" class="form-control form-control-sm"
                       placeholder="Mã đơn, tên, SĐT..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="trang_thai_thanhtoan" class="form-select form-select-sm">
                    <option value="">-- Thanh toán --</option>
                    <option value="chua_thanh_toan" {{ request('trang_thai_thanhtoan') === 'chua_thanh_toan' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="da_thanh_toan"   {{ request('trang_thai_thanhtoan') === 'da_thanh_toan'   ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="hoan_tien"       {{ request('trang_thai_thanhtoan') === 'hoan_tien'       ? 'selected' : '' }}>Hoàn tiền</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="trang_thai_van_chuyen" class="form-select form-select-sm">
                    <option value="">-- Vận chuyển --</option>
                    <option value="cho_lay_hang"      {{ request('trang_thai_van_chuyen') === 'cho_lay_hang'      ? 'selected' : '' }}>Chờ lấy hàng</option>
                    <option value="dang_van_chuyen"   {{ request('trang_thai_van_chuyen') === 'dang_van_chuyen'   ? 'selected' : '' }}>Đang vận chuyển</option>
                    <option value="da_giao"           {{ request('trang_thai_van_chuyen') === 'da_giao'           ? 'selected' : '' }}>Đã giao</option>
                    <option value="that_bai"          {{ request('trang_thai_van_chuyen') === 'that_bai'          ? 'selected' : '' }}>Thất bại</option>
                    <option value="hoan_hang"         {{ request('trang_thai_van_chuyen') === 'hoan_hang'         ? 'selected' : '' }}>Hoàn hàng</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary"><i class="fas fa-search me-1"></i>Lọc</button>
                <a href="{{ route('admin.donhang.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Xóa lọc</a>
            </div>
        </form>
    </div>
</div>

{{-- BẢNG --}}
<div class="card">
    <div class="card-header">
        <i class="fas fa-shopping-bag me-2"></i>Danh Sách Đơn Hàng
        <span class="badge bg-light text-dark ms-2">{{ $donhangs->total() }} đơn</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th width="70">Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Địa Chỉ</th>
                    <th class="text-end">Tổng Tiền</th>
                    <th class="text-center">Thanh Toán</th>
                    <th class="text-center">Vận Chuyển</th>
                    <th class="text-center">Trạng Thái</th>
                    <th>Ngày Đặt</th>
                    <th class="text-center" width="80">Chi Tiết</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donhangs as $dh)
                @php
                    $badgeDon = match($dh->trang_thai) {
                        0 => 'warning',
                        1 => 'info',
                        2 => 'success',
                        3 => 'danger',
                        default => 'secondary',
                    };
                    $labelDon = match($dh->trang_thai) {
                        0 => 'Mới',
                        1 => 'Xử lý',
                        2 => 'Hoàn tất',
                        3 => 'Đã hủy',
                        default => '?',
                    };
                    $badgeTT = match($dh->trang_thai_thanhtoan) {
                        'da_thanh_toan' => 'success',
                        'hoan_tien'     => 'warning',
                        default         => 'secondary',
                    };
                    $labelTT = match($dh->trang_thai_thanhtoan) {
                        'da_thanh_toan' => 'Đã TT',
                        'hoan_tien'     => 'Hoàn tiền',
                        default         => 'Chưa TT',
                    };
                    $badgeVC = match($dh->trang_thai_van_chuyen) {
                        'dang_van_chuyen' => 'info',
                        'da_giao'         => 'success',
                        'that_bai'        => 'danger',
                        'hoan_hang'       => 'warning',
                        default           => 'secondary',
                    };
                    $labelVC = match($dh->trang_thai_van_chuyen) {
                        'cho_lay_hang'    => 'Chờ lấy',
                        'dang_van_chuyen' => 'Đang giao',
                        'da_giao'         => 'Đã giao',
                        'that_bai'        => 'Thất bại',
                        'hoan_hang'       => 'Hoàn hàng',
                        default           => '?',
                    };
                @endphp
                <tr>
                    <td class="fw-bold">#{{ $dh->id }}</td>
                    <td>
                        <div class="fw-bold">{{ $dh->ten_nguoi_nhan }}</div>
                        <div class="text-muted">{{ $dh->so_dien_thoai }}</div>
                        @if($dh->user)
                            <div class="text-muted" style="font-size:0.68rem">
                                <i class="fas fa-user me-1"></i>{{ $dh->user->ho_ten }}
                            </div>
                        @endif
                    </td>
                    <td class="text-muted" style="max-width:160px">
                        {{ \Illuminate\Support\Str::limit($dh->dia_chi_chi_tiet . ', ' . $dh->phuong_xa . ', ' . $dh->quan_huyen . ', ' . $dh->tinh_thanh, 60) }}
                    </td>
                    <td class="text-end fw-bold text-danger">
                        {{ number_format($dh->tong_thanh_toan) }}đ
                        @if($dh->so_tien_giam > 0)
                            <div class="text-success" style="font-size:0.72rem">
                                -{{ number_format($dh->so_tien_giam) }}đ
                            </div>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $badgeTT }}">{{ $labelTT }}</span>
                        <div class="text-muted mt-1" style="font-size:0.68rem">
                            {{ strtoupper($dh->phuong_thuc_thanhtoan) }}
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $badgeVC }}">{{ $labelVC }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $badgeDon }}
                            {{ in_array($dh->trang_thai, [0,1]) ? 'text-dark' : '' }}">
                            {{ $labelDon }}
                        </span>
                    </td>
                    <td class="text-muted">{{ $dh->ngay_dat->format('d/m/Y H:i') }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.donhang.show', $dh) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-5">
                        <i class="fas fa-shopping-bag fa-2x mb-2 d-block"></i>
                        Chưa có đơn hàng nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($donhangs->hasPages())
    <div class="card-footer">{{ $donhangs->links() }}</div>
    @endif
</div>

@endsection