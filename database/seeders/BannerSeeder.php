<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'duong_dan_anh' => 'storage/banner/01.jpg',
                'tieu_de'       => 'Decor Bàn Làm Việc – Tinh Tế & Sang Trọng',
                'mo_ta'         => 'Hàng trăm mẫu tượng phong thủy, đèn decor và phụ kiện cao cấp.',
                'url_lien_ket'  => '/san-pham',
                'thu_tu'        => 1,
            ],
            [
                'duong_dan_anh' => 'storage/banner/02.jpg',
                'tieu_de'       => 'Giảm Đến 30% Đơn Hàng Đầu Tiên',
                'mo_ta'         => 'Dùng mã WELCOME10 khi thanh toán để nhận ưu đãi ngay hôm nay.',
                'url_lien_ket'  => '/san-pham',
                'thu_tu'        => 2,
            ],
            [
                'duong_dan_anh' => 'storage/banner/03.jpg',
                'tieu_de'       => 'Cây Xanh Mini & Đèn Decor Tinh Tế',
                'mo_ta'         => 'Thêm sức sống, ánh sáng ấm áp cho góc làm việc mỗi ngày.',
                'url_lien_ket'  => '/danh-muc/cay-xanh-mini',
                'thu_tu'        => 3,
            ],
        ];

        foreach ($banners as $b) {
            Banner::updateOrCreate(
                ['duong_dan_anh' => $b['duong_dan_anh']],
                [
                    'tieu_de'       => $b['tieu_de'],
                    'mo_ta'         => $b['mo_ta'],
                    'url_lien_ket'  => $b['url_lien_ket'],
                    'thu_tu'        => $b['thu_tu'],
                    'kich_hoat'     => true,
                    'ngay_bat_dau'  => null,
                    'ngay_ket_thuc' => null,
                ]
            );
        }

    }
}