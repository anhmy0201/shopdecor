@extends('layouts.admin')
@section('title', 'Thêm Loại Sản Phẩm')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Thêm Loại Sản Phẩm</h5>
        <small class="text-muted">
            <a href="{{ route('admin.loai-sanpham.index') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
            </a>
        </small>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-plus me-2"></i>Thông Tin Loại Sản Phẩm
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.loai-sanpham.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Tên Loại <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="ten_loai"
                               class="form-control @error('ten_loai') is-invalid @enderror"
                               value="{{ old('ten_loai') }}"
                               placeholder="VD: Đèn trang trí, Gối sofa..."
                               autofocus>
                        @error('ten_loai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Slug sẽ tự động tạo từ tên loại.
                            <span id="slug-preview" class="text-primary"></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Mô Tả</label>
                        <textarea name="mo_ta"
                                  class="form-control @error('mo_ta') is-invalid @enderror"
                                  rows="4"
                                  placeholder="Mô tả ngắn về loại sản phẩm này...">{{ old('mo_ta') }}</textarea>
                        @error('mo_ta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Lưu Loại Sản Phẩm
                        </button>
                        <a href="{{ route('admin.loai-sanpham.index') }}" class="btn btn-outline-secondary">
                            Huỷ
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script>
// Preview slug realtime
document.querySelector('[name=ten_loai]').addEventListener('input', function () {
    const slug = this.value
        .toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/đ/g, 'd').replace(/Đ/g, 'd')
        .replace(/[^a-z0-9\s-]/g, '')
        .trim().replace(/\s+/g, '-');
    document.getElementById('slug-preview').textContent = slug ? '→ ' + slug : '';
});
</script>
@endsection