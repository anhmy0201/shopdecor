<?php

namespace App\Http\Controllers;

use App\Models\Sanpham;
use App\Models\LoaiSanpham;

class HomeController extends Controller
{
    public function index()
    {
        // Sản phẩm nổi bật (6 SP có lượt mua cao nhất)
        $noiBat = Sanpham::with(['anhChinh'])
            ->withCount('binhluans')
            ->orderByDesc('luot_mua')
            ->take(6)
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

        // Số lượng SP theo danh mục (sidebar)
        $soLuong = LoaiSanpham::withCount('sanphams')
            ->get()
            ->mapWithKeys(fn($cat) => [
                match($cat->slug) {
                    'tuong-figurine' => 'tuong',
                    'den-decor'      => 'den',
                    'cay-xanh-mini'  => 'cay',
                    'van-phong-pham' => 'vanphong',
                    'to-chuc-ban'    => 'tochuc',
                    'desk-mat'       => 'deskmat',
                    default          => $cat->slug,
                } => $cat->sanphams_count
            ]);

        return view('pages.home', compact('noiBat', 'tatCa', 'banChay', 'soLuong'));
    }
}