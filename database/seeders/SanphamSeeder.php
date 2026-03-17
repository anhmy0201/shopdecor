<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sanpham;
use App\Models\SanphamHinhanh;
use App\Models\SanphamBienthe;
use App\Models\LoaiSanpham;

class SanphamSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy id các danh mục
        $loaiTuong   = LoaiSanpham::where('slug', 'tuong-figurine')->first()->id;
        $loaiVPP     = LoaiSanpham::where('slug', 'van-phong-pham')->first()->id;

        // =====================================================================
        // DỮ LIỆU SẢN PHẨM THẬT — từ file cấu trúc ảnh của shop
        // Đường dẫn ảnh: public/sanpham/{slug}/gallery/ và /bienthe/
        // =====================================================================
        $sanphams = [

            // ----- NHÓM TƯỢNG & FIGURINE -----

            [
                'loai_id'      => $loaiTuong,
                'ten_san_pham' => 'Kệ Rượu Vang Bát Mã Truy Phong Đá Ngọc Dát Vàng',
                'slug'         => 'ke-ruou-vang-bat-ma-truy-phong-da-ngoc-dat-vang',
                'gia'          => 1850000,
                'gia_cu'       => 2200000,
                'mo_ta'        => 'Kệ rượu vang phong thủy hình bát mã truy phong, chất liệu đá ngọc dát vàng 24K. Biểu tượng công danh, sự nghiệp thăng tiến. Thích hợp trang trí bàn làm việc và phòng khách.',
                'co_bien_the'  => true,
                'gallery'      => [
                    ['file' => 'Ke-ruou-vang-bat-ma-truy-phong-da-ngoc-dat-vang-2.jpg', 'chinh' => true],
                    ['file' => 'Ke-ruou-vang-bat-ma-truy-phong-da-ngoc-dat-vang-1.jpg', 'chinh' => false],
                ],
                'bienthes'     => [
                    [
                        'ten_bienthe' => 'Vàng Gold',
                        'ma_sku'      => 'KRVBM-A1',
                        'gia'         => 1850000,
                        'so_luong'    => 15,
                        'hinh_anh'    => 'Ke-ruou-vang-bat-ma-truy-phong-da-ngoc-dat-vang-A1.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Xanh Ngọc',
                        'ma_sku'      => 'KRVBM-A2',
                        'gia'         => 1950000,
                        'so_luong'    => 10,
                        'hinh_anh'    => 'Ke-ruou-vang-bat-ma-truy-phong-da-ngoc-dat-vang-A2.jpg',
                    ],
                ],
            ],

            [
                'loai_id'      => $loaiTuong,
                'ten_san_pham' => 'Decor Phong Thủy Tài Lộc Viên Mãn',
                'slug'         => 'decor-phong-thuy-tai-loc-vien-man',
                'gia'          => 1250000,
                'gia_cu'       => 1500000,
                'mo_ta'        => 'Decor phong thủy tài lộc viên mãn, mang ý nghĩa tài lộc đầy đủ, cuộc sống viên mãn. Thiết kế tinh xảo, phù hợp đặt trên bàn làm việc hoặc tủ kệ.',
                'co_bien_the'  => true,
                'gallery'      => [
                    ['file' => 'Decor-phong-thuy-tai-loc-vien-man-1.jpg', 'chinh' => true],
                    ['file' => 'Decor-phong-thuy-tai-loc-vien-man-8.jpg', 'chinh' => false],
                ],
                'bienthes'     => [
                    [
                        'ten_bienthe' => 'Đỏ Vàng',
                        'ma_sku'      => 'DPTLVM-A1',
                        'gia'         => 1250000,
                        'so_luong'    => 20,
                        'hinh_anh'    => 'Decor-phong-thuy-tai-loc-vien-man-A1.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Xanh Vàng',
                        'ma_sku'      => 'DPTLVM-A2',
                        'gia'         => 1350000,
                        'so_luong'    => 15,
                        'hinh_anh'    => 'Decor-phong-thuy-tai-loc-vien-man-A2.jpg',
                    ],
                ],
            ],

            [
                'loai_id'      => $loaiTuong,
                'ten_san_pham' => 'Tượng Ngựa Đồng Mã Vàng',
                'slug'         => 'tuong-ngua-dong-ma-vang',
                'gia'          => 2100000,
                'gia_cu'       => 2500000,
                'mo_ta'        => 'Tượng ngựa đồng mạ vàng, biểu tượng của sức mạnh, thành công và thăng tiến. Chất liệu đồng nguyên chất, gia công thủ công tỉ mỉ.',
                'co_bien_the'  => true,
                'gallery'      => [
                    ['file' => 'Tuong-ngua-dong-ma-vang-view.jpg',        'chinh' => true],
                    ['file' => 'Tuong-ngua-dong-ma-vang-trang-tri-1.jpg', 'chinh' => false],
                ],
                'bienthes'     => [
                    [
                        'ten_bienthe' => 'Size S – H15cm',
                        'ma_sku'      => 'TNDMV-A2',
                        'gia'         => 2100000,
                        'so_luong'    => 12,
                        'hinh_anh'    => 'Tuong-ngua-dong-ma-vang-trang-tri-A2.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Size M – H22cm',
                        'ma_sku'      => 'TNDMV-A3',
                        'gia'         => 2800000,
                        'so_luong'    => 8,
                        'hinh_anh'    => 'Tuong-ngua-dong-ma-vang-trang-tri-A3.jpg',
                    ],
                ],
            ],

            [
                'loai_id'      => $loaiTuong,
                'ten_san_pham' => 'Tượng Bát Mã Hùng Phong Đá Ngọc',
                'slug'         => 'tuong-bat-ma-hung-phong-da-ngoc',
                'gia'          => 3200000,
                'gia_cu'       => 3800000,
                'mo_ta'        => 'Tượng bát mã hùng phong chất liệu đá ngọc cao cấp, tượng trưng cho 8 con ngựa phi nước đại — ý nghĩa sự nghiệp thành công vang dội, thăng quan tiến chức.',
                'co_bien_the'  => true,
                'gallery'      => [
                    ['file' => 'Tuong-bat-ma-hung-phong-da-ngoc-1.jpg',  'chinh' => true],
                    ['file' => 'Tuong-bat-ma-hung-phong-da-ngoc-20.jpg', 'chinh' => false],
                ],
                'bienthes'     => [
                    [
                        'ten_bienthe' => 'Ngọc Trắng',
                        'ma_sku'      => 'TBMHP-A1',
                        'gia'         => 3200000,
                        'so_luong'    => 10,
                        'hinh_anh'    => 'Tuong-bat-ma-hung-phong-da-ngoc-A1.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Ngọc Xanh',
                        'ma_sku'      => 'TBMHP-A2',
                        'gia'         => 3500000,
                        'so_luong'    => 7,
                        'hinh_anh'    => 'Tuong-bat-ma-hung-phong-da-ngoc-A2.jpg',
                    ],
                ],
            ],

            [
                'loai_id'      => $loaiTuong,
                'ten_san_pham' => 'Quà Tặng Thuận Buồm Xuôi Gió Đá Ngọc',
                'slug'         => 'qua-tang-thuan-buom-xuoi-gio-da-ngoc',
                'gia'          => 1650000,
                'gia_cu'       => 2000000,
                'mo_ta'        => 'Mô hình thuyền buồm phong thủy chất liệu đá ngọc, mang ý nghĩa thuận buồm xuôi gió — mọi việc hanh thông, thuận lợi. Quà tặng sếp, đối tác ý nghĩa.',
                'co_bien_the'  => true,
                'gallery'      => [
                    ['file' => 'Qua-tang-thuan-buom-xuoi-gio-da-ngoc-View1.jpg', 'chinh' => true],
                    ['file' => 'Qua-tang-thuan-buom-xuoi-gio-da-ngoc-1.jpg',     'chinh' => false],
                ],
                'bienthes'     => [
                    [
                        'ten_bienthe' => 'Trắng Sữa',
                        'ma_sku'      => 'QTBBXG-A1',
                        'gia'         => 1650000,
                        'so_luong'    => 18,
                        'hinh_anh'    => 'Qua-tang-thuan-buom-xuoi-gio-da-ngoc-A1.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Xanh Ngọc',
                        'ma_sku'      => 'QTBBXG-A2',
                        'gia'         => 1750000,
                        'so_luong'    => 12,
                        'hinh_anh'    => 'Qua-tang-thuan-buom-xuoi-gio-da-ngoc-A2.jpg',
                    ],
                ],
            ],

            [
                'loai_id'      => $loaiTuong,
                'ten_san_pham' => 'Bình Hút Lộc Bát Tràng Cán Vàng 24K Thuyền Sen',
                'slug'         => 'binh-hut-loc-bat-trang-can-vang-24k-thuyen-sen',
                'gia'          => 980000,
                'gia_cu'       => 1200000,
                'mo_ta'        => 'Bình hút lộc gốm Bát Tràng cán vàng 24K hình thuyền sen, biểu tượng của sự thanh tịnh và tài lộc. Sản phẩm thủ công mỹ nghệ truyền thống Việt Nam.',
                'co_bien_the'  => true,
                'gallery'      => [
                    ['file' => 'Binh-hut-loc-bat-trang-can-vang-24k-thuyen-sen-2.jpg', 'chinh' => true],
                    ['file' => 'Binh-hut-loc-bat-trang-can-vang-24k-thuyen-sen-3.jpg', 'chinh' => false],
                ],
                'bienthes'     => [
                    [
                        'ten_bienthe' => 'Size S',
                        'ma_sku'      => 'BHLBT-A1',
                        'gia'         => 980000,
                        'so_luong'    => 25,
                        'hinh_anh'    => 'Binh-hut-loc-bat-trang-can-vang-24k-thuyen-sen-A1.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Size M',
                        'ma_sku'      => 'BHLBT-A2',
                        'gia'         => 1250000,
                        'so_luong'    => 18,
                        'hinh_anh'    => 'Binh-hut-loc-bat-trang-can-vang-24k-thuyen-sen-A2.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Size L',
                        'ma_sku'      => 'BHLBT-A3',
                        'gia'         => 1550000,
                        'so_luong'    => 10,
                        'hinh_anh'    => 'Binh-hut-loc-bat-trang-can-vang-24k-thuyen-sen-A3.jpg',
                    ],
                ],
            ],

            // ----- NHÓM VĂN PHÒNG PHẨM -----

            [
                'loai_id'      => $loaiVPP,
                'ten_san_pham' => 'Bút Ký Khảm Trai Cao Cấp',
                'slug'         => 'but-ky-kham-tra-cao-cap',
                'gia'          => 450000,
                'gia_cu'       => 550000,
                'mo_ta'        => 'Bút ký cao cấp khảm trai thủ công, thân bút inox mạ vàng, nét viết mượt mà. Hộp đựng sang trọng, thích hợp làm quà tặng sếp, đối tác, khách hàng VIP.',
                'co_bien_the'  => true,
                'gallery'      => [
                    ['file' => 'But-ky-kham-tra-cao-cap-view.jpg', 'chinh' => true],
                    ['file' => 'But-ky-kham-tra-cao-cap-4.jpg',    'chinh' => false],
                ],
                'bienthes'     => [
                    [
                        'ten_bienthe' => 'Vàng Gold',
                        'ma_sku'      => 'BKKT-A1',
                        'gia'         => 450000,
                        'so_luong'    => 30,
                        'hinh_anh'    => 'But-ky-kham-tra-cao-cap-A1.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Bạc Silver',
                        'ma_sku'      => 'BKKT-A2',
                        'gia'         => 450000,
                        'so_luong'    => 30,
                        'hinh_anh'    => 'But-ky-kham-tra-cao-cap-A2.jpg',
                    ],
                ],
            ],

            [
                'loai_id'      => $loaiVPP,
                'ten_san_pham' => 'Quà Tặng Bút Ký Hộp Kim Cao Cấp',
                'slug'         => 'qua-tang-but-ky-hop-kim-cao-cap',
                'gia'          => 680000,
                'gia_cu'       => 850000,
                'mo_ta'        => 'Set bút ký hộp kim cao cấp, thiết kế sang trọng. Thân bút kim loại nguyên khối, hộp đựng nhung cao cấp. Quà tặng doanh nhân ý nghĩa và đẳng cấp.',
                'co_bien_the'  => true,
                'gallery'      => [
                    ['file' => 'Qua-tang-but-ky-hop-kim-cao-cap-5.jpg',  'chinh' => true],
                    ['file' => 'Qua-tang-but-ky-hop-kim-cao-cap-12.jpg', 'chinh' => false],
                ],
                'bienthes'     => [
                    [
                        'ten_bienthe' => 'Đen Mạ Vàng',
                        'ma_sku'      => 'QTBKHK-A1',
                        'gia'         => 680000,
                        'so_luong'    => 25,
                        'hinh_anh'    => 'Qua-tang-but-ky-hop-kim-cao-cap-A1.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Bạc Mạ Chrome',
                        'ma_sku'      => 'QTBKHK-A2',
                        'gia'         => 680000,
                        'so_luong'    => 25,
                        'hinh_anh'    => 'Qua-tang-but-ky-hop-kim-cao-cap-A2.jpg',
                    ],
                ],
            ],

            [
                'loai_id'      => $loaiVPP,
                'ten_san_pham' => 'Quà Tặng Bút Ký Gỗ Cao Cấp',
                'slug'         => 'qua-tang-but-ky-go-cao-cap',
                'gia'          => 520000,
                'gia_cu'       => 650000,
                'mo_ta'        => 'Bút ký thân gỗ cao cấp, chạm khắc tinh tế, kèm hộp gỗ sang trọng. Vẻ đẹp tự nhiên, ấm áp và đẳng cấp. Quà tặng độc đáo và ý nghĩa cho người thân, đối tác.',
                'co_bien_the'  => true,
                'gallery'      => [
                    ['file' => 'Qua-tang-but-ky-go-cao-cap-View.jpg', 'chinh' => true],
                    ['file' => 'Qua-tang-but-ky-go-cao-cap-2.jpg',    'chinh' => false],
                ],
                'bienthes'     => [
                    [
                        'ten_bienthe' => 'Gỗ Trắc',
                        'ma_sku'      => 'QTBKGCC-A1',
                        'gia'         => 520000,
                        'so_luong'    => 20,
                        'hinh_anh'    => 'Qua-tang-but-ky-go-cao-cap-A1.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Gỗ Hương',
                        'ma_sku'      => 'QTBKGCC-A2',
                        'gia'         => 580000,
                        'so_luong'    => 15,
                        'hinh_anh'    => 'Qua-tang-but-ky-go-cao-cap-A2.jpg',
                    ],
                ],
            ],

        ];

        // =====================================================================
        // TẠO SẢN PHẨM, ẢNH, BIẾN THỂ
        // =====================================================================
        foreach ($sanphams as $data) {
            // 1. Tạo sản phẩm
            $sanpham = Sanpham::create([
                'loai_id'      => $data['loai_id'],
                'ten_san_pham' => $data['ten_san_pham'],
                'slug'         => $data['slug'],
                'gia'          => $data['gia'],
                'gia_cu'       => $data['gia_cu'],
                'mo_ta'        => $data['mo_ta'],
                'co_bien_the'  => $data['co_bien_the'],
                'so_luong'     => 0, // SP có biến thể — tồn kho tính từ bienthe
                'luot_xem'     => rand(50, 500),
                'luot_mua'     => rand(5, 80),
            ]);

            // 2. Tạo ảnh gallery
            foreach ($data['gallery'] as $i => $anh) {
                SanphamHinhanh::create([
                    'sanpham_id'    => $sanpham->id,
                    'duong_dan_anh' => "storage/sanpham/{$sanpham->slug}/gallery/{$anh['file']}",
                    'la_anh_chinh'  => $anh['chinh'],
                    'thu_tu'        => $i,
                ]);
            }

            // 3. Tạo biến thể
            foreach ($data['bienthes'] as $i => $bt) {
                SanphamBienthe::create([
                    'sanpham_id'  => $sanpham->id,
                    'ten_bienthe' => $bt['ten_bienthe'],
                    'ma_sku'      => $bt['ma_sku'],
                    'gia'         => $bt['gia'],
                    'so_luong'    => $bt['so_luong'],
                    'hinh_anh'    => "storage/sanpham/{$sanpham->slug}/bienthe/{$bt['hinh_anh']}",
                    'thu_tu'      => $i,
                    'kich_hoat'   => true,
                ]);
            }
        }
    }
}