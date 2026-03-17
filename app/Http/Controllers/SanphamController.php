<?php

namespace App\Http\Controllers;

use App\Models\Sanpham;
use App\Models\LoaiSanpham;
use Illuminate\Http\Request;

class SanphamController extends Controller
{
    public function show($slug)
    {
        $sanpham = Sanpham::with([
            'hinhanhs',
            'bienthesActive',
            'binhluans.user',
            'loai',
        ])->where('slug', $slug)->firstOrFail();

        // Tăng lượt xem
        $sanpham->increment('luot_xem');

        // Sản phẩm liên quan (cùng danh mục, khác SP hiện tại)
        $lienQuan = Sanpham::with(['anhChinh'])
            ->where('loai_id', $sanpham->loai_id)
            ->where('id', '!=', $sanpham->id)
            ->latest()
            ->take(4)
            ->get();

        return view('pages.san-pham', compact('sanpham', 'lienQuan'));
    }
}