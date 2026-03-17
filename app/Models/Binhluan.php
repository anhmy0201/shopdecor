<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Binhluan extends Model
{
    protected $table = 'binhluan';

    protected $fillable = ['user_id', 'sanpham_id', 'noi_dung', 'sao_danh_gia', 'ngay_dang'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sanpham()
    {
        return $this->belongsTo(Sanpham::class, 'sanpham_id');
    }
}