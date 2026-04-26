<?php

namespace App\Mail;

use App\Models\Donhang;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class DonHangMail extends Mailable
{
    public function __construct(public Donhang $donhang) {}

    public function envelope(): Envelope
    {
        $maDon = 'DH' . str_pad($this->donhang->id, 6, '0', STR_PAD_LEFT);
        return new Envelope(subject: "Xác nhận đơn hàng #{$maDon} - ShopDecor");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.xac-nhan-dat-hang');
    }
}
