<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class TinTucHinhanh extends Model
{
    protected $table = 'tintuc_hinhanh';
 
    protected $fillable = [
        'tintuc_id',
        'duong_dan_anh',
        'chu_thich',
        'thu_tu',
    ];
 
    public function tinTuc()
    {
        return $this->belongsTo(TinTuc::class, 'tintuc_id');
    }
}