<?php

namespace App\Http\Controllers;

use App\Models\Donhang;
use App\Models\Magiamgia;
use App\Models\Sanpham;
use App\Models\SanphamBienthe;
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

        // FIX: Dùng 1 query GROUP BY thay vì 5 query COUNT riêng lẻ
        $demTheo = Donhang::where('user_id', Auth::id())
            ->selectRaw('trang_thai, count(*) as total')
            ->groupBy('trang_thai')
            ->pluck('total', 'trang_thai');

        $tongTatCa = $demTheo->sum();

        $dem = [
            'tat-ca'       => $tongTatCa,
            'cho-xac-nhan' => $demTheo->get(Donhang::TRANG_THAI_MOI, 0),
            'dang-xu-ly'   => $demTheo->get(Donhang::TRANG_THAI_XU_LY, 0),
            'hoan-tat'     => $demTheo->get(Donhang::TRANG_THAI_HOAN_TAT, 0),
            'da-huy'       => $demTheo->get(Donhang::TRANG_THAI_HUY, 0),
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

        DB::transaction(function () use ($donhang) {
            // Hoàn tồn kho và giảm lượt mua
            foreach ($donhang->chitiets as $ct) {
                if ($ct->bienthe_id) {
                    SanphamBienthe::where('id', $ct->bienthe_id)
                        ->increment('so_luong', $ct->so_luong);
                } else {
                    Sanpham::where('id', $ct->sanpham_id)
                        ->increment('so_luong', $ct->so_luong);
                }

                // Fix #2: Hoàn lại lượt mua khi hủy đơn
                Sanpham::where('id', $ct->sanpham_id)
                    ->decrement('luot_mua', $ct->so_luong);
            }

            // Fix #1: Hoàn lại lượt dùng mã giảm giá khi hủy đơn
            if ($donhang->magiamgia_id) {
                Magiamgia::where('id', $donhang->magiamgia_id)
                    ->where('da_su_dung', '>', 0)
                    ->decrement('da_su_dung');
            }

            $donhang->update(['trang_thai' => Donhang::TRANG_THAI_HUY]);
        });

        return back()->with('success', 'Đã hủy đơn hàng #DH' . str_pad($donhang->id, 6, '0', STR_PAD_LEFT) . '. Tồn kho đã được hoàn lại.');
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