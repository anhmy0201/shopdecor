<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\LienHeMail;

class LienHeController extends Controller
{
    public function index()
    {
        return view('pages.lien-he');
    }

    public function gui(Request $request)
    {
        $request->validate([
            'ho_ten'     => 'required|string|max:100',
            'dien_thoai' => 'required|string|max:20',
            'noi_dung'   => 'required|string',
            'email'      => 'nullable|email|max:100',
        ]);

        $data = $request->only(['ho_ten', 'dien_thoai', 'email', 'chu_de', 'noi_dung']);

        Mail::to('your@gmail.com')->send(new LienHeMail($data));

        return back()->with('success', 'Cảm ơn bạn! Chúng tôi sẽ liên hệ lại sớm nhất.');
    }
}