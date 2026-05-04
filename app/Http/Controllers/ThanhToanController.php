<?php

namespace App\Http\Controllers;

use App\Models\ChitietDonhang;
use App\Models\Donhang;
use App\Models\Magiamgia;
use App\Models\Sanpham;
use App\Models\SanphamBienthe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Giohang;
use App\Models\DiaChiUser;
use PayOS\PayOS;
use App\Events\DonHangMoiEvent;
use App\Events\TonKhoCapNhatEvent;
use App\Mail\DonHangMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ThanhToanController extends Controller
{
    private function layGioHang(): Giohang
    {
        if (Auth::check()) {
            return Giohang::firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = session()->getId();
        return Giohang::firstOrCreate(['session_id' => $sessionId]);
    }

    public function index()
    {
        $muaNgay = session('mua_ngay');

        // Nếu request đến kèm tham số ?from=cart (bấm "Tiến hành thanh toán" từ giỏ hàng),
        // hoặc referrer là trang giỏ hàng → xóa session mua_ngay để dùng giỏ hàng thật
        $fromCart = request()->get('from') === 'cart'
            || str_contains(request()->headers->get('referer', ''), route('gio-hang'));

        if ($fromCart && $muaNgay) {
            session()->forget('mua_ngay');
            $muaNgay = null;
        }

        if ($muaNgay) {
            // Chế độ "Mua Ngay": tạo giỏ ảo từ session, không đụng DB giỏ hàng
            $sanpham = \App\Models\Sanpham::with('anhChinh')->findOrFail($muaNgay['san_pham_id']);
            $bienthe = $muaNgay['bienthe_id']
                ? \App\Models\SanphamBienthe::find($muaNgay['bienthe_id'])
                : null;

            // Tạo object giỏ ảo để blade dùng chung template
            $giohang = new \stdClass();
            $giohang->chitiets = collect([(object)[
                'id'         => null,
                'sanpham_id' => $sanpham->id,
                'bienthe_id' => $muaNgay['bienthe_id'],
                'so_luong'   => $muaNgay['so_luong'],
                'gia'        => $muaNgay['gia'],
                'thanh_tien' => $muaNgay['gia'] * $muaNgay['so_luong'],
                'sanpham'    => $sanpham,
                'bienthe'    => $bienthe,
            ]]);
            $giohang->tong_tien = $muaNgay['gia'] * $muaNgay['so_luong'];
            $giohang->la_mua_ngay = true;
        } else {
            $giohang = $this->layGioHang();

            if ($giohang->chitiets()->count() === 0) {
                return redirect()->route('gio-hang')
                    ->with('error', 'Giỏ hàng của bạn đang trống!');
            }

            $giohang->load(['chitiets.sanpham.anhChinh', 'chitiets.bienthe']);
            $giohang->la_mua_ngay = false;
        }

        $diaChis       = Auth::check()
            ? Auth::user()->diaChis()->orderByDesc('mac_dinh')->get()
            : collect();
        $diaChiMacDinh = $diaChis->firstWhere('mac_dinh', true);

        // Lấy danh sách mã giảm giá còn hiệu lực để render trong popup
        $danhSachMa = Magiamgia::where('kich_hoat', true)
            ->where(function($q) { $q->whereNull('ket_thuc')->orWhere('ket_thuc', '>=', now()); })
            ->where(function($q) { $q->whereNull('bat_dau')->orWhere('bat_dau', '<=', now()); })
            ->where(function($q) { $q->whereNull('so_luong')->orWhereColumn('da_su_dung', '<', 'so_luong'); })
            ->orderByDesc('created_at')
            ->get();

        return view('pages.thanh-toan', compact('giohang', 'diaChis', 'diaChiMacDinh', 'danhSachMa'));
    }

    public function apMa(Request $request)
    {
        $request->validate(['ma_code' => 'required|string']);

        $ma = Magiamgia::where('ma_code', strtoupper(trim($request->ma_code)))->first();

        if (!$ma || !$ma->conHieuLuc()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.',
            ]);
        }

        // Kiểm tra đã dùng mã này chưa
        if (Auth::check()) {
            $daDung = Donhang::where('user_id', Auth::id())
                ->where('magiamgia_id', $ma->id)
                ->exists();
            if ($daDung) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã sử dụng mã giảm giá này rồi.',
                ]);
            }
        } else {
            $maDaDungTrongSession = session('guest_ma_da_dung', []);
            if (in_array($ma->id, $maDaDungTrongSession)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá này đã được áp dụng cho đơn hàng của bạn.',
                ]);
            }
        }

        // Tính tổng tiền: ưu tiên session mua_ngay, fallback về giỏ hàng DB
        $muaNgay = session('mua_ngay');
        if ($muaNgay) {
            $tongTienHang = (float) ($muaNgay['gia'] * $muaNgay['so_luong']);
        } else {
            $giohang = $this->layGioHang();
            $giohang->load('chitiets');
            $tongTienHang = $giohang->tong_tien;
        }

        $soTienGiam = $ma->tinhSoTienGiam($tongTienHang);

        if ($soTienGiam <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($ma->don_hang_toi_thieu) . 'đ.',
            ]);
        }

        return response()->json([
            'success'          => true,
            'message'          => 'Áp dụng mã thành công!',
            'magiamgia_id'     => $ma->id,
            'ten_ma'           => $ma->ma_code,
            'mo_ta'            => $ma->mo_ta,
            'so_tien_giam'     => number_format($soTienGiam) . 'đ',
            'tong_thanh_toan'  => number_format(max(0, $tongTienHang - $soTienGiam)) . 'đ',
            'so_tien_giam_raw' => $soTienGiam,
        ]);
    }

    public function danhSachMa()
    {
        $giohang = $this->layGioHang();
        $giohang->load('chitiets');

        $mas = Magiamgia::where('kich_hoat', true)
            ->where(function($q) { $q->whereNull('ket_thuc')->orWhere('ket_thuc', '>=', now()); })
            ->where(function($q) { $q->whereNull('bat_dau')->orWhere('bat_dau', '<=', now()); })
            ->where(function($q) { $q->whereNull('so_luong')->orWhereColumn('da_su_dung', '<', 'so_luong'); })
            ->orderByDesc('created_at')
            ->get()
            ->map(function($ma) {
                return [
                    'id'                 => $ma->id,
                    'ma_code'            => $ma->ma_code,
                    'mo_ta'              => $ma->mo_ta,
                    'kieu_giam'          => $ma->kieu_giam,
                    'gia_tri'            => $ma->gia_tri,
                    'don_hang_toi_thieu' => $ma->don_hang_toi_thieu,
                    'giam_toi_da'        => $ma->giam_toi_da,
                    'so_luong'           => $ma->so_luong,
                    'da_su_dung'         => $ma->da_su_dung,
                    'ket_thuc'           => $ma->ket_thuc?->toDateString(),
                ];
            });

        return response()->json(['magiamgias' => $mas]);
    }

    public function store(Request $request)
    {
        if (Auth::check() && $request->filled('_dia_chi_chon')) {
            $diaChiChon = DiaChiUser::where('id', $request->_dia_chi_chon)
                ->where('user_id', Auth::id())
                ->first();

            if ($diaChiChon) {
                $request->merge([
                    'ten_nguoi_nhan'   => $diaChiChon->ho_ten,
                    'so_dien_thoai'    => $diaChiChon->so_dien_thoai,
                    'dia_chi_chi_tiet' => $diaChiChon->dia_chi_chi_tiet,
                    'phuong_xa'        => $diaChiChon->phuong_xa,
                    'tinh_thanh'       => $diaChiChon->tinh_thanh,
                ]);
            }
        }

        $request->validate([
            'ten_nguoi_nhan'        => 'required|string|max:255',
            'so_dien_thoai'         => ['required', 'string', 'max:15', 'regex:/^(0[3|5|7|8|9])[0-9]{8}$/'],
            'email'                 => Auth::check() ? 'nullable' : 'required|email|max:255',
            'dia_chi_chi_tiet'      => 'required|string|max:500',
            'phuong_xa'             => 'required|string|max:100',
            'tinh_thanh'            => 'required|string|max:100',
            'phuong_thuc_thanhtoan' => 'required|in:cod,payos',
            'ghi_chu_khach'         => 'nullable|string|max:500',
            'magiamgia_id'          => 'nullable|exists:magiamgia,id',
        ], [
            'ten_nguoi_nhan.required'        => 'Vui lòng nhập họ tên người nhận.',
            'so_dien_thoai.required'         => 'Vui lòng nhập số điện thoại.',
            'so_dien_thoai.regex'            => 'Số điện thoại không hợp lệ (VD: 0901234567).',
            'email.required'                 => 'Vui lòng nhập email để nhận xác nhận đơn hàng.',
            'email.email'                    => 'Email không hợp lệ.',
            'dia_chi_chi_tiet.required'      => 'Vui lòng nhập địa chỉ chi tiết.',
            'phuong_xa.required'             => 'Vui lòng nhập phường/xã.',
            'tinh_thanh.required'            => 'Vui lòng nhập tỉnh/thành phố.',
            'phuong_thuc_thanhtoan.required' => 'Vui lòng chọn phương thức thanh toán.',
        ]);

        $giohang = $this->layGioHang();

        // Kiểm tra chế độ "Mua Ngay" trước — nếu có, dùng dữ liệu session thay vì giỏ DB
        $muaNgay = session('mua_ngay');
        $laMuaNgay = false;

        if ($muaNgay) {
            $laMuaNgay = true;
            $sanpham = Sanpham::findOrFail($muaNgay['san_pham_id']);
            $bienthe = $muaNgay['bienthe_id']
                ? SanphamBienthe::find($muaNgay['bienthe_id'])
                : null;

            // Tạo collection giỏ ảo để dùng chung logic bên dưới
            $chitiets = collect([(object)[
                'sanpham_id' => $sanpham->id,
                'bienthe_id' => $muaNgay['bienthe_id'],
                'so_luong'   => $muaNgay['so_luong'],
                'gia'        => $muaNgay['gia'],
                'sanpham'    => $sanpham,
                'bienthe'    => $bienthe,
            ]]);
        } else {
            if ($giohang->chitiets()->count() === 0) {
                return redirect()->route('gio-hang')
                    ->with('error', 'Giỏ hàng của bạn đang trống!');
            }
            $giohang->load(['chitiets.sanpham.anhChinh', 'chitiets.bienthe']);
            $chitiets = $giohang->chitiets;
        }

        // Chuẩn bị dữ liệu mã giảm giá trước transaction
        $magiamgiaId  = null;
        $magiamgia    = null;
        $soTienGiam   = 0;

        if ($request->magiamgia_id) {
            $magiamgia = Magiamgia::find($request->magiamgia_id);
            if ($magiamgia && $magiamgia->conHieuLuc()) {
                if (Auth::check()) {
                    $daDung = Donhang::where('user_id', Auth::id())
                        ->where('magiamgia_id', $magiamgia->id)
                        ->exists();
                } else {
                    $maDaDungTrongSession = session('guest_ma_da_dung', []);
                    $daDung = in_array($magiamgia->id, $maDaDungTrongSession);
                }

                if ($daDung) {
                    $magiamgia = null;
                }
            } else {
                $magiamgia = null;
            }
        }

        $email = Auth::check()
            ? Auth::user()->email
            : $request->email;

        $donhang = null;
        $tongTienHang = 0;
        $tongThanhToan = 0;

        // Toàn bộ kiểm tra tồn kho và tạo đơn nằm trong cùng một transaction
        // lockForUpdate() chỉ có hiệu lực khi nằm bên trong transaction
        // try-catch bắt exception TON_KHO ném ra từ bên trong để trả về lỗi thân thiện
        try {
        DB::transaction(function () use (
            $request, $giohang, $magiamgia, $chitiets, $laMuaNgay,
            $email, &$donhang, &$tongTienHang, &$tongThanhToan, &$soTienGiam, &$magiamgiaId
        ) {
            // Kiểm tra và khóa tồn kho ngay trong transaction
            foreach ($chitiets as $ct) {
                if ($ct->bienthe_id) {
                    $tonKho = SanphamBienthe::where('id', $ct->bienthe_id)
                        ->lockForUpdate()
                        ->value('so_luong');
                    $tenSp  = $ct->sanpham->ten_san_pham . ' — ' . $ct->bienthe->ten_bienthe;
                } else {
                    $tonKho = Sanpham::where('id', $ct->sanpham_id)
                        ->lockForUpdate()
                        ->value('so_luong');
                    $tenSp  = $ct->sanpham->ten_san_pham;
                }

                if ($tonKho === null || $tonKho < $ct->so_luong) {
                    $conLai = $tonKho ?? 0;
                    $msg    = $conLai > 0
                        ? "Sản phẩm \"{$tenSp}\" chỉ còn {$conLai} trong kho, không đủ số lượng bạn yêu cầu."
                        : "Sản phẩm \"{$tenSp}\" đã hết hàng.";
                    throw new \Exception('TON_KHO:' . $msg);
                }
            }

            $tongTienHang = $chitiets->sum(fn($ct) => $ct->gia * $ct->so_luong);

            if ($magiamgia) {
                $soTienGiam = $magiamgia->tinhSoTienGiam($tongTienHang);
                if ($soTienGiam > 0) {
                    $magiamgiaId = $magiamgia->id;
                } else {
                    $soTienGiam = 0;
                    $magiamgia  = null;
                }
            }

            $tongThanhToan = max(0, $tongTienHang - $soTienGiam);

            $donhang = Donhang::create([
                'user_id'               => Auth::id(),
                'magiamgia_id'          => $magiamgiaId,
                'ten_nguoi_nhan'        => $request->ten_nguoi_nhan,
                'so_dien_thoai'         => $request->so_dien_thoai,
                'email'                 => $email,
                'dia_chi_chi_tiet'      => $request->dia_chi_chi_tiet,
                'phuong_xa'             => $request->phuong_xa,
                'tinh_thanh'            => $request->tinh_thanh,
                'phuong_thuc_thanhtoan' => $request->phuong_thuc_thanhtoan,
                'trang_thai_thanhtoan'  => 'chua_thanh_toan',
                'phi_ship'              => 0,
                'tong_tien_hang'        => $tongTienHang,
                'so_tien_giam'          => $soTienGiam,
                'tong_thanh_toan'       => $tongThanhToan,
                'trang_thai'            => Donhang::TRANG_THAI_MOI,
                'ghi_chu_khach'         => $request->ghi_chu_khach,
            ]);

            foreach ($chitiets as $ct) {
                ChitietDonhang::create([
                    'donhang_id'   => $donhang->id,
                    'sanpham_id'   => $ct->sanpham_id,
                    'bienthe_id'   => $ct->bienthe_id,
                    'ten_san_pham' => $ct->sanpham->ten_san_pham,
                    'ten_bienthe'  => $ct->bienthe?->ten_bienthe,
                    'ma_sku'       => $ct->bienthe?->ma_sku,
                    'hinh_anh'     => $ct->bienthe?->hinh_anh ?? $ct->sanpham->anhChinh?->duong_dan_anh,
                    'so_luong'     => $ct->so_luong,
                    'gia'          => $ct->gia,
                ]);

                if ($ct->bienthe_id) {
                    SanphamBienthe::where('id', $ct->bienthe_id)
                        ->decrement('so_luong', $ct->so_luong);
                } else {
                    Sanpham::where('id', $ct->sanpham_id)
                        ->decrement('so_luong', $ct->so_luong);
                }

                // Bug 2 fix: Tăng lượt mua để tính năng "bán chạy" hoạt động đúng
                Sanpham::where('id', $ct->sanpham_id)
                    ->increment('luot_mua', $ct->so_luong);
            }

            if ($magiamgia) {
                $magiamgia->tangDaSuDung();
                if (!Auth::check()) {
                    $dsDaDung   = session('guest_ma_da_dung', []);
                    $dsDaDung[] = $magiamgia->id;
                    session(['guest_ma_da_dung' => array_unique($dsDaDung)]);
                }
            }

            // Chỉ xóa giỏ DB khi không phải Mua Ngay
            if (!$laMuaNgay) {
                $giohang->chitiets()->delete();
            }

            if (Auth::check()) {
                $diaChiTonTai = DiaChiUser::where('user_id', Auth::id())
                    ->where('dia_chi_chi_tiet', $request->dia_chi_chi_tiet)
                    ->where('so_dien_thoai', $request->so_dien_thoai)
                    ->exists();

                if (!$diaChiTonTai) {
                    $laDauTien = DiaChiUser::where('user_id', Auth::id())->count() === 0;

                    DiaChiUser::create([
                        'user_id'          => Auth::id(),
                        'ho_ten'           => $request->ten_nguoi_nhan,
                        'so_dien_thoai'    => $request->so_dien_thoai,
                        'dia_chi_chi_tiet' => $request->dia_chi_chi_tiet,
                        'phuong_xa'        => $request->phuong_xa,
                        'quan_huyen'       => $request->quan_huyen ?? '',
                        'tinh_thanh'       => $request->tinh_thanh,
                        'mac_dinh'         => $laDauTien,
                    ]);
                }
            }
        });
        } catch (\Exception $e) {
            // Bug 1 fix: DB::transaction re-throw exception, phải bắt ở đây
            if (str_starts_with($e->getMessage(), 'TON_KHO:')) {
                $msg = substr($e->getMessage(), 8);
                return back()->with('error', $msg);
            }
            throw $e; // exception khác thì để Laravel xử lý
        }


        broadcast(new DonHangMoiEvent($donhang));

        $donhang->load('chitiets');
        foreach ($donhang->chitiets as $ct) {
            $sp = Sanpham::find($ct->sanpham_id);
            if (!$sp) continue;

            $bienthe = $ct->bienthe_id
                ? SanphamBienthe::find($ct->bienthe_id)
                : null;

            broadcast(new TonKhoCapNhatEvent($sp, $bienthe));
        }

        session(['don_hang_vua_dat' => $donhang->id]);

        // Xóa session "Mua Ngay" sau khi đặt hàng thành công
        session()->forget('mua_ngay');

        $emailNhan = $donhang->email;
        if ($emailNhan) {
            try {
                $donhang->load(['chitiets', 'magiamgia']);
                Mail::to($emailNhan)->send(new DonHangMail($donhang));
            } catch (\Exception $e) {
                Log::error('Gửi email xác nhận đơn hàng thất bại: ' . $e->getMessage(), [
                    'donhang_id' => $donhang->id,
                ]);
            }
        }

        if ($request->phuong_thuc_thanhtoan === 'payos') {
            return redirect()->route('payos.checkout', $donhang->id);
        }

        return redirect()->route('xac-nhan-don-hang', $donhang->id);
    }

    public function xacNhan($id)
    {
        $donHangVuaDat = session('don_hang_vua_dat');

        if (Auth::check()) {
            $donhang = Donhang::where('id', $id)
                ->where('user_id', Auth::id())
                ->with(['chitiets', 'magiamgia'])
                ->firstOrFail();
        } else {
            abort_if($donHangVuaDat != $id, 403, 'Bạn không có quyền xem đơn hàng này.');

            $donhang = Donhang::where('id', $id)
                ->whereNull('user_id')
                ->with(['chitiets', 'magiamgia'])
                ->firstOrFail();
        }

        return view('pages.xac-nhan-don-hang', compact('donhang'));
    }

    public function payosCheckout($id)
    {
        if (Auth::check()) {
            $donhang = Donhang::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
        } else {
            abort_if(session('don_hang_vua_dat') != $id, 403);
            $donhang = Donhang::where('id', $id)
                ->whereNull('user_id')
                ->firstOrFail();
        }

        if ($donhang->trang_thai_thanhtoan === 'da_thanh_toan') {
            return redirect()->route('xac-nhan-don-hang', $id)
                ->with('info', 'Đơn hàng đã được thanh toán.');
        }

        $payos = new PayOS(
            config('services.payos.client_id'),
            config('services.payos.api_key'),
            config('services.payos.checksum_key')
        );

        // FIX: Dùng donhang->id * 1_000_000 + random để tránh overflow và trùng orderCode
        // Giới hạn PayOS: < 9007199254740991 (~9 * 10^15)
        // donhang->id tối đa ~9_000_000 thì: 9_000_000 * 1_000_000 + 999_999 = 9_000_000_999_999 (~9 * 10^12) — an toàn
        $orderCode = $donhang->id * 1_000_000 + random_int(0, 999_999);

        $donhang->update(['payos_order_code' => $orderCode]);

        $data = [
            'orderCode'   => $orderCode,
            'amount'      => (int) $donhang->tong_thanh_toan,
            'description' => 'DH' . str_pad($donhang->id, 6, '0', STR_PAD_LEFT),
            'returnUrl'   => route('payos.success') . '?donhang_id=' . $donhang->id,
            'cancelUrl'   => route('payos.cancel')  . '?donhang_id=' . $donhang->id,
        ];

        $response = $payos->createPaymentLink($data);

        return redirect($response['checkoutUrl']);
    }

    public function payosSuccess(Request $request)
    {
        $donhangId = $request->donhang_id;

        if (Auth::check()) {
            $donhang = Donhang::where('id', $donhangId)
                ->where('user_id', Auth::id())
                ->first();
        } else {
            $donhang = session('don_hang_vua_dat') == $donhangId
                ? Donhang::where('id', $donhangId)->whereNull('user_id')->first()
                : null;
        }

        if (!$donhang) {
            return redirect()->route('gio-hang')->with('error', 'Không tìm thấy đơn hàng.');
        }

        if ($donhang->trang_thai_thanhtoan !== 'da_thanh_toan' && $donhang->payos_order_code) {
            try {
                $payos = new PayOS(
                    config('services.payos.client_id'),
                    config('services.payos.api_key'),
                    config('services.payos.checksum_key')
                );
                $info = $payos->getPaymentLinkInformation($donhang->payos_order_code);
                if (isset($info['status']) && $info['status'] === 'PAID') {
                    $donhang->update([
                        'trang_thai_thanhtoan' => 'da_thanh_toan',
                        'trang_thai'           => Donhang::TRANG_THAI_XU_LY,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('PayOS getPaymentLinkInformation lỗi: ' . $e->getMessage(), [
                    'donhang_id' => $donhang->id,
                ]);
            }
        }

        return redirect()->route('xac-nhan-don-hang', $donhangId)
            ->with('success', 'Cảm ơn! Thanh toán của bạn đang được xác nhận.');
    }

    // FIX: Thêm kiểm tra ownership để tránh người dùng tùy ý truyền donhang_id
    public function payosCancel(Request $request)
    {
        $donhangId = $request->donhang_id;

        if (Auth::check()) {
            $donhang = Donhang::where('id', $donhangId)
                ->where('user_id', Auth::id())
                ->first();
        } else {
            $donhang = session('don_hang_vua_dat') == $donhangId
                ? Donhang::where('id', $donhangId)->whereNull('user_id')->first()
                : null;
        }

        if (!$donhang) {
            return redirect()->route('gio-hang')->with('error', 'Không tìm thấy đơn hàng.');
        }

        return redirect()->route('xac-nhan-don-hang', $donhangId)
            ->with('warning', 'Bạn đã hủy thanh toán. Đơn hàng vẫn được giữ, có thể thanh toán lại sau.');
    }

    public function payosWebhook(Request $request)
    {
        $payos = new PayOS(
            config('services.payos.client_id'),
            config('services.payos.api_key'),
            config('services.payos.checksum_key')
        );

        try {
            $webhookData = $payos->verifyPaymentWebhookData($request->all());

            if ($webhookData['code'] === '00') {
                $donhang = Donhang::where('payos_order_code', $webhookData['orderCode'])->first();

                if ($donhang && $donhang->trang_thai_thanhtoan !== 'da_thanh_toan') {
                    $donhang->update([
                        'trang_thai_thanhtoan' => 'da_thanh_toan',
                        'trang_thai'           => Donhang::TRANG_THAI_XU_LY,
                    ]);
                }
            }

            return response()->json(['message' => 'ok']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'error'], 400);
        }
    }
}