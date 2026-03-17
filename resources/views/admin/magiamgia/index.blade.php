@extends('layouts.admin')
@section('title', 'Mã Giảm Giá')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Mã Giảm Giá</h5>
        <small class="text-muted">Quản lý coupon & khuyến mãi</small>
    </div>
    <a href="{{ route('admin.magiamgia.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i>Tạo Mã Mới
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-tag me-2"></i>Danh Sách Mã Giảm Giá
        <span class="badge bg-light text-dark ms-2">{{ $magiamgias->total() }} mã</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>Mã Code</th>
                    <th>Kiểu Giảm</th>
                    <th class="text-end">Giá Trị</th>
                    <th class="text-end">Đơn Tối Thiểu</th>
                    <th class="text-center">Đã Dùng / Tổng</th>
                    <th class="text-center">Thời Gian</th>
                    <th class="text-center">Trạng Thái</th>
                    <th class="text-center" width="120">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($magiamgias as $ma)
                @php
                    $conHan = true;
                    if ($ma->ket_thuc && $ma->ket_thuc->isPast()) $conHan = false;
                    if ($ma->bat_dau && $ma->bat_dau->isFuture()) $conHan = false;
                    $hetLuot = $ma->so_luong && $ma->da_su_dung >= $ma->so_luong;
                @endphp
                <tr class="{{ !$ma->kich_hoat || !$conHan || $hetLuot ? 'table-secondary' : '' }}">
                    <td>
                        <code class="fw-bold fs-6">{{ $ma->ma_code }}</code>
                        @if($ma->mo_ta)
                            <div class="text-muted" style="font-size:0.72rem">
                                {{ \Illuminate\Support\Str::limit($ma->mo_ta, 40) }}
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($ma->kieu_giam === 'phan_tram')
                            <span class="badge bg-info text-dark">% Phần trăm</span>
                        @else
                            <span class="badge bg-warning text-dark">đ Cố định</span>
                        @endif
                    </td>
                    <td class="text-end fw-bold text-danger">
                        @if($ma->kieu_giam === 'phan_tram')
                            {{ $ma->gia_tri }}%
                            @if($ma->giam_toi_da)
                                <div class="text-muted fw-normal" style="font-size:0.72rem">
                                    Tối đa {{ number_format($ma->giam_toi_da) }}đ
                                </div>
                            @endif
                        @else
                            {{ number_format($ma->gia_tri) }}đ
                        @endif
                    </td>
                    <td class="text-end">
                        {{ $ma->don_hang_toi_thieu > 0 ? number_format($ma->don_hang_toi_thieu) . 'đ' : '—' }}
                    </td>
                    <td class="text-center">
                        <span class="{{ $hetLuot ? 'text-danger fw-bold' : '' }}">
                            {{ $ma->da_su_dung }}
                        </span>
                        /
                        <span>{{ $ma->so_luong ?? '∞' }}</span>
                        @if($hetLuot)
                            <div class="text-danger" style="font-size:0.68rem">Hết lượt</div>
                        @endif
                    </td>
                    <td class="text-center" style="font-size:0.72rem">
                        @if($ma->bat_dau || $ma->ket_thuc)
                            @if($ma->bat_dau)
                                <div>Từ: {{ $ma->bat_dau->format('d/m/Y') }}</div>
                            @endif
                            @if($ma->ket_thuc)
                                <div class="{{ $ma->ket_thuc->isPast() ? 'text-danger' : '' }}">
                                    Đến: {{ $ma->ket_thuc->format('d/m/Y') }}
                                </div>
                            @endif
                        @else
                            <span class="text-muted">Không giới hạn</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if(!$conHan)
                            <span class="badge bg-secondary">Hết hạn</span>
                        @elseif($hetLuot)
                            <span class="badge bg-secondary">Hết lượt</span>
                        @elseif($ma->kich_hoat)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Vô hiệu</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            {{-- Bật/tắt nhanh --}}
                            <form action="{{ route('admin.magiamgia.toggle', $ma) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $ma->kich_hoat ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                        title="{{ $ma->kich_hoat ? 'Vô hiệu hóa' : 'Kích hoạt' }}">
                                    <i class="fas {{ $ma->kich_hoat ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                            </form>
                            <a href="{{ route('admin.magiamgia.edit', $ma) }}"
                               class="btn btn-sm btn-outline-primary" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.magiamgia.destroy', $ma) }}"
                                  method="POST"
                                  onsubmit="return confirm('Xóa mã \'{{ $ma->ma_code }}\'?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Xóa"
                                        {{ $ma->donhangs_count > 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="fas fa-tag fa-2x mb-2 d-block"></i>
                        Chưa có mã giảm giá nào.
                        <a href="{{ route('admin.magiamgia.create') }}">Tạo ngay</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($magiamgias->hasPages())
        <div class="card-footer">{{ $magiamgias->links() }}</div>
    @endif
</div>

@endsection