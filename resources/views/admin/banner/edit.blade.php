@extends('layouts.admin')
@section('title', 'Sửa Banner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="fas fa-pen me-2"></i>Sửa Banner</h5>
    <a href="{{ route('admin.banner.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.banner.update', $banner) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">

                {{-- Ảnh hiện tại --}}
                <div class="col-md-5 text-center">
                    <div class="mb-2 fw-semibold text-muted small">Ảnh hiện tại</div>
                    <img src="{{ asset($banner->duong_dan_anh) }}"
                         id="previewImg"
                         alt="{{ $banner->tieu_de }}"
                         class="rounded border w-100"
                         style="max-height:200px;object-fit:cover;">
                    <div class="mt-2">
                        <label class="form-label fw-semibold">Thay ảnh mới <span class="text-muted fw-normal">(tuỳ chọn)</span></label>
                        <input type="file" name="anh_banner"
                               class="form-control @error('anh_banner') is-invalid @enderror"
                               accept="image/*" onchange="previewNew(this)">
                        @error('anh_banner') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Để trống nếu không muốn đổi ảnh</div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Tiêu đề</label>
                            <input type="text" name="tieu_de" class="form-control"
                                   value="{{ old('tieu_de', $banner->tieu_de) }}"
                                   placeholder="VD: Ưu đãi mùa hè">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Mô tả ngắn</label>
                            <input type="text" name="mo_ta" class="form-control"
                                   value="{{ old('mo_ta', $banner->mo_ta) }}"
                                   placeholder="Hiển thị dưới tiêu đề">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Link khi click</label>
                            <input type="text" name="url_lien_ket"
                                   class="form-control @error('url_lien_ket') is-invalid @enderror"
                                   value="{{ old('url_lien_ket', $banner->url_lien_ket) }}"
                                   placeholder="https://...">
                            @error('url_lien_ket') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-semibold">Thứ tự</label>
                            <input type="number" name="thu_tu" class="form-control" min="0"
                                   value="{{ old('thu_tu', $banner->thu_tu) }}">
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-semibold">Ngày bắt đầu</label>
                            <input type="date" name="ngay_bat_dau" class="form-control"
                                   value="{{ old('ngay_bat_dau', $banner->ngay_bat_dau?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-semibold">Ngày kết thúc</label>
                            <input type="date" name="ngay_ket_thuc"
                                   class="form-control @error('ngay_ket_thuc') is-invalid @enderror"
                                   value="{{ old('ngay_ket_thuc', $banner->ngay_ket_thuc?->format('Y-m-d')) }}">
                            @error('ngay_ket_thuc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="kich_hoat"
                                       id="switchKichHoat" value="1"
                                       {{ old('kich_hoat', $banner->kich_hoat) ? 'checked' : '' }}>
                                <label class="form-check-label" for="switchKichHoat">Kích hoạt</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 d-flex gap-2 justify-content-end border-top pt-3 mt-1">
                    <a href="{{ route('admin.banner.index') }}" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Lưu thay đổi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewNew(input) {
    if (input.files && input.files[0]) {
        document.getElementById('previewImg').src = URL.createObjectURL(input.files[0]);
    }
}
</script>
@endpush