@extends('layouts.admin')
@section('title', 'Báo Cáo & Thống Kê')

@section('extra-css')
<style>
.stat-card { border-left: 4px solid; border-radius: 6px; }
.stat-card.blue   { border-left-color: #3498db; }
.stat-card.green  { border-left-color: #2ecc71; }
.stat-card.orange { border-left-color: #e67e22; }
.stat-card.purple { border-left-color: #9b59b6; }
.stat-card.red    { border-left-color: #e74c3c; }
.stat-card.teal   { border-left-color: #1abc9c; }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Báo Cáo & Thống Kê</h5>
        <small class="text-muted">Tổng quan hiệu quả kinh doanh</small>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card green mb-0 h-100">
            <div class="card-body py-3">
                <div class="small text-muted mb-1">Tổng Doanh Thu</div>
                <div class="fw-bold text-success" style="font-size:1.1rem">
                    {{ number_format($stats['tong_doanh_thu'] / 1000000, 1) }}M
                </div>
                <div class="text-muted" style="font-size:0.7rem">đơn hoàn tất</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card blue mb-0 h-100">
            <div class="card-body py-3">
                <div class="small text-muted mb-1">Tổng Đơn Hàng</div>
                <div class="fw-bold text-primary" style="font-size:1.1rem">
                    {{ number_format($stats['tong_don_hang']) }}
                </div>
                <div class="text-muted" style="font-size:0.7rem">tất cả trạng thái</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card orange mb-0 h-100">
            <div class="card-body py-3">
                <div class="small text-muted mb-1">TB / Đơn</div>
                <div class="fw-bold text-warning" style="font-size:1.1rem">
                    {{ number_format($stats['trung_binh_don'] / 1000, 0) }}K
                </div>
                <div class="text-muted" style="font-size:0.7rem">đơn hoàn tất</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card purple mb-0 h-100">
            <div class="card-body py-3">
                <div class="small text-muted mb-1">Khách Hàng</div>
                <div class="fw-bold text-purple" style="font-size:1.1rem;color:#9b59b6">
                    {{ number_format($stats['tong_khach']) }}
                </div>
                <div class="text-muted" style="font-size:0.7rem">tài khoản</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card teal mb-0 h-100">
            <div class="card-body py-3">
                <div class="small text-muted mb-1">Sản Phẩm</div>
                <div class="fw-bold" style="font-size:1.1rem;color:#1abc9c">
                    {{ number_format($stats['tong_san_pham']) }}
                </div>
                <div class="text-muted" style="font-size:0.7rem">trong kho</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card red mb-0 h-100">
            <div class="card-body py-3">
                <div class="small text-muted mb-1">Hết Hàng</div>
                <div class="fw-bold text-danger" style="font-size:1.1rem">
                    {{ number_format($stats['het_hang']) }}
                </div>
                <div class="text-muted" style="font-size:0.7rem">sản phẩm</div>
            </div>
        </div>
    </div>
</div>

{{-- BIỂU ĐỒ DOANH THU + TRẠNG THÁI ĐƠN --}}
<div class="row g-3 mb-3">

    {{-- Doanh thu 12 tháng --}}
    <div class="col-lg-8">
        <div class="card mb-0 h-100">
            <div class="card-header">
                <i class="fas fa-chart-line me-2"></i>Doanh Thu 12 Tháng Gần Nhất
            </div>
            <div class="card-body p-3">
                <canvas id="chartDoanhThu" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Trạng thái đơn hàng --}}
    <div class="col-lg-4">
        <div class="card mb-0 h-100">
            <div class="card-header">
                <i class="fas fa-chart-pie me-2"></i>Trạng Thái Đơn Hàng
            </div>
            <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
                <canvas id="chartTrangThai" height="200" style="max-width:220px"></canvas>
                <div class="mt-3 w-100">
                    <div class="d-flex justify-content-between small mb-1">
                        <span><span class="badge" style="background:#f39c12">●</span> Mới chờ xử lý</span>
                        <strong>{{ $trangThaiDon['cho_xac_nhan'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span><span class="badge" style="background:#3498db">●</span> Đang xử lý</span>
                        <strong>{{ $trangThaiDon['dang_xu_ly'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span><span class="badge" style="background:#2ecc71">●</span> Hoàn tất</span>
                        <strong>{{ $trangThaiDon['hoan_tat'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span><span class="badge" style="background:#e74c3c">●</span> Đã hủy</span>
                        <strong>{{ $trangThaiDon['da_huy'] }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- TOP SẢN PHẨM + TOP KHÁCH --}}
<div class="row g-3 mb-3">

    {{-- Top sản phẩm bán chạy --}}
    <div class="col-lg-8">
        <div class="card mb-0">
            <div class="card-header">
                <i class="fas fa-fire me-2"></i>Top 10 Sản Phẩm Bán Chạy
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th width="40">#</th>
                            <th>Sản Phẩm</th>
                            <th>Loại</th>
                            <th class="text-end">Lượt Mua</th>
                            <th class="text-end">Lượt Xem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSanpham as $i => $sp)
                        <tr>
                            <td>
                                @if($i < 3)
                                    <span class="badge" style="background:{{ ['#f39c12','#95a5a6','#cd7f32'][$i] }}">
                                        {{ $i + 1 }}
                                    </span>
                                @else
                                    <span class="text-muted">{{ $i + 1 }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.sanpham.show', $sp) }}" class="text-decoration-none fw-bold">
                                    {{ \Illuminate\Support\Str::limit($sp->ten_san_pham, 45) }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $sp->loai->ten_loai ?? '—' }}</span>
                            </td>
                            <td class="text-end fw-bold text-danger">{{ number_format($sp->luot_mua) }}</td>
                            <td class="text-end text-muted">{{ number_format($sp->luot_xem) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Chưa có dữ liệu.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Top khách hàng --}}
    <div class="col-lg-4">
        <div class="card mb-0">
            <div class="card-header">
                <i class="fas fa-crown me-2"></i>Top 5 Khách Hàng
            </div>
            <div class="card-body p-0">
                @forelse($topKhach as $i => $kh)
                <div class="d-flex align-items-center gap-3 px-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                         style="width:34px;height:34px;font-size:0.8rem;background:{{ ['#f39c12','#95a5a6','#cd7f32','#3498db','#9b59b6'][$i] }}">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold small text-truncate">{{ $kh->ho_ten }}</div>
                        <div class="text-muted" style="font-size:0.72rem">
                            {{ $kh->donhangs_count }} đơn hàng
                        </div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fw-bold text-danger small">
                            {{ number_format(($kh->donhangs_sum_tong_thanh_toan ?? 0) / 1000) }}K
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4 small">Chưa có dữ liệu.</div>
                @endforelse
            </div>
        </div>

        {{-- Top danh mục --}}
        <div class="card mb-0 mt-3">
            <div class="card-header">
                <i class="fas fa-layer-group me-2"></i>Top Danh Mục
            </div>
            <div class="card-body p-3">
                <canvas id="chartDanhMuc" height="180"></canvas>
            </div>
        </div>
    </div>

</div>

@endsection

@section('extra-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── DOANH THU 12 THÁNG ──
const doanhThuData = @json($doanhThuThang);
const labelsDoanhThu = doanhThuData.map(d => `T${d.thang}/${d.nam}`);
const valuesDoanhThu = doanhThuData.map(d => parseFloat(d.tong) / 1000000);

new Chart(document.getElementById('chartDoanhThu'), {
    type: 'bar',
    data: {
        labels: labelsDoanhThu.length ? labelsDoanhThu : ['Chưa có dữ liệu'],
        datasets: [{
            label: 'Doanh thu (triệu đ)',
            data: valuesDoanhThu.length ? valuesDoanhThu : [0],
            backgroundColor: 'rgba(26, 82, 118, 0.7)',
            borderColor: '#1a5276',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => `${ctx.parsed.y.toFixed(1)} triệu đ`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => v + 'M' }
            }
        }
    }
});

// ── TRẠNG THÁI ĐƠN ──
new Chart(document.getElementById('chartTrangThai'), {
    type: 'doughnut',
    data: {
        labels: ['Mới', 'Xử lý', 'Hoàn tất', 'Đã hủy'],
        datasets: [{
            data: [
                {{ $trangThaiDon['cho_xac_nhan'] }},
                {{ $trangThaiDon['dang_xu_ly'] }},
                {{ $trangThaiDon['hoan_tat'] }},
                {{ $trangThaiDon['da_huy'] }},
            ],
            backgroundColor: ['#f39c12','#3498db','#2ecc71','#e74c3c'],
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        cutout: '65%',
    }
});

// ── TOP DANH MỤC ──
const danhMucData = @json($topDanhMuc);
new Chart(document.getElementById('chartDanhMuc'), {
    type: 'bar',
    data: {
        labels: danhMucData.map(d => d.ten_loai),
        datasets: [{
            label: 'Số sản phẩm',
            data: danhMucData.map(d => d.sanphams_count),
            backgroundColor: ['#3498db','#2ecc71','#e67e22','#9b59b6','#1abc9c'],
            borderRadius: 4,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});
</script>
@endsection