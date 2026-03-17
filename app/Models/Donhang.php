<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donhang extends Model
{
    protected $table = 'donhang';

    const TRANG_THAI_MOI      = 0;
    const TRANG_THAI_XU_LY   = 1;
    const TRANG_THAI_HOAN_TAT = 2;
    const TRANG_THAI_HUY      = 3;

    protected $fillable = [
        'user_id', 'magiamgia_id',
        'ten_nguoi_nhan', 'so_dien_thoai', 'email',
        'dia_chi_chi_tiet', 'phuong_xa', 'quan_huyen', 'tinh_thanh',
        'phuong_thuc_thanhtoan', 'trang_thai_thanhtoan', 'ma_giao_dich',
        'ma_van_don', 'phi_ship', 'trang_thai_van_chuyen',
        'tong_tien_hang', 'so_tien_giam', 'tong_thanh_toan',
        'trang_thai', 'ngay_dat', 'ngay_duyet', 'ngay_giao',
        'ghi_chu_khach', 'ghi_chu_admin',
    ];

    protected $casts = [
        'ngay_dat'   => 'datetime',
        'ngay_duyet' => 'datetime',
        'ngay_giao'  => 'datetime',
        'trang_thai' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function magiamgia()
    {
        return $this->belongsTo(Magiamgia::class, 'magiamgia_id');
    }

    public function chitiets()
    {
        return $this->hasMany(ChitietDonhang::class, 'donhang_id');
    }

    public function coTheHuy(): bool
    {
        return in_array($this->trang_thai, [self::TRANG_THAI_MOI, self::TRANG_THAI_XU_LY]);
    }
}