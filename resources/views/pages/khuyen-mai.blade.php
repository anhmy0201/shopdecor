@extends('layouts.app')
@section('title', 'Khuyến Mãi & Mã Giảm Giá')

@section('extra-css')
<style>
@import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap');
.km-page, .km-page *:not(i):not(.fas):not(.far):not([class*="fa-"]) {
    font-family: 'Be Vietnam Pro', Arial, sans-serif !important;
}
.km-page i, .km-page [class*="fa-"] {
    font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
}

/* HERO */
.km-hero {
    background: linear-gradient(135deg, #1a5276 0%, #154360 40%, #0e2f45 100%);
    padding: 32px 0 0;
    position: relative;
    overflow: hidden;
}
.km-hero::before {
    content:'';
    position:absolute;inset:0;
    background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");
}
.km-hero-content { position:relative;z-index:1; }
.km-hero-title { font-size:2rem;font-weight:800;color:#fff;letter-spacing:-0.5px;margin-bottom:6px; }
.km-hero-title span { color:#f39c12; }
.km-hero-sub { color:rgba(255,255,255,0.75);font-size:0.88rem; }
.km-filter-tabs { display:flex;gap:8px;margin-top:20px; }
.km-tab {
    padding:9px 20px;border-radius:20px 20px 0 0;font-size:0.82rem;font-weight:600;
    cursor:pointer;border:none;background:rgba(255,255,255,0.12);color:rgba(255,255,255,0.8);transition:all 0.2s;
}
.km-tab:hover { background:rgba(255,255,255,0.2);color:#fff; }
.km-tab.active { background:#fff;color:#1a5276; }

/* MAIN */
.km-main { background:#f5f7fa;padding:24px 0 40px; }
.km-stats {
    background:#fff;border-radius:10px;padding:14px 20px;margin-bottom:20px;
    display:flex;align-items:center;gap:24px;box-shadow:0 1px 6px rgba(0,0,0,0.07);flex-wrap:wrap;
}
.km-stat-item { display:flex;align-items:center;gap:8px;font-size:0.82rem; }
.km-stat-icon { width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:0.9rem;flex-shrink:0; }
.km-stat-icon.blue{background:#eaf4fb;color:#1a5276;} .km-stat-icon.red{background:#fdf2f0;color:#e74c3c;} .km-stat-icon.green{background:#eafaf1;color:#27ae60;}
.km-stat-label{color:#666;} .km-stat-val{font-weight:700;color:#1a5276;font-size:0.95rem;}

/* VOUCHER CARD */
.voucher-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(310px,1fr));gap:14px; }
.vc {
    background:#fff;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);
    display:flex;overflow:visible;position:relative;transition:transform 0.2s,box-shadow 0.2s;
}
.vc:hover { transform:translateY(-3px);box-shadow:0 8px 24px rgba(26,82,118,0.14); }
.vc::before,.vc::after { content:'';position:absolute;top:50%;transform:translateY(-50%);width:18px;height:18px;background:#f5f7fa;border-radius:50%;z-index:2; }
.vc::before { left:86px; } .vc::after { right:-9px; }

.vc-left {
    width:86px;flex-shrink:0;border-radius:10px 0 0 10px;
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    padding:16px 8px;position:relative;gap:6px;
}
.vc-left::after { content:'';position:absolute;right:0;top:14px;bottom:14px;width:1px;border-right:2px dashed rgba(255,255,255,0.35); }
.vc-left.phan-tram { background:linear-gradient(160deg,#1a5276,#2980b9); }
.vc-left.co-dinh   { background:linear-gradient(160deg,#c0392b,#e74c3c); }
.vc-left-icon { width:36px;height:36px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1rem;color:#fff; }
.vc-left-val { font-size:1.2rem;font-weight:800;color:#fff;line-height:1;text-align:center; }
.vc-left-val small { font-size:0.65rem;font-weight:600;display:block;opacity:0.9; }
.vc-left-type { font-size:0.6rem;font-weight:700;color:rgba(255,255,255,0.85);text-transform:uppercase;letter-spacing:0.5px;text-align:center; }

.vc-right { flex:1;padding:14px 16px 12px;min-width:0; }
.vc-code-row { display:flex;align-items:center;gap:8px;margin-bottom:8px; }
.vc-code {
    font-family:'Courier New',monospace;font-size:1.05rem;font-weight:700;color:#1a5276;
    letter-spacing:2px;background:#f0f6fb;border:1.5px dashed #1a5276;border-radius:4px;
    padding:3px 10px;flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
}
.vc-left.co-dinh + .vc-right .vc-code { color:#c0392b;background:#fdf0ee;border-color:#c0392b; }
.btn-sao-chep {
    flex-shrink:0;padding:5px 12px;font-size:0.75rem;font-weight:700;border:none;border-radius:4px;
    cursor:pointer;transition:all 0.2s;background:#1a5276;color:#fff;white-space:nowrap;
}
.btn-sao-chep:hover{background:#154360;} .btn-sao-chep.copied{background:#27ae60;} .btn-sao-chep.red-btn{background:#e74c3c;} .btn-sao-chep.red-btn:hover{background:#c0392b;}
.vc-desc { font-size:0.78rem;color:#555;margin-bottom:7px;line-height:1.45; }
.vc-meta { display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:8px; }
.vc-badge { font-size:0.68rem;font-weight:600;padding:2px 8px;border-radius:30px; }
.vc-badge.blue{background:#eaf4fb;color:#1a5276;} .vc-badge.red{background:#fdf0ee;color:#c0392b;} .vc-badge.green{background:#eafaf1;color:#1e8449;} .vc-badge.orange{background:#fef9ec;color:#d68910;}
.vc-progress-wrap { margin-top:4px; }
.vc-progress-label { display:flex;justify-content:space-between;font-size:0.7rem;color:#888;margin-bottom:4px; }
.vc-progress-bar { height:5px;background:#e9ecef;border-radius:99px;overflow:hidden; }
.vc-progress-fill { height:100%;border-radius:99px;background:linear-gradient(90deg,#1a5276,#3498db);transition:width 0.6s ease; }
.vc-progress-fill.warning { background:linear-gradient(90deg,#e67e22,#e74c3c); }
.vc-footer { display:flex;align-items:center;justify-content:space-between;margin-top:8px;padding-top:8px;border-top:1px dashed #e9ecef; }
.vc-expire { font-size:0.7rem; }
.vc-expire.urgent{color:#e74c3c;font-weight:600;} .vc-expire.normal{color:#888;}
.vc-stamp { position:absolute;top:8px;right:12px;font-size:0.62rem;font-weight:700;background:#e74c3c;color:#fff;padding:2px 7px;border-radius:30px;text-transform:uppercase;letter-spacing:0.5px; }
.btn-dung-ngay { font-size:0.72rem;font-weight:700;color:#1a5276;text-decoration:none;border:1.5px solid #1a5276;border-radius:30px;padding:2px 10px;transition:all 0.2s;white-space:nowrap; }
.btn-dung-ngay:hover { background:#1a5276;color:#fff; }

/* GUIDE */
.km-guide { background:#fff;border-radius:10px;padding:20px 24px;margin-top:24px;box-shadow:0 1px 6px rgba(0,0,0,0.07); }
.km-guide-title { font-size:0.92rem;font-weight:700;color:#1a5276;margin-bottom:16px; }
.km-step { display:flex;align-items:flex-start;gap:14px; }
.km-step-num { width:30px;height:30px;border-radius:50%;background:#1a5276;color:#fff;font-size:0.82rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px; }
.km-step-body { font-size:0.82rem; }
.km-step-body strong { display:block;color:#222;font-weight:700;margin-bottom:2px; }
.km-step-body span { color:#777;line-height:1.5; }

@media(max-width:576px){ .voucher-grid{grid-template-columns:1fr;} .km-hero-title{font-size:1.4rem;} }
</style>
@endsection

@section('content')
<div class="km-page">

<div class="km-hero">
  <div class="container km-hero-content">
    <div class="d-flex align-items-center gap-3 mb-2">
      <span style="background:rgba(255,255,255,0.15);border-radius:6px;padding:6px 12px;font-size:0.75rem;font-weight:700;color:#f39c12;letter-spacing:1px;text-transform:uppercase">
        🎁 Ưu đãi đặc biệt
      </span>
    </div>
    <div class="km-hero-title">Mã Giảm Giá <span>Hấp Dẫn</span> Dành Cho Bạn</div>
    <div class="km-hero-sub">
      <i class="fas fa-mouse-pointer me-1"></i>
      Nhấn <strong style="color:#fff">Sao chép</strong> để lấy mã, dán vào trang thanh toán để được giảm giá
    </div>
    <div class="km-filter-tabs">
      <button class="km-tab active" onclick="locMa('tat-ca',this)"><i class="fas fa-th me-1"></i>Tất cả</button>
      <button class="km-tab" onclick="locMa('phan-tram',this)"><i class="fas fa-percent me-1"></i>Theo %</button>
      <button class="km-tab" onclick="locMa('co-dinh',this)"><i class="fas fa-tag me-1"></i>Cố định</button>
      <button class="km-tab" onclick="locMa('sap-het',this)"><i class="fas fa-fire me-1" style="color:#f39c12"></i>Sắp hết</button>
    </div>
  </div>
</div>

<div class="km-main">
  <div class="container">

    @if($stats['tong'] > 0)

    <div class="km-stats">
      <div class="km-stat-item">
        <div class="km-stat-icon blue"><i class="fas fa-ticket-alt"></i></div>
        <div><div class="km-stat-label">Tổng mã</div><div class="km-stat-val">{{ $stats['tong'] }} mã</div></div>
      </div>
      <div class="km-stat-item">
        <div class="km-stat-icon red"><i class="fas fa-percent"></i></div>
        <div><div class="km-stat-label">Giảm theo %</div><div class="km-stat-val">{{ $stats['phan_tram'] }} mã</div></div>
      </div>
      <div class="km-stat-item">
        <div class="km-stat-icon green"><i class="fas fa-tag"></i></div>
        <div><div class="km-stat-label">Giảm cố định</div><div class="km-stat-val">{{ $stats['co_dinh'] }} mã</div></div>
      </div>
      <div class="ms-auto text-muted" style="font-size:0.75rem"><i class="fas fa-sync-alt me-1"></i>Cập nhật liên tục</div>
    </div>

    <div class="voucher-grid" id="voucherGrid">
      @foreach($magiamgias as $ma)
      @php
        $phanTram = $ma->so_luong ? round($ma->da_su_dung / $ma->so_luong * 100) : 0;
        $ganHet   = $ma->so_luong && ($ma->so_luong - $ma->da_su_dung) <= 5;
        $kieuClass = $ma->kieu_giam === 'phan_tram' ? 'phan-tram' : 'co-dinh';
        $conLai   = $ma->ket_thuc ? now()->diffInDays($ma->ket_thuc, false) : null;
      @endphp

      <div class="vc" data-kieu="{{ $ma->kieu_giam }}" data-sap-het="{{ $ganHet ? '1' : '0' }}">
        @if($ganHet)
          <span class="vc-stamp">🔥 Sắp hết</span>
        @elseif($conLai !== null && $conLai <= 3)
          <span class="vc-stamp" style="background:#e67e22">⏰ Sắp HH</span>
        @endif

        <div class="vc-left {{ $kieuClass }}">
          <div class="vc-left-icon"><i class="{{ $ma->kieu_giam === 'phan_tram' ? 'fas fa-percent' : 'fas fa-tag' }}"></i></div>
          <div class="vc-left-val">
            @if($ma->kieu_giam === 'phan_tram')
              {{ $ma->gia_tri }}%<small>GIẢM</small>
            @else
              {{ $ma->gia_tri >= 1000 ? number_format($ma->gia_tri/1000).'K' : number_format($ma->gia_tri) }}<small>GIẢM</small>
            @endif
          </div>
          <div class="vc-left-type">{{ $ma->kieu_giam === 'phan_tram' ? 'Phần trăm' : 'Cố định' }}</div>
        </div>

        <div class="vc-right">
          <div class="vc-code-row">
            <span class="vc-code" id="ma-{{ $ma->id }}">{{ $ma->ma_code }}</span>
            <button class="btn-sao-chep {{ $ma->kieu_giam === 'co_dinh' ? 'red-btn' : '' }}"
                    id="btn-{{ $ma->id }}" onclick="saoChepMa('{{ $ma->ma_code }}',{{ $ma->id }})">
              <i class="fas fa-copy me-1"></i>Sao chép
            </button>
          </div>

          @if($ma->mo_ta)<div class="vc-desc">{{ $ma->mo_ta }}</div>@endif

          <div class="vc-meta">
            @if($ma->don_hang_toi_thieu > 0)
              <span class="vc-badge blue"><i class="fas fa-shopping-cart me-1"></i>Đơn từ {{ number_format($ma->don_hang_toi_thieu) }}đ</span>
            @else
              <span class="vc-badge green"><i class="fas fa-check me-1"></i>Không giới hạn đơn</span>
            @endif
            @if($ma->kieu_giam === 'phan_tram' && $ma->giam_toi_da)
              <span class="vc-badge orange">Tối đa {{ number_format($ma->giam_toi_da) }}đ</span>
            @endif
          </div>

          @if($ma->so_luong)
            <div class="vc-progress-wrap">
              <div class="vc-progress-label">
                <span>Đã dùng {{ $ma->da_su_dung }}/{{ $ma->so_luong }}</span>
                <span>Còn {{ $ma->so_luong - $ma->da_su_dung }}</span>
              </div>
              <div class="vc-progress-bar">
                <div class="vc-progress-fill {{ $phanTram >= 80 ? 'warning' : '' }}"
                     data-target="{{ $phanTram }}" style="width:0%"></div>
              </div>
            </div>
          @else
            <div style="font-size:0.72rem;color:#888;margin-top:4px"><i class="fas fa-infinity me-1"></i>Không giới hạn lượt</div>
          @endif

          <div class="vc-footer">
            <div class="vc-expire {{ ($conLai !== null && $conLai <= 3) ? 'urgent' : 'normal' }}">
              @if($ma->ket_thuc)
                @if($conLai !== null && $conLai <= 0)
                  <i class="fas fa-times-circle me-1"></i>Đã hết hạn
                @elseif($conLai !== null && $conLai <= 3)
                  <i class="fas fa-clock me-1"></i>Còn {{ $conLai }} ngày!
                @else
                  <i class="fas fa-calendar me-1"></i>HSD: {{ $ma->ket_thuc->format('d/m/Y') }}
                @endif
              @else
                <i class="fas fa-calendar-check me-1"></i>Vĩnh viễn
              @endif
            </div>
            <a href="{{ route('thanh-toan') }}" class="btn-dung-ngay">Dùng ngay →</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    @if($magiamgias->hasPages())
    <div class="d-flex justify-content-center mt-4">
      {{ $magiamgias->links() }}
    </div>
    @endif

    <div id="emptyFilter" style="display:none" class="text-center py-5 text-muted">
      <div style="font-size:2.5rem;margin-bottom:12px">🔍</div>
      <div class="fw-semibold">Không có mã nào thuộc loại này</div>
    </div>

    <div class="km-guide">
      <div class="km-guide-title"><i class="fas fa-lightbulb me-2" style="color:#f39c12"></i>Cách sử dụng mã giảm giá</div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="km-step">
            <div class="km-step-num">1</div>
            <div class="km-step-body"><strong>Sao chép mã</strong><span>Nhấn nút <b>Sao chép</b> trên voucher muốn dùng.</span></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="km-step">
            <div class="km-step-num">2</div>
            <div class="km-step-body"><strong>Thêm vào giỏ hàng</strong><span>Chọn sản phẩm, thêm vào giỏ và tiến hành thanh toán.</span></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="km-step">
            <div class="km-step-num">3</div>
            <div class="km-step-body"><strong>Áp dụng mã</strong><span>Nhấn <b>Chọn mã giảm giá</b> ở trang thanh toán và chọn mã.</span></div>
          </div>
        </div>
      </div>
    </div>

    @else
    <div class="text-center py-5">
      <div style="width:72px;height:72px;background:#eaf4fb;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.8rem;color:#1a5276;margin:0 auto 16px">
        <i class="fas fa-tags"></i>
      </div>
      <h5 class="fw-bold text-dark mb-2">Chưa có mã giảm giá nào</h5>
      <p class="text-muted small mb-4">Hãy quay lại sau để xem các chương trình khuyến mãi mới nhất!</p>
      <a href="{{ url('/') }}" class="btn btn-primary px-4" style="background:#1a5276;border-color:#1a5276;border-radius:30px">
        <i class="fas fa-shopping-bag me-2"></i>Khám phá sản phẩm
      </a>
    </div>
    @endif

  </div>
</div>
</div>
@endsection

@section('extra-js')
<script>
function saoChepMa(ma, id) {
    navigator.clipboard.writeText(ma).then(() => {
        capNhatNut(id, true);
        setTimeout(() => capNhatNut(id, false), 2200);
    }).catch(() => {
        const el = document.getElementById('ma-' + id);
        const range = document.createRange(); range.selectNode(el);
        window.getSelection().removeAllRanges(); window.getSelection().addRange(range);
        document.execCommand('copy'); window.getSelection().removeAllRanges();
        capNhatNut(id, true); setTimeout(() => capNhatNut(id, false), 2200);
    });
}
function capNhatNut(id, copied) {
    const btn = document.getElementById('btn-' + id);
    if (!btn) return;
    if (copied) { btn.classList.add('copied'); btn.innerHTML = '<i class="fas fa-check me-1"></i>Đã lấy!'; }
    else { btn.classList.remove('copied'); btn.innerHTML = '<i class="fas fa-copy me-1"></i>Sao chép'; }
}
function locMa(loai, tabEl) {
    document.querySelectorAll('.km-tab').forEach(t => t.classList.remove('active'));
    tabEl.classList.add('active');
    const cards = document.querySelectorAll('.vc');
    let visible = 0;
    cards.forEach(c => {
        let show = loai === 'tat-ca' || (loai === 'phan-tram' && c.dataset.kieu === 'phan_tram')
            || (loai === 'co-dinh' && c.dataset.kieu === 'co_dinh')
            || (loai === 'sap-het' && c.dataset.sapHet === '1');
        c.style.display = show ? '' : 'none'; if (show) visible++;
    });
    document.getElementById('emptyFilter').style.display = visible === 0 ? 'block' : 'none';
}
const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.style.width = e.target.dataset.target + '%'; } });
}, { threshold: 0.2 });
document.querySelectorAll('.vc-progress-fill').forEach(bar => obs.observe(bar));
</script>
@endsection