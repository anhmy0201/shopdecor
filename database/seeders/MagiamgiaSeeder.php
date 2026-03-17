<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Magiamgia;

class MagiamgiaSeeder extends Seeder
{
    public function run(): void
    {
        $magiamgias = [
            [
                'ma_code'           => 'WELCOME10',
                'mo_ta'             => 'Giảm 10% cho khách hàng mới',
                'kieu_giam'         => 'phan_tram',
                'gia_tri'           => 10,
                'don_hang_toi_thieu'=> 0,
                'giam_toi_da'       => 100000,
                'so_luong'          => null, // không giới hạn
                'da_su_dung'        => 0,
                'bat_dau'           => now(),
                'ket_thuc'          => now()->addMonths(6),
                'kich_hoat'         => true,
            ],
            [
                'ma_code'           => 'SALE20',
                'mo_ta'             => 'Giảm 20% cho đơn từ 500K',
                'kieu_giam'         => 'phan_tram',
                'gia_tri'           => 20,
                'don_hang_toi_thieu'=> 500000,
                'giam_toi_da'       => 200000,
                'so_luong'          => 100,
                'da_su_dung'        => 0,
                'bat_dau'           => now(),
                'ket_thuc'          => now()->addMonths(1),
                'kich_hoat'         => true,
            ],
            [
                'ma_code'           => 'GIAM50K',
                'mo_ta'             => 'Giảm thẳng 50.000đ cho đơn từ 300K',
                'kieu_giam'         => 'co_dinh',
                'gia_tri'           => 50000,
                'don_hang_toi_thieu'=> 300000,
                'giam_toi_da'       => null,
                'so_luong'          => 200,
                'da_su_dung'        => 0,
                'bat_dau'           => now(),
                'ket_thuc'          => now()->addMonths(2),
                'kich_hoat'         => true,
            ],
            [
                'ma_code'           => 'VIP30',
                'mo_ta'             => 'Giảm 30% dành riêng cho khách VIP — đơn từ 1 triệu',
                'kieu_giam'         => 'phan_tram',
                'gia_tri'           => 30,
                'don_hang_toi_thieu'=> 1000000,
                'giam_toi_da'       => 500000,
                'so_luong'          => 50,
                'da_su_dung'        => 0,
                'bat_dau'           => now(),
                'ket_thuc'          => now()->addMonths(3),
                'kich_hoat'         => true,
            ],
            [
                'ma_code'           => 'FLASHSALE',
                'mo_ta'             => 'Flash sale cuối tuần — đã hết hạn (dùng để test)',
                'kieu_giam'         => 'phan_tram',
                'gia_tri'           => 15,
                'don_hang_toi_thieu'=> 0,
                'giam_toi_da'       => 150000,
                'so_luong'          => 50,
                'da_su_dung'        => 50, // đã dùng hết
                'bat_dau'           => now()->subDays(10),
                'ket_thuc'          => now()->subDays(3), // đã hết hạn
                'kich_hoat'         => false,
            ],
        ];

        foreach ($magiamgias as $ma) {
            Magiamgia::create($ma);
        }
    }
}
