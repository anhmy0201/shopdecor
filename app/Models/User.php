<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const USER  = 0;
    const STAFF = 1;
    const ADMIN = 2;

    protected $table = 'users';

    protected $fillable = [
        'ho_ten', 'hinh_anh', 'ten_dang_nhap', 'email',
        'so_dien_thoai', 'mat_khau', 'quyen_han', 'kich_hoat',
    ];

    protected $hidden = ['mat_khau', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mat_khau'          => 'hashed',
            'quyen_han'         => 'integer',
            'kich_hoat'         => 'boolean',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->mat_khau;
    }

    public function isAdmin(): bool { return $this->quyen_han === self::ADMIN; }
    public function isStaff(): bool { return $this->quyen_han >= self::STAFF; }
    public function isUser(): bool  { return $this->quyen_han === self::USER; }

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