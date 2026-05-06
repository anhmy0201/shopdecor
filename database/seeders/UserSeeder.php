<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Cấp 4 — Admin: toàn quyền hệ thống
        User::create([
            'ho_ten'        => 'Super Admin',
            'ten_dang_nhap' => 'admin',
            'email'         => 'admin@deskdecor.vn',
            'mat_khau'      => Hash::make('123456'),
            'so_dien_thoai' => '0901234567',
            'quyen_han'     => User::ADMIN,         // 4
            'kich_hoat'     => true,
        ]);

        // Cấp 3 — Giám đốc: báo cáo, nhân sự, cài đặt
        User::create([
            'ho_ten'        => 'Giám Đốc',
            'ten_dang_nhap' => 'giamdoc01',
            'email'         => 'giamdoc01@deskdecor.vn',
            'mat_khau'      => Hash::make('123456'),
            'so_dien_thoai' => '0901111111',
            'quyen_han'     => User::GIAM_DOC,      // 3
            'kich_hoat'     => true,
        ]);

        // Cấp 2 — Quản lí: doanh thu, Excel
        User::create([
            'ho_ten'        => 'Quản Lí Trưởng',
            'ten_dang_nhap' => 'quanli01',
            'email'         => 'quanli01@deskdecor.vn',
            'mat_khau'      => Hash::make('123456'),
            'so_dien_thoai' => '0903456789',
            'quyen_han'     => User::QUANLI,        // 2
            'kich_hoat'     => true,
        ]);

        // Cấp 1 — Nhân viên: sản phẩm, đơn hàng, banner
        User::create([
            'ho_ten'        => 'Nhân Viên A',
            'ten_dang_nhap' => 'staff01',
            'email'         => 'staff01@deskdecor.vn',
            'mat_khau'      => Hash::make('123456'),
            'so_dien_thoai' => '0902345678',
            'quyen_han'     => User::STAFF,         // 1
            'kich_hoat'     => true,
        ]);

        // Cấp 0 — Khách hàng
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
                'quyen_han'     => User::USER,      // 0
                'kich_hoat'     => true,
            ]);
        }
    }
}