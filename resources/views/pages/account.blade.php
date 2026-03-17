@extends('layouts.app')

@section('title', 'Tài Khoản Của Tôi')

@section('extra-css')
<style>
    /* Chỉ giữ lại những gì Bootstrap không có sẵn */
    .nav-account .nav-link.active {
        background: #1a5276;
        color: #fff !important;
        border-radius: 0;
    }
    .nav-account .nav-link:hover:not(.active) {
        background: #eaf4fb;
        color: #1a5276 !important;
    }
    .avatar-wrap { position: relative; display: inline-block; }
    .avatar-wrap label {
        position: absolute; bottom: 0; right: 0;
        background: #1a5276; color: #fff;
        width: 28px; height: 28px;
        border-radius: 50%; display: flex;
        align-items: center; justify-content: center;
        cursor: pointer; font-size: 0.75rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Tài khoản</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- ── SIDEBAR ── --}}
        <div class="col-lg-3">
            {{-- Thông tin tóm tắt --}}
            <div class="card mb-3 border-0 shadow-sm text-center p-3">
                <div class="avatar-wrap mx-auto mb-2">
                    <img src="{{ $user->hinh_anh ? asset('storage/'.$user->hinh_anh) : 'https://ui-avatars.com/api/?name='.urlencode($user->ho_ten).'&background=1a5276&color=fff&size=80' }}"
                         class="rounded-circle border" width="80" height="80"
                         style="object-fit:cover" alt="Avatar">
                </div>
                <div class="fw-bold">{{ $user->ho_ten }}</div>
                <div class="text-muted small">{{ $user->email }}</div>
                @if($user->isAdmin())
                    <span class="badge bg-danger mt-1">Admin</span>
                @elseif($user->isStaff())
                    <span class="badge bg-warning text-dark mt-1">Nhân viên</span>
                @else
                    <span class="badge bg-secondary mt-1">Khách hàng</span>
                @endif
            </div>

            {{-- Menu điều hướng --}}
            <div class="list-group nav-account">
                <a href="#thong-tin" data-bs-toggle="tab"
                   class="list-group-item list-group-item-action active d-flex align-items-center gap-2">
                    <i class="fas fa-user fa-fw text-muted"></i> Thông tin cá nhân
                </a>
                <a href="#mat-khau" data-bs-toggle="tab"
                   class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                    <i class="fas fa-lock fa-fw text-muted"></i> Đổi mật khẩu
                </a>
                <a href="#dia-chi" data-bs-toggle="tab"
                   class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                    <i class="fas fa-map-marker-alt fa-fw text-muted"></i> Địa chỉ giao hàng
                </a>
                <a href="#don-hang" data-bs-toggle="tab"
                   class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                    <i class="fas fa-box fa-fw text-muted"></i> Đơn hàng gần đây
                </a>
            </div>
        </div>

        {{-- ── NỘI DUNG CHÍNH ── --}}
        <div class="col-lg-9">
            <div class="tab-content">

                {{-- ═══ TAB 1: THÔNG TIN CÁ NHÂN ═══ --}}
                <div class="tab-pane fade show active" id="thong-tin">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                            <i class="fas fa-user text-primary"></i>
                            <span class="fw-bold">Thông tin cá nhân</span>
                        </div>
                        <div class="card-body p-4">

                            @if($errors->has('ho_ten') || $errors->has('email') || $errors->has('so_dien_thoai') || $errors->has('hinh_anh'))
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->only(['ho_ten','email','so_dien_thoai','hinh_anh']) as $e)
                                            <li>{{ $e }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('account.cap-nhat') }}" method="POST" enctype="multipart/form-data">
                                @csrf @method('PUT')

                                {{-- Ảnh đại diện --}}
                                <div class="mb-4 text-center">
                                    <div class="avatar-wrap mx-auto">
                                        <img id="preview-avatar"
                                             src="{{ $user->hinh_anh ? asset('storage/'.$user->hinh_anh) : 'https://ui-avatars.com/api/?name='.urlencode($user->ho_ten).'&background=1a5276&color=fff&size=100' }}"
                                             class="rounded-circle border" width="100" height="100"
                                             style="object-fit:cover" alt="Avatar">
                                        <label for="hinh_anh" title="Đổi ảnh">
                                            <i class="fas fa-camera"></i>
                                        </label>
                                    </div>
                                    <input type="file" id="hinh_anh" name="hinh_anh" accept="image/*" class="d-none">
                                    <div class="text-muted small mt-1">JPG, PNG, WEBP — tối đa 2MB</div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Họ tên <span class="text-danger">*</span></label>
                                        <input type="text" name="ho_ten" class="form-control @error('ho_ten') is-invalid @enderror"
                                               value="{{ old('ho_ten', $user->ho_ten) }}" required>
                                        @error('ho_ten')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Tên đăng nhập</label>
                                        <input type="text" class="form-control bg-light" value="{{ $user->ten_dang_nhap }}" disabled>
                                        <div class="form-text">Không thể thay đổi tên đăng nhập.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email', $user->email) }}" required>
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Số điện thoại</label>
                                        <input type="text" name="so_dien_thoai" class="form-control @error('so_dien_thoai') is-invalid @enderror"
                                               value="{{ old('so_dien_thoai', $user->so_dien_thoai) }}"
                                               placeholder="0901 234 567">
                                        @error('so_dien_thoai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ═══ TAB 2: ĐỔI MẬT KHẨU ═══ --}}
                <div class="tab-pane fade" id="mat-khau">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                            <i class="fas fa-lock text-primary"></i>
                            <span class="fw-bold">Đổi mật khẩu</span>
                        </div>
                        <div class="card-body p-4">

                            @error('mat_khau_cu')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <form action="{{ route('account.doi-mat-khau') }}" method="POST" style="max-width:480px">
                                @csrf @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                    <input type="password" name="mat_khau_cu"
                                           class="form-control @error('mat_khau_cu') is-invalid @enderror"
                                           placeholder="Nhập mật khẩu hiện tại">
                                    @error('mat_khau_cu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" name="mat_khau_moi"
                                           class="form-control @error('mat_khau_moi') is-invalid @enderror"
                                           placeholder="Ít nhất 8 ký tự">
                                    @error('mat_khau_moi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" name="mat_khau_moi_confirmation"
                                           class="form-control @error('mat_khau_moi_confirmation') is-invalid @enderror"
                                           placeholder="Nhập lại mật khẩu mới">
                                    @error('mat_khau_moi_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <button type="submit" class="btn btn-danger px-4">
                                    <i class="fas fa-key me-2"></i>Đổi mật khẩu
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ═══ TAB 3: ĐỊA CHỈ GIAO HÀNG ═══ --}}
                <div class="tab-pane fade" id="dia-chi">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                                <span class="fw-bold">Địa chỉ giao hàng</span>
                            </div>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalThemDiaChi">
                                <i class="fas fa-plus me-1"></i>Thêm địa chỉ
                            </button>
                        </div>
                        <div class="card-body p-4">

                            @if($user->diaChis->isEmpty())
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-map-marker-alt fa-3x mb-3 opacity-25"></i>
                                    <p>Bạn chưa có địa chỉ nào. Hãy thêm địa chỉ giao hàng!</p>
                                </div>
                            @else
                                <div class="row g-3">
                                    @foreach($user->diaChis as $dc)
                                    <div class="col-md-6">
                                        <div class="border rounded p-3 h-100 {{ $dc->mac_dinh ? 'border-primary' : '' }}">
                                            @if($dc->mac_dinh)
                                                <span class="badge bg-primary mb-2">
                                                    <i class="fas fa-check-circle me-1"></i>Mặc định
                                                </span>
                                            @endif
                                            <div class="fw-bold">{{ $dc->ho_ten }}</div>
                                            <div class="text-muted small">{{ $dc->so_dien_thoai }}</div>
                                            <div class="small mt-1">
                                                {{ $dc->dia_chi_chi_tiet }},
                                                {{ $dc->phuong_xa }},
                                                {{ $dc->quan_huyen }},
                                                {{ $dc->tinh_thanh }}
                                            </div>
                                            <div class="d-flex gap-2 mt-3">
                                                @if(!$dc->mac_dinh)
                                                    <form action="{{ route('account.dia-chi.mac-dinh', $dc) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <button class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-check me-1"></i>Đặt mặc định
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('account.dia-chi.xoa', $dc) }}" method="POST"
                                                          onsubmit="return confirm('Xóa địa chỉ này?')">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted small align-self-center">
                                                        <i class="fas fa-info-circle me-1"></i>Địa chỉ mặc định không thể xóa
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- ═══ TAB 4: ĐƠN HÀNG GẦN ĐÂY ═══ --}}
                <div class="tab-pane fade" id="don-hang">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-box text-primary"></i>
                                <span class="fw-bold">Đơn hàng gần đây</span>
                            </div>
                            <a href="{{ url('/don-hang') }}" class="btn btn-sm btn-outline-primary">
                                Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body p-0">

                            @if($user->donhangs->isEmpty())
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
                                    <p>Bạn chưa có đơn hàng nào.</p>
                                    <a href="{{ url('/san-pham') }}" class="btn btn-primary">
                                        <i class="fas fa-shopping-bag me-2"></i>Mua sắm ngay
                                    </a>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mã đơn</th>
                                                <th>Ngày đặt</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->donhangs as $dh)
                                            <tr>
                                                <td class="fw-bold">#{{ $dh->id }}</td>
                                                <td class="text-muted small">{{ $dh->created_at->format('d/m/Y') }}</td>
                                                <td class="text-danger fw-bold">
                                                    {{ number_format($dh->tong_tien) }}đ
                                                </td>
                                                <td>
                                                    @php
                                                        $badge = match($dh->trang_thai ?? 'cho_xac_nhan') {
                                                            'cho_xac_nhan'  => 'warning',
                                                            'dang_xu_ly'    => 'info',
                                                            'dang_giao'     => 'primary',
                                                            'da_giao'       => 'success',
                                                            'da_huy'        => 'danger',
                                                            default         => 'secondary',
                                                        };
                                                        $label = match($dh->trang_thai ?? 'cho_xac_nhan') {
                                                            'cho_xac_nhan'  => 'Chờ xác nhận',
                                                            'dang_xu_ly'    => 'Đang xử lý',
                                                            'dang_giao'     => 'Đang giao',
                                                            'da_giao'       => 'Đã giao',
                                                            'da_huy'        => 'Đã hủy',
                                                            default         => 'Không rõ',
                                                        };
                                                    @endphp
                                                    <span class="badge bg-{{ $badge }}">{{ $label }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ url('/don-hang/'.$dh->id) }}"
                                                       class="btn btn-sm btn-outline-secondary">
                                                        Chi tiết
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

            </div>{{-- end tab-content --}}
        </div>
    </div>
