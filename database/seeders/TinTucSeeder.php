<?php

namespace Database\Seeders;

use App\Models\TinTuc;
use App\Models\TinTucHinhanh;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TinTucSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy admin để làm tác giả
        $admin = User::where('quyen_han', User::ADMIN)->first();

        $baiViet = [
            [
                'tieu_de'    => '5 Cách Trang Trí Bàn Làm Việc Giúp Tăng Năng Suất',
                'mo_ta_ngan' => 'Không gian làm việc gọn gàng và có thẩm mỹ không chỉ giúp bạn tập trung hơn mà còn tạo cảm hứng sáng tạo mỗi ngày. Khám phá 5 mẹo đơn giản dưới đây.',
                'noi_dung'   => '<p>Một bàn làm việc được trang trí đẹp mắt không chỉ là nơi để làm việc — đó còn là không gian phản ánh cá tính và phong cách sống của bạn.</p>
<h3>1. Giữ bàn gọn gàng với hộp đựng bút và khay tổ chức</h3>
<p>Hãy đầu tư vào một bộ hộp đựng bút đa năng, khay đựng tài liệu và hộp đựng dây cáp. Khi mọi thứ có chỗ của nó, bạn sẽ tiết kiệm được rất nhiều thời gian tìm kiếm đồ.</p>
<h3>2. Thêm cây xanh mini</h3>
<p>Cây xanh nhỏ như xương rồng, sen đá, hay cây không khí không chỉ làm đẹp không gian mà còn giúp lọc không khí và giảm căng thẳng. Nghiên cứu cho thấy làm việc bên cạnh cây xanh giúp tăng năng suất lên đến 15%.</p>
<h3>3. Chọn đèn bàn phù hợp</h3>
<p>Ánh sáng là yếu tố quan trọng nhất ảnh hưởng đến sức khỏe mắt và tâm trạng khi làm việc.</p>
<h3>4. Đặt một vài vật trang trí có ý nghĩa</h3>
<p>Một chiếc tượng nhỏ, khung ảnh kỷ niệm, hay mô hình yêu thích giúp không gian làm việc trở nên cá nhân hơn.</p>
<h3>5. Sử dụng desk mat</h3>
<p>Một tấm desk mat chất lượng không chỉ bảo vệ mặt bàn khỏi trầy xước mà còn tạo nên một tổng thể thẩm mỹ đồng bộ.</p>',
                'ngay_dang'  => now()->subDays(10),
                'luot_xem'   => 342,
                'hinhanhs'   => [
                    'Bàn làm việc gọn gàng với hộp tổ chức',
                    'Cây xanh mini trang trí bàn làm việc',
                    'Đèn decor tạo điểm nhấn',
                ],
            ],
            [
                'tieu_de'    => 'Top 10 Sản Phẩm Decor Bàn Làm Việc Hot Nhất 2024',
                'mo_ta_ngan' => 'Tổng hợp những sản phẩm trang trí bàn làm việc được yêu thích nhất trong năm 2024 — từ tượng figurine độc đáo đến đèn led thẩm mỹ.',
                'noi_dung'   => '<p>Năm 2024 chứng kiến sự bùng nổ của xu hướng trang trí bàn làm việc tại nhà. Dưới đây là danh sách 10 sản phẩm được người dùng yêu thích nhất tại ShopDecor.</p>
<h3>1. Tượng Figurine Mini Nhân Vật Anime</h3>
<p>Xu hướng sưu tầm figurine ngày càng phổ biến, đặc biệt trong giới trẻ yêu thích anime và manga.</p>
<h3>2. Đèn LED Cầu Vồng Nhiều Màu</h3>
<p>Đèn LED có thể điều chỉnh màu sắc theo mood — vàng ấm khi muốn tập trung, xanh lạnh khi cần sáng tạo.</p>
<h3>3. Khay Đựng Đồ Mặt Gỗ Walnut</h3>
<p>Vật liệu gỗ walnut tự nhiên mang lại vẻ đẹp sang trọng và ấm áp.</p>',
                'ngay_dang'  => now()->subDays(7),
                'luot_xem'   => 218,
                'hinhanhs'   => [
                    'Figurine anime trên bàn làm việc',
                    'Đèn LED decor nhiều màu',
                ],
            ],
            [
                'tieu_de'    => 'Hướng Dẫn Chọn Desk Mat Phù Hợp Với Setup Của Bạn',
                'mo_ta_ngan' => 'Desk mat là món phụ kiện không thể thiếu cho bất kỳ setup bàn làm việc nào. Nhưng làm sao chọn được chiếc phù hợp? Bài viết này sẽ giúp bạn.',
                'noi_dung'   => '<p>Desk mat đã trở thành một trong những phụ kiện được tìm kiếm nhiều nhất trong cộng đồng những người yêu thích setup bàn làm việc đẹp.</p>
<h3>Tại sao cần dùng Desk Mat?</h3>
<ul>
<li>Bảo vệ mặt bàn khỏi trầy xước, vết bẩn</li>
<li>Tạo nền đồng bộ cho toàn bộ setup</li>
<li>Giảm tiếng ồn khi gõ phím</li>
<li>Kê tay thoải mái hơn khi dùng chuột</li>
</ul>
<h3>Chọn kích thước bao nhiêu?</h3>
<p><strong>Small (60x30cm):</strong> Phù hợp với bàn nhỏ.</p>
<p><strong>Medium (80x40cm):</strong> Kích thước phổ biến nhất.</p>
<p><strong>XL (90x45cm trở lên):</strong> Cho những setup full-size.</p>',
                'ngay_dang'  => now()->subDays(3),
                'luot_xem'   => 476,
                'hinhanhs'   => [
                    'Desk mat da PU cao cấp',
                    'So sánh các kích thước desk mat',
                    'Setup bàn làm việc hoàn chỉnh với desk mat',
                ],
            ],
            [
                'tieu_de'    => 'Cây Xanh Mini Nào Phù Hợp Với Bàn Làm Việc?',
                'mo_ta_ngan' => 'Không phải loại cây nào cũng phù hợp để đặt trên bàn làm việc. Cùng khám phá những loại cây dễ chăm, không cần nhiều ánh sáng nhưng vẫn đẹp.',
                'noi_dung'   => '<p>Mang thiên nhiên vào không gian làm việc là xu hướng biophilic design đang rất thịnh hành.</p>
<h3>1. Sen Đá (Succulent)</h3>
<p>Sen đá là lựa chọn số 1 cho người bận rộn. Chỉ cần tưới 1-2 lần/tuần, chịu được điều kiện ánh sáng yếu.</p>
<h3>2. Xương Rồng Mini</h3>
<p>Cực kỳ dễ sống, có thể 2-3 tuần không tưới. Những chậu xương rồng nhỏ xinh tạo điểm nhấn thú vị.</p>
<h3>3. Cây Không Khí (Air Plant)</h3>
<p>Không cần đất, chỉ cần phun sương 2-3 lần/tuần. Có thể đặt trong bất kỳ vật đựng nào.</p>',
                'ngay_dang'  => now()->subDay(),
                'luot_xem'   => 89,
                'hinhanhs'   => [
                    'Sen đá nhiều màu trên bàn làm việc',
                    'Cây không khí trong bình thủy tinh',
                ],
            ],
            [
                'tieu_de'    => 'Quà Tặng Sếp Ý Nghĩa — Gợi Ý Từ ShopDecor',
                'mo_ta_ngan' => 'Đang loay hoay chưa biết tặng sếp món quà gì nhân dịp sinh nhật hay lễ tết? Những sản phẩm decor cao cấp từ ShopDecor sẽ là lựa chọn hoàn hảo.',
                'noi_dung'   => '<p>Chọn quà tặng sếp luôn là bài toán khó. Quà phải vừa thể hiện sự trân trọng, vừa phù hợp với địa vị, vừa không quá phô trương.</p>
<h3>Tại sao nên tặng đồ decor văn phòng?</h3>
<ul>
<li>Thực dụng — sếp dùng được hàng ngày</li>
<li>Sang trọng mà không quá đắt tiền</li>
<li>Ý nghĩa — thể hiện bạn quan tâm đến không gian làm việc của họ</li>
</ul>
<h3>Gợi ý theo ngân sách</h3>
<p><strong>Dưới 200.000đ:</strong> Bộ hộp đựng bút gỗ, cây xanh mini kèm chậu sứ.</p>
<p><strong>200.000 - 500.000đ:</strong> Đèn decor LED, tượng figurine cao cấp.</p>
<p><strong>Trên 500.000đ:</strong> Bộ desk mat + hộp đựng đồ đồng bộ, tượng điêu khắc nghệ thuật.</p>',
                'ngay_dang'  => now(),
                'luot_xem'   => 153,
                'hinhanhs'   => [
                    'Hộp quà tặng sang trọng từ ShopDecor',
                    'Bộ trang trí bàn làm việc cao cấp',
                ],
            ],
        ];

        foreach ($baiViet as $data) {
            $slug = Str::slug($data['tieu_de']);

            $tinTuc = TinTuc::create([
                'user_id'      => $admin?->id,
                'tieu_de'      => $data['tieu_de'],
                'slug'         => $slug,
                'mo_ta_ngan'   => $data['mo_ta_ngan'],
                'noi_dung'     => $data['noi_dung'],
                'anh_dai_dien' => null,
                'luot_xem'     => $data['luot_xem'],
                'kich_hoat'    => true,
                'ngay_dang'    => $data['ngay_dang'],
            ]);

            foreach ($data['hinhanhs'] as $i => $chuThich) {
                TinTucHinhanh::create([
                    'tintuc_id'     => $tinTuc->id,
                    'duong_dan_anh' => 'storage/tintuc/' . $slug . '/gallery/anh-' . ($i + 1) . '.jpg',
                    'chu_thich'     => $chuThich,
                    'thu_tu'        => $i,
                ]);
            }
        }

        $this->command->info('✅ Đã tạo ' . count($baiViet) . ' bài viết tin tức mẫu.');
    }
}
