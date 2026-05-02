<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // Hiển thị form nhập email
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    // Xử lý gửi email reset
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email'    => 'Email không hợp lệ.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Luôn thông báo thành công dù email có tồn tại hay không
        // để tránh lộ thông tin tài khoản
        if (!$user) {
            return back()->with('success',
                'Nếu email tồn tại trong hệ thống, chúng tôi đã gửi link đặt lại mật khẩu. Vui lòng kiểm tra hộp thư.'
            );
        }

        // Xóa token cũ nếu có
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Tạo token mới
        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => hash('sha256', $token), // lưu hash, không lưu raw
            'created_at' => Carbon::now(),
        ]);

        // Gửi email
        try {
            Mail::send('emails.reset-password', [
                'user'  => $user,
                'token' => $token,
                'url'   => route('password.reset.form', ['token' => $token, 'email' => $request->email]),
            ], function ($mail) use ($user) {
                $mail->to($user->email)
                     ->subject('Đặt lại mật khẩu - ShopDecor');
            });
        } catch (\Exception $e) {
            \Log::error('Gửi email reset password thất bại: ' . $e->getMessage());
        }

        return back()->with('success',
            'Nếu email tồn tại trong hệ thống, chúng tôi đã gửi link đặt lại mật khẩu. Vui lòng kiểm tra hộp thư.'
        );
    }
}
