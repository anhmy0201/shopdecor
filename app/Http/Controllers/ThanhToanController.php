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

class ThanhToanController extends Controller
{
    /**
     * Lấy giỏ hàng theo user đăng nhập hoặc session (guest).
     * Dùng chung cho index(), apMa(), store().
     */
    private function layGioHang(): Giohang
    {
        if (Auth::check()) {
            return Giohang::firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = session()->getId();
        return Giohang::firstOrCreate(['session_id' => $sessionId]);
    }

    // =========================================================
    // Hiển thị trang thanh toán
    // =========================================================
    public function index()
    {
        $giohang = $this->layGioHang();

        if ($giohang->chitiets()->count() === 0) {
            return redirect()->route('gio-hang')
                ->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $giohang->load(['chitiets.sanpham.anhChinh', 'chitiets.bienthe']);

        // Địa chỉ đã lưu — chỉ có khi đăng nhập
        $diaChis       = Auth::check()
            ? Auth::user()->diaChis()->orderByDesc('mac_dinh')->get()
            : collect();
        $diaChiMacDinh = $diaChis->firstWhere('mac_dinh', true);

        return view('pages.thanh-toan', compact('giohang', 'diaChis', 'diaChiMacDinh'));
    }

    // =========================================================
    // Áp dụng mã giảm giá (AJAX)
    // =========================================================
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

        // Dùng layGioHang() thay vì Auth::user()->giohang để hỗ trợ guest
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

    // =========================================================
    // Đặt hàng — hỗ trợ cả guest lẫn user đăng nhập
    // =========================================================
    public function store(Request $request)
    {
        $request->validate([
            'ten_nguoi_nhan'        => 'required|string|max:255',
            'so_dien_thoai'         => 'required|string|max:15',
            'email'                 => 'nullable|email|max:255',
            'dia_chi_chi_tiet'      => 'required|string|max:500',
            'phuong_xa'             => 'required|string|max:100',
            'quan_huyen'            => 'required|string|max:100',
            'tinh_thanh'            => 'required|string|max:100',
            'phuong_thuc_thanhtoan' => 'required|in:cod,chuyen_khoan',
            'ghi_chu_khach'         => 'nullable|string|max:500',
            'magiamgia_id'          => 'nullable|exists:magiamgia,id',
        ], [
            'ten_nguoi_nhan.required'       => 'Vui lòng nhập họ tên người nhận.',
            'so_dien_thoai.required'        => 'Vui lòng nhập số điện thoại.',
            'dia_chi_chi_tiet.required'     => 'Vui lòng nhập địa chỉ chi tiết.',
            'phuong_xa.required'            => 'Vui lòng nhập phường/xã.',
            'quan_huyen.required'           => 'Vui lòng nhập quận/huyện.',
            'tinh_thanh.required'           => 'Vui lòng nhập tỉnh/thành phố.',
            'phuong_thuc_thanhtoan.required' => 'Vui lòng chọn phương thức thanh toán.',
        ]);

        $giohang = $this->layGioHang();

        if ($giohang->chitiets()->count() === 0) {
            return redirect()->route('gio-hang')
                ->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $giohang->load(['chitiets.sanpham.anhChinh', 'chitiets.bienthe']);

        $tongTienHang = $giohang->tong_tien;
        $soTienGiam   = 0;
        $magiamgiaId  = null;
        $magiamgia    = null;

        // Kiểm tra mã giảm giá lần cuối trước khi lưu
        if ($request->magiamgia_id) {
            $magiamgia = Magiamgia::find($request->magiamgia_id);
            if ($magiamgia && $magiamgia->conHieuLuc()) {
                $soTienGiam  = $magiamgia->tinhSoTienGiam($tongTienHang);
                $magiamgiaId = $magiamgia->id;
            }
        }

        $tongThanhToan = max(0, $tongTienHang - $soTienGiam);

        // Xác định email: user đăng nhập dùng email tài khoản,
        // guest dùng email nhập tay (nếu có)
        $email = Auth::check()
            ? Auth::user()->email
            : $request->email;

        $donhang = null;

        DB::transaction(function () use (
            $request, $giohang, $tongTienHang, $soTienGiam,
            $tongThanhToan, $magiamgiaId, $magiamgia, $email, &$donhang
        ) {
            // 1. Tạo đơn hàng
            // user_id = null nếu guest → đơn vẫn được tạo bình thường
            $donhang = Donhang::create([
                'user_id'               => Auth::id(), // null nếu guest
                'magiamgia_id'          => $magiamgiaId,
                'ten_nguoi_nhan'        => $request->ten_nguoi_nhan,
                'so_dien_thoai'         => $request->so_dien_thoai,
                'email'                 => $email,
                'dia_chi_chi_tiet'      => $request->dia_chi_chi_tiet,
                'phuong_xa'             => $request->phuong_xa,
                'quan_huyen'            => $request->quan_huyen,
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

            // 2. Tạo chi tiết đơn hàng + trừ tồn kho
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

                // Trừ tồn kho atomic
                if ($ct->bienthe_id) {
                    SanphamBienthe::where('id', $ct->bienthe_id)
                        ->decrement('so_luong', $ct->so_luong);
                } else {
                    Sanpham::where('id', $ct->sanpham_id)
                        ->decrement('so_luong', $ct->so_luong);
                }
            }

            // 3. Tăng lượt dùng mã giảm giá
            if ($magiamgia) {
                $magiamgia->tangDaSuDung();
            }

            // 4. Xóa giỏ hàng
            $giohang->chitiets()->delete();

            // 5. Lưu địa chỉ vào dia_chi_user — CHỈ khi đã đăng nhập
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
                        'quan_huyen'       => $request->quan_huyen,
                        'tinh_thanh'       => $request->tinh_thanh,
                        'mac_dinh'         => $laDauTien,
                    ]);
                }
            }
        });

        // Lưu đơn hàng vào session để guest xem được trang xác nhận
        // User đăng nhập cũng lưu để trang xacNhan() dùng chung một logic
        session(['don_hang_vua_dat' => $donhang->id]);

        return redirect()->route('xac-nhan-don-hang', $donhang->id);
    }

    // =========================================================
    // Trang xác nhận đơn hàng — hỗ trợ cả guest lẫn user
    // =========================================================
    public function xacNhan($id)
    {
        // Lấy id đơn hàng vừa đặt từ session (cả guest lẫn user đều lưu ở store())
        $donHangVuaDat = session('don_hang_vua_dat');

        // Chống truy cập trực tiếp URL của người khác:
        // - Nếu đã đăng nhập → kiểm tra user_id
        // - Nếu guest → kiểm tra session don_hang_vua_dat khớp với id
        if (Auth::check()) {
            $donhang = Donhang::where('id', $id)
                ->where('user_id', Auth::id())
                ->with(['chitiets', 'magiamgia'])
                ->firstOrFail();
        } else {
            // Guest chỉ xem được đơn vừa đặt trong session hiện tại
            abort_if($donHangVuaDat != $id, 403, 'Bạn không có quyền xem đơn hàng này.');

            $donhang = Donhang::where('id', $id)
                ->whereNull('user_id')
                ->with(['chitiets', 'magiamgia'])
                ->firstOrFail();
        }

        return view('pages.xac-nhan-don-hang', compact('donhang'));
    }
}