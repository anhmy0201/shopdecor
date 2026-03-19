@extends('layouts.admin')
@section('title', 'Sản Phẩm')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Sản Phẩm</h5>
        <small class="text-muted">Quản lý toàn bộ sản phẩm</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.sanpham.xuat') }}" class="btn btn-success btn-sm">
            <i class="fas fa-download me-1"></i>Xuất Excel
        </a>
        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalNhapExcel">
            <i class="fas fa-upload me-1"></i>Nhập Excel
        </button>
        <a href="{{ route('admin.sanpham.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Thêm Sản Phẩm
        </a>
    </div>
</div>

{{-- BỘ LỌC --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control form-control-sm"
                       placeholder="Tìm tên sản phẩm..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="loai_id" class="form-select form-select-sm">
                    <option value="">-- Tất cả loại --</option>
                    @foreach($loais as $loai)
                        <option value="{{ $loai->id }}" {{ request('loai_id') == $loai->id ? 'selected' : '' }}>
                            {{ $loai->ten_loai }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="ton_kho" class="form-select form-select-sm">
                    <option value="">-- Tồn kho --</option>
                    <option value="het_hang" {{ request('ton_kho') === 'het_hang' ? 'selected' : '' }}>Hết hàng</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary">
                    <i class="fas fa-search me-1"></i>Lọc
                </button>
                <a href="{{ route('admin.sanpham.index') }}" class="btn btn-sm btn-outline-secondary ms-1">
                    Xóa lọc
                </a>
            </div>
        </form>
    </div>
</div>

{{-- BẢNG --}}
<div class="card">
    <div class="card-header">
        <i class="fas fa-box me-2"></i>Danh Sách Sản Phẩm
        <span class="badge bg-light text-dark ms-2">{{ $sanphams->total() }} sản phẩm</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th width="60">Ảnh</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Loại</th>
                    <th class="text-end">Giá</th>
                    <th class="text-center">Tồn Kho</th>
                    <th class="text-center">Biến Thể</th>
                    <th class="text-center">Lượt Mua</th>
                    <th class="text-center" width="120">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sanphams as $sp)
                <tr>
                    <td>
                        @if($sp->anhChinh)
                            <img src="{{ asset($sp->anhChinh->duong_dan_anh) }}"
                                 class="rounded" width="48" height="48"
                                 style="object-fit:cover">
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                 style="width:48px;height:48px">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold">{{ \Illuminate\Support\Str::limit($sp->ten_san_pham, 40) }}</div>
                        <code class="text-muted" style="font-size:0.68rem">{{ $sp->slug }}</code>
                    </td>
                    <td>
                        <span class="badge bg-info text-dark">{{ $sp->loai->ten_loai ?? '—' }}</span>
                    </td>
                    <td class="text-end">
                        <div class="fw-bold text-danger">{{ number_format($sp->gia) }}đ</div>
                        @if($sp->gia_cu)
                            <div class="text-muted text-decoration-line-through" style="font-size:0.75rem">
                                {{ number_format($sp->gia_cu) }}đ
                            </div>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($sp->co_bien_the)
                            <span class="badge bg-secondary">Theo biến thể</span>
                        @else
                            @if($sp->so_luong == 0)
                                <span class="badge bg-danger">Hết hàng</span>
                            @elseif($sp->so_luong <= 5)
                                <span class="badge bg-warning text-dark">{{ $sp->so_luong }} còn ít</span>
                            @else
                                <span class="badge bg-success">{{ $sp->so_luong }}</span>
                            @endif
                        @endif
                    </td>
                    <td class="text-center">
                        @if($sp->co_bien_the)
                            <span class="badge bg-primary">{{ $sp->bienthes_count }} biến thể</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center fw-bold">{{ number_format($sp->luot_mua) }}</td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('admin.sanpham.show', $sp) }}"
                               class="btn btn-sm btn-outline-info" title="Xem">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.sanpham.edit', $sp) }}"
                               class="btn btn-sm btn-outline-primary" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.sanpham.destroy', $sp) }}"
                                  method="POST"
                                  onsubmit="return confirm('Xóa sản phẩm \'{{ $sp->ten_san_pham }}\'?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                        Chưa có sản phẩm nào.
                        <a href="{{ route('admin.sanpham.create') }}">Thêm ngay</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($sanphams->hasPages())
    <div class="card-footer">
        {{ $sanphams->links() }}
    </div>
    @endif
</div>

{{-- MODAL NHẬP EXCEL --}}
<div class="modal fade" id="modalNhapExcel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.sanpham.nhap') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-upload me-2"></i>Nhập Sản Phẩm Từ Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Chọn file Excel</label>
                        <input type="file" class="form-control" name="file_excel"
                               accept=".xlsx,.xls,.csv" required>
                        <div class="form-text mt-1">
                            Định dạng: .xlsx, .xls, .csv — tối đa 5MB
                        </div>
                    </div>
                    <div class="alert alert-info small mb-0">
                        <strong>Cấu trúc file Excel:</strong><br>
                        Hàng 1 là tiêu đề:
                        <code>Mã loại | Tên sản phẩm | Giá | Giá cũ | Mô tả | Số lượng</code>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>Nhập dữ liệu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection