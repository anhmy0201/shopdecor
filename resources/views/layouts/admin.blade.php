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

        @media(max-width:768px){
            .sidebar { width:100%; height:auto; position:relative; }
            .main-content { margin-left:0; }
        }
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

        <li>
            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt fa-fw"></i> Dashboard
            </a>
        </li>

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

        <li><div class="sidebar-section">Bán Hàng</div></li>
        <li>
            <a href="{{ route('admin.donhang.index') }}"
               class="{{ request()->routeIs('admin.donhang.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag fa-fw"></i> Đơn Hàng
                @php $choXacNhan = \App\Models\Donhang::where('trang_thai', \App\Models\Donhang::TRANG_THAI_MOI)->count(); @endphp
                @if($choXacNhan > 0)
                    <span class="badge bg-danger ms-auto" style="font-size:0.65rem">{{ $choXacNhan }}</span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('admin.magiamgia.index') }}"
               class="{{ request()->routeIs('admin.magiamgia.*') ? 'active' : '' }}">
                <i class="fas fa-tag fa-fw"></i> Mã Giảm Giá
            </a>
        </li>

        <li><div class="sidebar-section">Người Dùng</div></li>
        <li>
            <a href="{{ route('admin.nguoidung.index') }}"
               class="{{ request()->routeIs('admin.nguoidung.*') ? 'active' : '' }}">
                <i class="fas fa-users fa-fw"></i> Người Dùng
            </a>
        </li>
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


        <li><div class="sidebar-section">Báo Cáo & Hệ Thống</div></li>
        <li>
            <a href="{{ route('admin.baocao.index') }}"
               class="{{ request()->routeIs('admin.baocao.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line fa-fw"></i> Thống Kê
            </a>
        </li>
        <li>
            <a href="{{ route('admin.caidat.index') }}"
               class="{{ request()->routeIs('admin.caidat.*') ? 'active' : '' }}">
                <i class="fas fa-cog fa-fw"></i> Cài Đặt
            </a>
        </li>
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
                        <span class="dropdown-item-text small text-muted">
                            @if(Auth::user()->isAdmin()) Admin
                            @elseif(Auth::user()->isStaff()) Nhân viên
                            @endif
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('extra-js')
</body>
</html>