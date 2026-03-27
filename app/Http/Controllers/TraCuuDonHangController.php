<?php

namespace App\Http\Controllers;

use App\Models\Donhang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TraCuuDonHangController extends Controller
{
    // =====================================================
    // Hiển thị form tra cứu
    // =====================================================
    public function index()
    {
        // User đã đăng nhập → chuyển thẳng sang trang đơn hàng
        if (Auth::check()) {
            return redirect()->route('don-hang')
                ->with('info', 'Bạn đã đăng nhập, xem đơn hàng tại đây.');
        }

        return view('pages.tra-cuu-don-hang');
    }

    // =====================================================
    // Xử lý tra cứu — SĐT + mã đơn
    // =====================================================
    public function traCuu(Request $request)
    {
        $request->validate([
            'so_dien_thoai' => 'required|string|max:15',
            'ma_don_hang'   => 'required|string|max:20',
        ], [
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại.',
            'ma_don_hang.required'   => 'Vui lòng nhập mã đơn hàng.',
        ]);

        // Mã đơn dạng "DH000123" → lấy số nguyên
        $maDon = strtoupper(trim($request->ma_don_hang));
        $id    = (int) preg_replace('/[^0-9]/', '', $maDon);

        $donhang = Donhang::where('id', $id)
            ->where('so_dien_thoai', trim($request->so_dien_thoai))
            ->with(['chitiets', 'magiamgia'])
            ->first();

        if (!$donhang) {
            return back()
                ->withInput()
                ->withErrors([
                    'ma_don_hang' => 'Không tìm thấy đơn hàng. Vui lòng kiểm tra lại SĐT và mã đơn.',
                ]);
        }

        return view('pages.ket-qua-tra-cuu', compact('donhang'));
    }
}
