<?php
 
namespace App\Http\Controllers;
 
use App\Models\TinTuc;
use Illuminate\Http\Request;
 
class TinTucController extends Controller
{
    // Danh sách tin tức
    public function index()
    {
        $tintuc = TinTuc::with(['tacGia', 'hinhanhs'])
            ->daPublish()
            ->latest('ngay_dang')
            ->paginate(9);
 
        return view('tin-tuc.index', compact('tintuc'));
    }
 
    // Chi tiết bài viết
    public function show(string $slug)
    {
        $bai = TinTuc::with(['tacGia', 'hinhanhs'])
            ->daPublish()
            ->where('slug', $slug)
            ->firstOrFail();
 
        // Tăng lượt xem
        $bai->increment('luot_xem');
 
        // Bài viết liên quan (cùng tác giả hoặc gần nhất)
        $lienQuan = TinTuc::with('hinhanhs')
            ->daPublish()
            ->where('id', '!=', $bai->id)
            ->latest('ngay_dang')
            ->take(3)
            ->get();
 
        return view('tin-tuc.show', compact('bai', 'lienQuan'));
    }
}