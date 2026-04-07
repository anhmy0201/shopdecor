<?php

namespace App\Traits;

use App\Models\Giohang;
use Illuminate\Support\Facades\Auth;

trait MergesGioHang
{
    protected function mergeGioHang(string $sessionId): void
    {
        $gioGuest = Giohang::where('session_id', $sessionId)
            ->with('chitiets')
            ->first();

        // Không có giỏ guest hoặc trống → bỏ qua
        if (!$gioGuest || $gioGuest->chitiets->isEmpty()) {
            return;
        }

        $gioUser = Giohang::firstOrCreate(['user_id' => Auth::id()]);

        foreach ($gioGuest->chitiets as $ct) {
            $existing = $gioUser->chitiets()
                ->where('sanpham_id', $ct->sanpham_id)
                ->when(
                    $ct->bienthe_id === null,
                    fn($q) => $q->whereNull('bienthe_id'),
                    fn($q) => $q->where('bienthe_id', $ct->bienthe_id)
                )
                ->first();

            if ($existing) {
                // Cộng dồn số lượng, không vượt quá 99
                $existing->update([
                    'so_luong' => min(99, $existing->so_luong + $ct->so_luong),
                ]);
            } else {
                $gioUser->chitiets()->create(
                    $ct->only(['sanpham_id', 'bienthe_id', 'so_luong', 'gia'])
                );
            }
        }

        // Xóa giỏ guest sau khi merge xong
        $gioGuest->chitiets()->delete();
        $gioGuest->delete();
    }
}
