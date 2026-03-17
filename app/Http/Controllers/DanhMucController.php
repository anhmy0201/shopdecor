<?php

namespace App\Http\Controllers;

use App\Models\LoaiSanpham;
use App\Models\Sanpham;

class DanhMucController extends Controller
{
    public function show($slug)
    {
        // Lấy danh mục theo slug
        $danhMuc = LoaiSanpham::where('slug', $slug)->firstOrFail();

        // Sản phẩm trong danh mục, phân trang 12 SP/trang
        $sanphams = Sanpham::with(['anhChinh'])
            ->withCount('binhluans')
            ->where('loai_id', $danhMuc->id)
            ->where(function ($q) {
                $q->where('co_bien_the', false)
                  ->orWhereHas('bienthesActive');
            })
            ->latest()
            ->paginate(12);

        // Tất cả danh mục cho sidebar
        $danhMucs = LoaiSanpham::withCount('sanphams')->get();

        // Sắp xếp
        $sapXep = request('sort', 'moi-nhat');

        // Lấy lại với sort
        $query = Sanpham::with(['anhChinh'])
            ->withCount('binhluans')
            ->where('loai_id', $danhMuc->id)
            ->where(function ($q) {
                $q->where('co_bien_the', false)
                  ->orWhereHas('bienthesActive');
            });

        switch ($sapXep) {
            case 'gia-tang':
                $query->orderBy('gia', 'asc');
                break;
            case 'gia-giam':
                $query->orderBy('gia', 'desc');
                break;
            case 'ban-chay':
                $query->orderByDesc('luot_mua');
                break;
            default:
                $query->latest();
        }

        $sanphams = $query->paginate(12)->withQueryString();

        return view('pages.danh-muc', compact('danhMuc', 'sanphams', 'danhMucs', 'sapXep'));
    }
}