<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    // Danh sách
    public function index()
    {
        $banners = Banner::orderBy('thu_tu')->orderBy('id')->get();
        return view('admin.banner.index', compact('banners'));
    }

    // Lưu banner mới
    public function store(Request $request)
    {
        $request->validate([
            'anh_banner'    => 'required|image|mimes:jpeg,png,webp,gif|max:5120',
            'tieu_de'       => 'nullable|string|max:150',
            'mo_ta'         => 'nullable|string|max:300',
            'url_lien_ket'  => 'nullable|string|max:255',
            'thu_tu'        => 'nullable|integer|min:0',
            'ngay_bat_dau'  => 'nullable|date',
            'ngay_ket_thuc' => 'nullable|date|after_or_equal:ngay_bat_dau',
        ], [
            'anh_banner.required'              => 'Vui lòng chọn ảnh.',
            'anh_banner.image'                 => 'File phải là ảnh.',
            'anh_banner.max'                   => 'Ảnh không vượt quá 5MB.',
            'ngay_ket_thuc.after_or_equal'     => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);

        $path = $request->file('anh_banner')->store('banner', 'public');

        Banner::create([
            'duong_dan_anh' => 'storage/' . $path,
            'tieu_de'       => $request->tieu_de,
            'mo_ta'         => $request->mo_ta,
            'url_lien_ket'  => $request->url_lien_ket,
            'thu_tu'        => $request->thu_tu ?? 0,
            'kich_hoat'     => $request->boolean('kich_hoat', true),
            'ngay_bat_dau'  => $request->ngay_bat_dau ?: null,
            'ngay_ket_thuc' => $request->ngay_ket_thuc ?: null,
        ]);

        return back()->with('success', 'Đã thêm banner thành công!');
    }

    // Form sửa
    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', compact('banner'));
    }

    // Cập nhật
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'anh_banner'    => 'nullable|image|mimes:jpeg,png,webp,gif|max:5120',
            'tieu_de'       => 'nullable|string|max:150',
            'mo_ta'         => 'nullable|string|max:300',
            'url_lien_ket'  => 'nullable|string|max:255',
            'thu_tu'        => 'nullable|integer|min:0',
            'ngay_bat_dau'  => 'nullable|date',
            'ngay_ket_thuc' => 'nullable|date|after_or_equal:ngay_bat_dau',
        ], [
            'ngay_ket_thuc.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);

        $data = [
            'tieu_de'       => $request->tieu_de,
            'mo_ta'         => $request->mo_ta,
            'url_lien_ket'  => $request->url_lien_ket,
            'thu_tu'        => $request->thu_tu ?? 0,
            'kich_hoat'     => $request->boolean('kich_hoat'),
            'ngay_bat_dau'  => $request->ngay_bat_dau ?: null,
            'ngay_ket_thuc' => $request->ngay_ket_thuc ?: null,
        ];

        if ($request->hasFile('anh_banner')) {
            $oldPath = str_replace('storage/', '', $banner->duong_dan_anh);
            Storage::disk('public')->delete($oldPath);

            $path = $request->file('anh_banner')->store('banner', 'public');
            $data['duong_dan_anh'] = 'storage/' . $path;
        }

        $banner->update($data);

        return redirect()->route('admin.banner.index')->with('success', 'Đã cập nhật banner!');
    }

    // Bật / tắt nhanh
    public function toggleKichHoat(Banner $banner)
    {
        $newValue = !$banner->kich_hoat;
        $banner->update(['kich_hoat' => $newValue]);
        $trang = $newValue ? 'bật' : 'tắt';
        return back()->with('success', "Đã {$trang} banner.");
    }

    // Xóa
    public function destroy(Banner $banner)
    {
        if ($banner->duong_dan_anh) {
            $path = str_replace('storage/', '', $banner->duong_dan_anh);
            Storage::disk('public')->delete($path);
        }
        $banner->delete();

        return back()->with('success', 'Đã xóa banner!');
    }
}