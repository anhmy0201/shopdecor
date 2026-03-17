<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donhang;
use App\Models\LoaiSanpham;
use App\Models\Sanpham;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BaocaoController extends Controller
{
    public function index(Request $request): View
    {
        // trang_thai = 2 (TRANG_THAI_HOAN_TAT), cột tong_thanh_toan
        $doanhThuThang = Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)
            ->selectRaw('MONTH(created_at) as thang, YEAR(created_at) as nam, SUM(tong_thanh_toan) as tong')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->take(12)
            ->get()
            ->reverse()
            ->values();

        // trang_thai là integer: 0=mới, 1=xử lý, 2=hoàn tất, 3=hủy
        $trangThaiDon = [
            'cho_xac_nhan' => Donhang::where('trang_thai', Donhang::TRANG_THAI_MOI)->count(),
            'dang_xu_ly'   => Donhang::where('trang_thai', Donhang::TRANG_THAI_XU_LY)->count(),
            'hoan_tat'     => Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)->count(),
            'da_huy'       => Donhang::where('trang_thai', Donhang::TRANG_THAI_HUY)->count(),
        ];

        $topSanpham = Sanpham::with('loai')
            ->orderByDesc('luot_mua')
            ->take(10)
            ->get();

        $topDanhMuc = LoaiSanpham::withCount('sanphams')
            ->orderByDesc('sanphams_count')
            ->take(5)
            ->get();

        $topKhach = User::where('quyen_han', User::USER)
            ->withCount('donhangs')
            ->withSum('donhangs', 'tong_thanh_toan')   // cột đúng
            ->orderByDesc('donhangs_sum_tong_thanh_toan')
            ->take(5)
            ->get();

        $stats = [
            'tong_doanh_thu' => Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)->sum('tong_thanh_toan'),
            'tong_don_hang'  => Donhang::count(),
            'tong_san_pham'  => Sanpham::count(),
            'tong_khach'     => User::where('quyen_han', User::USER)->count(),
            'trung_binh_don' => Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)->avg('tong_thanh_toan') ?? 0,
            'het_hang'       => Sanpham::where('co_bien_the', false)->where('so_luong', 0)->count(),
        ];

        return view('admin.baocao.index', compact(
            'doanhThuThang',
            'trangThaiDon',
            'topSanpham',
            'topDanhMuc',
            'topKhach',
            'stats'
        ));
    }
}