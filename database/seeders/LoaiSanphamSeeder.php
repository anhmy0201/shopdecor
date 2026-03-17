<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoaiSanpham;

class LoaiSanphamSeeder extends Seeder
{
    public function run(): void
    {
        $loais = [
            [
                'ten_loai' => 'Tượng & Figurine',
                'slug'     => 'tuong-figurine',
                'mo_ta'    => 'Tượng phong thủy, figurine trang trí bàn làm việc mang lại may mắn và tài lộc.',
            ],
            [
                'ten_loai' => 'Đèn Decor',
                'slug'     => 'den-decor',
                'mo_ta'    => 'Đèn LED, đèn neon tạo không gian làm việc ấm cúng và cá tính.',
            ],
            [
                'ten_loai' => 'Cây Xanh Mini',
                'slug'     => 'cay-xanh-mini',
                'mo_ta'    => 'Chậu cây nhỏ, cây để bàn giúp thanh lọc không khí và thư giãn mắt.',
            ],
            [
                'ten_loai' => 'Văn Phòng Phẩm',
                'slug'     => 'van-phong-pham',
                'mo_ta'    => 'Bút ký cao cấp, sổ tay, hộp đựng bút thiết kế tinh tế.',
            ],
            [
                'ten_loai' => 'Tổ Chức Bàn',
                'slug'     => 'to-chuc-ban',
                'mo_ta'    => 'Khay, kệ, hộp đựng đồ giúp bàn làm việc gọn gàng và ngăn nắp.',
            ],
            [
                'ten_loai' => 'Desk Mat',
                'slug'     => 'desk-mat',
                'mo_ta'    => 'Tấm lót bàn làm việc aesthetic, bảo vệ mặt bàn và nâng tầm setup.',
            ],
        ];

        foreach ($loais as $loai) {
            LoaiSanpham::create($loai);
        }
    }
}
