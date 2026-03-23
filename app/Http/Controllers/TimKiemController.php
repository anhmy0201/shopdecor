<?php

namespace App\Http\Controllers;

use App\Models\Sanpham;
use App\Models\LoaiSanpham;
use Illuminate\Http\Request;

class TimKiemController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        // Redirect về home nếu query rỗng
        if ($q === '') {
            return redirect()->route('home');
        }

        $sapXep   = $request->get('sort', 'lien-quan');
        $loaiId   = $request->get('loai');
        $giaMin   = $request->get('gia_min');
        $giaMax   = $request->get('gia_max');

        // ===== Query chính =====
        $query = Sanpham::with(['anhChinh', 'bienthesActive'])
            ->where(function ($q2) use ($q) {
                $q2->where('ten_san_pham', 'LIKE', "%{$q}%")
                   ->orWhere('mo_ta', 'LIKE', "%{$q}%");
            })
            ->where(function ($q2) {
                $q2->where('co_bien_the', false)
                   ->orWhereHas('bienthesActive');
            });

        // Lọc danh mục
        if ($loaiId) {
            $query->where('loai_id', $loaiId);
        }

        // Lọc giá (chỉ áp cho SP không biến thể; biến thể phức tạp hơn — dùng min gia)
        if ($giaMin !== null && $giaMin !== '') {
            $query->where('gia', '>=', (float) $giaMin);
        }
        if ($giaMax !== null && $giaMax !== '') {
            $query->where('gia', '<=', (float) $giaMax);
        }

        // Sắp xếp
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
            case 'moi-nhat':
                $query->latest();
                break;
            default: // lien-quan — ưu tiên khớp tên trước
                $query->orderByRaw("
                    CASE
                        WHEN ten_san_pham LIKE ? THEN 0
                        WHEN ten_san_pham LIKE ? THEN 1
                        ELSE 2
                    END, luot_xem DESC
                ", ["{$q}%", "%{$q}%"]);
        }

        $sanphams  = $query->paginate(12)->withQueryString();
        $tongKetQua = $query->toBase()->getCountForPagination();

        // Danh mục cho bộ lọc sidebar
        $danhMucs = LoaiSanpham::withCount(['sanphams' => function ($q2) use ($q) {
            $q2->where('ten_san_pham', 'LIKE', "%{$q}%");
        }])->get();

        return view('pages.tim-kiem', compact(
            'sanphams',
            'tongKetQua',
            'q',
            'sapXep',
            'loaiId',
            'giaMin',
            'giaMax',
            'danhMucs'
        ));
    }
}
