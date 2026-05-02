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
        // FIX: Đếm badge đơn hàng theo từng trạng thái bằng 1 query GROUP BY
        // thay vì 5 query COUNT riêng lẻ
        $demDonHang = Donhang::selectRaw('trang_thai, count(*) as total')
            ->groupBy('trang_thai')
            ->pluck('total', 'trang_thai');

        $stats = [
            'tong_san_pham'   => Sanpham::count(),
            'tong_don_hang'   => $demDonHang->sum(),
            'tong_nguoi_dung' => User::where('quyen_han', User::USER)->count(),
            'tong_binh_luan'  => Binhluan::count(),

            // Doanh thu tháng hiện tại từ đơn hoàn tất
            'doanh_thu_thang' => Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('tong_thanh_toan'),

            // Đơn mới chờ xác nhận
            'don_cho_xac_nhan'  => $demDonHang->get(Donhang::TRANG_THAI_MOI, 0),

            // Sản phẩm hết hàng (không có biến thể)
            'san_pham_het_hang' => Sanpham::where('co_bien_the', false)->where('so_luong', 0)->count(),

            // FIX: Bảng binhluan không có cột "duyet", dùng tong_binh_luan thay thế
            // Nếu sau này thêm tính năng duyệt bình luận, thêm cột `duyet` vào migration
            // và đổi lại thành: Binhluan::where('duyet', false)->count()
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