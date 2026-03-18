<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShopDecor') — Phụ Kiện & Decor Bàn Làm Việc</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        * { font-family: Arial, sans-serif; }
        body { background: #f5f5f5; }

        /* TOPBAR */
        .topbar { background: #1a5276; color: #fff; font-size: 0.82rem; padding: 6px 0; }
        .topbar a { color: #cce5ff; text-decoration: none; }
        .topbar a:hover { color: #fff; }
        .topbar i { margin-right: 4px; }

        /* HEADER */
        .site-header { background: #fff; border-bottom: 1px solid #ddd; padding: 12px 0; }
        .site-header .logo { font-size: 1.6rem; font-weight: 700; color: #1a5276; text-decoration: none; }
        .site-header .logo span { color: #e74c3c; }
        .site-header .hotline { font-size: 0.85rem; color: #333; }
        .site-header .hotline strong { color: #e74c3c; font-size: 1.1rem; }
        .search-bar input {
            border: 2px solid #1a5276; border-right: none;
            border-radius: 3px 0 0 3px; padding: 7px 12px;
            font-size: 0.88rem; outline: none; width: 280px;
        }
        .search-bar button {
            background: #1a5276; color: #fff;
            border: 2px solid #1a5276; border-radius: 0 3px 3px 0;
            padding: 7px 14px; cursor: pointer;
        }
        .search-bar button:hover { background: #154360; }
        .cart-btn {
            background: #e74c3c; color: #fff; border: none;
            border-radius: 3px; padding: 8px 16px; font-size: 0.88rem;
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
        }
        .cart-btn:hover { background: #c0392b; color: #fff; }
        .cart-btn .badge {
            background: #fff; color: #e74c3c; border-radius: 50%;
            font-size: 0.7rem; font-weight: 700; padding: 2px 5px;
        }

        /* MAIN NAVBAR */
        .main-nav { background: #1a5276; }
        .main-nav .nav-link {
            color: #fff !important; font-size: 0.88rem; font-weight: 600;
            padding: 10px 16px !important; text-transform: uppercase; letter-spacing: 0.3px;
        }
        .main-nav .nav-link:hover,
        .main-nav .nav-link.active { background: #e74c3c; color: #fff !important; }
        .main-nav .dropdown-menu {
            border-radius: 0; border: none;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15); margin-top: 0;
        }
        .main-nav .dropdown-item { font-size: 0.85rem; padding: 8px 16px; }
        .main-nav .dropdown-item:hover { background: #eaf4fb; color: #1a5276; }

        /* USER NAV */
        .user-nav { background: #154360; padding: 4px 0; font-size: 0.8rem; }
        .user-nav a { color: #afd6f5; text-decoration: none; }
        .user-nav a:hover { color: #fff; }

        /* FLASH MESSAGES */
        .flash-messages { position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px; }

        /* FOOTER */
        footer { background: #1a1a1a; color: #bbb; font-size: 0.88rem; margin-top: 40px; }
        footer h6 {
            color: #fff; font-weight: 700; text-transform: uppercase;
            font-size: 0.9rem; border-bottom: 2px solid #e74c3c;
            padding-bottom: 8px; margin-bottom: 15px;
        }
        footer a { color: #bbb; text-decoration: none; }
        footer a:hover { color: #e74c3c; }
        footer li { margin-bottom: 6px; }
        footer i { color: #e74c3c; margin-right: 6px; }
        .footer-bottom {
            background: #111; padding: 12px 0;
            color: #777; font-size: 0.8rem; border-top: 1px solid #333;
        }

        main { min-height: calc(100vh - 300px); }
    </style>

    @yield('extra-css')
</head>
<body>

{{-- TOPBAR --}}
<div class="topbar d-none d-md-block">
    <div class="container">
        <div class="d-flex justify-content-between">
            <div>
                <i class="fas fa-envelope"></i><a href="mailto:anhmy0201@gmail.com">anhmy0201@gmail.com</a>
                <span class="mx-2">|</span>
                <i class="fab fa-facebook"></i><a href="#">Facebook</a>
                <span class="mx-2">|</span>
                <i class="fab fa-tiktok"></i><a href="#">TikTok</a>
            </div>
            <div>
                <i class="fas fa-clock"></i>Mở cửa 8:00 – 22:00
                <span class="mx-2">|</span>
                <i class="fas fa-truck"></i>Giao hàng toàn quốc
            </div>
        </div>
    </div>
</div>

{{-- HEADER --}}
<div class="site-header">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="logo">
                <i class="fas fa-store me-1"></i>Shop<span>Decor</span>
            </a>

            {{-- Hotline --}}
            <div class="hotline d-none d-lg-block text-center">
                <div><i class="fas fa-phone-alt text-danger"></i> Hotline:</div>
                <strong>0799 669 238</strong>
            </div>

            {{-- Search --}}
            <form action="{{ url('/tim-kiem') }}" method="GET" class="search-bar d-none d-md-flex">
                <input type="text" name="q" placeholder="Tìm kiếm sản phẩm..." value="{{ request('q') }}">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>

            {{-- Giỏ hàng + User --}}
            <div class="d-flex align-items-center gap-2">
                <a href="{{ url('/gio-hang') }}" class="cart-btn">
                    <i class="fas fa-shopping-cart"></i> Giỏ hàng
                </a>
                @auth
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>{{ Auth::user()->ho_ten }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                <li><a class="dropdown-item" href="{{ url('/admin/dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Quản Trị
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ url('/tai-khoan') }}">
                                <i class="fas fa-user me-2"></i>Tài Khoản
                            </a></li>
                            <li><a class="dropdown-item" href="{{ url('/don-hang') }}">
                                <i class="fas fa-box me-2"></i>Đơn Hàng
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Đăng Xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Đăng Nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-primary">Đăng Ký</a>
                @endauth
            </div>

        </div>
    </div>
</div>

{{-- MAIN NAVBAR --}}
<nav class="main-nav d-none d-lg-block">
    <div class="container">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">TRANG CHỦ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('gioi-thieu') ? 'active' : '' }}" href="{{ url('/gioi-thieu') }}">GIỚI THIỆU</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">DANH MỤC</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ url('/danh-muc/tuong-figurine') }}">Tượng & Figurine</a></li>
                    <li><a class="dropdown-item" href="{{ url('/danh-muc/den-decor') }}">Đèn Decor</a></li>
                    <li><a class="dropdown-item" href="{{ url('/danh-muc/cay-xanh-mini') }}">Cây Xanh Mini</a></li>
                    <li><a class="dropdown-item" href="{{ url('/danh-muc/van-phong-pham') }}">Văn Phòng Phẩm</a></li>
                    <li><a class="dropdown-item" href="{{ url('/danh-muc/to-chuc-ban') }}">Tổ Chức Bàn</a></li>
                    <li><a class="dropdown-item" href="{{ url('/danh-muc/desk-mat') }}">Desk Mat</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/san-pham-ban-chay') }}">SẢN PHẨM BÁN CHẠY</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('khuyen-mai') ? 'active' : '' }}"
                   href="{{ route('khuyen-mai') }}">KHUYẾN MÃI</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('tin-tuc*') ? 'active' : '' }}"
                    href="{{ route('tin-tuc.index') }}">TIN TỨC</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">LIÊN HỆ</a>
            </li>
        </ul>
    </div>
</nav>

{{-- FLASH MESSAGES --}}
<div class="flash-messages">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

{{-- MAIN CONTENT --}}
<main>
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="pt-4">
    <div class="container pb-4">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <h6><i class="fas fa-store"></i>ShopDecor</h6>
                <p>Chuyên cung cấp phụ kiện và đồ decor bàn làm việc cao cấp. Nâng tầm không gian làm việc với sản phẩm tinh tế, ý nghĩa.</p>
                <div class="d-flex gap-3 mt-2">
                    <a href="#"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#"><i class="fab fa-tiktok fa-lg"></i></a>
                    <a href="#"><i class="fab fa-youtube fa-lg"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <h6>Danh Mục</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ url('/danh-muc/tuong-figurine') }}">Tượng & Figurine</a></li>
                    <li><a href="{{ url('/danh-muc/den-decor') }}">Đèn Decor</a></li>
                    <li><a href="{{ url('/danh-muc/cay-xanh-mini') }}">Cây Xanh Mini</a></li>
                    <li><a href="{{ url('/danh-muc/van-phong-pham') }}">Văn Phòng Phẩm</a></li>
                    <li><a href="{{ url('/danh-muc/to-chuc-ban') }}">Tổ Chức Bàn</a></li>
                    <li><a href="{{ url('/danh-muc/desk-mat') }}">Desk Mat</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <h6>Hỗ Trợ</h6>
                <ul class="list-unstyled">
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Chính sách vận chuyển</a></li>
                    <li><a href="#">Hướng dẫn mua hàng</a></li>
                    <li><a href="#">Câu hỏi thường gặp</a></li>
                    <li><a href="#">Liên hệ</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <h6>Liên Hệ</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt"></i>123 Nguyễn Huệ, Q.1, TP.HCM</li>
                    <li><i class="fas fa-phone"></i><a href="tel:0799669238">0799 669 238</a></li>
                    <li><i class="fas fa-envelope"></i><a href="mailto:anhmy0201@gmail.com">anhmy0201@gmail.com</a></li>
                    <li><i class="fas fa-clock"></i>8:00 – 22:00 mỗi ngày</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-bottom text-center">
        <div class="container">
            © {{ date('Y') }} ShopDecor. Thiết kế tại Việt Nam.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    setTimeout(function() {
        document.querySelectorAll('.flash-messages .alert').forEach(function(el) {
            bootstrap.Alert.getOrCreateInstance(el).close();
        });
    }, 4000);
</script>

@yield('extra-js')
</body>
</html>