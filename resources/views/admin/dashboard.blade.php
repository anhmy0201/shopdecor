@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- ── STAT CARDS ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card bg-primary text-white mb-0">
            <div class="card-body d-flex justify-content-between align-items-center py-3">
                <div>
                    <div class="small opacity-75">Sản Phẩm</div>
                    <div class="fs-3 fw-bold">{{ $stats['tong_san_pham'] }}</div>
                </div>
                <i class="fas fa-box fa-2x opacity-25"></i>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-2">
                <a href="{{ route('admin.sanpham.index') }}" class="text-white small text-decoration-none">
                    Quản lý <i class="fas fa-arrow-right ms-1" style="font-size:0.7rem"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card bg-success text-white mb-0">
            <div class="card-body d-flex justify-content-between align-items-center py-3">
                <div>
                    <div class="small opacity-75">Đơn Hàng</div>
                    <div class="fs-3 fw-bold">{{ $stats['tong_don_hang'] }}</div>
                </div>
                <i class="fas fa-shopping-bag fa-2x opacity-25"></i>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-2">
                <a href="{{ route('admin.donhang.index') }}" class="text-white small text-decoration-none">
                    Quản lý <i class="fas fa-arrow-right ms-1" style="font-size:0.7rem"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card bg-info text-white mb-0">
            <div class="card-body d-flex justify-content-between align-items-center py-3">
                <div>
                    <div class="small opacity-75">Khách Hàng</div>
                    <div class="fs-3 fw-bold">{{ $stats['tong_nguoi_dung'] }}</div>
                </div>
                <i class="fas fa-users fa-2x opacity-25"></i>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-2">
                <a href="{{ route('admin.nguoidung.index') }}" class="text-white small text-decoration-none">
                    Quản lý <i class="fas fa-arrow-right ms-1" style="font-size:0.7rem"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card text-white mb-0" style="background:#8e44ad">
            <div class="card-body d-flex justify-content-between align-items-center py-3">
                <div>
                    <div class="small opacity-75">Doanh Thu Tháng</div>
                    <div class="fs-6 fw-bold">{{ number_format($stats['doanh_thu_thang']) }}đ</div>
                </div>
                <i class="fas fa-chart-line fa-2x opacity-25"></i>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-2">
                <a href="{{ route('admin.baocao.index') }}" class="text-white small text-decoration-none">
                    Xem báo cáo <i class="fas fa-arrow-right ms-1" style="font-size:0.7rem"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ── CẢNH BÁO ── --}}
@if($stats['don_cho_xac_nhan'] > 0 || $stats['san_pham_het_hang'] > 0)
<div class="row g-3 mb-4">
    @if($stats['don_cho_xac_nhan'] > 0)
    <div class="col-md-4">
        <a href="{{ route('admin.donhang.index', ['trang_thai' => 0]) }}"
           class="card border-warning text-decoration-none mb-0 h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="fas fa-clock fa-2x text-warning"></i>
                <div>
                    <div class="fw-bold text-dark">{{ $stats['don_cho_xac_nhan'] }} đơn mới chờ xử lý</div>
                    <div class="small text-muted">Nhấn để xử lý ngay</div>
                </div>
            </div>
        </a>
    </div>
    @endif

    @if($stats['san_pham_het_hang'] > 0)
    <div class="col-md-4">
        <a href="{{ route('admin.sanpham.index', ['ton_kho' => 'het_hang']) }}"
           class="card border-danger text-decoration-none mb-0 h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                <div>
                    <div class="fw-bold text-dark">{{ $stats['san_pham_het_hang'] }} sản phẩm hết hàng</div>
                    <div class="small text-muted">Cần nhập thêm hàng</div>
                </div>
            </div>
        </a>
    </div>
    @endif

    @if($stats['binh_luan_cho_duyet'] > 0)
    <div class="col-md-4">
        <a href="{{ route('admin.binhluan.index') }}"
           class="card border-info text-decoration-none mb-0 h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="fas fa-comments fa-2x text-info"></i>
                <div>
                    <div class="fw-bold text-dark">{{ $stats['binh_luan_cho_duyet'] }} bình luận mới</div>
                    <div class="small text-muted">Nhấn để xem</div>
                </div>
            </div>
        </a>
    </div>
    @endif
