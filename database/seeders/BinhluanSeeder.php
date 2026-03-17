<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Binhluan;
use App\Models\Sanpham;
use App\Models\User;

class BinhluanSeeder extends Seeder
{
    public function run(): void
    {
        $users    = User::where('quyen_han', User::USER)->get();
        $sanphams = Sanpham::all();

        if ($users->isEmpty() || $sanphams->isEmpty()) return;

        // Kho nội dung bình luận thật
        $noiDungs = [
            5 => [
                'Sản phẩm đẹp hơn ảnh, đóng gói cẩn thận. Shop giao hàng nhanh, rất hài lòng!',
                'Chất lượng tuyệt vời, xứng đáng với giá tiền. Mình đã mua lần 2 rồi, sẽ tiếp tục ủng hộ shop.',
                'Đặt làm quà tặng sếp, nhìn rất sang và ý nghĩa. Sếp khen nhiều lắm, cảm ơn shop!',
                'Hàng y hình, chất liệu tốt. Giao hàng đúng hẹn. 5 sao không có gì để chê.',
                'Decor bàn làm việc của mình đẹp hẳn lên nhờ sản phẩm này. Rất thích!',
            ],
            4 => [
                'Sản phẩm đẹp, chỉ tiếc hộp đựng hơi đơn giản. Nhưng hàng thì ổn, sẽ mua lại.',
                'Chất lượng tốt, giá hơi cao một chút nhưng xứng đáng. Giao hàng nhanh.',
                'Nhìn thực tế đẹp hơn mình tưởng. Màu sắc chuẩn như ảnh. Hài lòng 4/5.',
                'Shop tư vấn nhiệt tình, sản phẩm ổn. Trừ 1 sao vì ship hơi lâu.',
            ],
            3 => [
                'Sản phẩm tạm được, không có gì đặc sắc lắm. Phù hợp làm quà bình thường.',
                'Chất lượng ở mức trung bình so với giá. Mong shop cải thiện thêm.',
            ],
        ];

        $daDanhGia = []; // tránh trùng user + sanpham

        // Mỗi sản phẩm có 3-5 bình luận
        foreach ($sanphams as $sanpham) {
            $soLuongBL = rand(3, 5);
            $shuffled  = $users->shuffle()->take($soLuongBL);

            foreach ($shuffled as $user) {
                $key = "{$user->id}_{$sanpham->id}";
                if (isset($daDanhGia[$key])) continue;
                $daDanhGia[$key] = true;

                // Phân bổ sao: 70% = 5 sao, 20% = 4 sao, 10% = 3 sao
                $rand = rand(1, 10);
                $sao  = $rand <= 7 ? 5 : ($rand <= 9 ? 4 : 3);

                Binhluan::create([
                    'user_id'      => $user->id,
                    'sanpham_id'   => $sanpham->id,
                    'sao_danh_gia' => $sao,
                    'noi_dung'     => $noiDungs[$sao][array_rand($noiDungs[$sao])],
                    'ngay_dang'    => now()->subDays(rand(1, 60)),
                ]);
            }
        }
    }
}
