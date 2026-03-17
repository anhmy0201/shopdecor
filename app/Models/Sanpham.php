<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sanpham extends Model
{
    use SoftDeletes;

    protected $table = 'sanpham';

    protected $fillable = [
        'loai_id', 'ten_san_pham', 'slug', 'gia', 'gia_cu',
        'mo_ta', 'so_luong', 'luot_xem', 'luot_mua', 'co_bien_the',
    ];

    protected $casts = [
        'co_bien_the' => 'boolean',
    ];

    public function loai()
    {
        return $this->belongsTo(LoaiSanpham::class, 'loai_id');
    }

    public function hinhanhs()
    {
        return $this->hasMany(SanphamHinhanh::class, 'sanpham_id');
    }

    public function anhChinh()
    {
        return $this->hasOne(SanphamHinhanh::class, 'sanpham_id')->where('la_anh_chinh', true);
    }

    // Tất cả biến thể — dùng trong admin
    public function bienthes()
    {
        return $this->hasMany(SanphamBienthe::class, 'sanpham_id')->orderBy('thu_tu');
    }

    // Chỉ biến thể active — dùng ngoài frontend
    public function bienthesActive()
    {
        return $this->hasMany(SanphamBienthe::class, 'sanpham_id')
                    ->where('kich_hoat', true)
                    ->orderBy('thu_tu');
    }

    public function binhluans()
    {
        return $this->hasMany(Binhluan::class, 'sanpham_id');
    }

    public function getTonKhoAttribute(): int
    {
        if ($this->co_bien_the) {
            $bienthes = $this->relationLoaded('bienthesActive')
                ? $this->bienthesActive
                : $this->bienthesActive()->get();
            return (int) $bienthes->sum('so_luong');
        }
        return (int) $this->so_luong;
    }

    public function getHienThiGiaAttribute(): string
    {
        $bienthes = $this->relationLoaded('bienthesActive')
            ? $this->bienthesActive
            : $this->bienthesActive()->get();

        if ($bienthes->isEmpty()) {
            return number_format($this->gia, 0, ',', '.') . 'đ';
        }

        $min = $bienthes->min('gia');
        $max = $bienthes->max('gia');

        if ($min == $max) {
            return number_format($min, 0, ',', '.') . 'đ';
        }

        return number_format($min, 0, ',', '.') . 'đ – ' . number_format($max, 0, ',', '.') . 'đ';
    }

    public function getConHangAttribute(): bool
    {
        return $this->ton_kho > 0;
    }
}