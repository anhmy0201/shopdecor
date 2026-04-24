<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;

class Banner extends Model
{
    use LogsActivity;

    protected $table = 'banners';

    protected $fillable = [
        'tieu_de', 'mo_ta', 'duong_dan_anh',
        'url_lien_ket', 'thu_tu', 'kich_hoat',
        'ngay_bat_dau', 'ngay_ket_thuc',
    ];

    protected $casts = [
        'kich_hoat'     => 'boolean',
        'ngay_bat_dau'  => 'date',
        'ngay_ket_thuc' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['tieu_de', 'kich_hoat', 'thu_tu'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $e) => match($e) {
                'created' => 'Thêm banner mới',
                'updated' => 'Cập nhật banner',
                'deleted' => 'Xóa banner',
                default   => $e,
            });
    }

    // Scope: chỉ lấy banner đang hoạt động và còn trong thời hạn
    public function scopeHoatDong($query)
    {
        $today = Carbon::today();
        return $query->where('kich_hoat', true)
            ->where(fn($q) => $q->whereNull('ngay_bat_dau')->orWhereDate('ngay_bat_dau', '<=', $today))
            ->where(fn($q) => $q->whereNull('ngay_ket_thuc')->orWhereDate('ngay_ket_thuc', '>=', $today))
            ->orderBy('thu_tu')
            ->orderBy('id');
    }
}
