<?php

namespace App\Http\Controllers;

use App\Models\Magiamgia;

class KhuyenMaiController extends Controller
{
    public function index()
    {
        $magiamgias = Magiamgia::where('kich_hoat', true)
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
            })
            ->orderByDesc('created_at')
            ->get();

        return view('pages.khuyen-mai', compact('magiamgias'));
    }
}
