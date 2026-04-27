<?php

namespace App\Http\Controllers;

use App\Models\Donhang;
use Illuminate\Support\Facades\DB;
use App\Models\Binhluan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonhangController extends Controller
{
   
    public function index(Request $request)
    {
        $trangThai = $request->get('trang_thai', 'tat-ca');

        $query = Donhang::where('user_id', Auth::id())
            ->with(['chitiets'])
            ->latest('ngay_dat');

        switch ($trangThai) {
            case 'cho-xac-nhan':
                $query->where('trang_thai', Donhang::TRANG_THAI_MOI);
                break;
            case 'dang-xu-ly':
                $query->where('trang_thai', Donhang::TRANG_THAI_XU_LY);
                break;
            case 'hoan-tat':
                $query->where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT);
                break;
            case 'da-huy':
                $query->where('trang_thai', Donhang::TRANG_THAI_HUY);
                break;
        }

        $donhangs = $query->paginate(8)->withQueryString();

        // Đếm từng tab để hiện badge số
        $dem = [
            'tat-ca'       => Donhang::where('user_id', Auth::id())->count(),
            'cho-xac-nhan' => Donhang::where('user_id', Auth::id())->where('trang_thai', Donhang::TRANG_THAI_MOI)->count(),
            'dang-xu-ly'   => Donhang::where('user_id', Auth::id())->where('trang_thai', Donhang::TRANG_THAI_XU_LY)->count(),
            'hoan-tat'     => Donhang::where('user_id', Auth::id())->where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)->count(),
            'da-huy'       => Donhang::where('user_id', Auth::id())->where('trang_thai', Donhang::TRANG_THAI_HUY)->count(),
        ];

        return view('pages.don-hang', compact('donhangs', 'trangThai', 'dem'));
    }

    public function chiTiet($id)
    {
        $donhang = Donhang::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['chitiets.sanpham', 'magiamgia'])
            ->firstOrFail();

        // Chỉ tính đánh giá khi đơn đã hoàn tất
        $sanphamChuaDanhGia = collect();
        $sanphamDaDanhGia   = collect();

        if ($donhang->trang_thai === Donhang::TRANG_THAI_HOAN_TAT) {
            $sanphamIds = $donhang->chitiets
                ->whereNotNull('sanpham_id')
                ->pluck('sanpham_id')
                ->unique();

            $daDanhGiaIds = Binhluan::where('user_id', Auth::id())
                ->whereIn('sanpham_id', $sanphamIds)
                ->pluck('sanpham_id');

            $sanphamChuaDanhGia = $donhang->chitiets
                ->whereNotNull('sanpham_id')
                ->whereNotIn('sanpham_id', $daDanhGiaIds->toArray())
                ->unique('sanpham_id');

            $sanphamDaDanhGia = $donhang->chitiets
                ->whereNotNull('sanpham_id')
                ->whereIn('sanpham_id', $daDanhGiaIds->toArray())
                ->unique('sanpham_id');
        }

        return view('pages.chi-tiet-don-hang', compact(
            'donhang',
            'sanphamChuaDanhGia',
            'sanphamDaDanhGia'
        ));
    }

    public function huy($id)
    {
        $donhang = Donhang::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('chitiets')
            ->firstOrFail();

        if (!$donhang->coTheHuy()) {
            return back()->with('error', 'Đơn hàng này không thể hủy.');
        }

        // Nếu đã thanh toán qua PayOS → cần hủy link thanh toán trước khi hủy đơn
        if ($donhang->phuong_thuc_thanhtoan === 'payos' && $donhang->daThanhToan()) {
            try {
                $payos = new \PayOS\PayOS(
                    config('services.payos.client_id'),
                    config('services.payos.api_key'),
                    config('services.payos.checksum_key')
                );
                $payos->cancelPaymentLink($donhang->payos_order_code, 'Khách hủy đơn');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('PayOS cancel lỗi: ' . $e->getMessage(), [
                    'donhang_id' => $donhang->id,
                ]);
                return back()->with('error', 'Không thể hủy thanh toán PayOS. Vui lòng liên hệ hỗ trợ.');
            }
        }

        DB::transaction(function () use ($donhang) {
            // Hoàn tồn kho
            foreach ($donhang->chitiets as $ct) {
                if ($ct->bienthe_id) {
                    \App\Models\SanphamBienthe::where('id', $ct->bienthe_id)
                        ->increment('so_luong', $ct->so_luong);
                } else {
                    \App\Models\Sanpham::where('id', $ct->sanpham_id)
                        ->increment('so_luong', $ct->so_luong);
                }
            }

            $donhang->update([
                'trang_thai'           => Donhang::TRANG_THAI_HUY,
                'trang_thai_thanhtoan' => $donhang->daThanhToan() ? 'hoan_tien' : $donhang->trang_thai_thanhtoan,
            ]);
        });

        $msg = $donhang->daThanhToan()
            ? 'Đã hủy đơn hàng. Yêu cầu hoàn tiền đã được gửi đến PayOS.'
            : 'Đã hủy đơn hàng #DH' . str_pad($donhang->id, 6, '0', STR_PAD_LEFT) . '.';

        return back()->with('success', $msg);
    }

    public function danhGia(Request $request, $donhangId)
    {
        $request->validate([
            'sanpham_id'   => 'required|exists:sanpham,id',
            'sao_danh_gia' => 'required|integer|min:1|max:5',
            'noi_dung'     => 'required|string|max:1000',
        ], [
            'noi_dung.required' => 'Vui lòng nhập nội dung đánh giá.',
        ]);

        // Xác nhận đơn hàng thuộc user này và đã hoàn tất
        $donhang = Donhang::where('id', $donhangId)
            ->where('user_id', Auth::id())
            ->where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)
            ->firstOrFail();

        // Xác nhận sản phẩm có trong đơn hàng
        $coTrongDon = $donhang->chitiets()
            ->where('sanpham_id', $request->sanpham_id)
            ->exists();

        if (!$coTrongDon) {
            return back()->with('error', 'Sản phẩm không thuộc đơn hàng này.');
        }

        // updateOrCreate: mỗi user chỉ đánh giá 1 lần / 1 SP (unique DB)
        Binhluan::updateOrCreate(
            [
                'user_id'    => Auth::id(),
                'sanpham_id' => $request->sanpham_id,
            ],
            [
                'sao_danh_gia' => $request->sao_danh_gia,
                'noi_dung'     => $request->noi_dung,
                'ngay_dang'    => now(),
            ]
        );

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}