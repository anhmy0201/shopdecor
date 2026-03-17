<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class TinTuc extends Model
{
 
    protected $table = 'tintuc';
 
    protected $fillable = [
        'user_id',
        'tieu_de',
        'slug',
        'mo_ta_ngan',
        'noi_dung',
        'anh_dai_dien',
        'luot_xem',
        'kich_hoat',
        'ngay_dang',
    ];
 
    protected $casts = [
        'kich_hoat' => 'boolean',
        'ngay_dang' => 'datetime',
    ];
 
    // Relationships
    public function tacGia()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
 
    public function hinhanhs()
    {
        return $this->hasMany(TinTucHinhanh::class, 'tintuc_id')
                    ->orderBy('thu_tu');
    }
 
    // Scope — chỉ lấy bài đã publish
    public function scopeDaPublish($query)
    {
        return $query->where('kich_hoat', true)
                     ->where('ngay_dang', '<=', now());
    }
}