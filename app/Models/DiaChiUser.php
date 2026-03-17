<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaChiUser extends Model
{
    protected $table = 'dia_chi_user';

    protected $fillable = [
        'user_id', 'ho_ten', 'so_dien_thoai',
        'dia_chi_chi_tiet', 'phuong_xa', 'quan_huyen', 'tinh_thanh', 'mac_dinh',
    ];

    protected $casts = ['mac_dinh' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}