<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'ho_ten'        => 'required|string|max:100',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'so_dien_thoai' => 'nullable|string|max:15',
            'hinh_anh'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'mat_khau_cu'   => 'nullable|string',
            'mat_khau_moi'  => ['nullable', 'confirmed', Password::min(6)],
        ], [
            'ho_ten.required'        => 'Vui lòng nhập họ tên.',
            'email.unique'           => 'Email này đã được dùng.',
            'hinh_anh.image'         => 'File phải là ảnh.',
            'mat_khau_moi.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'mat_khau_moi.min'       => 'Mật khẩu mới tối thiểu 6 ký tự.',
        ]);

        $data = [
            'ho_ten'        => $request->ho_ten,
            'email'         => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
        ];

        // Upload ảnh đại diện
        if ($request->hasFile('hinh_anh')) {
            // Xoá ảnh cũ nếu có
            if ($user->hinh_anh && Storage::disk('public')->exists(str_replace('storage/', '', $user->hinh_anh))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $user->hinh_anh));
            }
            $path = $request->file('hinh_anh')->store('avatars', 'public');
            $data['hinh_anh'] = 'storage/' . $path;
        }

        // Đổi mật khẩu
        if ($request->filled('mat_khau_moi')) {
            if (!$request->filled('mat_khau_cu') || !Hash::check($request->mat_khau_cu, $user->mat_khau)) {
                return back()->with('error', 'Mật khẩu hiện tại không đúng.');
            }
            $data['mat_khau'] = Hash::make($request->mat_khau_moi);
        }

        $user->update($data);

        return back()->with('success', 'Cập nhật hồ sơ thành công!');
    }
}