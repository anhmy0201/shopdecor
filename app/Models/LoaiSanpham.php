<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class LoaiSanpham extends Model
{
    use LogsActivity;

     public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['ten_loai', 'kich_hoat'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $e) => match($e) {
                'created' => 'Thêm loại sản phẩm mới',
                'updated' => 'Cập nhật loại sản phẩm',
                'deleted' => 'Xóa loại sản phẩm',
            });
    }
    protected $table = 'loai_sanpham';

    protected $fillable = ['ten_loai', 'slug', 'mo_ta'];

    public function sanphams()
    {
        return $this->hasMany(Sanpham::class, 'loai_id');
    }
}