<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiSanpham extends Model
{
    protected $table = 'loai_sanpham';

    protected $fillable = ['ten_loai', 'slug', 'mo_ta'];

    public function sanphams()
    {
        return $this->hasMany(Sanpham::class, 'loai_id');
    }
}