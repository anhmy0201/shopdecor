<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DanhMucController;
use App\Http\Controllers\SanphamController;
use App\Http\Controllers\GioHangController;
use App\Http\Controllers\ThanhToanController;
use App\Http\Controllers\DonhangController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TinTucController;
use App\Http\Controllers\KhuyenMaiController;
use App\Http\Controllers\TimKiemController;
use App\Http\Controllers\LienHeController;
use App\Http\Controllers\TraCuuDonHangController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\BannerController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login',     [LoginController::class,    'showLoginForm'])->name('login');
    Route::post('/login',    [LoginController::class,    'login']);
    Route::get('/register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/quen-mat-khau',  [ForgotPasswordController::class, 'showForm'])->name('password.forgot');
    Route::post('/quen-mat-khau', [ForgotPasswordController::class, 'sendResetLink'])->name('password.forgot.send');
    Route::get('/dat-lai-mat-khau/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset.form');
    Route::post('/dat-lai-mat-khau',        [ResetPasswordController::class, 'reset'])->name('password.reset');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/auth/google',          [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

Route::get('/san-pham',        [DanhMucController::class, 'index']);
Route::get('/danh-muc/{slug}', [DanhMucController::class, 'show']);
Route::get('/san-pham/{slug}', [SanphamController::class, 'show']);

Route::get('/gio-hang',                  [GioHangController::class, 'index'])->name('gio-hang');
Route::post('/gio-hang/mua-ngay',        [GioHangController::class, 'muaNgay']);
Route::post('/gio-hang/them',            [GioHangController::class, 'them']);
Route::patch('/gio-hang/cap-nhat/{id}', [GioHangController::class, 'capNhat']);
Route::post('/gio-hang/cap-nhat/{id}',  [GioHangController::class, 'capNhat']);
Route::delete('/gio-hang/xoa/{id}',     [GioHangController::class, 'xoa']);
Route::post('/gio-hang/xoa/{id}',       [GioHangController::class, 'xoa']);
Route::delete('/gio-hang/xoa-tat',      [GioHangController::class, 'xoaTat']);

Route::get('/thanh-toan',             [ThanhToanController::class, 'index'])->name('thanh-toan');
Route::post('/thanh-toan',            [ThanhToanController::class, 'store']);
Route::post('/thanh-toan/ap-ma',      [ThanhToanController::class, 'apMa']);
Route::get('/xac-nhan-don-hang/{id}', [ThanhToanController::class, 'xacNhan'])->name('xac-nhan-don-hang');
Route::get('/payos/checkout/{id}', [ThanhToanController::class, 'payosCheckout'])->name('payos.checkout');
Route::get('/payos/success',       [ThanhToanController::class, 'payosSuccess'])->name('payos.success');
Route::get('/payos/cancel',        [ThanhToanController::class, 'payosCancel'])->name('payos.cancel');
Route::post('/payos/webhook',      [ThanhToanController::class, 'payosWebhook'])
    ->name('payos.webhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::middleware('auth')->group(function () {
    Route::get('/don-hang',                       [DonhangController::class, 'index'])->name('don-hang');
    Route::get('/don-hang/{id}',                  [DonhangController::class, 'chiTiet'])->name('don-hang.chi-tiet');
    Route::patch('/don-hang/{id}/huy',            [DonhangController::class, 'huy'])->name('don-hang.huy');
    Route::post('/don-hang/{donhangId}/danh-gia', [DonhangController::class, 'danhGia'])->name('don-hang.danh-gia');
});

Route::prefix('tai-khoan')->name('account.')->group(function () {
    Route::get('/',                            [AccountController::class, 'index'])->name('index');
    Route::put('/cap-nhat',                    [AccountController::class, 'capNhatThongTin'])->name('cap-nhat');
    Route::put('/doi-mat-khau',                [AccountController::class, 'doiMatKhau'])->name('doi-mat-khau');
    Route::post('/dia-chi',                    [AccountController::class, 'themDiaChi'])->name('dia-chi.them');
    Route::delete('/dia-chi/{diaChi}',         [AccountController::class, 'xoaDiaChi'])->name('dia-chi.xoa');
    Route::patch('/dia-chi/{diaChi}/mac-dinh', [AccountController::class, 'datMacDinh'])->name('dia-chi.mac-dinh');
});

