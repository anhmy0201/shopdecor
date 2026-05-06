<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity; 

    const USER      = 0;   // Khách hàng
    const STAFF     = 1;   // Nhân viên
    const QUANLI    = 2;   // Quản lí
    const GIAM_DOC  = 3;   // Giám đốc
    const ADMIN     = 4;   // Admin — toàn quyền hệ thống

    protected $fillable = [
        'ho_ten',
        'hinh_anh',
        'ten_dang_nhap',
        'email',
        'so_dien_thoai',
        'email_verified_at',
        'mat_khau',
        'quyen_han',
        'kich_hoat',
    ];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'kich_hoat'         => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['ten_dang_nhap', 'email', 'quyen_han', 'kich_hoat'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $e) => match($e) {
                'created' => 'Thêm người dùng mới',
                'updated' => 'Cập nhật thông tin người dùng',
                'deleted' => 'Xóa người dùng',
            });
    }
    public function getAuthPassword(): string
    {
        return $this->mat_khau;
    }
    public function isAdmin(): bool
    {
        return $this->quyen_han === self::ADMIN;
    }

    public function isGiamDoc(): bool
    {
        return $this->quyen_han === self::GIAM_DOC;
    }

    public function isKetoan(): bool
    {
        return $this->quyen_han === self::QUANLI;
    }

    public function isStaff(): bool
    {
        return $this->quyen_han >= self::STAFF;
    }

    public function isNhanVien(): bool
    {
        return $this->quyen_han === self::STAFF;
    }

    public function isUser(): bool
    {
        return $this->quyen_han === self::USER;
    }

    public function tenQuyenHan(): string
    {
        return match (true) {
            $this->quyen_han >= self::ADMIN    => 'Admin',
            $this->quyen_han === self::GIAM_DOC => 'Giám đốc',
            $this->quyen_han === self::QUANLI   => 'Quản lí',
            $this->quyen_han === self::STAFF    => 'Nhân viên',
            default                             => 'Khách hàng',
        };
    }
        public function diaChis()
    {
        return $this->hasMany(DiaChiUser::class, 'user_id');
    }

    public function diaChiMacDinh()
    {
        return $this->hasOne(DiaChiUser::class, 'user_id')->where('mac_dinh', true);
    }

    public function binhluans()
    {
        return $this->hasMany(Binhluan::class);
    }

    public function giohang()
    {
        return $this->hasOne(Giohang::class)->whereNotNull('user_id');
    }

    public function donhangs()
    {
        return $this->hasMany(Donhang::class);
    }
}