<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — ShopDecor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-w: 240px; }
        body { background: #f4f6f9; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            background: #1a5276;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar-brand {
            display: block;
            padding: 18px 20px;
            color: #fff;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand:hover { color: #fff; }
        .sidebar-section {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.35);
            padding: 14px 20px 4px;
        }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 10px 20px;
            color: rgba(255,255,255,0.72);
            text-decoration: none;
            font-size: 0.875rem;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: #e74c3c;
            padding-left: 24px;
        }
        .sidebar-menu a .fa-fw { width: 15px; }

        /* ── MAIN ── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 11px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* ── CARD ── */
        .card {
            border: none;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            border-radius: 6px;
            margin-bottom: 24px;
        }
        .card-header {
            background: #1a5276;
            color: #fff;
            border: none;
            border-radius: 6px 6px 0 0 !important;
            padding: 11px 18px;
            font-weight: 600;
            font-size: 0.88rem;
        }

        /* ── PAGE BODY ── */
        .page-body { padding: 24px; flex: 1; }

        /* ── FLASH ── */
        .flash-wrap {
            position: fixed;
            top: 68px; right: 20px;
            z-index: 9999;
            min-width: 300px;
        }

        /* ── BADGE QUYỀN HẠN ── */
        .badge-role-superadmin { background: #6c3483; }
        .badge-role-giamdoc    { background: #c0392b; }
        .badge-role-ketoan     { background: #2980b9; }
        .badge-role-staff      { background: #27ae60; }

        @media(max-width:768px){
            .sidebar { width:100%; height:auto; position:relative; }
            .main-content { margin-left:0; }
        }

        /* ── REALTIME TOAST ── */
        @keyframes rtSlideIn {
            from { opacity:0; transform:translateX(40px); }
            to   { opacity:1; transform:translateX(0); }
        }
        @keyframes rtFadeOut {
            from { opacity:1; }
            to   { opacity:0; transform:translateX(40px); }
        }
        #rt-toast-wrap {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 99999;
            display: flex;
            flex-direction: column-reverse;
            gap: 10px;
            pointer-events: none;
        }
        .rt-toast {
            background: #fff;
            border-left: 4px solid #e74c3c;
            border-radius: 6px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            padding: 14px 16px;
            min-width: 300px;
            max-width: 360px;
            pointer-events: all;
            animation: rtSlideIn .3s ease;
            cursor: pointer;
        }
        .rt-toast:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.2); }
        .rt-toast .rt-title {
            font-weight: 700;
            font-size: 0.88rem;
            color: #1a5276;
            margin-bottom: 4px;
        }
        .rt-toast .rt-body { font-size: 0.82rem; color: #333; line-height: 1.6; }
        .rt-toast .rt-meta { font-size: 0.75rem; color: #888; margin-top: 6px; }
        .rt-toast .rt-close {
            float: right;
            background: none;
            border: none;
            font-size: 1.1rem;
            color: #aaa;
            cursor: pointer;
            line-height: 1;
            padding: 0;
            margin-left: 8px;
        }
        .rt-toast .rt-close:hover { color: #e74c3c; }
        .rt-toast .rt-link {
            float: right;
            color: #1a5276;
            font-weight: 600;
            font-size: 0.75rem;
            text-decoration: none;
        }
        .rt-toast .rt-link:hover { color: #e74c3c; }
    </style>
    @yield('extra-css')
</head>
<body>

{{-- ── SIDEBAR ── --}}
<div class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
        <div class="fw-bold fs-6"><i class="fas fa-store me-2"></i>ShopDecor</div>
        <div style="font-size:0.72rem;opacity:0.55;margin-top:2px">Quản trị hệ thống</div>
    </a>

    <ul class="sidebar-menu">

        {{-- Dashboard — tất cả --}}
        <li>
            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt fa-fw"></i> Dashboard
            </a>
        </li>

        {{-- ── SẢN PHẨM ── --}}
        <li><div class="sidebar-section">Sản Phẩm</div></li>
        <li>
            <a href="{{ route('admin.loai-sanpham.index') }}"
               class="{{ request()->routeIs('admin.loai-sanpham.*') ? 'active' : '' }}">
                <i class="fas fa-list fa-fw"></i> Loại Sản Phẩm
            </a>
        </li>
        <li>
            <a href="{{ route('admin.sanpham.index') }}"
               class="{{ request()->routeIs('admin.sanpham.*') ? 'active' : '' }}">
                <i class="fas fa-box fa-fw"></i> Sản Phẩm
            </a>
        </li>

        {{-- ── BÁN HÀNG ── --}}
        <li><div class="sidebar-section">Bán Hàng</div></li>
        <li>
            <a href="{{ route('admin.donhang.index') }}"
               class="{{ request()->routeIs('admin.donhang.*') ? 'active' : '' }}"
               id="sidebar-don-hang-link">
                <i class="fas fa-shopping-bag fa-fw"></i> Đơn Hàng
                @php $choXacNhan = \App\Models\Donhang::where('trang_thai', \App\Models\Donhang::TRANG_THAI_MOI)->count(); @endphp
                @if($choXacNhan > 0)
                    <span class="badge bg-danger ms-auto" id="rt-badge-don" style="font-size:0.65rem">{{ $choXacNhan }}</span>
                @else
                    <span class="badge bg-danger ms-auto" id="rt-badge-don" style="font-size:0.65rem;display:none">0</span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('admin.magiamgia.index') }}"
               class="{{ request()->routeIs('admin.magiamgia.*') ? 'active' : '' }}">
                <i class="fas fa-tag fa-fw"></i> Mã Giảm Giá
            </a>
        </li>

        @if(Auth::user()->isAdmin() || Auth::user()->isGiamDoc())
        <li><div class="sidebar-section">Người Dùng</div></li>
        <li>
            <a href="{{ route('admin.nguoidung.index') }}"
               class="{{ request()->routeIs('admin.nguoidung.*') ? 'active' : '' }}">
                <i class="fas fa-users fa-fw"></i> Người Dùng
            </a>
        </li>
        @endif

        {{-- Bình luận & Tin tức --}}
        <li>
            <a href="{{ route('admin.binhluan.index') }}"
               class="{{ request()->routeIs('admin.binhluan.*') ? 'active' : '' }}">
                <i class="fas fa-comments fa-fw"></i> Bình Luận
                @php $tongBinhluan = \App\Models\Binhluan::count(); @endphp
                @if($tongBinhluan > 0)
                    <span class="badge bg-warning text-dark ms-auto" style="font-size:0.65rem">{{ $tongBinhluan }}</span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('admin.tin-tuc.index') }}"
               class="{{ request()->routeIs('admin.tin-tuc.*') ? 'active' : '' }}">
                <i class="fas fa-newspaper fa-fw"></i> Tin Tức
            </a>
        </li>
        <li>
            <a href="{{ route('admin.banner.index') }}"
               class="{{ request()->routeIs('admin.banner.*') ? 'active' : '' }}">
                <i class="fas fa-newspaper fa-fw"></i> Banner
            </a>
        </li>

        {{-- ── BÁO CÁO & HỆ THỐNG ── --}}
        @if(Auth::user()->isAdmin() || Auth::user()->isGiamDoc() || Auth::user()->isKetoan())
        <li><div class="sidebar-section">Báo Cáo & Hệ Thống</div></li>
        <li>
            <a href="{{ route('admin.baocao.index') }}"
               class="{{ request()->routeIs('admin.baocao.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line fa-fw"></i> Thống Kê
            </a>
        </li>
        @endif
        @if(Auth::user()->isAdmin())
        <li>
            <a href="{{ route('admin.caidat.index') }}"
               class="{{ request()->routeIs('admin.caidat.*') ? 'active' : '' }}">
                <i class="fas fa-cog fa-fw"></i> Cài Đặt
            </a>
        </li>
        @endif

        {{-- Hồ sơ & Khác --}}
        <li><div class="sidebar-section">Tài Khoản</div></li>
        <li>
            <a href="{{ route('admin.profile') }}"
               class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                <i class="fas fa-user-circle fa-fw"></i> Hồ Sơ
            </a>
        </li>

        <li><div class="sidebar-section">Khác</div></li>
        <li>
            <a href="{{ url('/') }}" target="_blank">
                <i class="fas fa-globe fa-fw"></i> Xem Website
            </a>
        </li>
        <li>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit"
                        style="display:flex;align-items:center;gap:9px;width:100%;padding:10px 20px;background:none;border:none;border-left:3px solid transparent;color:rgba(255,255,255,0.72);font-size:0.875rem;cursor:pointer;transition:all 0.2s"
                        onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff'"
                        onmouseout="this.style.background='none';this.style.color='rgba(255,255,255,0.72)'">
                    <i class="fas fa-sign-out-alt fa-fw"></i> Đăng Xuất
                </button>
            </form>
        </li>

    </ul>
</div>

{{-- ── MAIN ── --}}
<div class="main-content">

    {{-- TOPBAR --}}
    <div class="topbar">
        <h6 class="mb-0 fw-bold text-secondary">
            <i class="fas fa-chevron-right me-1" style="font-size:0.7rem"></i>
            @yield('title', 'Dashboard')
        </h6>
        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->ho_ten }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <span class="dropdown-item-text small text-muted d-flex align-items-center gap-2">
                            @php $u = Auth::user(); @endphp
                            @if($u->isAdmin())
                                <span class="badge badge-role-superadmin">Super Admin</span>
                            @elseif($u->isGiamDoc())
                                <span class="badge badge-role-giamdoc">Giám Đốc</span>
                            @elseif($u->isKetoan())
                                <span class="badge badge-role-ketoan">Kế Toán</span>
                            @elseif($u->isNhanVien())
                                <span class="badge badge-role-staff">Nhân Viên</span>
                            @endif
                            {{ $u->email }}
                        </span>
                    </li>
                    <li><hr class="dropdown-divider m-0"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                            <i class="fas fa-user me-2"></i>Hồ Sơ
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ url('/') }}" target="_blank">
                            <i class="fas fa-globe me-2"></i>Xem Website
                        </a>
                    </li>
                    <li><hr class="dropdown-divider m-0"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Đăng Xuất
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    <div class="flash-wrap">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show shadow-sm">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- CONTENT --}}
    <div class="page-body">
        @yield('content')
    </div>

