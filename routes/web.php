<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DanhMucController;
use App\Http\Controllers\SanphamController;
use App\Http\Controllers\GiohangController;
use App\Http\Controllers\ThanhToanController;
use App\Http\Controllers\DonhangController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TinTucController;

// Admin controllers — dùng alias để tránh trùng tên với controller frontend
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BaocaoController;
use App\Http\Controllers\Admin\LoaiSanphamController as AdminLoaiSanphamController;
use App\Http\Controllers\Admin\SanphamController as AdminSanphamController;
use App\Http\Controllers\Admin\DonhangController as AdminDonhangController;
use App\Http\Controllers\Admin\MagiamgiaController;
use App\Http\Controllers\Admin\NguoidungController;
use App\Http\Controllers\Admin\BinhluanController;
use App\Http\Controllers\Admin\CaidatController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TinTucController as AdminTinTucController;

// ===== TRANG CHỦ =====
Route::get('/', [HomeController::class, 'index'])->name('home');

// ===== AUTH THƯỜNG =====
Route::middleware('guest')->group(function () {
    Route::get('/login',     [LoginController::class,    'showLoginForm'])->name('login');
    Route::post('/login',    [LoginController::class,    'login']);
    Route::get('/register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ===== GOOGLE LOGIN =====
Route::get('/auth/google',          [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

// ===== DANH MỤC & SẢN PHẨM =====
Route::get('/danh-muc/{slug}', [DanhMucController::class, 'show']);
Route::get('/san-pham/{slug}', [SanphamController::class, 'show']);

// ===== GIỎ HÀNG =====
Route::get('/gio-hang',                  [GiohangController::class, 'index'])->name('gio-hang');
Route::post('/gio-hang/them',            [GiohangController::class, 'them']);
Route::patch('/gio-hang/cap-nhat/{id}', [GiohangController::class, 'capNhat']);
Route::delete('/gio-hang/xoa/{id}',     [GiohangController::class, 'xoa']);
Route::delete('/gio-hang/xoa-tat',      [GiohangController::class, 'xoaTat']);

// ===== THANH TOÁN =====
Route::get('/thanh-toan',             [ThanhToanController::class, 'index'])->name('thanh-toan');
Route::post('/thanh-toan',            [ThanhToanController::class, 'store']);
Route::post('/thanh-toan/ap-ma',      [ThanhToanController::class, 'apMa']);
Route::get('/xac-nhan-don-hang/{id}', [ThanhToanController::class, 'xacNhan'])->name('xac-nhan-don-hang');

// ===== ĐƠN HÀNG — cần đăng nhập =====
Route::middleware('auth')->group(function () {
    Route::get('/don-hang',                       [DonhangController::class, 'index'])->name('don-hang');
    Route::get('/don-hang/{id}',                  [DonhangController::class, 'chiTiet'])->name('don-hang.chi-tiet');
    Route::patch('/don-hang/{id}/huy',            [DonhangController::class, 'huy'])->name('don-hang.huy');
    Route::post('/don-hang/{donhangId}/danh-gia', [DonhangController::class, 'danhGia'])->name('don-hang.danh-gia');
});

// ===== TÀI KHOẢN =====
Route::prefix('tai-khoan')->name('account.')->group(function () {
    Route::get('/',                            [AccountController::class, 'index'])->name('index');
    Route::put('/cap-nhat',                    [AccountController::class, 'capNhatThongTin'])->name('cap-nhat');
    Route::put('/doi-mat-khau',                [AccountController::class, 'doiMatKhau'])->name('doi-mat-khau');
    Route::post('/dia-chi',                    [AccountController::class, 'themDiaChi'])->name('dia-chi.them');
    Route::delete('/dia-chi/{diaChi}',         [AccountController::class, 'xoaDiaChi'])->name('dia-chi.xoa');
    Route::patch('/dia-chi/{diaChi}/mac-dinh', [AccountController::class, 'datMacDinh'])->name('dia-chi.mac-dinh');
});
Route::get('/tin-tuc',              [TinTucController::class, 'index'])->name('tin-tuc.index');
Route::get('/tin-tuc/{slug}',       [TinTucController::class, 'show'])->name('tin-tuc.show');


// ===== ADMIN =====
Route::prefix('admin')->name('admin.')->middleware(['auth', 'check.admin:staff'])->group(function () {

    // Dashboard
    Route::get('/',         [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard', [AdminDashboardController::class, 'index']);

    // Loại sản phẩm
    Route::resource('loai-sanpham', AdminLoaiSanphamController::class)
         ->except(['show']);

    // Sản phẩm
    Route::resource('sanpham', AdminSanphamController::class);

    // Đơn hàng
    Route::resource('donhang', AdminDonhangController::class)
         ->only(['index', 'show']);
    Route::patch('donhang/{donhang}/cap-nhat-trang-thai',
        [AdminDonhangController::class, 'capNhatTrangThai'])
        ->name('donhang.cap-nhat-trang-thai');

    // Mã giảm giá
    Route::resource('magiamgia', MagiamgiaController::class);
    Route::patch('magiamgia/{magiamgia}/toggle',
        [MagiamgiaController::class, 'toggleKichHoat'])
        ->name('magiamgia.toggle');

    // Người dùng
    Route::resource('nguoidung', NguoidungController::class)
         ->only(['index', 'show', 'edit', 'update']);
    Route::patch('nguoidung/{nguoidung}/toggle',
        [NguoidungController::class, 'toggleKichHoat'])
        ->name('nguoidung.toggle');
    
    // Bình luận
    Route::get('binhluan',              [BinhluanController::class, 'index'])->name('binhluan.index');
    Route::delete('binhluan/{binhluan}', [BinhluanController::class, 'destroy'])->name('binhluan.destroy');

    // Báo cáo
    Route::get('baocao', [BaocaoController::class, 'index'])->name('baocao.index');

    // Cài đặt
    Route::get('caidat',  [CaidatController::class, 'index'])->name('caidat.index');
    Route::post('caidat', [CaidatController::class, 'update'])->name('caidat.update');

    // Hồ sơ admin
    Route::get('profile',  [ProfileController::class, 'index'])->name('profile');
    Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Tin tức
    Route::resource('tin-tuc', AdminTinTucController::class);
    Route::patch('tin-tuc/{tinTuc}/toggle',
    [AdminTinTucController::class, 'toggleKichHoat'])
    ->name('tin-tuc.toggle');

});