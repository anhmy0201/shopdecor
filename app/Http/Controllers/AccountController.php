<?php

namespace App\Http\Controllers;

use App\Models\DiaChiUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountController extends Controller
{
    

    // ─── Trang chính ───────────────────────────────────────────────────────────

    public function index(): View
    {
        $user = Auth::user()->load([
            'diaChis',
            'donhangs' => fn($q) => $q->latest()->limit(5),
        ]);

        return view('pages.account', compact('user'));
    }

    // ─── Cập nhật thông tin cá nhân ────────────────────────────────────────────

    public function capNhatThongTin(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'ho_ten'        => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'so_dien_thoai' => ['nullable', 'string', 'max:15', 'regex:/^[0-9+\-\s]+$/'],
            'hinh_anh'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'ho_ten.required'     => 'Vui lòng nhập họ tên.',
            'email.required'      => 'Vui lòng nhập email.',
            'email.unique'        => 'Email đã được dùng bởi tài khoản khác.',
            'so_dien_thoai.regex' => 'Số điện thoại không hợp lệ.',
            'hinh_anh.image'      => 'File phải là hình ảnh.',
            'hinh_anh.mimes'      => 'Chỉ chấp nhận JPG, PNG, WEBP.',
            'hinh_anh.max'        => 'Ảnh không được lớn hơn 2MB.',
        ]);

        $data = $request->only('ho_ten', 'email', 'so_dien_thoai');

        if ($request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ nếu có
            if ($user->hinh_anh && Storage::disk('public')->exists($user->hinh_anh)) {
                Storage::disk('public')->delete($user->hinh_anh);
            }
            $data['hinh_anh'] = $request->file('hinh_anh')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    // ─── Đổi mật khẩu ──────────────────────────────────────────────────────────

    public function doiMatKhau(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'mat_khau_cu'               => ['required'],
            'mat_khau_moi'              => ['required', 'min:8', 'confirmed', 'different:mat_khau_cu'],
            'mat_khau_moi_confirmation' => ['required'],
        ], [
            'mat_khau_cu.required'               => 'Vui lòng nhập mật khẩu hiện tại.',
            'mat_khau_moi.required'              => 'Vui lòng nhập mật khẩu mới.',
            'mat_khau_moi.min'                   => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'mat_khau_moi.confirmed'             => 'Xác nhận mật khẩu không khớp.',
            'mat_khau_moi.different'             => 'Mật khẩu mới phải khác mật khẩu cũ.',
            'mat_khau_moi_confirmation.required' => 'Vui lòng xác nhận mật khẩu mới.',
        ]);

        if (!Hash::check($request->mat_khau_cu, $user->mat_khau)) {
            return back()->withErrors(['mat_khau_cu' => 'Mật khẩu hiện tại không đúng.'])->withInput();
        }

        $user->update(['mat_khau' => $request->mat_khau_moi]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    // ─── Địa chỉ giao hàng ─────────────────────────────────────────────────────

    public function themDiaChi(Request $request): RedirectResponse
    {
        $request->validate([
            'ho_ten'           => ['required', 'string', 'max:100'],
            'so_dien_thoai'    => ['required', 'string', 'max:15'],
            'dia_chi_chi_tiet' => ['required', 'string', 'max:255'],
            'phuong_xa'        => ['required', 'string', 'max:100'],
            'quan_huyen'       => ['required', 'string', 'max:100'],
            'tinh_thanh'       => ['required', 'string', 'max:100'],
        ]);

        $userId  = Auth::id();
        $isFirst = DiaChiUser::where('user_id', $userId)->doesntExist();

        DiaChiUser::create([
            'user_id'          => $userId,
            'ho_ten'           => $request->ho_ten,
            'so_dien_thoai'    => $request->so_dien_thoai,
            'dia_chi_chi_tiet' => $request->dia_chi_chi_tiet,
            'phuong_xa'        => $request->phuong_xa,
            'quan_huyen'       => $request->quan_huyen,
            'tinh_thanh'       => $request->tinh_thanh,
            'mac_dinh'         => $isFirst,
        ]);

        return back()->with('success', 'Thêm địa chỉ thành công!');
    }

    public function xoaDiaChi(DiaChiUser $diaChi): RedirectResponse
    {
        abort_unless($diaChi->user_id === Auth::id(), 403);

        $wasMacDinh = $diaChi->mac_dinh;
        $diaChi->delete();

        // Nếu xóa địa chỉ mặc định → tự gán cho địa chỉ còn lại đầu tiên
        if ($wasMacDinh) {
            DiaChiUser::where('user_id', Auth::id())
                ->oldest()->first()
                ?->update(['mac_dinh' => true]);
        }

        return back()->with('success', 'Đã xóa địa chỉ!');
    }

    public function datMacDinh(DiaChiUser $diaChi): RedirectResponse
    {
        abort_unless($diaChi->user_id === Auth::id(), 403);

        DB::transaction(function () use ($diaChi) {
            DiaChiUser::where('user_id', Auth::id())->update(['mac_dinh' => false]);
            $diaChi->update(['mac_dinh' => true]);
        });

        return back()->with('success', 'Đã đặt địa chỉ mặc định!');
    }
}