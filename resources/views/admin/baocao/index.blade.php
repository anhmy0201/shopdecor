@extends('layouts.admin')
@section('title', 'Báo Cáo & Thống Kê')

@section('extra-css')
<style>
/* ── STAT CARDS ── */
.stat-card { border-left: 4px solid; border-radius: 8px; transition: transform .15s, box-shadow .15s; }
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,.12); }
.stat-card.blue   { border-left-color: #3498db; }
.stat-card.green  { border-left-color: #2ecc71; }
.stat-card.orange { border-left-color: #e67e22; }
.stat-card.purple { border-left-color: #9b59b6; }
.stat-card.red    { border-left-color: #e74c3c; }
.stat-card.teal   { border-left-color: #1abc9c; }
.stat-card.indigo { border-left-color: #1a5276; }

/* ── FILTER BAR ── */
.filter-bar { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 10px; padding: 14px 18px; }

/* ── CHART SWITCH TABS ── */
.chart-tab-btn { border: none; background: transparent; color: #6c757d; padding: 4px 14px;
                 border-bottom: 2px solid transparent; font-size:.85rem; cursor:pointer; }
.chart-tab-btn.active { color: #1a5276; border-bottom-color: #1a5276; font-weight:600; }

/* ── EXPORT BTN ── */
.btn-export { background: #1a7a4a; color: #fff; border: none; border-radius: 8px;
              padding: 8px 18px; font-size:.85rem; display:inline-flex; align-items:center; gap:6px;
              text-decoration:none; transition: background .15s; }
.btn-export:hover { background: #145e38; color:#fff; }

/* ── RANK BADGE ── */
.rank-1 { background: #f39c12; }
.rank-2 { background: #95a5a6; }
.rank-3 { background: #cd7f32; }
</style>
@endsection

@section('content')

{{-- ── HEADER ── --}}
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
    <div>
        <h5 class="mb-0 fw-bold">Báo Cáo & Thống Kê</h5>
        <small class="text-muted">Tổng quan hiệu quả kinh doanh</small>
    </div>
    {{-- Nút xuất Excel (giữ nguyên filter hiện tại) --}}
    <a href="{{ route('admin.baocao.export', ['nam' => $namFilter, 'thang' => $thangFilter]) }}"
       class="btn-export">
        <i class="fas fa-file-excel"></i>
        Xuất Excel
        @if($thangFilter)
            (T{{ $thangFilter }}/{{ $namFilter }})
        @else
            (Năm {{ $namFilter }})
        @endif
    </a>
</div>

{{-- ── FILTER BAR ── --}}
<div class="filter-bar mb-4">
    <form method="GET" action="{{ route('admin.baocao.index') }}" class="row g-2 align-items-end">
        <div class="col-auto">
            <label class="form-label small mb-1 fw-semibold">Năm</label>
            <select name="nam" class="form-select form-select-sm" style="width:110px">
                @foreach($danhSachNam as $n)
                    <option value="{{ $n }}" {{ $n == $namFilter ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <label class="form-label small mb-1 fw-semibold">Tháng <span class="text-muted fw-normal">(tuỳ chọn)</span></label>
            <select name="thang" class="form-select form-select-sm" style="width:130px">
                <option value="">— Tất cả —</option>
                @foreach(range(1,12) as $t)
                    <option value="{{ $t }}" {{ $t == $thangFilter ? 'selected' : '' }}>Tháng {{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-filter me-1"></i>Lọc
            </button>
            <a href="{{ route('admin.baocao.index') }}" class="btn btn-outline-secondary btn-sm ms-1">
                <i class="fas fa-redo me-1"></i>Reset
            </a>
        </div>
        @if($thangFilter)
        <div class="col-auto ms-auto">
            <span class="badge bg-primary">
                Đang xem: Tháng {{ $thangFilter }} / {{ $namFilter }}
            </span>
        </div>
        @endif
    </form>
</div>

{{-- ── STAT CARDS ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card stat-card green mb-0 h-100">
            <div class="card-body py-3">
                <div class="small text-muted mb-1">Tổng Doanh Thu</div>
                <div class="fw-bold text-success" style="font-size:1.1rem">
                    {{ number_format($stats['tong_doanh_thu'] / 1000000, 1) }}M
                </div>
                <div class="text-muted" style="font-size:.7rem">tất cả thời gian</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card stat-card indigo mb-0 h-100">
            <div class="card-body py-3">
                <div class="small text-muted mb-1">DT Năm {{ $namFilter }}</div>
                <div class="fw-bold" style="font-size:1.1rem;color:#1a5276">
                    {{ number_format($stats['doanh_thu_nam'] / 1000000, 1) }}M
                </div>
                <div class="text-muted" style="font-size:.7rem">đơn hoàn tất</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card stat-card blue mb-0 h-100">
            <div class="card-body py-3">
                <div class="small text-muted mb-1">Tổng Đơn Hàng</div>
                <div class="fw-bold text-primary" style="font-size:1.1rem">
                    {{ number_format($stats['tong_don_hang']) }}
                </div>
                <div class="text-muted" style="font-size:.7rem">tất cả trạng thái</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card stat-card orange mb-0 h-100">
            <div class="card-body py-3">
                <div class="small text-muted mb-1">TB / Đơn</div>
                <div class="fw-bold text-warning" style="font-size:1.1rem">
                    {{ number_format($stats['trung_binh_don'] / 1000, 0) }}K
                </div>
                <div class="text-muted" style="font-size:.7rem">đơn hoàn tất</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card stat-card teal mb-0 h-100">
            <div class="card-body py-3">
                <div class="small text-muted mb-1">Sản Phẩm</div>
                <div class="fw-bold" style="font-size:1.1rem;color:#1abc9c">
                    {{ number_format($stats['tong_san_pham']) }}
                </div>
                <div class="text-muted" style="font-size:.7rem">trong kho</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card stat-card red mb-0 h-100">
            <div class="card-body py-3">
                <div class="small text-muted mb-1">Hết Hàng</div>
                <div class="fw-bold text-danger" style="font-size:1.1rem">
                    {{ number_format($stats['het_hang']) }}
                </div>
                <div class="text-muted" style="font-size:.7rem">sản phẩm</div>
            </div>
        </div>
    </div>
</div>

{{-- ── BIỂU ĐỒ DOANH THU + TRẠNG THÁI ── --}}
<div class="row g-3 mb-3">

    {{-- Chart doanh thu --}}
    <div class="col-lg-8">
        <div class="card mb-0 h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-chart-bar me-2"></i>
                    @if($thangFilter)
                        Doanh Thu Theo Ngày — Tháng {{ $thangFilter }}/{{ $namFilter }}
                    @else
                        Doanh Thu Theo Tháng — Năm {{ $namFilter }}
                    @endif
                </span>
                {{-- Tab switch bar/line --}}
                <div class="border-bottom-0">
                    <button class="chart-tab-btn active" id="btnBar" onclick="switchChart('bar')">
                        <i class="fas fa-chart-bar"></i> Cột
                    </button>
                    <button class="chart-tab-btn" id="btnLine" onclick="switchChart('line')">
                        <i class="fas fa-chart-line"></i> Đường
                    </button>
                </div>
            </div>
            <div class="card-body p-3">
                <canvas id="chartDoanhThu" height="110"></canvas>
            </div>
        </div>
    </div>

    {{-- Trạng thái đơn --}}
    <div class="col-lg-4">
        <div class="card mb-0 h-100">
            <div class="card-header">
                <i class="fas fa-chart-pie me-2"></i>Trạng Thái Đơn Hàng
            </div>
            <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
                <canvas id="chartTrangThai" height="200" style="max-width:210px"></canvas>
                <div class="mt-3 w-100">
                    @php
                    $ttItems = [
                        ['label'=>'Mới chờ xử lý','color'=>'#f39c12','key'=>'cho_xac_nhan'],
                        ['label'=>'Đang xử lý',   'color'=>'#3498db','key'=>'dang_xu_ly'],
                        ['label'=>'Hoàn tất',      'color'=>'#2ecc71','key'=>'hoan_tat'],
                        ['label'=>'Đã hủy',        'color'=>'#e74c3c','key'=>'da_huy'],
                    ];
                    $tongDon = array_sum($trangThaiDon);
                    @endphp
                    @foreach($ttItems as $item)
                    <div class="d-flex justify-content-between align-items-center small mb-1">
                        <span>
                            <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:{{ $item['color'] }};margin-right:4px"></span>
                            {{ $item['label'] }}
                        </span>
                        <span>
                            <strong>{{ $trangThaiDon[$item['key']] }}</strong>
                            <span class="text-muted ms-1" style="font-size:.75rem">
                                ({{ $tongDon ? round($trangThaiDon[$item['key']] / $tongDon * 100) : 0 }}%)
                            </span>
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── TOP SẢN PHẨM + TOP KHÁCH ── --}}
<div class="row g-3 mb-3">

    {{-- Top sản phẩm --}}
    <div class="col-lg-8">
        <div class="card mb-0">
            <div class="card-header">
                <i class="fas fa-fire me-2 text-danger"></i>Top 10 Sản Phẩm Bán Chạy
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
                                    <span class="badge rank-{{ $i+1 }}">{{ $i+1 }}</span>
                                @else
                                    <span class="text-muted">{{ $i+1 }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.sanpham.show', $sp) }}" class="text-decoration-none fw-bold">
                                    {{ \Illuminate\Support\Str::limit($sp->ten_san_pham, 48) }}
                                </a>
                            </td>
                            <td><span class="badge bg-info text-dark">{{ $sp->loai->ten_loai ?? '—' }}</span></td>
                            <td class="text-end fw-bold text-danger">{{ number_format($sp->luot_mua) }}</td>
                            <td class="text-end text-muted">{{ number_format($sp->luot_xem) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Chưa có dữ liệu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Top khách + danh mục --}}
    <div class="col-lg-4">
        <div class="card mb-0">
            <div class="card-header">
                <i class="fas fa-crown me-2 text-warning"></i>Top 5 Khách Hàng
            </div>
            <div class="card-body p-0">
                @php $colors = ['#f39c12','#95a5a6','#cd7f32','#3498db','#9b59b6']; @endphp
                @forelse($topKhach as $i => $kh)
                <div class="d-flex align-items-center gap-3 px-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                         style="width:34px;height:34px;font-size:.8rem;background:{{ $colors[$i] }}">
                        {{ $i+1 }}
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold small text-truncate">{{ $kh->ho_ten }}</div>
                        <div class="text-muted" style="font-size:.72rem">{{ $kh->donhangs_count }} đơn hàng</div>
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
// ═══ DỮ LIỆU TỪ PHP ═══
const doanhThuFull = @json($doanhThuFull);
const doanhThuNgay = @json($doanhThuNgay);
const thangFilter  = "{{ $thangFilter }}";

// Labels & values
let labels, values, donCounts;
if (thangFilter !== '') {
    // Theo ngày
    labels    = doanhThuNgay.map(d => `Ngày ${d.ngay}`);
    values    = doanhThuNgay.map(d => parseFloat(d.tong) / 1000000);
    donCounts = doanhThuNgay.map(d => parseInt(d.so_don));
} else {
    // Theo tháng
    labels    = doanhThuFull.map(d => `T${d.thang}`);
    values    = doanhThuFull.map(d => parseFloat(d.tong) / 1000000);
    donCounts = doanhThuFull.map(d => parseInt(d.so_don));
}

// ─ Chart doanh thu (switchable) ─
let doanhThuChart;
const dtCtx = document.getElementById('chartDoanhThu').getContext('2d');

function makeDatasets(type) {
    const isLine = type === 'line';
    return [{
        label: 'Doanh thu (triệu đ)',
        data: values,
        backgroundColor: isLine ? 'rgba(26,82,118,.15)' : 'rgba(26,82,118,.75)',
        borderColor: '#1a5276',
        borderWidth: isLine ? 2 : 1,
        borderRadius: isLine ? 0 : 5,
        fill: isLine,
        tension: .35,
        pointBackgroundColor: '#1a5276',
        pointRadius: isLine ? 4 : 0,
        yAxisID: 'y',
    }, {
        label: 'Số đơn',
        data: donCounts,
        backgroundColor: 'rgba(46,204,113,.6)',
        borderColor: '#2ecc71',
        borderWidth: isLine ? 2 : 1,
        borderRadius: isLine ? 0 : 5,
        fill: false,
        tension: .35,
        type: isLine ? 'line' : 'bar',
        pointRadius: isLine ? 4 : 0,
        yAxisID: 'y2',
    }];
}

function buildChart(type) {
    if (doanhThuChart) doanhThuChart.destroy();
    doanhThuChart = new Chart(dtCtx, {
        type: type === 'line' ? 'line' : 'bar',
        data: {
            labels: labels.length ? labels : ['Chưa có dữ liệu'],
            datasets: makeDatasets(type),
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.datasetIndex === 0
                            ? `Doanh thu: ${ctx.parsed.y.toFixed(1)}M đ`
                            : `Số đơn: ${ctx.parsed.y}`,
                    }
                }
            },
            scales: {
                y:  { beginAtZero:true, position:'left',  ticks:{ callback: v => v+'M' }, title:{ display:true, text:'Triệu đ', font:{size:11} } },
                y2: { beginAtZero:true, position:'right', grid:{ drawOnChartArea:false }, title:{ display:true, text:'Đơn', font:{size:11} } },
            }
        }
    });
}

buildChart('bar');

function switchChart(type) {
    buildChart(type);
    document.getElementById('btnBar').classList.toggle('active', type === 'bar');
    document.getElementById('btnLine').classList.toggle('active', type === 'line');
}

// ─ Chart trạng thái ─
new Chart(document.getElementById('chartTrangThai'), {
    type: 'doughnut',
    data: {
        labels: ['Mới','Xử lý','Hoàn tất','Đã hủy'],
        datasets: [{
            data: [{{ $trangThaiDon['cho_xac_nhan'] }},{{ $trangThaiDon['dang_xu_ly'] }},{{ $trangThaiDon['hoan_tat'] }},{{ $trangThaiDon['da_huy'] }}],
            backgroundColor: ['#f39c12','#3498db','#2ecc71','#e74c3c'],
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        cutout: '65%',
    }
});

// ─ Chart danh mục ─
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
        scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
@endsection