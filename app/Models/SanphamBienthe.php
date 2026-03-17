<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanphamBienthe extends Model
{
    protected $table = 'sanpham_bienthe';

    protected $fillable = [
        'sanpham_id', 'ma_sku', 'ten_bienthe', 'hinh_anh',
        'gia', 'so_luong', 'thu_tu', 'kich_hoat',
    ];

    protected $casts = ['kich_hoat' => 'boolean'];

    public function sanpham()
    {
        return $this->belongsTo(Sanpham::class, 'sanpham_id');
    }

    public function getConHangAttribute(): bool
    {
        return $this->so_luong > 0;
    }
}