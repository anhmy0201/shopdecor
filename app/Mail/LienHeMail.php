<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class LienHeMail extends Mailable
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this
            ->subject('📩 Yêu cầu tư vấn từ: ' . $this->data['ho_ten'])
            ->view('emails.lien-he');
    }
}