@extends('layouts.admin')
@section('title', 'Hồ Sơ Của Tôi')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Hồ Sơ Của Tôi</h5>
        <small class="text-muted">Cập nhật thông tin cá nhân và mật khẩu</small>
    </div>
</div>

<div class="row g-3 justify-content-center">

    {{-- CỘT TRÁI — Avatar + info --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body p-4 text-center">

                {{-- Avatar --}}
                <div class="position-relative d-inline-block mb-3">
                    @if($user->hinh_anh)
                        <img src="{{ asset($user->hinh_anh) }}" id="avatarPreview"
                             width="100" height="100"
                             class="rounded-circle"
                             style="object-fit:cover;border:3px solid #dee2e6">
                    @else
                        <div id="avatarPreview" class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold mx-auto"
                             style="width:100px;height:100px;font-size:2rem;border:3px solid #dee2e6">
                            {{ strtoupper(mb_substr($user->ho_ten, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <h6 class="fw-bold mb-1">{{ $user->ho_ten }}</h6>
                <div class="text-muted small mb-2">{{ $user->email }}</div>

                <div class="d-flex justify-content-center gap-2 mb-3">
                    @if($user->isAdmin())
                        <span class="badge bg-danger">Admin</span>
                    @elseif($user->isStaff())
                        <span class="badge bg-info text-dark">Nhân viên</span>
                    @endif
                    <span class="badge bg-success">Hoạt động</span>
                </div>

                <table class="table table-sm table-borderless small text-start mb-0">
                    <tr>
                        <td class="text-muted">Tên đăng nhập</td>
                        <td><code>{{ $user->ten_dang_nhap }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">SĐT</td>
                        <td>{{ $user->so_dien_thoai ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tham gia</td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    </tr>
                </table>

            </div>
        </div>
    </div>

    {{-- CỘT PHẢI — Form --}}
    <div class="col-lg-7">

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('POST')

            {{-- Thông tin cá nhân --}}
            <div class="card mb-3">
                <div class="card-header"><i class="fas fa-user me-2"></i>Thông Tin Cá Nhân</div>
                <div class="card-body p-4">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ảnh Đại Diện</label>
                        <div class="d-flex align-items-center gap-3">
                            <label class="btn btn-outline-secondary btn-sm mb-0">
                                <i class="fas fa-upload me-1"></i>Chọn ảnh
                                <input type="file" name="hinh_anh" accept="image/*"
                                       class="d-none" onchange="previewAvatar(this)">
                            </label>
                            <span class="text-muted small">JPG/PNG/WebP, tối đa 2MB</span>
                        </div>
                        @error('hinh_anh')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Họ Tên <span class="text-danger">*</span></label>
                        <input type="text" name="ho_ten"
                               class="form-control @error('ho_ten') is-invalid @enderror"
                               value="{{ old('ho_ten', $user->ho_ten) }}" autofocus>
                        @error('ho_ten')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Số Điện Thoại</label>
                            <input type="text" name="so_dien_thoai" class="form-control"
                                   value="{{ old('so_dien_thoai', $user->so_dien_thoai) }}"
                                   placeholder="0901234567">
                        </div>
                    </div>

                </div>
            </div>

            {{-- Đổi mật khẩu --}}
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-key me-2"></i>Đổi Mật Khẩu</div>
                <div class="card-body p-4">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật Khẩu Hiện Tại</label>
                        <input type="password" name="mat_khau_cu" class="form-control"
                               placeholder="Nhập mật khẩu hiện tại để xác nhận">
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Mật Khẩu Mới</label>
                            <input type="password" name="mat_khau_moi"
                                   class="form-control @error('mat_khau_moi') is-invalid @enderror"
                                   placeholder="Tối thiểu 6 ký tự">
                            @error('mat_khau_moi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Xác Nhận Mật Khẩu Mới</label>
                            <input type="password" name="mat_khau_moi_confirmation"
                                   class="form-control" placeholder="Nhập lại mật khẩu mới">
                        </div>
                    </div>

                    <div class="form-text mt-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Để trống nếu không muốn đổi mật khẩu.
                    </div>

                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Lưu Thay Đổi
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Huỷ</a>
            </div>

        </form>

    </div>

</div>

@endsection

@section('extra-js')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('avatarPreview');
            // Nếu đang là div thì thay bằng img
            if (preview.tagName === 'DIV') {
                const img = document.createElement('img');
                img.id = 'avatarPreview';
                img.width = 100;
                img.height = 100;
                img.className = 'rounded-circle';
                img.style = 'object-fit:cover;border:3px solid #dee2e6';
                img.src = e.target.result;
                preview.replaceWith(img);
            } else {
                preview.src = e.target.result;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection