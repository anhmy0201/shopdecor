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

    const USER   = 0;   // Khách hàng
    const STAFF  = 1;   // Nhân viên
    const KETOAN = 2;   // Kế toán
    const ADMIN  = 3;   // Giám đốc / Admin

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

    public function isKetoan(): bool
    {
        return $this->quyen_han === self::KETOAN;
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
        return match ($this->quyen_han) {
            self::ADMIN  => 'Giám đốc',
            self::KETOAN => 'Kế toán',
            self::STAFF  => 'Nhân viên',
            default      => 'Khách hàng',
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