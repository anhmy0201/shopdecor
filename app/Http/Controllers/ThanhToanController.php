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
        $giohang = $this->layGioHang();

        if ($giohang->chitiets()->count() === 0) {
            return redirect()->route('gio-hang')
                ->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $giohang->load(['chitiets.sanpham.anhChinh', 'chitiets.bienthe']);

        $diaChis       = Auth::check()
            ? Auth::user()->diaChis()->orderByDesc('mac_dinh')->get()
            : collect();
        $diaChiMacDinh = $diaChis->firstWhere('mac_dinh', true);

        return view('pages.thanh-toan', compact('giohang', 'diaChis', 'diaChiMacDinh'));
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
            // User đã đăng nhập: kiểm tra lịch sử đơn hàng
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
            // Guest: kiểm tra session — mỗi session chỉ dùng mỗi mã 1 lần
            $maDaDungTrongSession = session('guest_ma_da_dung', []);
            if (in_array($ma->id, $maDaDungTrongSession)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá này đã được áp dụng cho đơn hàng của bạn.',
                ]);
            }
        }

        $giohang = $this->layGioHang();
        $giohang->load('chitiets');
        $tongTienHang = $giohang->tong_tien;

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

    public function store(Request $request)
    {
        // Nếu user chọn địa chỉ đã lưu (_dia_chi_chon), lấy dữ liệu từ DB
        // để tránh bị bypass validate bằng cách sửa form phía client
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
            // Guest bắt buộc nhập email để nhận xác nhận đơn hàng
            // User đăng nhập thì lấy email từ tài khoản, không cần nhập lại
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

        if ($giohang->chitiets()->count() === 0) {
            return redirect()->route('gio-hang')
                ->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $giohang->load(['chitiets.sanpham.anhChinh', 'chitiets.bienthe']);

        foreach ($giohang->chitiets as $ct) {
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

                return redirect()->route('gio-hang')->with('error', $msg);
            }
        }

        $tongTienHang = $giohang->tong_tien;
        $soTienGiam   = 0;
        $magiamgiaId  = null;
        $magiamgia    = null;

        if ($request->magiamgia_id) {
            $magiamgia = Magiamgia::find($request->magiamgia_id);
            if ($magiamgia && $magiamgia->conHieuLuc()) {
                // Kiểm tra đã dùng mã này chưa (user: theo DB, guest: theo session)
                if (Auth::check()) {
                    $daDung = Donhang::where('user_id', Auth::id())
                        ->where('magiamgia_id', $magiamgia->id)
                        ->exists();
                } else {
                    $maDaDungTrongSession = session('guest_ma_da_dung', []);
                    $daDung = in_array($magiamgia->id, $maDaDungTrongSession);
                }

                if (!$daDung) {
                    $soTienGiam = $magiamgia->tinhSoTienGiam($tongTienHang);
                    if ($soTienGiam > 0) {
                        $magiamgiaId = $magiamgia->id;
                    } else {
                        $soTienGiam = 0;
                        $magiamgia  = null;
                    }
                } else {
                    $magiamgia = null; // bỏ qua vì đã dùng
                }
            }
        }

        $tongThanhToan = max(0, $tongTienHang - $soTienGiam);

        $email = Auth::check()
            ? Auth::user()->email
            : $request->email;

        $donhang = null;

        DB::transaction(function () use (
            $request, $giohang, $tongTienHang, $soTienGiam,
            $tongThanhToan, $magiamgiaId, $magiamgia, $email, &$donhang
        ) {
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

            foreach ($giohang->chitiets as $ct) {
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
            }

            if ($magiamgia) {
                $magiamgia->tangDaSuDung();
                // Ghi vào session cho guest để không dùng lại mã này
                if (!Auth::check()) {
                    $dsDaDung   = session('guest_ma_da_dung', []);
                    $dsDaDung[] = $magiamgia->id;
                    session(['guest_ma_da_dung' => array_unique($dsDaDung)]);
                }
            }

            $giohang->chitiets()->delete();

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
                        'tinh_thanh'       => $request->tinh_thanh,
                        'mac_dinh'         => $laDauTien,
                    ]);
                }
            }
        });

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

        // Gửi email xác nhận đơn hàng
        // User đăng nhập: email lấy từ tài khoản (luôn có)
        // Guest: email lấy từ form (bắt buộc nhập, luôn có)
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

        // Tạo orderCode unique: donhang_id ghép 6 chữ số cuối unix timestamp
        // Tối đa 12 chữ số, an toàn với giới hạn int của PayOS (< 9007199254740991)
        $suffix    = (int) substr((string) time(), -6);
        $orderCode = (int) ($donhang->id . str_pad($suffix, 6, '0', STR_PAD_LEFT));

        // Lưu orderCode TRƯỚC khi gọi PayOS để webhook luôn tìm được đơn
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

        // Nếu chưa được cập nhật bởi webhook, kiểm tra lại với PayOS API
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

    public function payosCancel(Request $request)
    {
        return redirect()->route('xac-nhan-don-hang', $request->donhang_id)
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