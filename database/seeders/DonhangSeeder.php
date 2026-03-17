<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donhang;
use App\Models\ChitietDonhang;
use App\Models\SanphamBienthe;
use App\Models\User;

class DonhangSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy danh sách user khách (quyen_han = 0)
        $users = User::where('quyen_han', User::USER)->get();

        // Lấy tất cả biến thể đang active
        $bienthes = SanphamBienthe::with('sanpham')->where('kich_hoat', true)->get();

        if ($bienthes->isEmpty()) return;

        // Danh sách địa chỉ mẫu
        $diaChis = [
            ['ten' => 'Nguyễn Văn An',  'sdt' => '0911111111', 'dc' => '12 Lê Lợi',      'px' => 'Phường Bến Nghé',  'qh' => 'Quận 1',        'tt' => 'TP. Hồ Chí Minh'],
            ['ten' => 'Trần Thị Bích',  'sdt' => '0922222222', 'dc' => '45 Trần Phú',     'px' => 'Phường Hải Châu', 'qh' => 'Quận Hải Châu', 'tt' => 'Đà Nẵng'],
            ['ten' => 'Lê Hoàng Minh',  'sdt' => '0933333333', 'dc' => '78 Hoàn Kiếm',    'px' => 'Phường Hàng Bạc', 'qh' => 'Quận Hoàn Kiếm','tt' => 'Hà Nội'],
            ['ten' => 'Phạm Thúy Hằng', 'sdt' => '0944444444', 'dc' => '33 Nguyễn Huệ',   'px' => 'Phường Bến Thành','qh' => 'Quận 1',        'tt' => 'TP. Hồ Chí Minh'],
            ['ten' => 'Võ Quốc Toàn',   'sdt' => '0955555555', 'dc' => '56 Điện Biên Phủ','px' => 'Phường Đa Kao',   'qh' => 'Quận 1',        'tt' => 'TP. Hồ Chí Minh'],
        ];

        // Tạo 8 đơn hàng mẫu với các trạng thái khác nhau
        $donhangMaus = [
            ['trang_thai' => Donhang::TRANG_THAI_HOAN_TAT, 'tt_tt' => 'da_thanh_toan',   'tt_vc' => 'da_giao',          'pttt' => 'cod'],
            ['trang_thai' => Donhang::TRANG_THAI_HOAN_TAT, 'tt_tt' => 'da_thanh_toan',   'tt_vc' => 'da_giao',          'pttt' => 'momo'],
            ['trang_thai' => Donhang::TRANG_THAI_XU_LY,    'tt_tt' => 'chua_thanh_toan',  'tt_vc' => 'dang_van_chuyen',  'pttt' => 'cod'],
            ['trang_thai' => Donhang::TRANG_THAI_XU_LY,    'tt_tt' => 'da_thanh_toan',   'tt_vc' => 'cho_lay_hang',     'pttt' => 'chuyen_khoan'],
            ['trang_thai' => Donhang::TRANG_THAI_MOI,      'tt_tt' => 'chua_thanh_toan',  'tt_vc' => 'cho_lay_hang',     'pttt' => 'cod'],
            ['trang_thai' => Donhang::TRANG_THAI_MOI,      'tt_tt' => 'chua_thanh_toan',  'tt_vc' => 'cho_lay_hang',     'pttt' => 'momo'],
            ['trang_thai' => Donhang::TRANG_THAI_HUY,      'tt_tt' => 'chua_thanh_toan',  'tt_vc' => 'cho_lay_hang',     'pttt' => 'cod'],
            ['trang_thai' => Donhang::TRANG_THAI_HOAN_TAT, 'tt_tt' => 'da_thanh_toan',   'tt_vc' => 'da_giao',          'pttt' => 'chuyen_khoan'],
        ];

        foreach ($donhangMaus as $i => $mau) {
            $user    = $users[$i % $users->count()];
            $diaChi  = $diaChis[$i % count($diaChis)];

            // Chọn ngẫu nhiên 1-2 biến thể
            $chonBienthes = $bienthes->random(min(2, $bienthes->count()));
            $phiShip      = 30000;
            $tongTienHang = 0;

            // Tính tổng tiền hàng
            foreach ($chonBienthes as $bt) {
                $soLuong       = rand(1, 2);
                $tongTienHang += $bt->gia * $soLuong;
            }

            $soTienGiam   = ($i === 0) ? 100000 : 0; // đơn đầu dùng mã giảm giá
            $tongThanhToan = $tongTienHang + $phiShip - $soTienGiam;

            $donhang = Donhang::create([
                'user_id'               => $user->id,
                'magiamgia_id'          => ($i === 0) ? 1 : null,
                'ten_nguoi_nhan'        => $diaChi['ten'],
                'so_dien_thoai'         => $diaChi['sdt'],
                'email'                 => $user->email,
                'dia_chi_chi_tiet'      => $diaChi['dc'],
                'phuong_xa'             => $diaChi['px'],
                'quan_huyen'            => $diaChi['qh'],
                'tinh_thanh'            => $diaChi['tt'],
                'phuong_thuc_thanhtoan' => $mau['pttt'],
                'trang_thai_thanhtoan'  => $mau['tt_tt'],
                'ma_giao_dich'          => $mau['tt_tt'] === 'da_thanh_toan' ? 'TXN' . strtoupper(uniqid()) : null,
                'ma_van_don'            => $mau['tt_vc'] !== 'cho_lay_hang' ? 'GHN' . rand(100000, 999999) : null,
                'phi_ship'              => $phiShip,
                'trang_thai_van_chuyen' => $mau['tt_vc'],
                'tong_tien_hang'        => $tongTienHang,
                'so_tien_giam'          => $soTienGiam,
                'tong_thanh_toan'       => $tongThanhToan,
                'trang_thai'            => $mau['trang_thai'],
                'ngay_dat'              => now()->subDays(rand(1, 30)),
                'ngay_duyet'            => in_array($mau['trang_thai'], [Donhang::TRANG_THAI_XU_LY, Donhang::TRANG_THAI_HOAN_TAT])
                                            ? now()->subDays(rand(1, 5)) : null,
                'ngay_giao'             => $mau['trang_thai'] === Donhang::TRANG_THAI_HOAN_TAT
                                            ? now()->subDays(rand(0, 3)) : null,
                'ghi_chu_khach'         => $i === 0 ? 'Giao giờ hành chính, gọi trước khi giao.' : null,
                'ghi_chu_admin'         => null,
            ]);

            // Tạo chi tiết đơn hàng — snapshot
            foreach ($chonBienthes as $bt) {
                $soLuong = rand(1, 2);
                ChitietDonhang::create([
                    'donhang_id'   => $donhang->id,
                    'sanpham_id'   => $bt->sanpham_id,
                    'bienthe_id'   => $bt->id,
                    'ten_san_pham' => $bt->sanpham->ten_san_pham,
                    'ten_bienthe'  => $bt->ten_bienthe,
                    'ma_sku'       => $bt->ma_sku,
                    'hinh_anh'     => $bt->hinh_anh,
                    'so_luong'     => $soLuong,
                    'gia'          => $bt->gia,
                ]);
            }
        }
    }
}
