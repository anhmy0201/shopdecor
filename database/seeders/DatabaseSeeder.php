<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,          // 1. Users trước
            LoaiSanphamSeeder::class,   // 2. Danh mục
            SanphamSeeder::class,       // 3. Sản phẩm + ảnh + biến thể
            MagiamgiaSeeder::class,     // 4. Mã giảm giá
            DonhangSeeder::class,       // 5. Đơn hàng mẫu
            BinhluanSeeder::class,      // 6. Bình luận / đánh giá
            TinTucSeeder::class,       // 7. Tin tức
        ]);
    }
}