</div>
@endif

{{-- ── BẢNG CHÍNH ── --}}
<div class="row g-3 mb-3">

    {{-- Đơn hàng mới nhất --}}
    <div class="col-lg-7">
        <div class="card mb-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-shopping-bag me-2"></i>Đơn Hàng Mới Nhất</span>
                <a href="{{ route('admin.donhang.index') }}" class="btn btn-sm btn-light">Xem tất cả</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Mã</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donHangMoi as $dh)
                        @php
                            // trang_thai là integer: 0=mới, 1=xử lý, 2=hoàn tất, 3=hủy
                            $badge = match($dh->trang_thai) {
                                0 => 'warning',
                                1 => 'info',
                                2 => 'success',
                                3 => 'danger',
                                default => 'secondary',
                            };
                            $label = match($dh->trang_thai) {
                                0 => 'Mới',
                                1 => 'Đang xử lý',
                                2 => 'Hoàn tất',
                                3 => 'Đã hủy',
                                default => 'Không rõ',
                            };
                        @endphp
                        <tr>
                            <td class="fw-bold">#{{ $dh->id }}</td>
                            <td>{{ $dh->ten_nguoi_nhan }}</td>
                            {{-- tong_thanh_toan là tên cột đúng --}}
                            <td class="text-danger fw-bold">{{ number_format($dh->tong_thanh_toan) }}đ</td>
                            <td><span class="badge bg-{{ $badge }}">{{ $label }}</span></td>
                            <td>
                                <a href="{{ route('admin.donhang.show', $dh) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Chưa có đơn hàng nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Sản phẩm bán chạy --}}
    <div class="col-lg-5">
        <div class="card mb-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-fire me-2"></i>Bán Chạy Nhất</span>
                <a href="{{ route('admin.baocao.index') }}" class="btn btn-sm btn-light">Xem thêm</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th width="10%">#</th>
                            <th>Sản phẩm</th>
                            <th>Loại</th>
                            <th class="text-end">Lượt mua</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banChay as $i => $sp)
                        <tr>
                            <td>
                                @if($i < 3)
                                    <span class="badge bg-warning text-dark">{{ $i + 1 }}</span>
                                @else
                                    {{ $i + 1 }}
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.sanpham.edit', $sp) }}" class="text-decoration-none">
                                    {{ \Illuminate\Support\Str::limit($sp->ten_san_pham, 30) }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark" style="font-size:0.68rem">
                                    {{ $sp->loai->ten_loai ?? '—' }}
                                </span>
                            </td>
                            <td class="text-end fw-bold text-danger">
                                {{ number_format($sp->luot_mua) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Chưa có dữ liệu.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- ── BÌNH LUẬN GẦN ĐÂY ── --}}
<div class="row g-3">
    <div class="col-12">
        <div class="card mb-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-comments me-2"></i>Bình Luận Gần Đây</span>
                <a href="{{ route('admin.binhluan.index') }}" class="btn btn-sm btn-light">Xem tất cả</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Người dùng</th>
                            <th>Sản phẩm</th>
                            <th>Nội dung</th>
                            <th class="text-center">Sao</th>
                            <th class="text-center">Trạng thái</th>
                            <th>Ngày</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($binhLuanMoi as $bl)
                        <tr>
                            <td class="fw-bold">{{ $bl->user->ho_ten ?? '—' }}</td>
                            <td class="text-muted">
                                {{ \Illuminate\Support\Str::limit($bl->sanpham->ten_san_pham ?? '—', 30) }}
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($bl->noi_dung, 60) }}</td>
                            <td class="text-center">
                                @if($bl->sao_danh_gia)
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $bl->sao_danh_gia ? 'text-warning' : 'text-muted' }}"
                                           style="font-size:0.7rem"></i>
                                    @endfor
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">Đã đăng</span>
                            </td>
                            <td>{{ $bl->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.binhluan.index') }}"
                                       class="btn btn-sm btn-outline-secondary" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.binhluan.destroy', $bl) }}" method="POST"
                                          onsubmit="return confirm('Xóa bình luận này?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Chưa có bình luận nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection