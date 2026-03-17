@extends('layouts.admin')
@section('title', 'Sửa Sản Phẩm')

@section('extra-css')
<style>
.anh-preview { width:80px;height:80px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6; }
.anh-wrap { position:relative;display:inline-block; }
.anh-wrap .badge-chinh { position:absolute;bottom:2px;left:2px;font-size:0.6rem; }
.bienthe-row { background:#f8f9fa;border-radius:6px;padding:12px;margin-bottom:8px;border:1px solid #e9ecef; }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Sửa Sản Phẩm</h5>
        <small class="text-muted">
            <a href="{{ route('admin.sanpham.index') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
            </a>
        </small>
    </div>
</div>

<form action="{{ route('admin.sanpham.update', $sanpham) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="row g-3">

    {{-- CỘT TRÁI --}}
    <div class="col-lg-8">

        {{-- Thông tin cơ bản --}}
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i>Thông Tin Cơ Bản</div>
            <div class="card-body p-4">

                <div class="mb-3">
                    <label class="form-label fw-bold">Tên Sản Phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="ten_san_pham"
                           class="form-control @error('ten_san_pham') is-invalid @enderror"
                           value="{{ old('ten_san_pham', $sanpham->ten_san_pham) }}" autofocus>
                    @error('ten_san_pham')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Loại Sản Phẩm <span class="text-danger">*</span></label>
                        <select name="loai_id" class="form-select @error('loai_id') is-invalid @enderror">
                            <option value="">-- Chọn loại --</option>
                            @foreach($loais as $loai)
                                <option value="{{ $loai->id }}"
                                    {{ old('loai_id', $sanpham->loai_id) == $loai->id ? 'selected' : '' }}>
                                    {{ $loai->ten_loai }}
                                </option>
                            @endforeach
                        </select>
                        @error('loai_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Slug</label>
                        <input type="text" class="form-control bg-light" value="{{ $sanpham->slug }}" disabled>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Mô Tả</label>
                    <textarea name="mo_ta" class="form-control" rows="5">{{ old('mo_ta', $sanpham->mo_ta) }}</textarea>
                </div>

            </div>
        </div>

        {{-- Giá & Tồn kho --}}
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-tag me-2"></i>Giá & Tồn Kho</div>
            <div class="card-body p-4">

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="co_bien_the"
                               id="coBienThe" value="1"
                               {{ old('co_bien_the', $sanpham->co_bien_the) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="coBienThe">
                            Sản phẩm có biến thể
                        </label>
                    </div>
                </div>

                {{-- Không có biến thể --}}
                <div id="khong-bien-the" {{ old('co_bien_the', $sanpham->co_bien_the) ? 'class=d-none' : '' }}>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá Bán <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="gia" class="form-control"
                                       value="{{ old('gia', $sanpham->gia) }}" min="0" step="1000">
                                <span class="input-group-text">đ</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá Gốc <span class="text-muted small">(gạch ngang)</span></label>
                            <div class="input-group">
                                <input type="number" name="gia_cu" class="form-control"
                                       value="{{ old('gia_cu', $sanpham->gia_cu) }}" min="0" step="1000">
                                <span class="input-group-text">đ</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Số Lượng Tồn Kho</label>
                            <input type="number" name="so_luong" class="form-control"
                                   value="{{ old('so_luong', $sanpham->so_luong) }}" min="0">
                        </div>
                    </div>
                </div>

                {{-- Có biến thể --}}
                <div id="co-bien-the" {{ !old('co_bien_the', $sanpham->co_bien_the) ? 'class=d-none' : '' }}>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá Hiển Thị <span class="text-muted small">(mặc định)</span></label>
                            <div class="input-group">
                                <input type="number" name="gia" class="form-control"
                                       value="{{ old('gia', $sanpham->gia) }}" min="0" step="1000">
                                <span class="input-group-text">đ</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold">Danh Sách Biến Thể</span>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="themBienThe()">
                            <i class="fas fa-plus me-1"></i>Thêm Biến Thể
                        </button>
                    </div>
                    <div id="danh-sach-bienthe">
                        @foreach($sanpham->bienthes as $i => $bt)
                        <div class="bienthe-row" id="bt-existing-{{ $bt->id }}">
                            <input type="hidden" name="bienthe[existing-{{ $bt->id }}][id]" value="{{ $bt->id }}">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold small">Biến thể #{{ $i+1 }}</span>
                                <button type="button" class="btn btn-sm btn-outline-danger py-0"
                                        onclick="document.getElementById('bt-existing-{{ $bt->id }}').remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <input type="text" name="bienthe[existing-{{ $bt->id }}][ten_bienthe]"
                                           class="form-control form-control-sm"
                                           value="{{ $bt->ten_bienthe }}" placeholder="Tên biến thể" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="bienthe[existing-{{ $bt->id }}][ma_sku]"
                                           class="form-control form-control-sm"
                                           value="{{ $bt->ma_sku }}" placeholder="Mã SKU" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="bienthe[existing-{{ $bt->id }}][gia]"
                                           class="form-control form-control-sm"
                                           value="{{ $bt->gia }}" placeholder="Giá (đ)" min="0" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="bienthe[existing-{{ $bt->id }}][so_luong]"
                                           class="form-control form-control-sm"
                                           value="{{ $bt->so_luong }}" placeholder="SL" min="0" required>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox"
                                           name="bienthe[existing-{{ $bt->id }}][kich_hoat]"
                                           value="1" {{ $bt->kich_hoat ? 'checked' : '' }}>
                                    <label class="form-check-label small">Kích hoạt</label>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- CỘT PHẢI --}}
    <div class="col-lg-4">

        {{-- Ảnh hiện tại --}}
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-images me-2"></i>Ảnh Sản Phẩm</div>
            <div class="card-body p-3">

                @if($sanpham->hinhanhs->count() > 0)
                <p class="small text-muted mb-2">Ảnh hiện tại — tick để xoá:</p>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @foreach($sanpham->hinhanhs as $anh)
                    <div class="anh-wrap">
                        <img src="{{ asset($anh->duong_dan_anh) }}" class="anh-preview">
                        @if($anh->la_anh_chinh)
                            <span class="badge bg-primary badge-chinh">Chính</span>
                        @endif
                        <div class="form-check" style="margin-top:2px">
                            <input class="form-check-input" type="checkbox"
                                   name="xoa_anh[]" value="{{ $anh->id }}"
                                   id="xoa-{{ $anh->id }}">
                            <label class="form-check-label text-danger small" for="xoa-{{ $anh->id }}">Xoá</label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <label class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fas fa-upload me-1"></i>Thêm ảnh mới
                    <input type="file" name="hinhanh[]" multiple accept="image/*"
                           class="d-none" onchange="previewAnhMoi(this)">
                </label>
                <div id="anh-moi-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                <div class="form-text">JPG/PNG/WebP, tối đa 2MB mỗi ảnh.</div>

            </div>
        </div>

        {{-- Thống kê --}}
        <div class="card">
            <div class="card-header"><i class="fas fa-chart-bar me-2"></i>Thống Kê</div>
            <div class="card-body p-3 small">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Lượt xem</span>
                    <strong>{{ number_format($sanpham->luot_xem) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Lượt mua</span>
                    <strong class="text-danger">{{ number_format($sanpham->luot_mua) }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Ngày tạo</span>
                    <strong>{{ $sanpham->created_at->format('d/m/Y') }}</strong>
                </div>
            </div>
        </div>

    </div>

</div>

<div class="d-flex gap-2 mt-2">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save me-1"></i>Cập Nhật
    </button>
    <a href="{{ route('admin.sanpham.index') }}" class="btn btn-outline-secondary">Huỷ</a>
</div>

</form>

@endsection

@section('extra-js')
<script>
document.getElementById('coBienThe').addEventListener('change', function() {
    document.getElementById('khong-bien-the').classList.toggle('d-none', this.checked);
    document.getElementById('co-bien-the').classList.toggle('d-none', !this.checked);
});

function previewAnhMoi(input) {
    const wrap = document.getElementById('anh-moi-preview');
    wrap.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            wrap.innerHTML += `<img src="${e.target.result}" class="anh-preview">`;
        };
        reader.readAsDataURL(file);
    });
}

let demBienThe = 1000;
function themBienThe() {
    const i = demBienThe++;
    const html = `
    <div class="bienthe-row" id="bt-${i}">
        <div class="d-flex justify-content-between mb-2">
            <span class="fw-bold small">Biến thể mới</span>
            <button type="button" class="btn btn-sm btn-outline-danger py-0"
                    onclick="document.getElementById('bt-${i}').remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="bienthe[${i}][ten_bienthe]"
                       class="form-control form-control-sm" placeholder="Tên biến thể" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="bienthe[${i}][ma_sku]"
                       class="form-control form-control-sm" placeholder="Mã SKU" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="bienthe[${i}][gia]"
                       class="form-control form-control-sm" placeholder="Giá (đ)" min="0" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="bienthe[${i}][so_luong]"
                       class="form-control form-control-sm" placeholder="SL" min="0" value="0" required>
            </div>
        </div>
        <div class="mt-2">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox"
                       name="bienthe[${i}][kich_hoat]" value="1" checked>
                <label class="form-check-label small">Kích hoạt</label>
            </div>
        </div>
    </div>`;
    document.getElementById('danh-sach-bienthe').insertAdjacentHTML('beforeend', html);
}
</script>
@endsection