@extends('layouts.admin')
@section('title', 'Thêm Sản Phẩm')

@section('extra-css')
<style>
.anh-preview { width:80px;height:80px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6; }
.anh-wrap { position:relative;display:inline-block; }
.anh-wrap .btn-xoa { position:absolute;top:-6px;right:-6px;width:20px;height:20px;padding:0;font-size:10px;border-radius:50%; }
.bienthe-row { background:#f8f9fa;border-radius:6px;padding:12px;margin-bottom:8px;border:1px solid #e9ecef; }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Thêm Sản Phẩm</h5>
        <small class="text-muted">
            <a href="{{ route('admin.sanpham.index') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
            </a>
        </small>
    </div>
</div>

<form action="{{ route('admin.sanpham.store') }}" method="POST" enctype="multipart/form-data">
@csrf

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
                           value="{{ old('ten_san_pham') }}" autofocus>
                    @error('ten_san_pham')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Loại Sản Phẩm <span class="text-danger">*</span></label>
                        <select name="loai_id" class="form-select @error('loai_id') is-invalid @enderror">
                            <option value="">-- Chọn loại --</option>
                            @foreach($loais as $loai)
                                <option value="{{ $loai->id }}" {{ old('loai_id') == $loai->id ? 'selected' : '' }}>
                                    {{ $loai->ten_loai }}
                                </option>
                            @endforeach
                        </select>
                        @error('loai_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Mô Tả</label>
                    <textarea name="mo_ta" class="form-control" rows="5"
                              placeholder="Mô tả chi tiết sản phẩm...">{{ old('mo_ta') }}</textarea>
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
                               id="coBienThe" value="1" {{ old('co_bien_the') ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="coBienThe">
                            Sản phẩm có biến thể (màu sắc, kích thước...)
                        </label>
                    </div>
                </div>

                {{-- Không có biến thể --}}
                <div id="khong-bien-the">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá Bán <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="gia"
                                       class="form-control @error('gia') is-invalid @enderror"
                                       value="{{ old('gia') }}" min="0" step="1000">
                                <span class="input-group-text">đ</span>
                            </div>
                            @error('gia')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá Gốc <span class="text-muted small">(gạch ngang)</span></label>
                            <div class="input-group">
                                <input type="number" name="gia_cu" class="form-control"
                                       value="{{ old('gia_cu') }}" min="0" step="1000">
                                <span class="input-group-text">đ</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Số Lượng Tồn Kho</label>
                            <input type="number" name="so_luong" class="form-control"
                                   value="{{ old('so_luong', 0) }}" min="0">
                        </div>
                    </div>
                </div>

                {{-- Có biến thể --}}
                <div id="co-bien-the" class="d-none">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá Hiển Thị <span class="text-muted small">(mặc định)</span></label>
                            <div class="input-group">
                                <input type="number" name="gia" class="form-control"
                                       value="{{ old('gia', 0) }}" min="0" step="1000">
                                <span class="input-group-text">đ</span>
                            </div>
                            <div class="form-text">Giá này hiển thị khi chưa chọn biến thể.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold">Danh Sách Biến Thể</span>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="themBienThe()">
                            <i class="fas fa-plus me-1"></i>Thêm Biến Thể
                        </button>
                    </div>
                    <div id="danh-sach-bienthe"></div>
                </div>

            </div>
        </div>

    </div>

    {{-- CỘT PHẢI --}}
    <div class="col-lg-4">

        {{-- Ảnh sản phẩm --}}
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-images me-2"></i>Ảnh Sản Phẩm</div>
            <div class="card-body p-3">
                <div id="anh-preview" class="d-flex flex-wrap gap-2 mb-2"></div>
                <div id="anh-inputs"></div>
                <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="chonAnh()">
                    <i class="fas fa-upload me-1"></i>Chọn ảnh (có thể chọn nhiều lần)
                </button>
                <input type="file" id="inputAnhAn" multiple accept="image/*" class="d-none" onchange="themAnh(this)">
                <div class="form-text mt-1">Ảnh đầu tiên sẽ là ảnh chính. JPG/PNG/WebP, tối đa 2MB. Nhấn nút nhiều lần để thêm nhiều ảnh.</div>
                @error('hinhanh.*')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>

    </div>

</div>

<div class="d-flex gap-2 mt-2">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save me-1"></i>Lưu Sản Phẩm
    </button>
    <a href="{{ route('admin.sanpham.index') }}" class="btn btn-outline-secondary">Huỷ</a>
</div>

</form>

@endsection

@section('extra-js')
<script>
// Toggle biến thể
document.getElementById('coBienThe').addEventListener('change', function() {
    document.getElementById('khong-bien-the').classList.toggle('d-none', this.checked);
    document.getElementById('co-bien-the').classList.toggle('d-none', !this.checked);
});

// Trigger on load nếu old value
if (document.getElementById('coBienThe').checked) {
    document.getElementById('khong-bien-the').classList.add('d-none');
    document.getElementById('co-bien-the').classList.remove('d-none');
}

// Upload ảnh nhiều đợt dùng DataTransfer
let danhSachFile = [];
let demAnh = 0;

function chonAnh() {
    document.getElementById('inputAnhAn').click();
}

function themAnh(input) {
    Array.from(input.files).forEach(file => {
        const id = demAnh++;
        danhSachFile.push({ id, file });

        // Tạo hidden input riêng cho mỗi file
        const container = document.getElementById('anh-inputs');
        const dt = new DataTransfer();
        dt.items.add(file);
        const inp = document.createElement('input');
        inp.type = 'file';
        inp.name = 'hinhanh[]';
        inp.className = 'd-none';
        inp.id = 'file-' + id;
        inp.files = dt.files;
        container.appendChild(inp);

        // Preview
        const reader = new FileReader();
        reader.onload = e => {
            const wrap = document.getElementById('anh-preview');
            const isFirst = danhSachFile.length === 1 && id === danhSachFile[0].id;
            wrap.insertAdjacentHTML('beforeend', `
                <div class="anh-wrap" id="wrap-${id}">
                    <img src="${e.target.result}" class="anh-preview">
                    ${isFirst ? '<span class="badge bg-primary" style="font-size:0.6rem;position:absolute;bottom:2px;left:2px">Chính</span>' : ''}
                    <button type="button" class="btn btn-danger btn-xoa" onclick="xoaAnh(${id})" title="Xoá">✕</button>
                </div>`);
        };
        reader.readAsDataURL(file);
    });
    // Reset input ẩn để có thể chọn lại cùng file
    input.value = '';
}

function xoaAnh(id) {
    danhSachFile = danhSachFile.filter(f => f.id !== id);
    document.getElementById('wrap-' + id)?.remove();
    document.getElementById('file-' + id)?.remove();

    // Cập nhật lại badge Chính cho ảnh đầu tiên còn lại
    document.querySelectorAll('#anh-preview .badge').forEach(b => b.remove());
    const first = document.querySelector('#anh-preview .anh-wrap img');
    if (first) {
        first.insertAdjacentHTML('afterend', '<span class="badge bg-primary" style="font-size:0.6rem;position:absolute;bottom:2px;left:2px">Chính</span>');
    }
}

// Thêm biến thể
let demBienThe = 0;
function themBienThe() {
    const i = demBienThe++;
    const html = `
    <div class="bienthe-row" id="bt-${i}">
        <div class="d-flex justify-content-between mb-2">
            <span class="fw-bold small">Biến thể #${i+1}</span>
            <button type="button" class="btn btn-sm btn-outline-danger py-0" onclick="xoaBienThe(${i})">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="bienthe[${i}][ten_bienthe]" class="form-control form-control-sm"
                       placeholder="Tên biến thể (Xanh, H32cm...)" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="bienthe[${i}][ma_sku]" class="form-control form-control-sm"
                       placeholder="Mã SKU" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="bienthe[${i}][gia]" class="form-control form-control-sm"
                       placeholder="Giá (đ)" min="0" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="bienthe[${i}][so_luong]" class="form-control form-control-sm"
                       placeholder="SL" min="0" value="0" required>
            </div>
            <div class="col-md-2">
                <label class="btn btn-outline-secondary btn-sm w-100 mb-0" style="font-size:0.75rem">
                    <i class="fas fa-image"></i> Ảnh
                    <input type="file" name="bienthe[${i}][hinh_anh]" accept="image/*"
                           class="d-none" onchange="previewAnhBienThe(this, ${i})">
                </label>
                <img id="prev-bt-${i}" src="" class="d-none mt-1 rounded"
                     style="width:40px;height:40px;object-fit:cover">
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

function xoaBienThe(i) {
    document.getElementById('bt-' + i)?.remove();
}

function previewAnhBienThe(input, i) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('prev-bt-' + i);
            if (img) { img.src = e.target.result; img.classList.remove('d-none'); }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection