<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiSanpham;
use App\Models\Sanpham;
use App\Models\SanphamBienthe;
use App\Models\SanphamHinhanh;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Imports\SanphamImport;
use App\Exports\SanphamExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Events\TonKhoCapNhatEvent;

class SanphamController extends Controller
{
    public function index(Request $request): View
    {
        $query = Sanpham::with(['loai', 'anhChinh'])
            ->withCount('bienthes');

        if ($request->filled('loai_id')) {
            $query->where('loai_id', $request->loai_id);
        }

        if ($request->ton_kho === 'het_hang') {
            $query->where('co_bien_the', false)->where('so_luong', 0);
        }

        if ($request->filled('q')) {
            $query->where('ten_san_pham', 'like', '%' . $request->q . '%');
        }

        $sanphams = $query->latest()->paginate(15)->withQueryString();
        $loais    = LoaiSanpham::orderBy('ten_loai')->get();

        return view('admin.sanpham.index', compact('sanphams', 'loais'));
    }

    public function create(): View
    {
        $loais = LoaiSanpham::orderBy('ten_loai')->get();
        return view('admin.sanpham.create', compact('loais'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'ten_san_pham'          => 'required|string|max:255',
            'loai_id'               => 'required|exists:loai_sanpham,id',
            'gia'                   => 'required|numeric|min:0',
            'gia_cu'                => 'nullable|numeric|min:0',
            'mo_ta'                 => 'nullable|string',
            'so_luong'              => 'required_if:co_bien_the,0|nullable|integer|min:0',
            'hinhanh.*'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'bienthe.*.ten_bienthe' => 'required_with:bienthe|string|max:100',
            'bienthe.*.ma_sku'      => 'required_with:bienthe|string|max:50',
            'bienthe.*.gia'         => 'required_with:bienthe|numeric|min:0',
            'bienthe.*.so_luong'    => 'required_with:bienthe|integer|min:0',
        ], [
            'ten_san_pham.required' => 'Vui lòng nhập tên sản phẩm.',
            'loai_id.required'      => 'Vui lòng chọn loại sản phẩm.',
            'gia.required'          => 'Vui lòng nhập giá sản phẩm.',
        ]);

        DB::transaction(function () use ($request) {
            $coBienThe = $request->boolean('co_bien_the');

            $sanpham = Sanpham::create([
                'loai_id'      => $request->loai_id,
                'ten_san_pham' => $request->ten_san_pham,
                'slug'         => $this->taoSlug($request->ten_san_pham),
                'gia'          => $request->gia,
                'gia_cu'       => $request->gia_cu,
                'mo_ta'        => $request->mo_ta,
                'so_luong'     => $coBienThe ? 0 : $request->so_luong,
                'co_bien_the'  => $coBienThe,
            ]);

            if ($request->hasFile('hinhanh')) {
                foreach ($request->file('hinhanh') as $i => $file) {
                    $path = $file->store('sanpham/' . $sanpham->slug . '/gallery', 'public');
                    SanphamHinhanh::create([
                        'sanpham_id'    => $sanpham->id,
                        'duong_dan_anh' => 'storage/' . $path,
                        'la_anh_chinh'  => $i === 0,
                        'thu_tu'        => $i,
                    ]);
                }
            }

            if ($coBienThe && $request->filled('bienthe')) {
                $bientheDir = 'sanpham/' . $sanpham->slug . '/bienthe';
                $thuTu = 0;
                foreach ($request->bienthe as $bt) {
                    $data = [
                        'sanpham_id'  => $sanpham->id,
                        'ma_sku'      => $bt['ma_sku'],
                        'ten_bienthe' => $bt['ten_bienthe'],
                        'gia'         => $bt['gia'],
                        'so_luong'    => $bt['so_luong'],
                        'thu_tu'      => $thuTu++,
                        'kich_hoat'   => isset($bt['kich_hoat']),
                    ];
                    if (isset($bt['hinh_anh']) && $bt['hinh_anh'] instanceof \Illuminate\Http\UploadedFile) {
                        $data['hinh_anh'] = 'storage/' . $bt['hinh_anh']->store($bientheDir, 'public');
                    }
                    SanphamBienthe::create($data);
                }
            }
        });

        return redirect()->route('admin.sanpham.index')
            ->with('success', 'Thêm sản phẩm thành công!');
    }

    public function show(Sanpham $sanpham): View
    {
        $sanpham->load(['loai', 'hinhanhs', 'bienthes']);
        return view('admin.sanpham.show', compact('sanpham'));
    }

    public function edit(Sanpham $sanpham): View
    {
        $sanpham->load(['hinhanhs', 'bienthes']);
        $loais = LoaiSanpham::orderBy('ten_loai')->get();
        return view('admin.sanpham.edit', compact('sanpham', 'loais'));
    }