Route::get('/tin-tuc',        [TinTucController::class, 'index'])->name('tin-tuc.index');
Route::get('/tin-tuc/{slug}', [TinTucController::class, 'show'])->name('tin-tuc.show');
Route::get('/khuyen-mai',     [KhuyenMaiController::class, 'index'])->name('khuyen-mai');
Route::view('/gioi-thieu',    'pages.gioi-thieu')->name('gioi-thieu');
Route::get('/tim-kiem',       [TimKiemController::class, 'index'])->name('tim-kiem');
Route::get('/lien-he',        [LienHeController::class, 'index'])->name('lien-he');
Route::post('/lien-he/gui',   [LienHeController::class, 'gui'])->name('lien-he.gui');
Route::get('/tra-cuu-don-hang',  [TraCuuDonHangController::class, 'index'])->name('tra-cuu-don-hang');
Route::post('/tra-cuu-don-hang', [TraCuuDonHangController::class, 'traCuu'])->name('tra-cuu-don-hang.ket-qua');

Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])
    ->name('chatbot.chat')
    ->middleware('throttle:30,1');

require __DIR__ . '/../routes/channels.php';

Route::prefix('admin')->name('admin.')->middleware(['auth', 'check.admin:staff'])->group(function () {

    Route::get('/',         [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard', [AdminDashboardController::class, 'index']);

    Route::resource('loai-sanpham', AdminLoaiSanphamController::class)
         ->except(['show']);

    Route::post('sanpham/nhap', [AdminSanphamController::class, 'postNhap'])->name('sanpham.nhap');
    Route::get('sanpham/xuat',  [AdminSanphamController::class, 'getXuat'])->name('sanpham.xuat');
    Route::resource('sanpham', AdminSanphamController::class);

    Route::get('donhang/xuat', [AdminDonhangController::class, 'getXuat'])->name('donhang.xuat');
    Route::resource('donhang', AdminDonhangController::class)->only(['index', 'show']);
    Route::patch('donhang/{donhang}/cap-nhat-trang-thai',
        [AdminDonhangController::class, 'capNhatTrangThai'])
        ->name('donhang.cap-nhat-trang-thai');

    Route::post('magiamgia/nhap', [MagiamgiaController::class, 'postNhap'])->name('magiamgia.nhap');
    Route::get('magiamgia/xuat',  [MagiamgiaController::class, 'getXuat'])->name('magiamgia.xuat');
    Route::resource('magiamgia', MagiamgiaController::class)
         ->parameters(['magiamgia' => 'magiamgia']);
    Route::patch('magiamgia/{magiamgia}/toggle',
        [MagiamgiaController::class, 'toggleKichHoat'])
        ->name('magiamgia.toggle');

    Route::get('binhluan',               [BinhluanController::class, 'index'])->name('binhluan.index');
    Route::delete('binhluan/{binhluan}', [BinhluanController::class, 'destroy'])->name('binhluan.destroy');

    Route::resource('tin-tuc', AdminTinTucController::class);
    Route::patch('tin-tuc/{tinTuc}/toggle',
        [AdminTinTucController::class, 'toggleKichHoat'])
        ->name('tin-tuc.toggle');

    Route::get('banner',                  [BannerController::class, 'index'])->name('banner.index');
    Route::post('banner',                 [BannerController::class, 'store'])->name('banner.store');
    Route::get('banner/{banner}/edit',    [BannerController::class, 'edit'])->name('banner.edit');
    Route::put('banner/{banner}',         [BannerController::class, 'update'])->name('banner.update');
    Route::post('banner/{banner}/toggle', [BannerController::class, 'toggleKichHoat'])->name('banner.toggle');
    Route::delete('banner/{banner}',      [BannerController::class, 'destroy'])->name('banner.destroy');

    Route::middleware('check.admin:admin')->group(function () {
        Route::get('baocao',        [BaocaoController::class, 'index'])->name('baocao.index');
        Route::get('baocao/export', [BaocaoController::class, 'exportExcel'])->name('baocao.export');

        Route::get('caidat',  [CaidatController::class, 'index'])->name('caidat.index');
        Route::post('caidat', [CaidatController::class, 'update'])->name('caidat.update');
        Route::delete('caidat/log/all',        [CaidatController::class, 'destroyAllLog'])
               ->name('caidat.log.destroy-all');
        Route::delete('caidat/log/{activity}', [CaidatController::class, 'destroyLog'])
               ->name('caidat.log.destroy');

        Route::resource('nguoidung', NguoidungController::class)
             ->only(['index', 'show', 'edit', 'update']);
        Route::patch('nguoidung/{nguoidung}/toggle',
            [NguoidungController::class, 'toggleKichHoat'])
            ->name('nguoidung.toggle');
    });

    Route::get('profile',  [ProfileController::class, 'index'])->name('profile');
    Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');
});