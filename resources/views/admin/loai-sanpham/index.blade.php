@extends('layouts.admin')
@section('title', 'Loại Sản Phẩm')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Loại Sản Phẩm</h5>
        <small class="text-muted">Quản lý danh mục sản phẩm</small>
    </div>
    <a href="{{ route('admin.loai-sanpham.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Thêm Loại Mới
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-list me-2"></i>Danh Sách Loại Sản Phẩm
        <span class="badge bg-light text-dark ms-2">{{ $loais->total() }} loại</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="5%">#</th>
                    <th>Tên Loại</th>
                    <th>Slug</th>
                    <th>Mô Tả</th>
                    <th class="text-center" width="12%">Sản Phẩm</th>
                    <th class="text-center" width="15%">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loais as $loai)
                <tr>
                    <td class="text-muted small">{{ $loai->id }}</td>
                    <td class="fw-bold">{{ $loai->ten_loai }}</td>
                    <td><code class="small">{{ $loai->slug }}</code></td>
                    <td class="text-muted small">
                        {{ $loai->mo_ta ? \Illuminate\Support\Str::limit($loai->mo_ta, 60) : '—' }}
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.sanpham.index', ['loai_id' => $loai->id]) }}"
                           class="badge bg-info text-dark text-decoration-none">
                            {{ $loai->sanphams_count }} SP
                        </a>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('admin.loai-sanpham.edit', $loai) }}"
                               class="btn btn-sm btn-outline-primary" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.loai-sanpham.destroy', $loai) }}"
                                  method="POST"
                                  onsubmit="return confirm('Xóa loại \'{{ $loai->ten_loai }}\'?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Xóa"
                                        {{ $loai->sanphams_count > 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        @if($loai->sanphams_count > 0)
                        <small class="text-muted d-block mt-1" style="font-size:0.65rem">Không thể xóa</small>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Chưa có loại sản phẩm nào.
                        <a href="{{ route('admin.loai-sanpham.create') }}">Thêm ngay</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($loais->hasPages())
    <div class="card-footer">
        {{ $loais->links() }}
    </div>
    @endif
</div>

@endsection