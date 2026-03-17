@extends('layouts.admin')
@section('title', 'Bình Luận')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Bình Luận</h5>
        <small class="text-muted">Kiểm duyệt đánh giá sản phẩm</small>
    </div>
</div>

{{-- THỐNG KÊ SAO --}}
<div class="row g-2 mb-3">
    @for($i = 5; $i >= 1; $i--)
    <div class="col">
        <a href="{{ route('admin.binhluan.index', array_merge(request()->query(), ['sao' => $i])) }}"
           class="card mb-0 text-decoration-none {{ request('sao') == $i ? 'border-warning border-2' : '' }}">
            <div class="card-body py-2 px-3 text-center">
                <div class="fw-bold">{{ $demSao[$i] }}</div>
                <div>
                    @for($s = 1; $s <= 5; $s++)
                        <i class="fas fa-star {{ $s <= $i ? 'text-warning' : 'text-muted' }}"
                           style="font-size:0.65rem"></i>
                    @endfor
                </div>
            </div>
        </a>
    </div>
    @endfor
    <div class="col">
        <a href="{{ route('admin.binhluan.index') }}"
           class="card mb-0 text-decoration-none {{ !request('sao') ? 'border-dark border-2' : '' }}">
            <div class="card-body py-2 px-3 text-center">
                <div class="fw-bold">{{ array_sum($demSao) }}</div>
                <div class="small text-muted">Tất cả</div>
            </div>
        </a>
    </div>
</div>

{{-- BỘ LỌC --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            @if(request('sao'))
                <input type="hidden" name="sao" value="{{ request('sao') }}">
            @endif
            <div class="col-md-4">
                <input type="text" name="q" class="form-control form-control-sm"
                       placeholder="Nội dung, tên người dùng..."
                       value="{{ request('q') }}">
            </div>
            <div class="col-md-4">
                <select name="sanpham_id" class="form-select form-select-sm">
                    <option value="">-- Tất cả sản phẩm --</option>
                    @foreach($sanphams as $sp)
                        <option value="{{ $sp->id }}" {{ request('sanpham_id') == $sp->id ? 'selected' : '' }}>
                            {{ \Illuminate\Support\Str::limit($sp->ten_san_pham, 50) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary">
                    <i class="fas fa-search me-1"></i>Lọc
                </button>
                <a href="{{ route('admin.binhluan.index') }}"
                   class="btn btn-sm btn-outline-secondary ms-1">Xóa lọc</a>
            </div>
        </form>
    </div>
</div>

{{-- BẢNG --}}
<div class="card">
    <div class="card-header">
        <i class="fas fa-comments me-2"></i>Danh Sách Bình Luận
        <span class="badge bg-light text-dark ms-2">{{ $binhluans->total() }} bình luận</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>Người Dùng</th>
                    <th>Sản Phẩm</th>
                    <th>Nội Dung</th>
                    <th class="text-center" width="100">Đánh Giá</th>
                    <th width="120">Ngày Đăng</th>
                    <th class="text-center" width="70">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($binhluans as $bl)
                <tr>
                    <td>
                        <div class="fw-bold">{{ $bl->user->ho_ten ?? '—' }}</div>
                        <div class="text-muted" style="font-size:0.72rem">
                            {{ $bl->user->email ?? '' }}
                        </div>
                    </td>
                    <td>
                        @if($bl->sanpham)
                            <a href="{{ route('admin.sanpham.show', $bl->sanpham) }}"
                               class="text-decoration-none fw-bold">
                                {{ \Illuminate\Support\Str::limit($bl->sanpham->ten_san_pham, 35) }}
                            </a>
                        @else
                            <span class="text-muted fst-italic">Sản phẩm đã xoá</span>
                        @endif
                    </td>
                    <td style="max-width:300px">
                        <div>{{ \Illuminate\Support\Str::limit($bl->noi_dung, 120) }}</div>
                        @if(strlen($bl->noi_dung) > 120)
                            <a href="#" class="small text-primary"
                               data-bs-toggle="tooltip" title="{{ $bl->noi_dung }}">
                                Xem thêm
                            </a>
                        @endif
                    </td>
                    <td class="text-center">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $bl->sao_danh_gia ? 'text-warning' : 'text-muted' }}"
                               style="font-size:0.75rem"></i>
                        @endfor
                        <div class="text-muted" style="font-size:0.68rem">
                            {{ $bl->sao_danh_gia }}/5 sao
                        </div>
                    </td>
                    <td class="text-muted">
                        {{ $bl->created_at->format('d/m/Y') }}
                        <div style="font-size:0.68rem">{{ $bl->created_at->format('H:i') }}</div>
                    </td>
                    <td class="text-center">
                        <form action="{{ route('admin.binhluan.destroy', $bl) }}"
                              method="POST"
                              onsubmit="return confirm('Xóa bình luận này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-comments fa-2x mb-2 d-block"></i>
                        Chưa có bình luận nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($binhluans->hasPages())
        <div class="card-footer">{{ $binhluans->links() }}</div>
    @endif
</div>

@endsection

@section('extra-js')
<script>
// Tooltip cho nội dung dài
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
    new bootstrap.Tooltip(el, { placement: 'left' });
});
</script>
@endsection