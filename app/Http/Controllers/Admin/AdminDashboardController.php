<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Binhluan;
use App\Models\Donhang;
use App\Models\Sanpham;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'tong_san_pham'       => Sanpham::count(),
            'tong_don_hang'       => Donhang::count(),
            'tong_nguoi_dung'     => User::where('quyen_han', User::USER)->count(),
            'tong_binh_luan'      => Binhluan::count(),

            // trang_thai = 2 (integer) là hoàn tất, cột tong_thanh_toan
            'doanh_thu_thang'     => Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)
                                        ->whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->sum('tong_thanh_toan'),

            // trang_thai = 0 là đơn mới
            'don_cho_xac_nhan'    => Donhang::where('trang_thai', Donhang::TRANG_THAI_MOI)->count(),
            'san_pham_het_hang'   => Sanpham::where('co_bien_the', false)->where('so_luong', 0)->count(),
            'binh_luan_cho_duyet' => Binhluan::count(),
        ];

        $donHangMoi = Donhang::with('user')
            ->latest()
            ->take(8)
            ->get();

        $banChay = Sanpham::with('loai')
            ->orderByDesc('luot_mua')
            ->take(8)
            ->get();

        $binhLuanMoi = Binhluan::with(['user', 'sanpham'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'donHangMoi',
            'banChay',
            'binhLuanMoi'
        ));
    }
}