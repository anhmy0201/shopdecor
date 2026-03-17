<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChitietGiohang extends Model
{
    protected $table = 'chitiet_giohang';

    protected $fillable = ['giohang_id', 'sanpham_id', 'bienthe_id', 'so_luong', 'gia'];

    public function giohang()
    {
        return $this->belongsTo(Giohang::class, 'giohang_id');
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