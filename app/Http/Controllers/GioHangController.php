<?php

namespace App\Http\Controllers;

use App\Models\Giohang;
use App\Models\ChitietGiohang;
use App\Models\Sanpham;
use App\Models\SanphamBienthe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GioHangController extends Controller
{
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
        // Xóa session mua_ngay khi user quay lại giỏ hàng
        // để tránh xung đột khi vào thanh toán từ giỏ hàng thường
        session()->forget('mua_ngay');

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
            'success'       => true,
            'message'       => 'Đã thêm vào giỏ hàng!',
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

        $soLuong = (int) $request->so_luong;

        $chitiet->update(['so_luong' => $soLuong]);
        $thanhTien = $soLuong * $chitiet->gia;
        $tongTien  = $giohang->chitiets()->selectRaw('SUM(so_luong * gia) as total')->value('total') ?? 0;

        return response()->json([
            'success'    => true,
            'thanh_tien' => number_format($thanhTien) . 'đ',
            'tong_tien'  => number_format($tongTien) . 'đ',
        ]);
    }

    // FIX: Trả về JSON nếu request là AJAX, redirect nếu là form thường
    public function xoa(Request $request, $id)
    {
        $giohang = $this->layGioHang();
        ChitietGiohang::where('id', $id)
            ->where('giohang_id', $giohang->id)
            ->delete();

        $tongSoLuong = $giohang->chitiets()->sum('so_luong');
        $tongTien    = $giohang->chitiets()->selectRaw('SUM(so_luong * gia) as total')->value('total') ?? 0;

        if ($request->expectsJson()) {
            return response()->json([
                'success'       => true,
                'message'       => 'Đã xóa sản phẩm khỏi giỏ hàng!',
                'tong_so_luong' => $tongSoLuong,
                'tong_tien'     => number_format($tongTien) . 'đ',
            ]);
        }

        return redirect()->route('gio-hang')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    // Mua ngay — lưu session, không đụng giỏ hàng
    public function muaNgay(Request $request)
    {
        $request->validate([
            'san_pham_id' => 'required|exists:sanpham,id',
            'so_luong'    => 'integer|min:1|max:99',
            'bienthe_id'  => 'nullable|exists:sanpham_bienthe,id',
        ]);

        $sanpham   = Sanpham::findOrFail($request->san_pham_id);
        $bientheId = $request->bienthe_id;
        $soLuong   = (int) ($request->so_luong ?? 1);

        // Lấy giá từ biến thể hoặc sản phẩm
        $gia = $sanpham->gia;
        if ($bientheId) {
            $bienthe = SanphamBienthe::find($bientheId);
            $gia = $bienthe ? $bienthe->gia : $sanpham->gia;
        }
        session(['mua_ngay' => [
            'san_pham_id' => $sanpham->id,
            'bienthe_id'  => $bientheId,
            'so_luong'    => $soLuong,
            'gia'         => $gia,
            'ten_san_pham' => $sanpham->ten_san_pham,
        ]]);

        return redirect()->route('thanh-toan');
    }

    // Xóa toàn bộ giỏ
    public function xoaTat(Request $request)
    {
        $giohang = $this->layGioHang();
        $giohang->chitiets()->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa toàn bộ giỏ hàng!',
            ]);
        }

        return redirect()->route('gio-hang')->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }
}