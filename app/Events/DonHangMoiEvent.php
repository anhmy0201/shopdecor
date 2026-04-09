<?php

namespace App\Events;

use App\Models\Donhang;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class DonHangMoiEvent implements ShouldBroadcastNow
{
    use SerializesModels;

    public function __construct(public Donhang $donhang) {}

    public function broadcastOn(): Channel
    {
        return new Channel('admin-notifications');
    }

    public function broadcastAs(): string
    {
        return 'don-hang-moi';
    }

    public function broadcastWith(): array
    {
        $maDon = '#DH' . str_pad($this->donhang->id, 6, '0', STR_PAD_LEFT);

        return [
            'id'         => $this->donhang->id,
            'ma_don'     => $maDon,
            'ten_khach'  => $this->donhang->ten_nguoi_nhan,
            'so_dt'      => $this->donhang->so_dien_thoai,
            'tong_tien'  => $this->donhang->tong_thanh_toan,
            'phuong_thuc'=> $this->donhang->phuong_thuc_thanhtoan === 'cod' ? 'COD' : 'Chuyển khoản',
            'thoi_gian'  => now()->format('H:i d/m/Y'),
            'url'        => url('/admin/donhang/' . $this->donhang->id),
        ];
    }
}