<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Sanpham;
use App\Models\LoaiSanpham;

class HomeController extends Controller
{
    public function index()
    {
        // Sản phẩm nổi bật (4 SP có lượt mua cao nhất)
        $noiBat = Sanpham::with(['anhChinh'])
            ->withCount('binhluans')
            ->orderByDesc('luot_mua')
            ->take(4)
            ->get();

        // Tất cả sản phẩm (8 SP mới nhất)
        $tatCa = Sanpham::with(['anhChinh'])
            ->withCount('binhluans')
            ->latest()
            ->take(8)
            ->get();

        // Bán chạy nhất (sidebar)
        $banChay = Sanpham::with(['anhChinh'])
            ->orderByDesc('luot_xem')
            ->take(3)
            ->get();

        // Danh mục sản phẩm (dynamic từ DB)
        $danhMucs = LoaiSanpham::withCount('sanphams')
            ->orderBy('ten_loai')
            ->get();

        $banners = Banner::hoatDong()->get();

        return view('pages.home', compact('noiBat', 'tatCa', 'banChay', 'danhMucs', 'banners'));
    }
}