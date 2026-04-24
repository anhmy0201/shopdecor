@extends('layouts.admin')
@section('title', 'Cài Đặt')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="fas fa-cog me-2"></i>Cài Đặt Hệ Thống</h5>
</div>

{{-- ── TAB NAV ── --}}
<ul class="nav nav-tabs mb-0" id="caidatTab" role="tablist">
    <li class="nav-item">
        <button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-chung">
            <i class="fas fa-sliders-h me-1"></i> Thông Tin Chung
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-log"
                id="btn-tab-log">
            <i class="fas fa-history me-1"></i> Nhật Ký Hoạt Động
            @if($tongLog > 0)
                <span class="badge bg-danger ms-1">{{ number_format($tongLog) }}</span>
            @endif
        </button>
    </li>
</ul>

<div class="tab-content border border-top-0 bg-white rounded-bottom p-4 shadow-sm">

    {{-- ══════════ TAB THÔNG TIN CHUNG ══════════ --}}
    <div class="tab-pane fade show active" id="tab-chung">
        <form action="{{ route('admin.caidat.update') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tên Cửa Hàng</label>
                    <input type="text" name="ten_cua_hang" class="form-control"
                           value="{{ old('ten_cua_hang', $settings['ten_cua_hang'] ?? '') }}"
                           placeholder="ShopDecor">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email Liên Hệ</label>
                    <input type="email" name="email_lien_he" class="form-control"
                           value="{{ old('email_lien_he', $settings['email_lien_he'] ?? '') }}"
                           placeholder="contact@shopdecor.vn">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Số Điện Thoại</label>
                    <input type="text" name="so_dien_thoai" class="form-control"
                           value="{{ old('so_dien_thoai', $settings['so_dien_thoai'] ?? '') }}"
                           placeholder="0900 000 000">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Địa Chỉ</label>
                    <input type="text" name="dia_chi" class="form-control"
                           value="{{ old('dia_chi', $settings['dia_chi'] ?? '') }}"
                           placeholder="123 Đường ABC, TP.HCM">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Mô Tả Ngắn</label>
                    <textarea name="mo_ta_ngan" class="form-control" rows="3"
                              placeholder="Mô tả ngắn về cửa hàng...">{{ old('mo_ta_ngan', $settings['mo_ta_ngan'] ?? '') }}</textarea>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Lưu Cài Đặt
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ══════════ TAB NHẬT KÝ HOẠT ĐỘNG ══════════ --}}
    <div class="tab-pane fade" id="tab-log">

        {{-- Bộ lọc --}}
        <form method="GET" action="{{ route('admin.caidat.index') }}" id="formFilterLog">
            <input type="hidden" name="tab" value="log">
            <div class="row g-2 mb-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">Người thực hiện</label>
                    <select name="log_causer" class="form-select form-select-sm">
                        <option value="">— Tất cả —</option>
                        @foreach($causers as $c)
                            <option value="{{ $c->id }}"
                                {{ request('log_causer') == $c->id ? 'selected' : '' }}>
                                {{ $c->ho_ten }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">Đối tượng</label>
                    <select name="log_subject" class="form-select form-select-sm">
                        <option value="">— Tất cả —</option>
                        @foreach($subjectMap as $label => $class)
                            <option value="{{ $label }}"
                                {{ request('log_subject') == $label ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">Sự kiện</label>
                    <select name="log_event" class="form-select form-select-sm">
                        <option value="">— Tất cả —</option>
                        <option value="created"  {{ request('log_event') == 'created'  ? 'selected' : '' }}>Thêm mới</option>
                        <option value="updated"  {{ request('log_event') == 'updated'  ? 'selected' : '' }}>Cập nhật</option>
                        <option value="deleted"  {{ request('log_event') == 'deleted'  ? 'selected' : '' }}>Xóa</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">Từ ngày</label>
                    <input type="date" name="log_date_from" class="form-control form-control-sm"
                           value="{{ request('log_date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">Đến ngày</label>
                    <input type="date" name="log_date_to" class="form-control form-control-sm"
                           value="{{ request('log_date_to') }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="fas fa-filter me-1"></i>Lọc
                    </button>
                    <a href="{{ route('admin.caidat.index') }}?tab=log"
                       class="btn btn-outline-secondary btn-sm flex-fill">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </form>

        {{-- Toolbar --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small text-muted">
                Tổng: <strong>{{ number_format($tongLog) }}</strong> bản ghi
                @if($activityLogs->total() != $tongLog)
                    &bull; Kết quả lọc: <strong>{{ number_format($activityLogs->total()) }}</strong>
                @endif
            </span>
            <button class="btn btn-outline-danger btn-sm"
                    data-bs-toggle="modal" data-bs-target="#modalXoaLog">
                <i class="fas fa-trash me-1"></i>Xóa Toàn Bộ
            </button>
        </div>

        {{-- Bảng log --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle small mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="14%">Người thực hiện</th>
                        <th width="10%">Sự kiện</th>
                        <th width="12%">Đối tượng</th>
                        <th width="5%">ID</th>
                        <th>Mô tả / Thay đổi</th>
                        <th width="13%">Thời gian</th>
                        <th width="5%"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activityLogs as $log)
                    @php
                        $eventColor = match($log->event) {
                            'created' => 'success',
                            'updated' => 'primary',
                            'deleted' => 'danger',
                            default   => 'secondary',
                        };
                        $eventLabel = match($log->event) {
                            'created' => 'Thêm',
                            'updated' => 'Sửa',
                            'deleted' => 'Xóa',
                            default   => $log->event ?? '—',
                        };
                        $subjectShort = $log->subject_type
                            ? class_basename($log->subject_type)
                            : '—';
                        $changes = $log->properties->get('attributes', []);
                        $old     = $log->properties->get('old', []);
                    @endphp
                    <tr>
                        <td class="text-muted">{{ $log->id }}</td>
                        <td>
                            @if($log->causer)
                                <span class="fw-semibold">{{ $log->causer->ho_ten ?? '—' }}</span><br>
                                <span class="text-muted" style="font-size:0.75rem">{{ $log->causer->email ?? '' }}</span>
                            @else
                                <span class="text-muted fst-italic">Hệ thống</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $eventColor }}">{{ $eventLabel }}</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $subjectShort }}</span>
                        </td>
                        <td class="text-muted">{{ $log->subject_id ?? '—' }}</td>
                        <td>
                            <div class="text-dark">{{ $log->description }}</div>
                            @if(!empty($changes))
                                <div class="mt-1">
                                    @foreach($changes as $field => $newVal)
                                        <span class="badge bg-light text-dark border me-1 mb-1"
                                              style="font-size:0.7rem;font-weight:normal">
                                            <span class="text-secondary">{{ $field }}:</span>
                                            @if(isset($old[$field]))
                                                <span class="text-danger text-decoration-line-through me-1">
                                                    {{ \Illuminate\Support\Str::limit((string)$old[$field], 20) }}
                                                </span>
                                            @endif
                                            <span class="text-success fw-semibold">
                                                {{ \Illuminate\Support\Str::limit((string)$newVal, 30) }}
                                            </span>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="text-muted" style="font-size:0.78rem">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}<br>
                            <span class="text-secondary">{{ $log->created_at->diffForHumans() }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.caidat.log.destroy', $log) }}" method="POST"
                                  onsubmit="return confirm('Xóa mục log này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Xóa">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="fas fa-history fa-2x mb-2 d-block opacity-25"></i>
                            Chưa có nhật ký hoạt động nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        @if($activityLogs->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $activityLogs->links() }}
        </div>
        @endif

    </div>
    {{-- end tab-log --}}

</div>
{{-- end tab-content --}}

{{-- ── MODAL XÓA TOÀN BỘ LOG ── --}}
<div class="modal fade" id="modalXoaLog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Xóa Toàn Bộ Nhật Ký
                </h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.caidat.log.destroy-all') }}" method="POST">
                @csrf @method('DELETE')
                <div class="modal-body">
                    <p class="mb-2">Hành động này sẽ <strong class="text-danger">xóa vĩnh viễn</strong>
                        toàn bộ <strong>{{ number_format($tongLog) }}</strong> bản ghi nhật ký và
                        <strong>không thể khôi phục</strong>.</p>
                    <label class="form-label fw-semibold">
                        Nhập <code>XOA</code> để xác nhận:
                    </label>
                    <input type="text" name="confirm_xoa" class="form-control @error('confirm_xoa') is-invalid @enderror"
                           placeholder="XOA" autocomplete="off">
                    @error('confirm_xoa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Xóa Toàn Bộ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script>
// Tự động mở tab log nếu URL có ?tab=log hoặc có filter log đang active
document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const isLogTab = params.get('tab') === 'log'
        || params.get('log_causer') || params.get('log_subject')
        || params.get('log_event')  || params.get('log_date_from');

    if (isLogTab) {
        const tabEl = document.getElementById('btn-tab-log');
        if (tabEl) new bootstrap.Tab(tabEl).show();
    }

    // Mở lại tab log sau khi xóa thất bại (có lỗi validation)
    @if($errors->has('confirm_xoa'))
        const tabLog = document.getElementById('btn-tab-log');
        if (tabLog) new bootstrap.Tab(tabLog).show();
        const modal = new bootstrap.Modal(document.getElementById('modalXoaLog'));
        modal.show();
    @endif
});
</script>
@endsection
