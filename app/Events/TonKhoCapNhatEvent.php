<?php

namespace App\Events;

use App\Models\Sanpham;
use App\Models\SanphamBienthe;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class TonKhoCapNhatEvent implements ShouldBroadcastNow
{
    use SerializesModels;

    public function __construct(
        public Sanpham $sanpham,
        public ?SanphamBienthe $bienthe = null
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('san-pham-' . $this->sanpham->id);
    }

    public function broadcastAs(): string
    {
        return 'ton-kho-cap-nhat';
    }

    public function broadcastWith(): array
    {
        $sp = $this->sanpham->fresh()->load('bienthesActive');
        $bt = $this->bienthe?->fresh();

        return [
            'sanpham_id'       => $sp->id,
            'so_luong'         => $sp->ton_kho,
            'con_hang'         => (bool) $sp->con_hang,
            'bienthe_id'       => $bt?->id,
            'bienthe_so_luong' => $bt?->so_luong,
        ];
    }
}