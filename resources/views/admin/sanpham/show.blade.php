@extends('layouts.admin')
@section('title', 'Chi Tiết Sản Phẩm')

@section('extra-css')
<style>
.anh-gallery img {
    width: 80px; height: 80px;
    object-fit: cover;
    border-radius: 6px;
    border: 2px solid #dee2e6;
    cursor: pointer;
    transition: border-color .2s;
}
.anh-gallery img.active,
.anh-gallery img:hover { border-color: #1a5276; }
.anh-main {
    width: 100%; height: 280px;
    object-fit: contain;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    background: #f8f9fa;
}
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Chi Tiết Sản Phẩm</h5>
        <small class="text-muted">
            <a href="{{ route('admin.sanpham.index') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
            </a>
        </small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.sanpham.edit', $sanpham) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit me-1"></i>Chỉnh Sửa
        </a>
        <form action="{{ route('admin.sanpham.destroy', $sanpham) }}" method="POST"
              onsubmit="return confirm('Xóa sản phẩm này?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger btn-sm">
                <i class="fas fa-trash me-1"></i>Xóa
            </button>
        </form>
    </div>
</div>

<div class="row g-3">

    {{-- CỘT TRÁI --}}
    <div class="col-lg-8">

        {{-- Thông tin chính --}}
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i>Thông Tin Sản Phẩm</div>
            <div class="card-body p-4">
                <div class="row g-3">

                    {{-- Ảnh --}}
                    <div class="col-md-5">
                        @if($sanpham->hinhanhs->count() > 0)
                            <img id="anh-main"
                                 src="{{ asset($sanpham->hinhanhs->where('la_anh_chinh', true)->first()?->duong_dan_anh ?? $sanpham->hinhanhs->first()->duong_dan_anh) }}"
                                 class="anh-main mb-2">
                            <div class="anh-gallery d-flex flex-wrap gap-2">
                                @foreach($sanpham->hinhanhs as $anh)
                                <img src="{{ asset($anh->duong_dan_anh) }}"
                                     onclick="document.getElementById('anh-main').src=this.src; document.querySelectorAll('.anh-gallery img').forEach(i=>i.classList.remove('active')); this.classList.add('active')"
                                     class="{{ $anh->la_anh_chinh ? 'active' : '' }}">
                                @endforeach
                            </div>
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light rounded"
                                 style="height:200px">
                                <div class="text-center text-muted">
                                    <i class="fas fa-image fa-3x mb-2"></i>
                                    <div>Chưa có ảnh</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Thông tin --}}
                    <div class="col-md-7">
                        <h5 class="fw-bold mb-1">{{ $sanpham->ten_san_pham }}</h5>
                        <div class="text-muted small mb-3"><code>{{ $sanpham->slug }}</code></div>

                        <table class="table table-sm table-borderless small mb-0">
                            <tr>
                                <td class="text-muted" width="120">Loại</td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ $sanpham->loai->ten_loai ?? '—' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Giá bán</td>
                                <td class="fw-bold text-danger fs-6">
                                    {{ number_format($sanpham->gia) }}đ
                                    @if($sanpham->gia_cu)
                                        <span class="text-muted text-decoration-line-through ms-2 fs-6 fw-normal">
                                            {{ number_format($sanpham->gia_cu) }}đ
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tồn kho</td>
                                <td>
                                    @if($sanpham->co_bien_the)
                                        <span class="badge bg-secondary">Theo biến thể</span>
                                    @elseif($sanpham->so_luong == 0)
                                        <span class="badge bg-danger">Hết hàng</span>
                                    @else
                                        <span class="badge bg-success">{{ $sanpham->so_luong }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Biến thể</td>
                                <td>
                                    @if($sanpham->co_bien_the)
                                        <span class="text-primary fw-bold">{{ $sanpham->bienthes->count() }} biến thể</span>
                                    @else
                                        <span class="text-muted">Không có</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Lượt xem</td>
                                <td>{{ number_format($sanpham->luot_xem) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Lượt mua</td>
                                <td class="fw-bold text-danger">{{ number_format($sanpham->luot_mua) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Ngày tạo</td>
                                <td>{{ $sanpham->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Cập nhật</td>
                                <td>{{ $sanpham->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($sanpham->mo_ta)
                <hr>
                <div class="small text-muted mb-1">Mô tả</div>
                <div class="small">{{ $sanpham->mo_ta }}</div>
                @endif
            </div>
        </div>

        {{-- Biến thể --}}
        @if($sanpham->co_bien_the && $sanpham->bienthes->count() > 0)
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-layer-group me-2"></i>Biến Thể
                <span class="badge bg-light text-dark ms-2">{{ $sanpham->bienthes->count() }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tên Biến Thể</th>
                            <th>Mã SKU</th>
                            <th class="text-end">Giá</th>
                            <th class="text-center">Tồn Kho</th>
                            <th class="text-center">Trạng Thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sanpham->bienthes as $bt)
                        <tr class="{{ !$bt->kich_hoat ? 'table-secondary' : '' }}">
                            <td class="text-muted">{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $bt->ten_bienthe }}</td>
                            <td><code>{{ $bt->ma_sku }}</code></td>
                            <td class="text-end text-danger fw-bold">{{ number_format($bt->gia) }}đ</td>
                            <td class="text-center">
                                @if($bt->so_luong == 0)
                                    <span class="badge bg-danger">Hết</span>
                                @elseif($bt->so_luong <= 5)
                                    <span class="badge bg-warning text-dark">{{ $bt->so_luong }}</span>
                                @else
                                    <span class="badge bg-success">{{ $bt->so_luong }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($bt->kich_hoat)
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Ẩn</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Bình luận --}}
        <div class="card">
            <div class="card-header">
                <i class="fas fa-comments me-2"></i>Bình Luận
                <span class="badge bg-light text-dark ms-2">{{ $sanpham->binhluans->count() }}</span>
            </div>
            @if($sanpham->binhluans->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Người dùng</th>
                            <th>Nội dung</th>
                            <th class="text-center">Sao</th>
                            <th>Ngày</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sanpham->binhluans as $bl)
                        <tr>
                            <td class="fw-bold">{{ $bl->user->ho_ten ?? '—' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($bl->noi_dung, 80) }}</td>
                            <td class="text-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $bl->sao_danh_gia ? 'text-warning' : 'text-muted' }}"
                                       style="font-size:0.7rem"></i>
                                @endfor
                            </td>
                            <td>{{ $bl->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body text-center text-muted py-4 small">
                <i class="fas fa-comments fa-2x mb-2 d-block"></i>
                Chưa có bình luận nào.
            </div>
            @endif
        </div>

    </div>

    {{-- CỘT PHẢI --}}
    <div class="col-lg-4">

        {{-- Thống kê nhanh --}}
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-chart-bar me-2"></i>Thống Kê</div>
            <div class="card-body p-3">
                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="p-2 bg-light rounded">
                            <div class="fs-4 fw-bold text-primary">{{ number_format($sanpham->luot_xem) }}</div>
                            <div class="small text-muted">Lượt xem</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded">
                            <div class="fs-4 fw-bold text-danger">{{ number_format($sanpham->luot_mua) }}</div>
                            <div class="small text-muted">Lượt mua</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded">
                            <div class="fs-4 fw-bold text-success">{{ $sanpham->binhluans->count() }}</div>
                            <div class="small text-muted">Bình luận</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded">
                            <div class="fs-4 fw-bold text-warning">
                                {{ $sanpham->binhluans->count() > 0 ? number_format($sanpham->binhluans->avg('sao_danh_gia'), 1) : '—' }}
                            </div>
                            <div class="small text-muted">Sao TB</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hành động nhanh --}}
        <div class="card">
            <div class="card-header"><i class="fas fa-bolt me-2"></i>Hành Động</div>
            <div class="card-body p-3 d-grid gap-2">
                <a href="{{ route('admin.sanpham.edit', $sanpham) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit me-1"></i>Chỉnh Sửa Sản Phẩm
                </a>
                <a href="{{ route('admin.sanpham.index', ['loai_id' => $sanpham->loai_id]) }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-list me-1"></i>SP Cùng Loại
                </a>
                <form action="{{ route('admin.sanpham.destroy', $sanpham) }}" method="POST"
                      onsubmit="return confirm('Xóa sản phẩm này?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm w-100">
                        <i class="fas fa-trash me-1"></i>Xóa Sản Phẩm
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>

@endsection