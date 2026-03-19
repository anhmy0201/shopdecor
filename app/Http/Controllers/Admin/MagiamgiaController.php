<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Magiamgia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Imports\MagiamgiaImport;
use App\Exports\MagiamgiaExport;
use Maatwebsite\Excel\Facades\Excel;

class MagiamgiaController extends Controller
{
    public function index(): View
    {
        $magiamgias = Magiamgia::withCount('donhangs')
            ->latest()
            ->paginate(15);

        return view('admin.magiamgia.index', compact('magiamgias'));
    }

    public function create(): View
    {
        return view('admin.magiamgia.form', ['magiamgia' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ma_code'            => 'required|string|max:50|unique:magiamgia,ma_code',
            'mo_ta'              => 'nullable|string',
            'kieu_giam'          => 'required|in:phan_tram,co_dinh',
            'gia_tri'            => 'required|numeric|min:0',
            'don_hang_toi_thieu' => 'nullable|numeric|min:0',
            'giam_toi_da'        => 'nullable|numeric|min:0',
            'so_luong'           => 'nullable|integer|min:1',
            'bat_dau'            => 'nullable|date',
            'ket_thuc'           => 'nullable|date|after_or_equal:bat_dau',
            'kich_hoat'          => 'boolean',
        ], [
            'ma_code.required' => 'Vui lòng nhập mã giảm giá.',
            'ma_code.unique'   => 'Mã này đã tồn tại.',
            'gia_tri.required' => 'Vui lòng nhập giá trị giảm.',
            'ket_thuc.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);

        $data['ma_code']  = strtoupper($data['ma_code']);
        $data['kich_hoat'] = $request->boolean('kich_hoat');
        $data['don_hang_toi_thieu'] = $request->don_hang_toi_thieu ?? 0;

        Magiamgia::create($data);

        return redirect()->route('admin.magiamgia.index')
            ->with('success', 'Thêm mã giảm giá thành công!');
    }

    public function edit(Magiamgia $magiamgia): View
    {
        return view('admin.magiamgia.form', compact('magiamgia'));
    }

    public function update(Request $request, Magiamgia $magiamgia): RedirectResponse
    {
        $data = $request->validate([
            'ma_code'            => 'required|string|max:50|unique:magiamgia,ma_code,' . $magiamgia->id,
            'mo_ta'              => 'nullable|string',
            'kieu_giam'          => 'required|in:phan_tram,co_dinh',
            'gia_tri'            => 'required|numeric|min:0',
            'don_hang_toi_thieu' => 'nullable|numeric|min:0',
            'giam_toi_da'        => 'nullable|numeric|min:0',
            'so_luong'           => 'nullable|integer|min:1',
            'bat_dau'            => 'nullable|date',
            'ket_thuc'           => 'nullable|date|after_or_equal:bat_dau',
            'kich_hoat'          => 'boolean',
        ], [
            'ma_code.unique'   => 'Mã này đã tồn tại.',
            'ket_thuc.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);

        $data['ma_code']   = strtoupper($data['ma_code']);
        $data['kich_hoat'] = $request->boolean('kich_hoat');
        $data['don_hang_toi_thieu'] = $request->don_hang_toi_thieu ?? 0;

        $magiamgia->update($data);

        return redirect()->route('admin.magiamgia.index')
            ->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    public function destroy(Magiamgia $magiamgia): RedirectResponse
    {
        if ($magiamgia->donhangs()->count() > 0) {
            return back()->with('error', 'Không thể xóa! Mã này đã được dùng trong ' . $magiamgia->donhangs()->count() . ' đơn hàng.');
        }

        $magiamgia->delete();

        return redirect()->route('admin.magiamgia.index')
            ->with('success', 'Đã xóa mã giảm giá!');
    }

    // Bật/tắt nhanh
    public function toggleKichHoat(Magiamgia $magiamgia): RedirectResponse
    {
        $magiamgia->update(['kich_hoat' => !$magiamgia->kich_hoat]);

        $trangThai = $magiamgia->kich_hoat ? 'kích hoạt' : 'vô hiệu hóa';
        return back()->with('success', "Đã {$trangThai} mã {$magiamgia->ma_code}!");
    }
    public function postNhap(Request $request): RedirectResponse
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        Excel::import(new MagiamgiaImport, $request->file('file_excel'));

        return redirect()->route('admin.magiamgia.index')
            ->with('success', 'Nhập mã giảm giá thành công!');
    }

    public function getXuat(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new MagiamgiaExport, 'danh-sach-ma-giam-gia.xlsx');
    }
}