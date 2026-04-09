<?php

namespace Tests\Unit;

use App\Models\Magiamgia;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

/**
 * Test logic nghiệp vụ của mã giảm giá — KHÔNG cần database.
 *
 * Lưu ý: conHieuLuc() gọi $this->fresh() nên cần DB.
 * Vì vậy các test conHieuLuc được tách sang MagiamgiaFeatureTest.php.
 * File này chỉ test tinhSoTienGiam() — thuần PHP, chạy nhanh.
 *
 * Chạy: php artisan test --filter=MagiamgiaTest
 */
class MagiamgiaTest extends TestCase
{
    // -------------------------------------------------------
    //  tinhSoTienGiam — giảm cố định
    // -------------------------------------------------------

    public function test_giam_co_dinh_tru_thang_gia_tri(): void
    {
        $ma = new Magiamgia([
            'kieu_giam'          => 'co_dinh',
            'gia_tri'            => 50000,
            'don_hang_toi_thieu' => 0,
            'giam_toi_da'        => null,
        ]);

        $this->assertEquals(50000, $ma->tinhSoTienGiam(200000));
    }

    public function test_giam_co_dinh_khong_vuot_qua_tong_tien_hang(): void
    {
        $ma = new Magiamgia([
            'kieu_giam'          => 'co_dinh',
            'gia_tri'            => 500000,
            'don_hang_toi_thieu' => 0,
            'giam_toi_da'        => null,
        ]);

        // Giảm không được âm — tối đa bằng tổng tiền hàng
        $this->assertEquals(100000, $ma->tinhSoTienGiam(100000));
    }

    // -------------------------------------------------------
    //  tinhSoTienGiam — giảm phần trăm
    // -------------------------------------------------------

    public function test_giam_phan_tram_tinh_dung(): void
    {
        $ma = new Magiamgia([
            'kieu_giam'          => 'phan_tram',
            'gia_tri'            => 10,   // 10%
            'don_hang_toi_thieu' => 0,
            'giam_toi_da'        => null,
        ]);

        $this->assertEquals(20000, $ma->tinhSoTienGiam(200000));
    }

    public function test_giam_phan_tram_bi_cap_boi_giam_toi_da(): void
    {
        $ma = new Magiamgia([
            'kieu_giam'          => 'phan_tram',
            'gia_tri'            => 50,     // 50%
            'don_hang_toi_thieu' => 0,
            'giam_toi_da'        => 100000, // tối đa 100k
        ]);

        // 50% của 500k = 250k → bị cap ở 100k
        $this->assertEquals(100000, $ma->tinhSoTienGiam(500000));
    }

    public function test_giam_phan_tram_khong_vuot_cap_neu_thap_hon(): void
    {
        $ma = new Magiamgia([
            'kieu_giam'          => 'phan_tram',
            'gia_tri'            => 10,
            'don_hang_toi_thieu' => 0,
            'giam_toi_da'        => 100000,
        ]);

        // 10% của 200k = 20k, thấp hơn cap 100k → không bị giới hạn
        $this->assertEquals(20000, $ma->tinhSoTienGiam(200000));
    }

    public function test_giam_100_phan_tram_khong_am(): void
    {
        $ma = new Magiamgia([
            'kieu_giam'          => 'phan_tram',
            'gia_tri'            => 100,
            'don_hang_toi_thieu' => 0,
            'giam_toi_da'        => null,
        ]);

        // 100% → giảm toàn bộ, bằng đúng tổng tiền hàng
        $this->assertEquals(300000, $ma->tinhSoTienGiam(300000));
    }

    // -------------------------------------------------------
    //  don_hang_toi_thieu
    // -------------------------------------------------------

    public function test_don_hang_chua_dat_toi_thieu_tra_ve_0(): void
    {
        $ma = new Magiamgia([
            'kieu_giam'          => 'co_dinh',
            'gia_tri'            => 50000,
            'don_hang_toi_thieu' => 200000,
            'giam_toi_da'        => null,
        ]);

        $this->assertEquals(0, $ma->tinhSoTienGiam(150000));
    }

    public function test_don_hang_bang_dung_toi_thieu_duoc_giam(): void
    {
        $ma = new Magiamgia([
            'kieu_giam'          => 'co_dinh',
            'gia_tri'            => 50000,
            'don_hang_toi_thieu' => 200000,
            'giam_toi_da'        => null,
        ]);

        // Đúng bằng tối thiểu → được áp dụng
        $this->assertEquals(50000, $ma->tinhSoTienGiam(200000));
    }

    public function test_don_hang_lon_hon_toi_thieu_duoc_giam(): void
    {
        $ma = new Magiamgia([
            'kieu_giam'          => 'co_dinh',
            'gia_tri'            => 50000,
            'don_hang_toi_thieu' => 200000,
            'giam_toi_da'        => null,
        ]);

        $this->assertEquals(50000, $ma->tinhSoTienGiam(500000));
    }

    // -------------------------------------------------------
    //  Edge cases
    // -------------------------------------------------------

    public function test_tong_tien_hang_bang_0_tra_ve_0(): void
    {
        $ma = new Magiamgia([
            'kieu_giam'          => 'co_dinh',
            'gia_tri'            => 50000,
            'don_hang_toi_thieu' => 0,
            'giam_toi_da'        => null,
        ]);

        $this->assertEquals(0, $ma->tinhSoTienGiam(0));
    }

    public function test_giam_phan_tram_tong_tien_0_tra_ve_0(): void
    {
        $ma = new Magiamgia([
            'kieu_giam'          => 'phan_tram',
            'gia_tri'            => 20,
            'don_hang_toi_thieu' => 0,
            'giam_toi_da'        => null,
        ]);

        $this->assertEquals(0.0, $ma->tinhSoTienGiam(0));
    }
}
