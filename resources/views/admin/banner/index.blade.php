@extends('layouts.admin')
@section('title', 'Quản Lý Banner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="fas fa-images me-2"></i>Quản Lý Banner</h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalThemBanner">
        <i class="fas fa-plus me-1"></i> Thêm Banner
    </button>
</div>

@if($banners->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="fas fa-image fa-3x mb-3 d-block opacity-25"></i>
        Chưa có banner nào.
    </div>
@else
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:80px">Thứ tự</th>
                    <th style="width:120px">Ảnh</th>
                    <th>Tiêu đề / Mô tả</th>
                    <th>Link</th>
                    <th>Thời hạn</th>
                    <th style="width:90px">Trạng thái</th>
                    <th style="width:100px"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($banners as $banner)
                <tr>
                    <td class="text-center fw-semibold text-muted">{{ $banner->thu_tu }}</td>
                    <td>
                        <img src="{{ asset($banner->duong_dan_anh) }}"
                             alt="{{ $banner->tieu_de }}"
                             class="rounded"
                             style="width:100px;height:60px;object-fit:cover;">
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $banner->tieu_de ?: '—' }}</div>
                        @if($banner->mo_ta)
                            <div class="text-muted small text-truncate" style="max-width:220px">{{ $banner->mo_ta }}</div>
                        @endif
                    </td>
                    <td>
                        @if($banner->url_lien_ket)
                            <a href="{{ $banner->url_lien_ket }}" target="_blank"
                               class="small text-truncate d-block" style="max-width:180px">
                                {{ $banner->url_lien_ket }}
                            </a>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td class="small text-muted">
                        @if($banner->ngay_bat_dau || $banner->ngay_ket_thuc)
                            {{ $banner->ngay_bat_dau?->format('d/m/Y') ?? '∞' }}
                            →
                            {{ $banner->ngay_ket_thuc?->format('d/m/Y') ?? '∞' }}
                        @else
                            <span>Không giới hạn</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <form action="{{ route('admin.banner.toggle', $banner) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm {{ $banner->kich_hoat ? 'btn-success' : 'btn-outline-secondary' }}"
                                    title="{{ $banner->kich_hoat ? 'Đang bật – click để tắt' : 'Đang tắt – click để bật' }}">
                                <i class="fas {{ $banner->kich_hoat ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                            </button>
                        </form>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.banner.edit', $banner) }}"
                           class="btn btn-sm btn-outline-secondary" title="Sửa">
                            <i class="fas fa-pen"></i>
                        </a>
                        <form action="{{ route('admin.banner.destroy', $banner) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Xóa banner này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif


{{-- ══ MODAL THÊM BANNER ══ --}}
<div class="modal fade" id="modalThemBanner" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Thêm Banner Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Preview --}}
                        <div class="col-12 text-center" id="previewWrap" style="display:none">
                            <img id="previewImg" src="" alt="preview"
                                 class="rounded border"
                                 style="max-height:180px;max-width:100%;object-fit:contain;">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Ảnh banner <span class="text-danger">*</span></label>
                            <input type="file" name="anh_banner" id="inputFile"
                                   class="form-control @error('anh_banner') is-invalid @enderror"
                                   accept="image/*" onchange="previewBanner(this)">
                            @error('anh_banner') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">JPEG, PNG, WebP, GIF – tối đa 5MB</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tiêu đề</label>
                            <input type="text" name="tieu_de" class="form-control"
                                   placeholder="VD: Ưu đãi mùa hè" value="{{ old('tieu_de') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Link khi click</label>
                            <input type="text" name="url_lien_ket" class="form-control @error('url_lien_ket') is-invalid @enderror"
                                   placeholder="https://..." value="{{ old('url_lien_ket') }}">
                            @error('url_lien_ket') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Mô tả ngắn</label>
                            <input type="text" name="mo_ta" class="form-control"
                                   placeholder="Hiển thị dưới tiêu đề trên slide" value="{{ old('mo_ta') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Thứ tự</label>
                            <input type="number" name="thu_tu" class="form-control"
                                   value="{{ old('thu_tu', 0) }}" min="0">
                            <div class="form-text">Số nhỏ hiển thị trước</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Ngày bắt đầu</label>
                            <input type="date" name="ngay_bat_dau" class="form-control"
                                   value="{{ old('ngay_bat_dau') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Ngày kết thúc</label>
                            <input type="date" name="ngay_ket_thuc" class="form-control @error('ngay_ket_thuc') is-invalid @enderror"
                                   value="{{ old('ngay_ket_thuc') }}">
                            @error('ngay_ket_thuc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="kich_hoat"
                                       id="switchKichHoat" value="1"
                                       {{ old('kich_hoat', '1') ? 'checked' : '' }}>
                                <label class="form-check-label" for="switchKichHoat">Kích hoạt ngay</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function previewBanner(input) {
    if (input.files && input.files[0]) {
        document.getElementById('previewImg').src = URL.createObjectURL(input.files[0]);
        document.getElementById('previewWrap').style.display = '';
    }
}

@if($errors->any())
document.addEventListener('DOMContentLoaded', () => {
    new bootstrap.Modal(document.getElementById('modalThemBanner')).show();
});
@endif
</script>
@endpush