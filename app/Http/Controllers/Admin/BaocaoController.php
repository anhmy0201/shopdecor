<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donhang;
use App\Models\LoaiSanpham;
use App\Models\Sanpham;
use App\Models\User;
use App\Exports\BaocaoExport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class BaocaoController extends Controller
{
    public function index(Request $request): View
    {
        $driver  = config('database.connections.' . config('database.default') . '.driver');
        $namFilter  = $request->input('nam',   now()->year);
        $thangFilter = $request->input('thang', ''); // '' = tất cả tháng

        // ── DOANH THU THEO THÁNG (12 tháng hoặc theo năm filter) ──
        if ($driver === 'sqlite') {
            $qDoanhThu = Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)
                ->selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as thang,
                              CAST(strftime('%Y', created_at) AS INTEGER) as nam,
                              SUM(tong_thanh_toan) as tong,
                              COUNT(*) as so_don")
                ->whereRaw("strftime('%Y', created_at) = ?", [$namFilter])
                ->groupByRaw("strftime('%Y', created_at), strftime('%m', created_at)")
                ->orderByRaw("strftime('%m', created_at)");
        } else {
            $qDoanhThu = Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)
                ->selectRaw('MONTH(created_at) as thang, YEAR(created_at) as nam,
                              SUM(tong_thanh_toan) as tong, COUNT(*) as so_don')
                ->whereYear('created_at', $namFilter)
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('MONTH(created_at)');
        }
        $doanhThuThang = $qDoanhThu->get()->values();

        // Điền đủ 12 tháng (tháng không có đơn = 0)
        $doanhThuFull = collect(range(1, 12))->map(function ($t) use ($doanhThuThang, $namFilter) {
            $found = $doanhThuThang->firstWhere('thang', $t);
            return [
                'thang'  => $t,
                'nam'    => $namFilter,
                'tong'   => $found ? (float) $found->tong   : 0,
                'so_don' => $found ? (int)   $found->so_don : 0,
            ];
        });

        // ── DOANH THU THEO NGÀY (khi chọn tháng cụ thể) ──
        $doanhThuNgay = collect();
        if ($thangFilter !== '') {
            if ($driver === 'sqlite') {
                $doanhThuNgay = Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)
                    ->selectRaw("CAST(strftime('%d', created_at) AS INTEGER) as ngay,
                                  SUM(tong_thanh_toan) as tong, COUNT(*) as so_don")
                    ->whereRaw("strftime('%Y', created_at) = ? AND strftime('%m', created_at) = ?",
                                [$namFilter, str_pad($thangFilter, 2, '0', STR_PAD_LEFT)])
                    ->groupByRaw("strftime('%d', created_at)")
                    ->orderByRaw("strftime('%d', created_at)")
                    ->get();
            } else {
                $doanhThuNgay = Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)
                    ->selectRaw('DAY(created_at) as ngay, SUM(tong_thanh_toan) as tong, COUNT(*) as so_don')
                    ->whereYear('created_at', $namFilter)
                    ->whereMonth('created_at', $thangFilter)
                    ->groupByRaw('DAY(created_at)')
                    ->orderByRaw('DAY(created_at)')
                    ->get();
            }
        }

        // ── TRẠNG THÁI ĐƠN (toàn bộ) ──
        $trangThaiDon = [
            'cho_xac_nhan' => Donhang::where('trang_thai', Donhang::TRANG_THAI_MOI)->count(),
            'dang_xu_ly'   => Donhang::where('trang_thai', Donhang::TRANG_THAI_XU_LY)->count(),
            'hoan_tat'     => Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)->count(),
            'da_huy'       => Donhang::where('trang_thai', Donhang::TRANG_THAI_HUY)->count(),
        ];

        // ── TOP SẢN PHẨM ──
        $topSanpham = Sanpham::with('loai')
            ->orderByDesc('luot_mua')
            ->take(10)
            ->get();

        // ── TOP DANH MỤC ──
        $topDanhMuc = LoaiSanpham::withCount('sanphams')
            ->orderByDesc('sanphams_count')
            ->take(5)
            ->get();

        // ── TOP KHÁCH HÀNG ──
        $topKhach = User::where('quyen_han', User::USER)
            ->withCount('donhangs')
            ->withSum('donhangs', 'tong_thanh_toan')
            ->orderByDesc('donhangs_sum_tong_thanh_toan')
            ->take(5)
            ->get();

        // ── THỐNG KÊ TỔNG QUAN ──
        $stats = [
            'tong_doanh_thu' => Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)->sum('tong_thanh_toan'),
            'tong_don_hang'  => Donhang::count(),
            'tong_san_pham'  => Sanpham::count(),
            'tong_khach'     => User::where('quyen_han', User::USER)->count(),
            'trung_binh_don' => Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)->avg('tong_thanh_toan') ?? 0,
            'het_hang'       => Sanpham::where('co_bien_the', false)->where('so_luong', 0)->count(),
            // Doanh thu năm đang filter
            'doanh_thu_nam'  => Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)
                                    ->whereYear('created_at', $namFilter)->sum('tong_thanh_toan'),
        ];

        // Danh sách năm có đơn hàng (để dropdown filter)
        if ($driver === 'sqlite') {
            $danhSachNam = Donhang::selectRaw("DISTINCT CAST(strftime('%Y', created_at) AS INTEGER) as nam")
                ->orderByDesc('nam')->pluck('nam');
        } else {
            $danhSachNam = Donhang::selectRaw('DISTINCT YEAR(created_at) as nam')
                ->orderByDesc('nam')->pluck('nam');
        }
        if ($danhSachNam->isEmpty()) {
            $danhSachNam = collect([now()->year]);
        }

        return view('admin.baocao.index', compact(
            'doanhThuFull',
            'doanhThuNgay',
            'trangThaiDon',
            'topSanpham',
            'topDanhMuc',
            'topKhach',
            'stats',
            'namFilter',
            'thangFilter',
            'danhSachNam'
        ));
    }

    /**
     * Xuất Excel báo cáo tổng hợp.
     */
    public function exportExcel(Request $request)
    {
        $nam   = $request->input('nam',   now()->year);
        $thang = $request->input('thang', '');

        $tenFile = 'baocao_' . $nam . ($thang ? "_thang{$thang}" : '') . '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new BaocaoExport($nam, $thang), $tenFile);
    }
}