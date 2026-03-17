<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiSanpham;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoaiSanphamController extends Controller
{
    public function index(): View
    {
        $loais = LoaiSanpham::withCount('sanphams')
            ->orderBy('ten_loai')
            ->paginate(15);

        return view('admin.loai-sanpham.index', compact('loais'));
    }

    public function create(): View
    {
        return view('admin.loai-sanpham.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ten_loai' => 'required|string|max:100|unique:loai_sanpham,ten_loai',
            'mo_ta'    => 'nullable|string',
        ], [
            'ten_loai.required' => 'Vui lòng nhập tên loại.',
            'ten_loai.unique'   => 'Tên loại này đã tồn tại.',
        ]);

        $data['slug'] = Str::slug($data['ten_loai']);

        // Đảm bảo slug không trùng
        $originalSlug = $data['slug'];
        $count = 1;
        while (LoaiSanpham::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $count++;
        }

        LoaiSanpham::create($data);

        return redirect()->route('admin.loai-sanpham.index')
            ->with('success', 'Thêm loại sản phẩm thành công!');
    }

    public function edit(LoaiSanpham $loaiSanpham): View
    {
        return view('admin.loai-sanpham.edit', compact('loaiSanpham'));
    }

    public function update(Request $request, LoaiSanpham $loaiSanpham): RedirectResponse
    {
        $data = $request->validate([
            'ten_loai' => 'required|string|max:100|unique:loai_sanpham,ten_loai,' . $loaiSanpham->id,
            'mo_ta'    => 'nullable|string',
        ], [
            'ten_loai.required' => 'Vui lòng nhập tên loại.',
            'ten_loai.unique'   => 'Tên loại này đã tồn tại.',
        ]);

        // Chỉ cập nhật slug nếu tên thay đổi
        if ($data['ten_loai'] !== $loaiSanpham->ten_loai) {
            $newSlug = Str::slug($data['ten_loai']);
            $originalSlug = $newSlug;
            $count = 1;
            while (LoaiSanpham::where('slug', $newSlug)->where('id', '!=', $loaiSanpham->id)->exists()) {
                $newSlug = $originalSlug . '-' . $count++;
            }
            $data['slug'] = $newSlug;
        }

        $loaiSanpham->update($data);

        return redirect()->route('admin.loai-sanpham.index')
            ->with('success', 'Cập nhật loại sản phẩm thành công!');
    }

    public function destroy(LoaiSanpham $loaiSanpham): RedirectResponse
    {
        if ($loaiSanpham->sanphams()->count() > 0) {
            return back()->with('error', 'Không thể xóa! Loại này đang có ' . $loaiSanpham->sanphams()->count() . ' sản phẩm.');
        }

        $loaiSanpham->delete();

        return redirect()->route('admin.loai-sanpham.index')
            ->with('success', 'Đã xóa loại sản phẩm thành công!');
    }
}