@extends('layouts.admin')
@section('title', 'Sửa Loại Sản Phẩm')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Sửa Loại Sản Phẩm</h5>
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
                <i class="fas fa-edit me-2"></i>Chỉnh Sửa: {{ $loaiSanpham->ten_loai }}
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.loai-sanpham.update', $loaiSanpham) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Tên Loại <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="ten_loai"
                               class="form-control @error('ten_loai') is-invalid @enderror"
                               value="{{ old('ten_loai', $loaiSanpham->ten_loai) }}"
                               autofocus>
                        @error('ten_loai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Slug hiện tại</label>
                        <input type="text" class="form-control bg-light" value="{{ $loaiSanpham->slug }}" disabled>
                        <div class="form-text">Slug sẽ tự động cập nhật nếu bạn đổi tên loại.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Mô Tả</label>
                        <textarea name="mo_ta"
                                  class="form-control @error('mo_ta') is-invalid @enderror"
                                  rows="4">{{ old('mo_ta', $loaiSanpham->mo_ta) }}</textarea>
                        @error('mo_ta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 p-3 bg-light rounded small text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Loại này đang có
                        <strong>{{ $loaiSanpham->sanphams()->count() }} sản phẩm</strong>.
                        <a href="{{ route('admin.sanpham.index', ['loai_id' => $loaiSanpham->id]) }}"
                           class="text-decoration-none">Xem sản phẩm</a>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Cập Nhật
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