<?php

namespace App\Http\Controllers;

use App\Models\Giohang;
use App\Models\ChitietGiohang;
use App\Models\Sanpham;
use App\Models\SanphamBienthe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GiohangController extends Controller
{
    // Lấy hoặc tạo giỏ hàng cho user/session
    private function layGioHang()
    {
        if (Auth::check()) {
            return Giohang::firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = session()->getId();
        return Giohang::firstOrCreate(['session_id' => $sessionId]);
    }

    // Xem giỏ hàng
    public function index()
    {
        $giohang = $this->layGioHang();
        $giohang->load(['chitiets.sanpham.anhChinh', 'chitiets.bienthe']);

        return view('pages.gio-hang', compact('giohang'));
    }

    // Thêm vào giỏ
    public function them(Request $request)
    {
        $request->validate([
            'san_pham_id' => 'required|exists:sanpham,id',
            'so_luong'    => 'integer|min:1|max:99',
            'bienthe_id'  => 'nullable|exists:sanpham_bienthe,id',
        ]);

        $sanpham   = Sanpham::findOrFail($request->san_pham_id);
        $bientheId = $request->bienthe_id;
        $soLuong   = $request->so_luong ?? 1;

        // Lấy giá
        $gia = $sanpham->gia;
        if ($bientheId) {
            $bienthe = SanphamBienthe::find($bientheId);
            $gia = $bienthe ? $bienthe->gia : $sanpham->gia;
        }

        $giohang = $this->layGioHang();

        // Kiểm tra đã có chưa
        // Dùng whereNull khi bienthe_id = null vì MySQL không match NULL bằng WHERE = NULL
        $existing = ChitietGiohang::where('giohang_id', $giohang->id)
            ->where('sanpham_id', $sanpham->id)
            ->when(
                $bientheId === null,
                fn($q) => $q->whereNull('bienthe_id'),
                fn($q) => $q->where('bienthe_id', $bientheId)
            )
            ->first();

        if ($existing) {
            $existing->increment('so_luong', $soLuong);
        } else {
            ChitietGiohang::create([
                'giohang_id' => $giohang->id,
                'sanpham_id' => $sanpham->id,
                'bienthe_id' => $bientheId,
                'so_luong'   => $soLuong,
                'gia'        => $gia,
            ]);
        }

        $tongSoLuong = $giohang->chitiets()->sum('so_luong');

        return response()->json([
            'success'      => true,
            'message'      => 'Đã thêm vào giỏ hàng!',
            'tong_so_luong' => $tongSoLuong,
        ]);
    }

    // Cập nhật số lượng
    public function capNhat(Request $request, $id)
    {
        $request->validate(['so_luong' => 'required|integer|min:1|max:99']);

        $giohang = $this->layGioHang();
        $chitiet = ChitietGiohang::where('id', $id)
            ->where('giohang_id', $giohang->id)
            ->firstOrFail();

        $chitiet->update(['so_luong' => $request->so_luong]);

        return response()->json([
            'success'    => true,
            'thanh_tien' => number_format($chitiet->thanh_tien) . 'đ',
            'tong_tien'  => number_format($giohang->fresh()->load('chitiets')->tong_tien) . 'đ',
        ]);
    }

    // Xóa 1 sản phẩm
    public function xoa($id)
    {
        $giohang = $this->layGioHang();
        ChitietGiohang::where('id', $id)
            ->where('giohang_id', $giohang->id)
            ->delete();

        return response()->json(['success' => true]);
    }

    // Xóa toàn bộ giỏ
    public function xoaTat()
    {
        $giohang = $this->layGioHang();
        $giohang->chitiets()->delete();

        return redirect()->route('gio-hang')->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }
}