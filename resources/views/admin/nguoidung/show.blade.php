@extends('layouts.admin')
@section('title', 'Chi Tiết Người Dùng')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">{{ $nguoidung->ho_ten }}</h5>
        <small class="text-muted">
            <a href="{{ route('admin.nguoidung.index') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
            </a>
        </small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.nguoidung.edit', $nguoidung) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit me-1"></i>Chỉnh Sửa
        </a>
        @if($nguoidung->id !== auth()->id())
        <form action="{{ route('admin.nguoidung.toggle', $nguoidung) }}" method="POST">
            @csrf @method('PATCH')
            <button class="btn btn-sm {{ $nguoidung->kich_hoat ? 'btn-outline-warning' : 'btn-outline-success' }}">
                <i class="fas {{ $nguoidung->kich_hoat ? 'fa-lock' : 'fa-lock-open' }} me-1"></i>
                {{ $nguoidung->kich_hoat ? 'Khoá Tài Khoản' : 'Mở Khoá' }}
            </button>
        </form>
        @endif
    </div>
</div>

<div class="row g-3">

    {{-- CỘT TRÁI --}}
    <div class="col-lg-4">

        {{-- Thông tin cá nhân --}}
        <div class="card mb-3">
            <div class="card-body p-4 text-center">
                @if($nguoidung->hinh_anh)
                    <img src="{{ asset($nguoidung->hinh_anh) }}" width="90" height="90"
                         class="rounded-circle mb-3" style="object-fit:cover;border:3px solid #dee2e6">
                @else
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold mx-auto mb-3"
                         style="width:90px;height:90px;font-size:2rem">
                        {{ strtoupper(mb_substr($nguoidung->ho_ten, 0, 1)) }}
                    </div>
                @endif
                <h6 class="fw-bold mb-1">{{ $nguoidung->ho_ten }}</h6>
                <div class="text-muted small mb-2">{{ $nguoidung->email }}</div>
                <div class="d-flex justify-content-center gap-2">
                    @if($nguoidung->isAdmin())
                        <span class="badge bg-danger">Admin</span>
                    @elseif($nguoidung->isStaff())
                        <span class="badge bg-info text-dark">Nhân viên</span>
                    @else
                        <span class="badge bg-primary">Khách hàng</span>
                    @endif
                    @if($nguoidung->kich_hoat)
                        <span class="badge bg-success">Hoạt động</span>
                    @else
                        <span class="badge bg-danger">Đã khoá</span>
                    @endif
                </div>
            </div>
            <div class="card-footer p-3">
                <table class="table table-sm table-borderless small mb-0">
                    <tr>
                        <td class="text-muted">Tên đăng nhập</td>
                        <td><code>{{ $nguoidung->ten_dang_nhap }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">SĐT</td>
                        <td>{{ $nguoidung->so_dien_thoai ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Ngày tạo</td>
                        <td>{{ $nguoidung->created_at->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Thống kê --}}
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-chart-bar me-2"></i>Thống Kê</div>
            <div class="card-body p-3">
                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="p-2 bg-light rounded">
                            <div class="fs-4 fw-bold text-primary">{{ $thongKe['tong_don'] }}</div>
                            <div class="small text-muted">Tổng đơn</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded">
                            <div class="fs-4 fw-bold text-success">{{ $thongKe['don_hoan_tat'] }}</div>
                            <div class="small text-muted">Hoàn tất</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded">
                            <div class="fs-4 fw-bold text-danger">{{ $thongKe['don_huy'] }}</div>
                            <div class="small text-muted">Đã hủy</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded">
                            <div class="fs-5 fw-bold text-danger">
                                {{ $thongKe['tong_chi'] > 0 ? number_format($thongKe['tong_chi'] / 1000) . 'K' : '0' }}
                            </div>
                            <div class="small text-muted">Tổng chi (đ)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Địa chỉ --}}
        @if($nguoidung->diaChis->count() > 0)
        <div class="card">
            <div class="card-header"><i class="fas fa-map-marker-alt me-2"></i>Địa Chỉ Giao Hàng</div>
            <div class="card-body p-3">
                @foreach($nguoidung->diaChis as $dc)
                <div class="mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold small">{{ $dc->ho_ten }}</span>
                        @if($dc->mac_dinh)
                            <span class="badge bg-primary" style="font-size:0.6rem">Mặc định</span>
                        @endif
                    </div>
                    <div class="text-muted small">{{ $dc->so_dien_thoai }}</div>
                    <div class="text-muted small">
                        {{ $dc->dia_chi_chi_tiet }}, {{ $dc->phuong_xa }},
                        {{ $dc->quan_huyen }}, {{ $dc->tinh_thanh }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- CỘT PHẢI --}}
    <div class="col-lg-8">

        {{-- Lịch sử đơn hàng --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-shopping-bag me-2"></i>Lịch Sử Đơn Hàng</span>
                <a href="{{ route('admin.donhang.index', ['q' => $nguoidung->so_dien_thoai]) }}"
                   class="btn btn-sm btn-light">Xem tất cả</a>
            </div>
            @if($nguoidung->donhangs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Mã Đơn</th>
                            <th class="text-end">Tổng Tiền</th>
                            <th class="text-center">Thanh Toán</th>
                            <th class="text-center">Trạng Thái</th>
                            <th>Ngày Đặt</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nguoidung->donhangs as $dh)
                        @php
                            $badge = match($dh->trang_thai) {
                                0=>'warning', 1=>'info', 2=>'success', 3=>'danger', default=>'secondary'
                            };
                            $label = match($dh->trang_thai) {
                                0=>'Mới', 1=>'Xử lý', 2=>'Hoàn tất', 3=>'Đã hủy', default=>'?'
                            };
                        @endphp
                        <tr>
                            <td class="fw-bold">#{{ $dh->id }}</td>
                            <td class="text-end fw-bold text-danger">
                                {{ number_format($dh->tong_thanh_toan) }}đ
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $dh->trang_thai_thanhtoan === 'da_thanh_toan' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $dh->trang_thai_thanhtoan === 'da_thanh_toan' ? 'Đã TT' : 'Chưa TT' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $badge }}">{{ $label }}</span>
                            </td>
                            <td>{{ $dh->ngay_dat->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.donhang.show', $dh) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body text-center text-muted py-5">
                <i class="fas fa-shopping-bag fa-2x mb-2 d-block"></i>
                Chưa có đơn hàng nào.
            </div>
            @endif
        </div>

    </div>
</div>

@endsection