</div>

{{-- ═══ MODAL THÊM ĐỊA CHỈ ═══ --}}
<div class="modal fade" id="modalThemDiaChi" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('account.dia-chi.them') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Thêm địa chỉ mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Họ tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" name="ho_ten" class="form-control" placeholder="Nguyễn Văn A" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="so_dien_thoai" class="form-control" placeholder="0901 234 567" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                            <input type="text" name="dia_chi_chi_tiet" class="form-control" placeholder="Số nhà, tên đường..." required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Phường/Xã <span class="text-danger">*</span></label>
                            <input type="text" name="phuong_xa" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Quận/Huyện <span class="text-danger">*</span></label>
                            <input type="text" name="quan_huyen" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                            <input type="text" name="tinh_thanh" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu địa chỉ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script>
// Preview ảnh đại diện trước khi upload
document.getElementById('hinh_anh').addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('preview-avatar').src = e.target.result;
        reader.readAsDataURL(file);
    }
});

// Giữ đúng tab khi có lỗi validation (dựa vào tên field lỗi)
document.addEventListener('DOMContentLoaded', function () {
    const errFields = @json($errors->keys());
    const matKhauFields = ['mat_khau_cu', 'mat_khau_moi', 'mat_khau_moi_confirmation'];

    if (errFields.some(f => matKhauFields.includes(f))) {
        document.querySelector('[href="#mat-khau"]').click();
    }
});
</script>
@endsection