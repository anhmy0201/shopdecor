<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class Magiamgia extends Model
{
    protected $table = 'magiamgia';
    use LogsActivity;

     public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['ma_code', 'gia_tri', 'kich_hoat'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $e) => match($e) {
                'created' => 'Thêm mã giảm giá mới',
                'updated' => 'Cập nhật mã giảm giá',
                'deleted' => 'Xóa mã giảm giá',
            });
    }
    protected $fillable = [
        'ma_code', 'mo_ta', 'kieu_giam', 'gia_tri',
        'don_hang_toi_thieu', 'giam_toi_da', 'so_luong',
        'da_su_dung', 'bat_dau', 'ket_thuc', 'kich_hoat',
    ];

    protected $casts = [
        'bat_dau'   => 'datetime',
        'ket_thuc'  => 'datetime',
        'kich_hoat' => 'boolean',
    ];

    public function donhangs()
    {
        return $this->hasMany(Donhang::class, 'magiamgia_id');
    }

    public function tinhSoTienGiam(float $tongTienHang): float
    {
        if ($tongTienHang < $this->don_hang_toi_thieu) return 0;

        if ($this->kieu_giam === 'phan_tram') {
            $giam = $tongTienHang * ($this->gia_tri / 100);
            if ($this->giam_toi_da) {
                $giam = min($giam, (float) $this->giam_toi_da);
            }
            return $giam;
        }

        return min((float) $this->gia_tri, $tongTienHang);
    }

    public function conHieuLuc(): bool
    {
        $ma = $this->fresh();

        if (!$ma->kich_hoat) return false;
        if ($ma->so_luong !== null && $ma->da_su_dung >= $ma->so_luong) return false;

        $now = now();
        if ($ma->bat_dau && $now->lt($ma->bat_dau)) return false;
        if ($ma->ket_thuc && $now->gt($ma->ket_thuc)) return false;

        return true;
    }

    public function tangDaSuDung(): void
    {
        static::where('id', $this->id)->increment('da_su_dung');
    }
}