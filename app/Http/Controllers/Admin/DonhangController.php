<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donhang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonhangController extends Controller
{
    public function index(Request $request): View
    {
        $query = Donhang::with('user')->latest('ngay_dat');

        // Filter trạng thái đơn
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Filter thanh toán
        if ($request->filled('trang_thai_thanhtoan')) {
            $query->where('trang_thai_thanhtoan', $request->trang_thai_thanhtoan);
        }

        // Filter vận chuyển
        if ($request->filled('trang_thai_van_chuyen')) {
            $query->where('trang_thai_van_chuyen', $request->trang_thai_van_chuyen);
        }

        // Tìm kiếm theo mã đơn hoặc tên người nhận
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('id', $q)
                    ->orWhere('ten_nguoi_nhan', 'like', '%' . $q . '%')
                    ->orWhere('so_dien_thoai', 'like', '%' . $q . '%');
            });
        }

        $donhangs = $query->paginate(15)->withQueryString();

        // Đếm theo từng trạng thái cho tab
        $demTrangThai = [
            'tat_ca'   => Donhang::count(),
            'moi'      => Donhang::where('trang_thai', Donhang::TRANG_THAI_MOI)->count(),
            'xu_ly'    => Donhang::where('trang_thai', Donhang::TRANG_THAI_XU_LY)->count(),
            'hoan_tat' => Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)->count(),
            'huy'      => Donhang::where('trang_thai', Donhang::TRANG_THAI_HUY)->count(),
        ];

        return view('admin.donhang.index', compact('donhangs', 'demTrangThai'));
    }

    public function show(Donhang $donhang): View
    {
        $donhang->load(['user', 'chitiets', 'magiamgia']);
        return view('admin.donhang.show', compact('donhang'));
    }

    public function capNhatTrangThai(Request $request, Donhang $donhang): RedirectResponse
    {
        $request->validate([
            'trang_thai'             => 'sometimes|integer|in:0,1,2,3',
            'trang_thai_thanhtoan'   => 'sometimes|in:chua_thanh_toan,da_thanh_toan,hoan_tien',
            'trang_thai_van_chuyen'  => 'sometimes|in:cho_lay_hang,dang_van_chuyen,da_giao,that_bai,hoan_hang',
            'ma_van_don'             => 'nullable|string|max:100',
            'ghi_chu_admin'          => 'nullable|string|max:500',
        ]);

        $data = $request->only([
            'trang_thai',
            'trang_thai_thanhtoan',
            'trang_thai_van_chuyen',
            'ma_van_don',
            'ghi_chu_admin',
        ]);

        // Tự động set ngày duyệt / ngày giao
        if (isset($data['trang_thai'])) {
            if ((int)$data['trang_thai'] === Donhang::TRANG_THAI_XU_LY && !$donhang->ngay_duyet) {
                $data['ngay_duyet'] = now();
            }
            if ((int)$data['trang_thai'] === Donhang::TRANG_THAI_HOAN_TAT && !$donhang->ngay_giao) {
                $data['ngay_giao'] = now();
            }
        }

        $donhang->update($data);

        return back()->with('success', 'Cập nhật đơn hàng thành công!');
    }
}