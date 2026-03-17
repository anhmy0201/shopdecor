@extends('layouts.admin')
@section('title', 'Chi Tiết Đơn #' . $donhang->id)

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Đơn Hàng #{{ $donhang->id }}</h5>
        <small class="text-muted">
            <a href="{{ route('admin.donhang.index') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
            </a>
        </small>
    </div>
    <div>
        @php
            $badgeDon = match($donhang->trang_thai) {
                0 => 'warning', 1 => 'info', 2 => 'success', 3 => 'danger', default => 'secondary'
            };
            $labelDon = match($donhang->trang_thai) {
                0 => 'Mới', 1 => 'Đang Xử Lý', 2 => 'Hoàn Tất', 3 => 'Đã Hủy', default => '?'
            };
        @endphp
        <span class="badge bg-{{ $badgeDon }} fs-6">{{ $labelDon }}</span>
    </div>
</div>

<div class="row g-3">

    {{-- CỘT TRÁI --}}
    <div class="col-lg-8">

        {{-- Sản phẩm đặt --}}
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-box me-2"></i>Sản Phẩm Đặt
                <span class="badge bg-light text-dark ms-2">{{ $donhang->chitiets->count() }} sản phẩm</span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th width="50">Ảnh</th>
                            <th>Sản Phẩm</th>
                            <th class="text-center">SL</th>
                            <th class="text-end">Đơn Giá</th>
                            <th class="text-end">Thành Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donhang->chitiets as $ct)
                        <tr>
                            <td>
                                @if($ct->hinh_anh)
                                    <img src="{{ asset($ct->hinh_anh) }}" width="44" height="44"
                                         class="rounded" style="object-fit:cover">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                         style="width:44px;height:44px">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $ct->ten_san_pham }}</div>
                                @if($ct->ten_bienthe)
                                    <span class="badge bg-light text-dark border">{{ $ct->ten_bienthe }}</span>
                                @endif
                                @if($ct->ma_sku)
                                    <code class="text-muted ms-1" style="font-size:0.68rem">{{ $ct->ma_sku }}</code>
                                @endif
                            </td>
                            <td class="text-center fw-bold">{{ $ct->so_luong }}</td>
                            <td class="text-end">{{ number_format($ct->gia) }}đ</td>
                            <td class="text-end fw-bold text-danger">
                                {{ number_format($ct->so_luong * $ct->gia) }}đ
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end text-muted">Tổng tiền hàng</td>
                            <td class="text-end fw-bold">{{ number_format($donhang->tong_tien_hang) }}đ</td>
                        </tr>
                        @if($donhang->phi_ship > 0)
                        <tr>
                            <td colspan="4" class="text-end text-muted">Phí ship</td>
                            <td class="text-end">{{ number_format($donhang->phi_ship) }}đ</td>
                        </tr>
                        @endif
                        @if($donhang->so_tien_giam > 0)
                        <tr>
                            <td colspan="4" class="text-end text-success">
                                Giảm giá
                                @if($donhang->magiamgia)
                                    <code class="ms-1">{{ $donhang->magiamgia->ma_giam_gia }}</code>
                                @endif
                            </td>
                            <td class="text-end text-success fw-bold">-{{ number_format($donhang->so_tien_giam) }}đ</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="text-end fw-bold fs-6">Tổng Thanh Toán</td>
                            <td class="text-end fw-bold fs-6 text-danger">{{ number_format($donhang->tong_thanh_toan) }}đ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Thông tin người nhận --}}
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-map-marker-alt me-2"></i>Thông Tin Giao Hàng</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless small mb-0">
                            <tr>
                                <td class="text-muted" width="110">Người nhận</td>
                                <td class="fw-bold">{{ $donhang->ten_nguoi_nhan }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">SĐT</td>
                                <td>{{ $donhang->so_dien_thoai }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email</td>
                                <td>{{ $donhang->email ?? '—' }}</td>
                            </tr>
                            @if($donhang->user)
                            <tr>
                                <td class="text-muted">Tài khoản</td>
                                <td>
                                    <a href="{{ route('admin.nguoidung.show', $donhang->user) }}"
                                       class="text-decoration-none">
                                        {{ $donhang->user->ho_ten }}
                                    </a>
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Địa chỉ giao hàng</div>
                        <div>{{ $donhang->dia_chi_chi_tiet }}</div>
                        <div>{{ $donhang->phuong_xa }}, {{ $donhang->quan_huyen }}</div>
                        <div class="fw-bold">{{ $donhang->tinh_thanh }}</div>
                    </div>
                    @if($donhang->ghi_chu_khach)
                    <div class="col-12">
                        <div class="text-muted small mb-1">Ghi chú của khách</div>
                        <div class="p-2 bg-light rounded small fst-italic">{{ $donhang->ghi_chu_khach }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Timeline --}}
        <div class="card">
            <div class="card-header"><i class="fas fa-history me-2"></i>Timeline Đơn Hàng</div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-2 small">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success d-flex align-items-center justify-content-center"
                             style="width:32px;height:32px;flex-shrink:0">
                            <i class="fas fa-shopping-cart text-white" style="font-size:0.75rem"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Đặt hàng</div>
                            <div class="text-muted">{{ $donhang->ngay_dat->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    @if($donhang->ngay_duyet)
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-info d-flex align-items-center justify-content-center"
                             style="width:32px;height:32px;flex-shrink:0">
                            <i class="fas fa-check text-white" style="font-size:0.75rem"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Đã duyệt / Đang xử lý</div>
                            <div class="text-muted">{{ $donhang->ngay_duyet->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    @endif
                    @if($donhang->ngay_giao)
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success d-flex align-items-center justify-content-center"
                             style="width:32px;height:32px;flex-shrink:0">
                            <i class="fas fa-truck text-white" style="font-size:0.75rem"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Hoàn tất / Đã giao</div>
                            <div class="text-muted">{{ $donhang->ngay_giao->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    @endif
                    @if($donhang->trang_thai === 3)
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center"
                             style="width:32px;height:32px;flex-shrink:0">
                            <i class="fas fa-times text-white" style="font-size:0.75rem"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-danger">Đã hủy</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- CỘT PHẢI --}}
    <div class="col-lg-4">

        {{-- Cập nhật trạng thái --}}
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-edit me-2"></i>Cập Nhật Trạng Thái</div>
            <div class="card-body p-3">
                <form action="{{ route('admin.donhang.cap-nhat-trang-thai', $donhang) }}"
                      method="POST">
                    @csrf @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Trạng Thái Đơn</label>
                        <select name="trang_thai" class="form-select form-select-sm">
                            <option value="0" {{ $donhang->trang_thai === 0 ? 'selected' : '' }}>🟡 Mới</option>
                            <option value="1" {{ $donhang->trang_thai === 1 ? 'selected' : '' }}>🔵 Đang Xử Lý</option>
                            <option value="2" {{ $donhang->trang_thai === 2 ? 'selected' : '' }}>🟢 Hoàn Tất</option>
                            <option value="3" {{ $donhang->trang_thai === 3 ? 'selected' : '' }}>🔴 Đã Hủy</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Thanh Toán</label>
                        <select name="trang_thai_thanhtoan" class="form-select form-select-sm">
                            <option value="chua_thanh_toan" {{ $donhang->trang_thai_thanhtoan === 'chua_thanh_toan' ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="da_thanh_toan"   {{ $donhang->trang_thai_thanhtoan === 'da_thanh_toan'   ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="hoan_tien"       {{ $donhang->trang_thai_thanhtoan === 'hoan_tien'       ? 'selected' : '' }}>Hoàn tiền</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Vận Chuyển</label>
                        <select name="trang_thai_van_chuyen" class="form-select form-select-sm">
                            <option value="cho_lay_hang"    {{ $donhang->trang_thai_van_chuyen === 'cho_lay_hang'    ? 'selected' : '' }}>Chờ lấy hàng</option>
                            <option value="dang_van_chuyen" {{ $donhang->trang_thai_van_chuyen === 'dang_van_chuyen' ? 'selected' : '' }}>Đang vận chuyển</option>
                            <option value="da_giao"         {{ $donhang->trang_thai_van_chuyen === 'da_giao'         ? 'selected' : '' }}>Đã giao</option>
                            <option value="that_bai"        {{ $donhang->trang_thai_van_chuyen === 'that_bai'        ? 'selected' : '' }}>Giao thất bại</option>
                            <option value="hoan_hang"       {{ $donhang->trang_thai_van_chuyen === 'hoan_hang'       ? 'selected' : '' }}>Hoàn hàng</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Mã Vận Đơn</label>
                        <input type="text" name="ma_van_don" class="form-control form-control-sm"
                               value="{{ $donhang->ma_van_don }}" placeholder="VD: VN123456789">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Ghi Chú Admin</label>
                        <textarea name="ghi_chu_admin" class="form-control form-control-sm"
                                  rows="3" placeholder="Ghi chú nội bộ...">{{ $donhang->ghi_chu_admin }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-save me-1"></i>Lưu Thay Đổi
                    </button>
                </form>
            </div>
        </div>

        {{-- Thông tin thanh toán --}}
        <div class="card">
            <div class="card-header"><i class="fas fa-credit-card me-2"></i>Thanh Toán</div>
            <div class="card-body p-3 small">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Phương thức</span>
                    <strong class="text-uppercase">{{ $donhang->phuong_thuc_thanhtoan }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Trạng thái TT</span>
                    @php
                        $ttColor = match($donhang->trang_thai_thanhtoan) {
                            'da_thanh_toan' => 'success', 'hoan_tien' => 'warning', default => 'secondary'
                        };
                        $ttLabel = match($donhang->trang_thai_thanhtoan) {
                            'da_thanh_toan' => 'Đã thanh toán', 'hoan_tien' => 'Hoàn tiền', default => 'Chưa thanh toán'
                        };
                    @endphp
                    <span class="badge bg-{{ $ttColor }}">{{ $ttLabel }}</span>
                </div>
                @if($donhang->ma_giao_dich)
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Mã GD</span>
                    <code>{{ $donhang->ma_giao_dich }}</code>
                </div>
                @endif
                @if($donhang->ma_van_don)
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Mã vận đơn</span>
                    <code>{{ $donhang->ma_van_don }}</code>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection