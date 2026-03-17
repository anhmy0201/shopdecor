@extends('layouts.admin')
@section('title', 'Tin Tức')
 
@section('content')
 
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Tin Tức</h5>
        <small class="text-muted">Quản lý bài viết & tin tức</small>
    </div>
    <a href="{{ route('admin.tin-tuc.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i>Viết Bài Mới
    </a>
</div>
 
<div class="card">
    <div class="card-header">
        <i class="fas fa-newspaper me-2"></i>Danh Sách Tin Tức
        <span class="badge bg-light text-dark ms-2">{{ $tintuc->total() }} bài</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th width="70">Ảnh</th>
                    <th>Tiêu Đề</th>
                    <th>Tác Giả</th>
                    <th class="text-center">Ảnh Gallery</th>
                    <th class="text-center">Lượt Xem</th>
                    <th>Ngày Đăng</th>
                    <th class="text-center">Trạng Thái</th>
                    <th class="text-center" width="120">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tintuc as $bai)
                <tr class="{{ !$bai->kich_hoat ? 'table-secondary' : '' }}">
                    <td>
                        @if($bai->anh_dai_dien)
                            <img src="{{ asset($bai->anh_dai_dien) }}"
                                 width="60" height="45"
                                 class="rounded" style="object-fit:cover">
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                 style="width:60px;height:45px">
                                <i class="fas fa-newspaper text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold">{{ \Illuminate\Support\Str::limit($bai->tieu_de, 50) }}</div>
                        @if($bai->mo_ta_ngan)
                            <div class="text-muted" style="font-size:0.72rem">
                                {{ \Illuminate\Support\Str::limit($bai->mo_ta_ngan, 60) }}
                            </div>
                        @endif
                        <code class="text-muted" style="font-size:0.65rem">{{ $bai->slug }}</code>
                    </td>
                    <td class="text-muted">{{ $bai->tacGia?->ho_ten ?? '—' }}</td>
                    <td class="text-center">
                        <span class="badge bg-info text-dark">{{ $bai->hinhanhs_count }} ảnh</span>
                    </td>
                    <td class="text-center">{{ number_format($bai->luot_xem) }}</td>
                    <td class="text-muted" style="font-size:0.8rem">
                        {{ $bai->ngay_dang?->format('d/m/Y H:i') ?? '—' }}
                    </td>
                    <td class="text-center">
                        @if($bai->kich_hoat && $bai->ngay_dang && $bai->ngay_dang->isPast())
                            <span class="badge bg-success">Đã đăng</span>
                        @elseif(!$bai->kich_hoat)
                            <span class="badge bg-secondary">Ẩn</span>
                        @else
                            <span class="badge bg-warning text-dark">Chờ đăng</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <form action="{{ route('admin.tin-tuc.toggle', $bai) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $bai->kich_hoat ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                        title="{{ $bai->kich_hoat ? 'Ẩn' : 'Hiện' }}">
                                    <i class="fas {{ $bai->kich_hoat ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                </button>
                            </form>
                            <a href="{{ route('admin.tin-tuc.edit', $bai) }}"
                               class="btn btn-sm btn-outline-primary" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.tin-tuc.destroy', $bai) }}"
                                  method="POST"
                                  onsubmit="return confirm('Xoá bài này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Xoá">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="fas fa-newspaper fa-2x mb-2 d-block"></i>
                        Chưa có bài viết nào.
                        <a href="{{ route('admin.tin-tuc.create') }}">Viết bài ngay</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tintuc->hasPages())
        <div class="card-footer">{{ $tintuc->links() }}</div>
    @endif
</div>
 
@endsection