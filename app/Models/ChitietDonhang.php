<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChitietDonhang extends Model
{
    protected $table = 'chitiet_donhang';

    protected $fillable = [
        'donhang_id', 'sanpham_id', 'bienthe_id',
        'ten_san_pham', 'ten_bienthe', 'ma_sku', 'hinh_anh',
        'so_luong', 'gia',
    ];

    public function donhang()
    {
        return $this->belongsTo(Donhang::class, 'donhang_id');
    }

    public function sanpham()
    {
        return $this->belongsTo(Sanpham::class, 'sanpham_id');
    }

    public function bienthe()
    {
        return $this->belongsTo(SanphamBienthe::class, 'bienthe_id');
    }

    public function getThanhTienAttribute(): float
    {
        return $this->so_luong * $this->gia;
    }
}