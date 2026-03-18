<?php

namespace App\Http\Controllers;

use App\Models\LoaiSanpham;
use App\Models\Sanpham;

class DanhMucController extends Controller
{
    // Tất cả sản phẩm
    public function index()
    {
        $sapXep = request('sort', 'moi-nhat');

        $query = Sanpham::with(['anhChinh'])
            ->withCount('binhluans')
            ->where(function ($q) {
                $q->where('co_bien_the', false)
                  ->orWhereHas('bienthesActive');
            });

        switch ($sapXep) {
            case 'gia-tang':  $query->orderBy('gia', 'asc'); break;
            case 'gia-giam':  $query->orderBy('gia', 'desc'); break;
            case 'ban-chay':  $query->orderByDesc('luot_mua'); break;
            default:          $query->latest();
        }

        $sanphams = $query->paginate(12)->withQueryString();
        $danhMucs = LoaiSanpham::withCount('sanphams')->get();

        $danhMuc = (object)[
            'ten_loai' => 'Tất Cả Sản Phẩm',
            'slug'     => null,
        ];

        return view('pages.danh-muc', compact('danhMuc', 'sanphams', 'danhMucs', 'sapXep'));
    }

    // Theo danh mục
    public function show($slug)
    {
        $danhMuc = LoaiSanpham::where('slug', $slug)->firstOrFail();

        $sapXep = request('sort', 'moi-nhat');

        $query = Sanpham::with(['anhChinh'])
            ->withCount('binhluans')
            ->where('loai_id', $danhMuc->id)
            ->where(function ($q) {
                $q->where('co_bien_the', false)
                  ->orWhereHas('bienthesActive');
            });

        switch ($sapXep) {
            case 'gia-tang':  $query->orderBy('gia', 'asc'); break;
            case 'gia-giam':  $query->orderBy('gia', 'desc'); break;
            case 'ban-chay':  $query->orderByDesc('luot_mua'); break;
            default:          $query->latest();
        }

        $sanphams = $query->paginate(12)->withQueryString();
        $danhMucs = LoaiSanpham::withCount('sanphams')->get();

        return view('pages.danh-muc', compact('danhMuc', 'sanphams', 'danhMucs', 'sapXep'));
    }
}