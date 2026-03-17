@extends('layouts.admin')
@section('title', 'Người Dùng')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Người Dùng</h5>
        <small class="text-muted">Quản lý tài khoản hệ thống</small>
    </div>
</div>

{{-- TABS QUYỀN HẠN --}}
<div class="card mb-3">
    <div class="card-body py-2 px-3">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.nguoidung.index') }}"
               class="btn btn-sm {{ !request('quyen_han') ? 'btn-dark' : 'btn-outline-secondary' }}">
                Tất Cả <span class="badge bg-secondary ms-1">{{ $demQuyen['tat_ca'] }}</span>
            </a>
            <a href="{{ route('admin.nguoidung.index', ['quyen_han' => 0]) }}"
               class="btn btn-sm {{ request('quyen_han') === '0' ? 'btn-primary' : 'btn-outline-primary' }}">
                Khách Hàng <span class="badge bg-primary ms-1">{{ $demQuyen['user'] }}</span>
            </a>
            <a href="{{ route('admin.nguoidung.index', ['quyen_han' => 1]) }}"
               class="btn btn-sm {{ request('quyen_han') === '1' ? 'btn-info' : 'btn-outline-info' }}">
                Nhân Viên <span class="badge bg-info text-dark ms-1">{{ $demQuyen['staff'] }}</span>
            </a>
            <a href="{{ route('admin.nguoidung.index', ['quyen_han' => 2]) }}"
               class="btn btn-sm {{ request('quyen_han') === '2' ? 'btn-danger' : 'btn-outline-danger' }}">
                Admin <span class="badge bg-danger ms-1">{{ $demQuyen['admin'] }}</span>
            </a>
        </div>
    </div>
</div>

{{-- BỘ LỌC --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            @if(request('quyen_han') !== null && request('quyen_han') !== '')
                <input type="hidden" name="quyen_han" value="{{ request('quyen_han') }}">
            @endif
            <div class="col-md-5">
                <input type="text" name="q" class="form-control form-control-sm"
                       placeholder="Tên, email, SĐT, tên đăng nhập..."
                       value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="kich_hoat" class="form-select form-select-sm">
                    <option value="">-- Trạng thái --</option>
                    <option value="1" {{ request('kich_hoat') === '1' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="0" {{ request('kich_hoat') === '0' ? 'selected' : '' }}>Đã khoá</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary"><i class="fas fa-search me-1"></i>Lọc</button>
                <a href="{{ route('admin.nguoidung.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Xóa lọc</a>
            </div>
        </form>
    </div>
</div>

{{-- BẢNG --}}
<div class="card">
    <div class="card-header">
        <i class="fas fa-users me-2"></i>Danh Sách Người Dùng
        <span class="badge bg-light text-dark ms-2">{{ $users->total() }} người</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th width="44">Ảnh</th>
                    <th>Họ Tên</th>
                    <th>Tài Khoản</th>
                    <th class="text-center">Quyền Hạn</th>
                    <th class="text-center">Đơn Hàng</th>
                    <th class="text-end">Tổng Chi</th>
                    <th class="text-center">Trạng Thái</th>
                    <th class="text-center" width="110">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="{{ !$user->kich_hoat ? 'table-secondary' : '' }}">
                    <td>
                        @if($user->hinh_anh)
                            <img src="{{ asset($user->hinh_anh) }}" width="36" height="36"
                                 class="rounded-circle" style="object-fit:cover">
                        @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold"
                                 style="width:36px;height:36px;font-size:0.8rem">
                                {{ strtoupper(mb_substr($user->ho_ten, 0, 1)) }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold">{{ $user->ho_ten }}</div>
                        <div class="text-muted">{{ $user->so_dien_thoai ?? '—' }}</div>
                    </td>
                    <td>
                        <div>{{ $user->email }}</div>
                        <code class="text-muted" style="font-size:0.68rem">{{ $user->ten_dang_nhap }}</code>
                    </td>
                    <td class="text-center">
                        @if($user->isAdmin())
                            <span class="badge bg-danger">Admin</span>
                        @elseif($user->isStaff())
                            <span class="badge bg-info text-dark">Nhân viên</span>
                        @else
                            <span class="badge bg-primary">Khách hàng</span>
                        @endif
                    </td>
                    <td class="text-center fw-bold">{{ $user->donhangs_count }}</td>
                    <td class="text-end fw-bold text-danger">
                        {{ $user->donhangs_sum_tong_thanh_toan > 0
                            ? number_format($user->donhangs_sum_tong_thanh_toan) . 'đ'
                            : '—' }}
                    </td>
                    <td class="text-center">
                        @if($user->kich_hoat)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Đã khoá</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('admin.nguoidung.show', $user) }}"
                               class="btn btn-sm btn-outline-info" title="Xem">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.nguoidung.edit', $user) }}"
                               class="btn btn-sm btn-outline-primary" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.nguoidung.toggle', $user) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $user->kich_hoat ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                        title="{{ $user->kich_hoat ? 'Khoá' : 'Mở khoá' }}">
                                    <i class="fas {{ $user->kich_hoat ? 'fa-lock' : 'fa-lock-open' }}"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="fas fa-users fa-2x mb-2 d-block"></i>
                        Không tìm thấy người dùng nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="card-footer">{{ $users->links() }}</div>
    @endif
</div>

@endsection