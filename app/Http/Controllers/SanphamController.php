<?php

namespace App\Http\Controllers;

use App\Models\Sanpham;
use App\Models\LoaiSanpham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SanphamController extends Controller
{
    public function show(Request $request, $slug)
    {
        $sanpham = Sanpham::with([
            'hinhanhs',
            'bienthesActive',
            'binhluans.user',
            'loai',
        ])->where('slug', $slug)->firstOrFail();

        // Tăng lượt xem: mỗi IP chỉ được tính 1 lần trong 24 giờ
        $this->tangLuotXem($request, $sanpham);

        // Sản phẩm liên quan (cùng danh mục, khác SP hiện tại)
        $lienQuan = Sanpham::with(['anhChinh'])
            ->where('loai_id', $sanpham->loai_id)
            ->where('id', '!=', $sanpham->id)
            ->latest()
            ->take(4)
            ->get();

        return view('pages.san-pham', compact('sanpham', 'lienQuan'));
    }

    private function tangLuotXem(Request $request, Sanpham $sanpham): void
    {
        $ip       = $request->ip();
        $cacheKey = 'view_sp_' . md5($ip) . '_' . $sanpham->id;

        if (! Cache::has($cacheKey)) {
            $sanpham->increment('luot_xem');
            Cache::put($cacheKey, true, now()->addHours(24));
        }
    }
}