    public function update(Request $request, Sanpham $sanpham): RedirectResponse
    {
        $request->validate([
            'ten_san_pham'          => 'required|string|max:255',
            'loai_id'               => 'required|exists:loai_sanpham,id',
            'gia'                   => 'required|numeric|min:0',
            'gia_cu'                => 'nullable|numeric|min:0',
            'mo_ta'                 => 'nullable|string',
            'so_luong'              => 'nullable|integer|min:0',
            'hinhanh.*'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'bienthe.*.ten_bienthe' => 'required_with:bienthe|string|max:100',
            'bienthe.*.ma_sku'      => 'required_with:bienthe|string|max:50',
            'bienthe.*.gia'         => 'required_with:bienthe|numeric|min:0',
            'bienthe.*.so_luong'    => 'required_with:bienthe|integer|min:0',
        ]);

        $bientheIdsDaCapNhat = [];

        DB::transaction(function () use ($request, $sanpham, &$bientheIdsDaCapNhat) {
            $coBienThe = $request->boolean('co_bien_the');

            $slug = $sanpham->ten_san_pham !== $request->ten_san_pham
                ? $this->taoSlug($request->ten_san_pham, $sanpham->id)
                : $sanpham->slug;

            $sanpham->update([
                'loai_id'      => $request->loai_id,
                'ten_san_pham' => $request->ten_san_pham,
                'slug'         => $slug,
                'gia'          => $request->gia,
                'gia_cu'       => $request->gia_cu,
                'mo_ta'        => $request->mo_ta,
                'so_luong'     => $coBienThe ? 0 : $request->so_luong,
                'co_bien_the'  => $coBienThe,
            ]);

            if ($request->hasFile('hinhanh')) {
                $coAnhChinh = $sanpham->hinhanhs()->where('la_anh_chinh', true)->exists();
                foreach ($request->file('hinhanh') as $i => $file) {
                    $path = $file->store('sanpham/' . $sanpham->slug . '/gallery', 'public');
                    SanphamHinhanh::create([
                        'sanpham_id'    => $sanpham->id,
                        'duong_dan_anh' => 'storage/' . $path,
                        'la_anh_chinh'  => !$coAnhChinh && $i === 0,
                        'thu_tu'        => $sanpham->hinhanhs()->count() + $i,
                    ]);
                }
            }

            if ($request->filled('xoa_anh')) {
                foreach ($request->xoa_anh as $anhId) {
                    $anh = SanphamHinhanh::find($anhId);
                    if ($anh && $anh->sanpham_id === $sanpham->id) {
                        Storage::disk('public')->delete(
                            str_replace('storage/', '', $anh->duong_dan_anh)
                        );
                        $anh->delete();
                    }
                }
                if (!$sanpham->fresh()->anhChinh && $sanpham->hinhanhs()->count() > 0) {
                    $sanpham->hinhanhs()->first()->update(['la_anh_chinh' => true]);
                }
            }

            if ($coBienThe && $request->filled('bienthe')) {
                $bientheDir = 'sanpham/' . $sanpham->slug . '/bienthe';
                $ids   = [];
                $thuTu = 0;

                foreach ($request->bienthe as $bt) {
                    $data = [
                        'ma_sku'      => $bt['ma_sku'],
                        'ten_bienthe' => $bt['ten_bienthe'],
                        'gia'         => $bt['gia'],
                        'so_luong'    => $bt['so_luong'],
                        'thu_tu'      => $thuTu++,
                        'kich_hoat'   => isset($bt['kich_hoat']),
                    ];

                    if (isset($bt['hinh_anh']) && $bt['hinh_anh'] instanceof \Illuminate\Http\UploadedFile) {
                        $data['hinh_anh'] = 'storage/' . $bt['hinh_anh']->store($bientheDir, 'public');
                    }

                    if (!empty($bt['id'])) {
                        $realId = (int) str_replace('existing-', '', $bt['id']);

                        SanphamBienthe::where('id', $realId)
                            ->where('sanpham_id', $sanpham->id)
                            ->update($data);

                        $ids[] = $realId;
                        $bientheIdsDaCapNhat[] = $realId;
                    } else {
                        $new   = SanphamBienthe::create(array_merge($data, ['sanpham_id' => $sanpham->id]));
                        $ids[] = $new->id;
                        $bientheIdsDaCapNhat[] = $new->id;
                    }
                }

                $sanpham->bienthes()->whereNotIn('id', $ids)->delete();

            } elseif (!$coBienThe) {
                $sanpham->bienthes()->delete();
            }
        });

        // Broadcast cập nhật tồn kho real-time ra ngoài frontend
        $sanpham->refresh()->load('bienthesActive');

        if ($sanpham->co_bien_the && count($bientheIdsDaCapNhat) > 0) {
            foreach ($bientheIdsDaCapNhat as $btId) {
                $bienthe = SanphamBienthe::find($btId);
                if ($bienthe) {
                    broadcast(new TonKhoCapNhatEvent($sanpham, $bienthe));
                }
            }
        } else {
            broadcast(new TonKhoCapNhatEvent($sanpham));
        }

        return redirect()->route('admin.sanpham.index')
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Sanpham $sanpham): RedirectResponse
    {
        $sanpham->delete();

        return redirect()->route('admin.sanpham.index')
            ->with('success', 'Đã xóa sản phẩm!');
    }

    private function taoSlug(string $ten, ?int $boQuaId = null): string
    {
        $slug     = Str::slug($ten);
        $original = $slug;
        $count    = 1;

        $query = Sanpham::where('slug', $slug);
        if ($boQuaId) $query->where('id', '!=', $boQuaId);

        while ($query->exists()) {
            $slug  = $original . '-' . $count++;
            $query = Sanpham::where('slug', $slug);
            if ($boQuaId) $query->where('id', '!=', $boQuaId);
        }

        return $slug;
    }

    public function postNhap(Request $request): RedirectResponse
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        Excel::import(new SanphamImport, $request->file('file_excel'));

        return redirect()->route('admin.sanpham.index')
            ->with('success', 'Nhập sản phẩm thành công!');
    }

    public function getXuat(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new SanphamExport, 'danh-sach-san-pham.xlsx');
    }
}