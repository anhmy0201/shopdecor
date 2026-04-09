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
        $loaiDen     = LoaiSanpham::where('slug', 'den-decor')->first()->id;
        $loaiCay     = LoaiSanpham::where('slug', 'cay-xanh-mini')->first()->id;
        $loaiVPP     = LoaiSanpham::where('slug', 'van-phong-pham')->first()->id;

        $sanphams = [

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


            [
                'loai_id'      => $loaiTuong,
                'ten_san_pham' => 'Decor Hạc Đồng Phong Thủy',
                'slug'         => 'decor-hac-dong-phong-thuy',
                'gia'          => 1380000,
                'gia_cu'       => 1680000,
                'mo_ta'        => 'Decor hạc đồng phong thủy cao cấp, được đúc từ đồng nguyên chất với kỹ thuật thủ công tỉ mỉ. Hạc là biểu tượng của trường thọ, thanh cao và điềm lành trong văn hóa phương Đông. Đặt trên bàn làm việc hay tủ kệ, sản phẩm không chỉ mang vẻ đẹp thẩm mỹ tinh tế mà còn giúp thu hút năng lượng tích cực, mang lại sự bình an và tài lộc cho gia chủ.',
                'co_bien_the'  => true,
                'gallery'      => [
                    ['file' => 'Decor-hac-dong-phong-thuy-1.jpg', 'chinh' => true],
                    ['file' => 'Decor-hac-dong-phong-thuy-2.jpg', 'chinh' => false],
                    ['file' => 'Decor-hac-dong-phong-thuy-3.jpg', 'chinh' => false],
                ],
                'bienthes'     => [
                    [
                        'ten_bienthe' => 'Size S – 23cm',
                        'ma_sku'      => 'DHDPT-A1',
                        'gia'         => 1380000,
                        'so_luong'    => 15,
                        'hinh_anh'    => 'Decor-hac-dong-phong-thuy-A1.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Size M – 32cm',
                        'ma_sku'      => 'DHDPT-A2',
                        'gia'         => 1850000,
                        'so_luong'    => 10,
                        'hinh_anh'    => 'Decor-hac-dong-phong-thuy-A2.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Size L – 38cm',
                        'ma_sku'      => 'DHDPT-A3',
                        'gia'         => 2350000,
                        'so_luong'    => 7,
                        'hinh_anh'    => 'Decor-hac-dong-phong-thuy-A3.jpg',
                    ],
                ],
            ],


            [
                'loai_id'      => $loaiDen,
                'ten_san_pham' => 'Đèn Để Bàn Cặp Hươu Decor',
                'slug'         => 'den-de-ban-cap-huou-decor',
                'gia'          => 620000,
                'gia_cu'       => 780000,
                'mo_ta'        => 'Đèn để bàn tạo hình cặp hươu decor độc đáo, ánh sáng LED ấm áp dịu nhẹ tạo nên không gian làm việc thư giãn và ấm cúng. Thiết kế nghệ thuật với đường nét uốn lượn mềm mại, chất liệu kim loại sơn tĩnh điện bền đẹp. Phù hợp đặt trang trí bàn làm việc, bàn đọc sách hoặc phòng ngủ, vừa thắp sáng vừa là điểm nhấn thẩm mỹ thu hút ánh nhìn.',
                'co_bien_the'  => false,
                'gallery'      => [
                    ['file' => 'Den-de-ban-cap-huou-decor-1.jpg', 'chinh' => true],
                    ['file' => 'Den-de-ban-cap-huou-decor-3.jpg', 'chinh' => false],
                    ['file' => 'Den-de-ban-cap-huou-decor-2.jpg', 'chinh' => false],
                ],
                'bienthes'     => [],
            ],

            [
                'loai_id'      => $loaiDen,
                'ten_san_pham' => 'Đèn Bàn Làm Việc IZKEA DB50',
                'slug'         => 'den-ban-lam-viec-izkea-db50',
                'gia'          => 485000,
                'gia_cu'       => 590000,
                'mo_ta'        => 'Đèn bàn làm việc IZKEA DB50 thiết kế tối giản hiện đại, ánh sáng LED chống chói bảo vệ mắt tối ưu trong các buổi làm việc dài. Cần đèn linh hoạt điều chỉnh góc chiếu 360 độ, công tắc cảm ứng tiện lợi với 3 chế độ sáng và điều chỉnh độ sáng vô cấp. Cổng sạc USB tích hợp giúp bạn sạc điện thoại trong lúc làm việc mà không cần thêm ổ điện.',
                'co_bien_the'  => false,
                'gallery'      => [
                    ['file' => 'den-ban-lam-viec-izkea-db50-hoaianvendor-4.jpg', 'chinh' => true],
                    ['file' => 'den-ban-lam-viec-izkea-db50-hoaianvendor-2.jpg', 'chinh' => false],
                    ['file' => 'den-ban-lam-viec-izkea-db50-hoaianvendor-1.jpg', 'chinh' => false],
                ],
                'bienthes'     => [],
            ],

            [
                'loai_id'      => $loaiDen,
                'ten_san_pham' => 'Đèn LED Trang Trí Phòng Ngủ Tsuisuto DB07',
                'slug'         => 'den-led-trang-tri-phong-ngu-tsuisuto-db07',
                'gia'          => 320000,
                'gia_cu'       => 420000,
                'mo_ta'        => 'Đèn LED trang trí phòng ngủ Tsuisuto DB07 với hiệu ứng ánh sáng lung linh tạo bầu không khí mơ mộng và lãng mạn. Dải LED nhiều màu sắc điều chỉnh qua remote hoặc app điện thoại, hỗ trợ các chế độ nhấp nháy theo nhạc sinh động. Dán tường hoặc quấn quanh đồ vật dễ dàng, tiêu thụ điện năng cực thấp, an toàn sử dụng liên tục cho phòng ngủ và góc setup bàn làm việc.',
                'co_bien_the'  => false,
                'gallery'      => [
                    ['file' => 'den-led-trang-tri-phong-ngu-tsuisuto-db07-1.jpg', 'chinh' => true],
                    ['file' => 'den-led-trang-tri-phong-ngu-tsuisuto-db07-2.jpg', 'chinh' => false],
                    ['file' => 'den-led-trang-tri-phong-ngu-tsuisuto-db07-4.jpg', 'chinh' => false],
                ],
                'bienthes'     => [],
            ],

            [
                'loai_id'      => $loaiDen,
                'ten_san_pham' => 'Đèn Để Bàn Đọc Sách Giá Rẻ Manabu DB04',
                'slug'         => 'den-de-ban-doc-sach-gia-re-manabu-db04',
                'gia'          => 185000,
                'gia_cu'       => 240000,
                'mo_ta'        => 'Đèn để bàn đọc sách Manabu DB04 nhỏ gọn tiện dụng, ánh sáng LED trắng trung tính 4000K không gây mỏi mắt kể cả khi đọc sách nhiều giờ liền. Cổng sạc USB cắm nguồn tiện lợi không cần thay pin, thân đèn uốn linh hoạt điều hướng ánh sáng theo ý muốn. Thiết kế nhẹ nhàng dễ mang theo, phù hợp sử dụng tại bàn học, bàn làm việc hoặc trên giường trước khi ngủ.',
                'co_bien_the'  => true,
                'gallery'      => [
                    ['file' => 'den-de-ban-doc-sach-gia-re-manabu-db04-2.jpg', 'chinh' => true],
                ],
                'bienthes'     => [
                    [
                        'ten_bienthe' => 'Trắng',
                        'ma_sku'      => 'MANABU-DB04-TG',
                        'gia'         => 185000,
                        'so_luong'    => 30,
                        'hinh_anh'    => 'den-de-ban-doc-sach-gia-re-manabu-db04-1.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Đen',
                        'ma_sku'      => 'MANABU-DB04-DN',
                        'gia'         => 185000,
                        'so_luong'    => 30,
                        'hinh_anh'    => 'den-de-ban-doc-sach-gia-re-manabu-db04-2.jpg',
                    ],
                ],
            ],

            [
                'loai_id'      => $loaiCay,
                'ten_san_pham' => 'Chậu Kim Ngân 3 Thân',
                'slug'         => 'chau-kim-ngan-3-than',
                'gia'          => 145000,
                'gia_cu'       => 190000,
                'mo_ta'        => 'Chậu kim ngân 3 thân bện xoắn thủ công tinh tế, cây khỏe mạnh tươi tốt quanh năm với lá xanh óng ánh. Kim ngân là loài cây phong thủy biểu tượng cho tài lộc, may mắn và thịnh vượng trong phong thủy học. Cây dễ chăm sóc, chịu bóng tốt, phù hợp đặt trong nhà, văn phòng hay trên bàn làm việc để thanh lọc không khí và tạo năng lượng tích cực.',
                'co_bien_the'  => false,
                'gallery'      => [
                    ['file' => 'chau-kim-ngan-3-than-1.jpg', 'chinh' => true],
                    ['file' => 'chau-kim-ngan-3-than-2.jpg', 'chinh' => false],
                ],
                'bienthes'     => [],
            ],

            [
                'loai_id'      => $loaiCay,
                'ten_san_pham' => 'Kim Tiền Để Bàn',
                'slug'         => 'kim-tien-de-ban',
                'gia'          => 110000,
                'gia_cu'       => 150000,
                'mo_ta'        => 'Cây kim tiền để bàn mini xinh xắn trong chậu sứ nhỏ gọn, mang ý nghĩa phong thủy thu hút tài lộc và tiền bạc. Lá cây tròn xanh mướt như những đồng xu xếp chồng lên nhau tượng trưng cho sự sung túc và giàu có. Cây phát triển tốt trong điều kiện ánh sáng yếu, ít cần tưới nước — lý tưởng cho người bận rộn muốn có một góc xanh trên bàn làm việc.',
                'co_bien_the'  => true,
                'gallery'      => [
                    ['file' => 'kim-tien-de-ban.jpg', 'chinh' => true],
                ],
                'bienthes'     => [
                    [
                        'ten_bienthe' => 'Chậu Hồng',
                        'ma_sku'      => 'KTDB-HONG',
                        'gia'         => 110000,
                        'so_luong'    => 25,
                        'hinh_anh'    => 'cay-kim-tien-size-s-1.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Chậu Trắng',
                        'ma_sku'      => 'KTDB-TRANG',
                        'gia'         => 110000,
                        'so_luong'    => 25,
                        'hinh_anh'    => 'cay-kim-tien-size-s-2.jpg',
                    ],
                ],
            ],

            [
                'loai_id'      => $loaiCay,
                'ten_san_pham' => 'Chậu Hoa Dành Dành',
                'slug'         => 'chau-hoa-danh-danh',
                'gia'          => 125000,
                'gia_cu'       => 165000,
                'mo_ta'        => 'Hoa dành dành trồng chậu mini thanh lịch với hương thơm dịu nhẹ tự nhiên, mang lại cảm giác thư thái và dễ chịu cho không gian làm việc. Cánh hoa trắng muốt hoặc hồng phấn mỏng manh xinh xắn, nở bung tỏa hương suốt mùa hè. Chậu sứ nhỏ gọn đặt vừa vặn trên góc bàn, vừa đẹp mắt vừa giúp thanh lọc không khí và giảm căng thẳng hiệu quả.',
                'co_bien_the'  => true,
                'gallery'      => [
                    ['file' => 'chau-hoa-danh-danh-1.jpg', 'chinh' => true],
                ],
                'bienthes'     => [
                    [
                        'ten_bienthe' => 'Hoa Hồng',
                        'ma_sku'      => 'CHDD-HONG',
                        'gia'         => 125000,
                        'so_luong'    => 20,
                        'hinh_anh'    => 'chau-hoa-danh-danh-2.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Hoa Trắng',
                        'ma_sku'      => 'CHDD-TRANG',
                        'gia'         => 125000,
                        'so_luong'    => 20,
                        'hinh_anh'    => 'chau-hoa-danh-danh-3.jpg',
                    ],
                ],
            ],

            [
                'loai_id'      => $loaiCay,
                'ten_san_pham' => 'Đỏ Lá Cỏ Thư',
                'slug'         => 'do-la-co-thu',
                'gia'          => 98000,
                'gia_cu'       => 130000,
                'mo_ta'        => 'Cỏ thư đỏ lá mini trồng chậu, màu sắc nổi bật và cá tính tạo điểm nhấn sinh động cho góc bàn làm việc của bạn. Cây ưa ẩm, sống tốt trong nhà với ánh sáng gián tiếp, không cần chăm sóc phức tạp. Chậu nhỏ gọn tinh tế, phù hợp đặt cạnh màn hình máy tính hay trên kệ sách, vừa mang lại màu sắc tươi vui vừa giúp thư giãn đôi mắt sau những giờ làm việc căng thẳng.',
                'co_bien_the'  => true,
                'gallery'      => [
                    ['file' => 'do-la-co-thu-1.jpg', 'chinh' => true],
                ],
                'bienthes'     => [
                    [
                        'ten_bienthe' => 'Chậu Hồng',
                        'ma_sku'      => 'DLCT-HONG',
                        'gia'         => 98000,
                        'so_luong'    => 20,
                        'hinh_anh'    => 'do-la-hoa-co-thu.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Chậu Trắng',
                        'ma_sku'      => 'DLCT-TRANG',
                        'gia'         => 98000,
                        'so_luong'    => 20,
                        'hinh_anh'    => 'do-la-co-thu-2.jpg',
                    ],
                ],
            ],

            [
                'loai_id'      => $loaiCay,
                'ten_san_pham' => 'Chậu Vạn Lộc Mix Cẩm Nhung',
                'slug'         => 'chau-van-loc-mix-cam-nhung',
                'gia'          => 135000,
                'gia_cu'       => 175000,
                'mo_ta'        => 'Chậu vạn lộc mix cẩm nhung là sự kết hợp độc đáo của hai loài cây phong thủy may mắn trong cùng một chậu, tạo nên vẻ đẹp tương phản xanh — đỏ bắt mắt và đầy sức sống. Vạn lộc mang ý nghĩa vạn điều may mắn, cẩm nhung với lá nhung đỏ rực rỡ tượng trưng cho sự nhiệt huyết và thành công. Bộ đôi cây dễ chăm, thích hợp đặt trong văn phòng hay bàn làm việc để thu hút tài lộc và nâng cao tinh thần.',
                'co_bien_the'  => true,
                'gallery'      => [
                    ['file' => 'chau-van-loc-mix-cam-nhung-1.jpg',          'chinh' => true],
                    ['file' => 'van-loc-mix-fittonia-la-may-man-1-1.jpg',   'chinh' => false],
                ],
                'bienthes'     => [
                    [
                        'ten_bienthe' => 'Chậu Hồng',
                        'ma_sku'      => 'CVLMCN-HONG',
                        'gia'         => 135000,
                        'so_luong'    => 20,
                        'hinh_anh'    => 'van-loc-mix-fittonia-la-may-man-2.jpg',
                    ],
                    [
                        'ten_bienthe' => 'Chậu Trắng',
                        'ma_sku'      => 'CVLMCN-TRANG',
                        'gia'         => 135000,
                        'so_luong'    => 20,
                        'hinh_anh'    => 'chau-van-loc-mix-cam-nhung-2.jpg',
                    ],
                ],
            ],

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


            [
                'loai_id'      => $loaiVPP,
                'ten_san_pham' => 'Bút Ký Cao Cấp Le Noble Starry Night',
                'slug'         => 'but-ky-cao-cap-le-noble-starry-night',
                'gia'          => 890000,
                'gia_cu'       => 1100000,
                'mo_ta'        => 'Bút ký cao cấp Le Noble Starry Night lấy cảm hứng từ bầu trời đêm đầy sao huyền ảo, thân bút được phủ lacquer đen bóng điểm xuyết các mảnh ánh kim lấp lánh như ngàn vì sao. Ngòi bút vàng 18K viết cực mượt và chính xác, phù hợp cho những buổi ký kết hợp đồng quan trọng hay làm quà tặng sếp, đối tác doanh nhân. Đi kèm hộp quà nhung sang trọng, đây là lựa chọn hoàn hảo cho những ai trân trọng từng nét chữ.',
                'co_bien_the'  => false,
                'gallery'      => [
                    ['file' => 'Le-Noble-Starry-Night-1.png', 'chinh' => true],
                    ['file' => 'Le-Noble-Starry-Night-2.png', 'chinh' => false],
                    ['file' => 'Le-Noble-Starry-Night-3.png', 'chinh' => false],
                ],
                'bienthes'     => [],
            ],

            [
                'loai_id'      => $loaiVPP,
                'ten_san_pham' => 'Bút Ký Cao Cấp Leon Dio Primal Hive White',
                'slug'         => 'but-ky-cao-cap-leon-dio-primal-hive-white',
                'gia'          => 750000,
                'gia_cu'       => 920000,
                'mo_ta'        => 'Bút ký cao cấp Leon Dio Primal Hive White với thiết kế độc bản lấy cảm hứng từ tổ ong lục giác tự nhiên — biểu tượng của sự cần cù, trật tự và thịnh vượng. Thân bút màu trắng tinh khiết được chạm khắc hoa văn tổ ong 3D tinh xảo nổi bật, cầm chắc tay và không trượt. Ngòi bút thép không gỉ độ cứng trung bình cho nét chữ đều và mượt. Hộp đựng thiết kế cao cấp biến đây thành món quà ký kết hay tặng thưởng đẳng cấp.',
                'co_bien_the'  => false,
                'gallery'      => [
                    ['file' => 'Hive-trang-copy.png', 'chinh' => true],
                    ['file' => 'Hive-W-1.jpg',        'chinh' => false],
                    ['file' => '35.png',               'chinh' => false],
                ],
                'bienthes'     => [],
            ],

        ];

        foreach ($sanphams as $data) {
            $sanpham = Sanpham::create([
                'loai_id'      => $data['loai_id'],
                'ten_san_pham' => $data['ten_san_pham'],
                'slug'         => $data['slug'],
                'gia'          => $data['gia'],
                'gia_cu'       => $data['gia_cu'],
                'mo_ta'        => $data['mo_ta'],
                'co_bien_the'  => $data['co_bien_the'],
                'so_luong'     => 0,
                'luot_xem'     => rand(50, 500),
                'luot_mua'     => rand(5, 80),
            ]);

            foreach ($data['gallery'] as $i => $anh) {
                SanphamHinhanh::create([
                    'sanpham_id'    => $sanpham->id,
                    'duong_dan_anh' => "storage/sanpham/{$sanpham->slug}/gallery/{$anh['file']}",
                    'la_anh_chinh'  => $anh['chinh'],
                    'thu_tu'        => $i,
                ]);
            }

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