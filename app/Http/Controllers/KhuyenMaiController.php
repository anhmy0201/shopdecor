<?php

namespace App\Http\Controllers;

use App\Models\Magiamgia;

class KhuyenMaiController extends Controller
{
    public function index()
    {
        $baseQuery = Magiamgia::where('kich_hoat', true)
            ->where(function ($q) {
                $q->whereNull('ket_thuc')
                  ->orWhere('ket_thuc', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('bat_dau')
                  ->orWhere('bat_dau', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('so_luong')
                  ->orWhereColumn('da_su_dung', '<', 'so_luong');
            });

        $stats = [
            'tong'      => (clone $baseQuery)->count(),
            'phan_tram' => (clone $baseQuery)->where('kieu_giam', 'phan_tram')->count(),
            'co_dinh'   => (clone $baseQuery)->where('kieu_giam', 'co_dinh')->count(),
        ];

        $magiamgias = $baseQuery->orderByDesc('created_at')->paginate(12)->withQueryString();

        return view('pages.khuyen-mai', compact('magiamgias', 'stats'));
    }
}
