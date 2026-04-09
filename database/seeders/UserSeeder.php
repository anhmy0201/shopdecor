<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'ho_ten'        => 'Admin Shop',
            'ten_dang_nhap' => 'admin',
            'email'         => 'admin@deskdecor.vn',
            'mat_khau'      => Hash::make('123456'),
            'so_dien_thoai' => '0901234567',
            'quyen_han'     => User::ADMIN,     // 3
            'kich_hoat'     => true,
        ]);

        User::create([
            'ho_ten'        => 'Kế Toán Trưởng',
            'ten_dang_nhap' => 'ketoan01',
            'email'         => 'ketoan01@deskdecor.vn',
            'mat_khau'      => Hash::make('123456'),
            'so_dien_thoai' => '0903456789',
            'quyen_han'     => User::KETOAN,    // 2
            'kich_hoat'     => true,
        ]);

        User::create([
            'ho_ten'        => 'Nhân Viên A',
            'ten_dang_nhap' => 'staff01',
            'email'         => 'staff01@deskdecor.vn',
            'mat_khau'      => Hash::make('123456'),
            'so_dien_thoai' => '0902345678',
            'quyen_han'     => User::STAFF,     // 1
            'kich_hoat'     => true,
        ]);

        $khachs = [
            ['ho_ten' => 'Nguyễn Văn An',  'ten_dang_nhap' => 'vanan',    'email' => 'vanan@gmail.com',    'sdt' => '0911111111'],
            ['ho_ten' => 'Trần Thị Bích',  'ten_dang_nhap' => 'thibich',  'email' => 'thibich@gmail.com',  'sdt' => '0922222222'],
            ['ho_ten' => 'Lê Hoàng Minh',  'ten_dang_nhap' => 'hminh',    'email' => 'hminh@gmail.com',    'sdt' => '0933333333'],
            ['ho_ten' => 'Phạm Thúy Hằng', 'ten_dang_nhap' => 'thuyhang', 'email' => 'thuyhang@gmail.com', 'sdt' => '0944444444'],
            ['ho_ten' => 'Võ Quốc Toàn',   'ten_dang_nhap' => 'quoctoan', 'email' => 'quoctoan@gmail.com', 'sdt' => '0955555555'],
        ];

        foreach ($khachs as $k) {
            User::create([
                'ho_ten'        => $k['ho_ten'],
                'ten_dang_nhap' => $k['ten_dang_nhap'],
                'email'         => $k['email'],
                'mat_khau'      => Hash::make('123456'),
                'so_dien_thoai' => $k['sdt'],
                'quyen_han'     => User::USER,  // 0
                'kich_hoat'     => true,
            ]);
        }
    }
}