</div>

{{-- ── REALTIME TOAST CONTAINER ── --}}
<div id="rt-toast-wrap"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@vite(['resources/js/app.js'])

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.Echo === 'undefined') return;

    window.Echo.channel('admin-notifications')
        .listen('.don-hang-moi', function (data) {
            hienToast(data);
            capNhatBadge();
            phatAmThanh();
        });

    function hienToast(data) {
        const wrap = document.getElementById('rt-toast-wrap');
        const tien = Number(data.tong_tien).toLocaleString('vi-VN');

        const toast = document.createElement('div');
        toast.className = 'rt-toast';
        toast.innerHTML = `
            <button class="rt-close" onclick="event.stopPropagation();this.closest('.rt-toast').remove()">×</button>
            <div class="rt-title">
                <i class="fas fa-shopping-bag me-1" style="color:#e74c3c"></i>
                Đơn hàng mới!
            </div>
            <div class="rt-body">
                <strong>${data.ten_khach}</strong> &middot; ${data.so_dt}<br>
                <span style="color:#e74c3c;font-weight:700">${tien}đ</span>
                &middot; <span style="color:#888">${data.phuong_thuc}</span>
            </div>
            <div class="rt-meta">
                ${data.ma_don} &middot; ${data.thoi_gian}
                <a href="${data.url}" class="rt-link" onclick="event.stopPropagation()">Xem →</a>
            </div>
        `;

        toast.addEventListener('click', function () {
            window.location.href = data.url;
        });

        wrap.prepend(toast);
        setTimeout(function () {
            toast.style.animation = 'rtFadeOut .4s ease forwards';
            setTimeout(() => toast.remove(), 400);
        }, 8000);
    }

    function capNhatBadge() {
        const badge = document.getElementById('rt-badge-don');
        if (!badge) return;
        const soHienTai = parseInt(badge.textContent) || 0;
        badge.textContent = soHienTai + 1;
        badge.style.display = 'inline-block';
    }

    function phatAmThanh() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc  = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.setValueAtTime(880, ctx.currentTime);
            osc.frequency.setValueAtTime(660, ctx.currentTime + 0.1);
            gain.gain.setValueAtTime(0.3, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.4);
        } catch (e) { }
    }
});
</script>

@yield('extra-js')
</body>
</html>
