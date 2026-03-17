<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Binhluan;
use App\Models\Sanpham;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BinhluanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Binhluan::with(['user', 'sanpham'])
            ->latest();

        // Filter theo sản phẩm
        if ($request->filled('sanpham_id')) {
            $query->where('sanpham_id', $request->sanpham_id);
        }

        // Filter theo số sao
        if ($request->filled('sao')) {
            $query->where('sao_danh_gia', $request->sao);
        }

        // Tìm kiếm nội dung
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('noi_dung', 'like', '%' . $q . '%')
                    ->orWhereHas('user', fn($u) => $u->where('ho_ten', 'like', '%' . $q . '%'));
            });
        }

        $binhluans = $query->paginate(20)->withQueryString();

        $sanphams = Sanpham::orderBy('ten_san_pham')->get(['id', 'ten_san_pham']);

        $demSao = [];
        for ($i = 1; $i <= 5; $i++) {
            $demSao[$i] = Binhluan::where('sao_danh_gia', $i)->count();
        }

        return view('admin.binhluan.index', compact('binhluans', 'sanphams', 'demSao'));
    }

    public function destroy(Binhluan $binhluan): RedirectResponse
    {
        $binhluan->delete();

        return back()->with('success', 'Đã xóa bình luận!');
    }
}