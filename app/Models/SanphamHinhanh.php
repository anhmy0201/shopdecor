<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanphamHinhanh extends Model
{
    protected $table = 'sanpham_hinhanh';

    protected $fillable = ['sanpham_id', 'duong_dan_anh', 'la_anh_chinh', 'thu_tu'];

    protected $casts = ['la_anh_chinh' => 'boolean'];

    public function sanpham()
    {
        return $this->belongsTo(Sanpham::class, 'sanpham_id');
    }
}