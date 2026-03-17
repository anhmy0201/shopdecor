@extends('layouts.admin')
@section('title', $tinTuc ? 'Sửa Tin Tức' : 'Viết Bài Mới')
 
@section('extra-css')
<style>
.anh-preview { width:100px;height:70px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6; }
.anh-wrap { position:relative;display:inline-block; }
.anh-wrap .btn-xoa { position:absolute;top:-6px;right:-6px;width:20px;height:20px;padding:0;font-size:10px;border-radius:50%; }
</style>
@endsection
 
@section('content')
 
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">{{ $tinTuc ? 'Sửa: ' . \Illuminate\Support\Str::limit($tinTuc->tieu_de, 40) : 'Viết Bài Mới' }}</h5>
        <small class="text-muted">
            <a href="{{ route('admin.tin-tuc.index') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
            </a>
        </small>
    </div>
</div>
 
<form action="{{ $tinTuc ? route('admin.tin-tuc.update', $tinTuc) : route('admin.tin-tuc.store') }}"
      method="POST" enctype="multipart/form-data">
@csrf
@if($tinTuc) @method('PUT') @endif
 
<div class="row g-3">
 
    {{-- CỘT TRÁI --}}
    <div class="col-lg-8">
 
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-pen me-2"></i>Nội Dung Bài Viết</div>
            <div class="card-body p-4">
 
                <div class="mb-3">
                    <label class="form-label fw-bold">Tiêu Đề <span class="text-danger">*</span></label>
                    <input type="text" name="tieu_de"
                           class="form-control @error('tieu_de') is-invalid @enderror"
                           value="{{ old('tieu_de', $tinTuc?->tieu_de) }}"
                           placeholder="Tiêu đề bài viết..." autofocus>
                    @error('tieu_de')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
 
                <div class="mb-3">
                    <label class="form-label fw-bold">Mô Tả Ngắn</label>
                    <textarea name="mo_ta_ngan"
                              class="form-control"
                              rows="2"
                              placeholder="Tóm tắt hiển thị ngoài trang danh sách (tối đa 500 ký tự)...">{{ old('mo_ta_ngan', $tinTuc?->mo_ta_ngan) }}</textarea>
                </div>
 
                <div class="mb-3">
                    <label class="form-label fw-bold">Nội Dung</label>
                    <textarea name="noi_dung"
                              class="form-control"
                              rows="15"
                              placeholder="Nội dung đầy đủ của bài viết...">{{ old('noi_dung', $tinTuc?->noi_dung) }}</textarea>
                    <div class="form-text">Hỗ trợ HTML cơ bản: &lt;b&gt;, &lt;i&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;h3&gt;...</div>
                </div>
 
            </div>
        </div>
 
        {{-- Ảnh gallery trong bài --}}
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-images me-2"></i>Ảnh Trong Bài</div>
            <div class="card-body p-4">
 
                @if($tinTuc && $tinTuc->hinhanhs->count() > 0)
                <p class="small text-muted mb-2">Ảnh hiện tại — tick để xoá:</p>
                <div class="d-flex flex-wrap gap-3 mb-3">
                    @foreach($tinTuc->hinhanhs as $anh)
                    <div class="anh-wrap">
                        <img src="{{ asset($anh->duong_dan_anh) }}" class="anh-preview">
                        @if($anh->chu_thich)
                            <div class="text-muted mt-1" style="font-size:0.68rem;max-width:100px;text-align:center">
                                {{ \Illuminate\Support\Str::limit($anh->chu_thich, 20) }}
                            </div>
                        @endif
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox"
                                   name="xoa_anh[]" value="{{ $anh->id }}"
                                   id="xoa-{{ $anh->id }}">
                            <label class="form-check-label text-danger small" for="xoa-{{ $anh->id }}">Xoá</label>
                        </div>
                    </div>
                    @endforeach
                </div>
                <hr>
                <p class="small fw-bold mb-2">Thêm ảnh mới:</p>
                @endif
 
                <div id="anh-preview-wrap" class="d-flex flex-wrap gap-2 mb-2"></div>
                <div id="anh-inputs"></div>
 
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="chonAnh()">
                    <i class="fas fa-upload me-1"></i>Chọn ảnh (có thể chọn nhiều lần)
                </button>
                <input type="file" id="inputAnhAn" multiple accept="image/*"
                       class="d-none" onchange="themAnh(this)">
 
                <div class="form-text mt-1">JPG/PNG/WebP, tối đa 3MB mỗi ảnh. Khuyến nghị 2–3 ảnh minh hoạ.</div>
 
                {{-- Chú thích cho ảnh mới --}}
                <div id="chu-thich-wrap" class="mt-3 d-none">
                    <p class="small fw-bold mb-2">Chú thích ảnh mới:</p>
                    <div id="danh-sach-chu-thich"></div>
                </div>
 
            </div>
        </div>
 
    </div>
 
    {{-- CỘT PHẢI --}}
    <div class="col-lg-4">
 
        {{-- Ảnh đại diện --}}
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-image me-2"></i>Ảnh Đại Diện</div>
            <div class="card-body p-3">
                @if($tinTuc?->anh_dai_dien)
                    <img src="{{ asset($tinTuc->anh_dai_dien) }}"
                         id="previewAnhDaiDien"
                         class="img-fluid rounded mb-2" style="max-height:150px;object-fit:cover;width:100%">
                @else
                    <img id="previewAnhDaiDien" src="" class="img-fluid rounded mb-2 d-none"
                         style="max-height:150px;object-fit:cover;width:100%">
                @endif
                <label class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fas fa-upload me-1"></i>{{ $tinTuc?->anh_dai_dien ? 'Đổi ảnh' : 'Chọn ảnh đại diện' }}
                    <input type="file" name="anh_dai_dien" accept="image/*"
                           class="d-none" onchange="previewAnhDD(this)">
                </label>
                @error('anh_dai_dien')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
 
        {{-- Cài đặt đăng --}}
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-cog me-2"></i>Cài Đặt Đăng</div>
            <div class="card-body p-3">
 
                <div class="mb-3">
                    <label class="form-label fw-bold small">Ngày Đăng</label>
                    <input type="datetime-local" name="ngay_dang"
                           class="form-control form-control-sm"
                           value="{{ old('ngay_dang', $tinTuc?->ngay_dang?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}">
                    <div class="form-text">Để trống = đăng ngay.</div>
                </div>
 
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox"
                           name="kich_hoat" id="kichHoat" value="1"
                           {{ old('kich_hoat', $tinTuc?->kich_hoat ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="kichHoat">Hiển thị bài viết</label>
                </div>
 
                @if($tinTuc)
                <hr>
                <table class="table table-sm table-borderless small mb-0">
                    <tr>
                        <td class="text-muted">Tác giả</td>
                        <td>{{ $tinTuc->tacGia?->ho_ten ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Lượt xem</td>
                        <td>{{ number_format($tinTuc->luot_xem) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tạo lúc</td>
                        <td>{{ $tinTuc->created_at->format('d/m/Y') }}</td>
                    </tr>
                </table>
                @endif
 
            </div>
        </div>
 
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i>{{ $tinTuc ? 'Cập Nhật' : 'Đăng Bài' }}
            </button>
            <a href="{{ route('admin.tin-tuc.index') }}" class="btn btn-outline-secondary">Huỷ</a>
        </div>
 
    </div>
 
</div>
 
</form>
 
@endsection
 
@section('extra-js')
<script>
// Preview ảnh đại diện
function previewAnhDD(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('previewAnhDaiDien');
            img.src = e.target.result;
            img.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
 
// Upload ảnh gallery nhiều đợt
let danhSachFile = [];
let demAnh = 0;
 
function chonAnh() {
    document.getElementById('inputAnhAn').click();
}
 
function themAnh(input) {
    const wrapChuThich = document.getElementById('chu-thich-wrap');
    const danhSachCT = document.getElementById('danh-sach-chu-thich');
 
    Array.from(input.files).forEach(file => {
        const id = demAnh++;
        danhSachFile.push({ id, file });
 
        // Input file ẩn
        const dt = new DataTransfer();
        dt.items.add(file);
        const inp = document.createElement('input');
        inp.type = 'file';
        inp.name = 'hinhanh[]';
        inp.className = 'd-none';
        inp.id = 'file-' + id;
        inp.files = dt.files;
        document.getElementById('anh-inputs').appendChild(inp);
 
        // Preview
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('anh-preview-wrap').insertAdjacentHTML('beforeend', `
                <div class="anh-wrap" id="wrap-${id}">
                    <img src="${e.target.result}" class="anh-preview">
                    <button type="button" class="btn btn-danger btn-xoa"
                            onclick="xoaAnh(${id})" title="Xoá">✕</button>
                </div>`);
        };
        reader.readAsDataURL(file);
 
        // Input chú thích
        wrapChuThich.classList.remove('d-none');
        danhSachCT.insertAdjacentHTML('beforeend', `
            <div class="mb-2" id="ct-${id}">
                <input type="text" name="chu_thich_moi[]"
                       class="form-control form-control-sm"
                       placeholder="Chú thích ảnh ${id + 1} (tuỳ chọn)">
            </div>`);
    });
    input.value = '';
}
 
function xoaAnh(id) {
    danhSachFile = danhSachFile.filter(f => f.id !== id);
    document.getElementById('wrap-' + id)?.remove();
    document.getElementById('file-' + id)?.remove();
    document.getElementById('ct-' + id)?.remove();
    if (danhSachFile.length === 0) {
        document.getElementById('chu-thich-wrap').classList.add('d-none');
    }
}
</script>
@endsection