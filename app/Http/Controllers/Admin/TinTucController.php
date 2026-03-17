<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\TinTuc;
use App\Models\TinTucHinhanh;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
 
class TinTucController extends Controller
{
    public function index(): View
    {
        $tintuc = TinTuc::with('tacGia')
            ->withCount('hinhanhs')
            ->latest()
            ->paginate(15);
 
        return view('admin.tin-tuc.index', compact('tintuc'));
    }
 
    public function create(): View
    {
        return view('admin.tin-tuc.form', ['tinTuc' => null]);
    }
 
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tieu_de'      => 'required|string|max:255',
            'mo_ta_ngan'   => 'nullable|string|max:500',
            'noi_dung'     => 'nullable|string',
            'anh_dai_dien' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'hinhanh.*'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'chu_thich.*'  => 'nullable|string|max:200',
            'ngay_dang'    => 'nullable|date',
            'kich_hoat'    => 'boolean',
        ], [
            'tieu_de.required' => 'Vui lòng nhập tiêu đề.',
        ]);
 
        $slug = $this->taoSlug($request->tieu_de);
 
        $data = [
            'user_id'    => auth()->id(),
            'tieu_de'    => $request->tieu_de,
            'slug'       => $slug,
            'mo_ta_ngan' => $request->mo_ta_ngan,
            'noi_dung'   => $request->noi_dung,
            'kich_hoat'  => $request->boolean('kich_hoat'),
            'ngay_dang'  => $request->ngay_dang ?? now(),
        ];
 
        // Upload ảnh đại diện
        if ($request->hasFile('anh_dai_dien')) {
            $path = $request->file('anh_dai_dien')
                ->store('tintuc/' . $slug, 'public');
            $data['anh_dai_dien'] = 'storage/' . $path;
        }
 
        $tinTuc = TinTuc::create($data);
 
        // Upload ảnh gallery trong bài
        if ($request->hasFile('hinhanh')) {
            foreach ($request->file('hinhanh') as $i => $file) {
                $path = $file->store('tintuc/' . $slug . '/gallery', 'public');
                TinTucHinhanh::create([
                    'tintuc_id'    => $tinTuc->id,
                    'duong_dan_anh'=> 'storage/' . $path,
                    'chu_thich'    => $request->chu_thich[$i] ?? null,
                    'thu_tu'       => $i,
                ]);
            }
        }
 
        return redirect()->route('admin.tin-tuc.index')
            ->with('success', 'Đăng tin tức thành công!');
    }
 
    public function edit(TinTuc $tinTuc): View
    {
        $tinTuc->load('hinhanhs');
        return view('admin.tin-tuc.form', compact('tinTuc'));
    }
 
    public function update(Request $request, TinTuc $tinTuc): RedirectResponse
    {
        $request->validate([
            'tieu_de'      => 'required|string|max:255',
            'mo_ta_ngan'   => 'nullable|string|max:500',
            'noi_dung'     => 'nullable|string',
            'anh_dai_dien' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'hinhanh.*'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'chu_thich.*'  => 'nullable|string|max:200',
            'ngay_dang'    => 'nullable|date',
            'kich_hoat'    => 'boolean',
        ]);
 
        $data = [
            'tieu_de'    => $request->tieu_de,
            'mo_ta_ngan' => $request->mo_ta_ngan,
            'noi_dung'   => $request->noi_dung,
            'kich_hoat'  => $request->boolean('kich_hoat'),
            'ngay_dang'  => $request->ngay_dang ?? $tinTuc->ngay_dang,
        ];
 
        // Cập nhật slug nếu đổi tiêu đề
        if ($tinTuc->tieu_de !== $request->tieu_de) {
            $data['slug'] = $this->taoSlug($request->tieu_de, $tinTuc->id);
        }
 
        $slug = $data['slug'] ?? $tinTuc->slug;
 
        // Upload ảnh đại diện mới
        if ($request->hasFile('anh_dai_dien')) {
            if ($tinTuc->anh_dai_dien) {
                Storage::disk('public')->delete(
                    str_replace('storage/', '', $tinTuc->anh_dai_dien)
                );
            }
            $path = $request->file('anh_dai_dien')
                ->store('tintuc/' . $slug, 'public');
            $data['anh_dai_dien'] = 'storage/' . $path;
        }
 
        $tinTuc->update($data);
 
        // Xoá ảnh gallery được chọn xoá
        if ($request->filled('xoa_anh')) {
            foreach ($request->xoa_anh as $anhId) {
                $anh = TinTucHinhanh::find($anhId);
                if ($anh && $anh->tintuc_id === $tinTuc->id) {
                    Storage::disk('public')->delete(
                        str_replace('storage/', '', $anh->duong_dan_anh)
                    );
                    $anh->delete();
                }
            }
        }
 
        // Upload ảnh gallery mới
        if ($request->hasFile('hinhanh')) {
            $soHienTai = $tinTuc->hinhanhs()->count();
            foreach ($request->file('hinhanh') as $i => $file) {
                $path = $file->store('tintuc/' . $slug . '/gallery', 'public');
                TinTucHinhanh::create([
                    'tintuc_id'    => $tinTuc->id,
                    'duong_dan_anh'=> 'storage/' . $path,
                    'chu_thich'    => $request->chu_thich_moi[$i] ?? null,
                    'thu_tu'       => $soHienTai + $i,
                ]);
            }
        }
 
        return redirect()->route('admin.tin-tuc.index')
            ->with('success', 'Cập nhật tin tức thành công!');
    }
 
    public function destroy(TinTuc $tinTuc): RedirectResponse
    {
        // Xoá ảnh vật lý
        if ($tinTuc->anh_dai_dien) {
            Storage::disk('public')->delete(
                str_replace('storage/', '', $tinTuc->anh_dai_dien)
            );
        }
        foreach ($tinTuc->hinhanhs as $anh) {
            Storage::disk('public')->delete(
                str_replace('storage/', '', $anh->duong_dan_anh)
            );
        }
 
        $tinTuc->delete(); // soft delete
 
        return redirect()->route('admin.tin-tuc.index')
            ->with('success', 'Đã xoá tin tức!');
    }
 
    public function toggleKichHoat(TinTuc $tinTuc): RedirectResponse
    {
        $tinTuc->update(['kich_hoat' => !$tinTuc->kich_hoat]);
        $trangThai = $tinTuc->kich_hoat ? 'hiển thị' : 'ẩn';
        return back()->with('success', "Đã {$trangThai} bài: {$tinTuc->tieu_de}");
    }
 
    private function taoSlug(string $tieu_de, ?int $boQuaId = null): string
    {
        $slug = Str::slug($tieu_de);
        $original = $slug;
        $count = 1;
 
        $query = TinTuc::where('slug', $slug);
        if ($boQuaId) $query->where('id', '!=', $boQuaId);
 
        while ($query->exists()) {
            $slug = $original . '-' . $count++;
            $query = TinTuc::where('slug', $slug);
            if ($boQuaId) $query->where('id', '!=', $boQuaId);
        }
 
        return $slug;
    }
}