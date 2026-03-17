<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class NguoidungController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::withCount('donhangs')
            ->withSum('donhangs', 'tong_thanh_toan');

        // Filter theo quyền hạn
        if ($request->filled('quyen_han')) {
            $query->where('quyen_han', $request->quyen_han);
        }

        // Filter theo trạng thái
        if ($request->filled('kich_hoat')) {
            $query->where('kich_hoat', $request->kich_hoat);
        }

        // Tìm kiếm
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('ho_ten', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhere('so_dien_thoai', 'like', '%' . $q . '%')
                    ->orWhere('ten_dang_nhap', 'like', '%' . $q . '%');
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $demQuyen = [
            'tat_ca' => User::count(),
            'user'   => User::where('quyen_han', User::USER)->count(),
            'staff'  => User::where('quyen_han', User::STAFF)->count(),
            'admin'  => User::where('quyen_han', User::ADMIN)->count(),
        ];

        return view('admin.nguoidung.index', compact('users', 'demQuyen'));
    }

    public function show(User $nguoidung): View
    {
        $nguoidung->load(['diaChis', 'donhangs' => function ($q) {
            $q->latest()->take(10);
        }]);

        $thongKe = [
            'tong_don'     => $nguoidung->donhangs()->count(),
            'tong_chi'     => $nguoidung->donhangs()
                                ->where('trang_thai', \App\Models\Donhang::TRANG_THAI_HOAN_TAT)
                                ->sum('tong_thanh_toan'),
            'don_hoan_tat' => $nguoidung->donhangs()
                                ->where('trang_thai', \App\Models\Donhang::TRANG_THAI_HOAN_TAT)
                                ->count(),
            'don_huy'      => $nguoidung->donhangs()
                                ->where('trang_thai', \App\Models\Donhang::TRANG_THAI_HUY)
                                ->count(),
        ];

        return view('admin.nguoidung.show', compact('nguoidung', 'thongKe'));
    }

    public function edit(User $nguoidung): View
    {
        return view('admin.nguoidung.edit', compact('nguoidung'));
    }

    public function update(Request $request, User $nguoidung): RedirectResponse
    {
        $request->validate([
            'ho_ten'       => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email,' . $nguoidung->id,
            'so_dien_thoai'=> 'nullable|string|max:15',
            'quyen_han'    => 'required|in:0,1,2',
            'kich_hoat'    => 'boolean',
            'mat_khau'     => 'nullable|string|min:6|confirmed',
        ], [
            'ho_ten.required'   => 'Vui lòng nhập họ tên.',
            'email.unique'      => 'Email này đã được dùng.',
            'mat_khau.min'      => 'Mật khẩu tối thiểu 6 ký tự.',
            'mat_khau.confirmed'=> 'Xác nhận mật khẩu không khớp.',
        ]);

        // Không cho phép tự hạ quyền chính mình
        if ($nguoidung->id === auth()->id() && (int)$request->quyen_han < User::ADMIN) {
            return back()->with('error', 'Không thể tự hạ quyền tài khoản của chính bạn.');
        }

        $data = [
            'ho_ten'        => $request->ho_ten,
            'email'         => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'quyen_han'     => $request->quyen_han,
            'kich_hoat'     => $request->boolean('kich_hoat'),
        ];

        if ($request->filled('mat_khau')) {
            $data['mat_khau'] = Hash::make($request->mat_khau);
        }

        $nguoidung->update($data);

        return redirect()->route('admin.nguoidung.show', $nguoidung)
            ->with('success', 'Cập nhật người dùng thành công!');
    }

    public function toggleKichHoat(User $nguoidung): RedirectResponse
    {
        if ($nguoidung->id === auth()->id()) {
            return back()->with('error', 'Không thể khoá tài khoản của chính bạn.');
        }

        $nguoidung->update(['kich_hoat' => !$nguoidung->kich_hoat]);

        $trangThai = $nguoidung->kich_hoat ? 'kích hoạt' : 'khoá';
        return back()->with('success', "Đã {$trangThai} tài khoản {$nguoidung->ho_ten}!");
    }
}