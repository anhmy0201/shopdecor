<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    // Hiển thị form đặt lại mật khẩu
    public function showForm(Request $request, $token)
    {
        $email = $request->email;

        // Kiểm tra token hợp lệ
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record || !hash_equals($record->token, hash('sha256', $token))) {
            return redirect()->route('password.forgot')
                ->with('error', 'Link đặt lại mật khẩu không hợp lệ.');
        }

        // Kiểm tra token hết hạn (60 phút)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('password.forgot')
                ->with('error', 'Link đặt lại mật khẩu đã hết hạn. Vui lòng gửi lại yêu cầu.');
        }

        return view('auth.reset-password', compact('token', 'email'));
    }

    // Xử lý đặt lại mật khẩu
    public function reset(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email|exists:users,email',
            'token'                 => 'required',
            'mat_khau'              => 'required|min:6|confirmed',
            'mat_khau_confirmation' => 'required',
        ], [
            'email.exists'              => 'Email không tồn tại trong hệ thống.',
            'mat_khau.required'         => 'Vui lòng nhập mật khẩu mới.',
            'mat_khau.min'              => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'mat_khau.confirmed'        => 'Xác nhận mật khẩu không khớp.',
            'mat_khau_confirmation.required' => 'Vui lòng xác nhận mật khẩu.',
        ]);

        // Kiểm tra token hợp lệ lần nữa (tránh bypass)
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !hash_equals($record->token, hash('sha256', $request->token))) {
            return back()->with('error', 'Link đặt lại mật khẩu không hợp lệ.');
        }

        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect()->route('password.forgot')
                ->with('error', 'Link đặt lại mật khẩu đã hết hạn. Vui lòng gửi lại yêu cầu.');
        }

        // Cập nhật mật khẩu
        User::where('email', $request->email)->update([
            'mat_khau' => Hash::make($request->mat_khau),
        ]);

        // Xóa token sau khi dùng
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.');
    }
}
