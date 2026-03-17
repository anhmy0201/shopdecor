<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Giohang extends Model
{
    protected $table = 'giohang';

    protected $fillable = ['user_id', 'session_id', 'ngay_tao'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chitiets()
    {
        return $this->hasMany(ChitietGiohang::class, 'giohang_id');
    }

    public function getTongTienAttribute(): float
    {
        $chitiets = $this->relationLoaded('chitiets')
            ? $this->chitiets
            : $this->chitiets()->get();

        return (float) $chitiets->sum(fn($ct) => $ct->so_luong * $ct->gia);
    }
}