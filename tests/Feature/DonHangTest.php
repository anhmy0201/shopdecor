<?php

namespace Tests\Feature;

use App\Models\Donhang;
use App\Models\Giohang;
use App\Models\ChitietGiohang;
use App\Models\Magiamgia;
use App\Models\LoaiSanpham;
use App\Models\Sanpham;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests cho luồng đặt hàng, giỏ hàng, phân quyền, và bảo mật PayOS.
 *
 * Chạy: php artisan test --filter=DonHangTest
 */
class DonHangTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------
    //  Helpers dùng chung
    // -------------------------------------------------------

    private function taoSanpham(int $soLuong = 10): Sanpham
    {
        $loai = LoaiSanpham::create([
            'ten_loai' => 'Decor',
            'slug'     => 'decor',
        ]);

        return Sanpham::create([
            'loai_id'      => $loai->id,
            'ten_san_pham' => 'Đèn trang trí',
            'slug'         => 'den-trang-tri',
            'gia'          => 150000,
            'so_luong'     => $soLuong,
            'co_bien_the'  => false,
        ]);
    }

    private function taoUser(): User
    {
        return User::factory()->create();
    }

    private function themVaoGioHangUser(User $user, Sanpham $sanpham, int $soLuong = 1): void
    {
        $giohang = Giohang::firstOrCreate(['user_id' => $user->id]);
        ChitietGiohang::create([
            'giohang_id' => $giohang->id,
            'sanpham_id' => $sanpham->id,
            'bienthe_id' => null,
            'so_luong'   => $soLuong,
            'gia'        => $sanpham->gia,
        ]);
    }

    private function duLieuDatHang(array $merge = []): array
    {
        return array_merge([
            'ten_nguoi_nhan'        => 'Nguyễn Văn A',
            'so_dien_thoai'         => '0901234567',
            'email'                 => 'test@example.com',
            'dia_chi_chi_tiet'      => '123 Đường ABC',
            'phuong_xa'             => 'Phường 1',
            'quan_huyen'            => 'Quận 1',
            'tinh_thanh'            => 'TP. Hồ Chí Minh',
            'phuong_thuc_thanhtoan' => 'cod',
            'ghi_chu_khach'         => '',
            'magiamgia_id'          => null,
        ], $merge);
    }

    private function taoDonHang(User $user, Sanpham $sanpham): Donhang
    {
        return Donhang::create([
            'user_id'               => $user->id,
            'ten_nguoi_nhan'        => 'Test',
            'so_dien_thoai'         => '0900000000',
            'dia_chi_chi_tiet'      => '123',
            'phuong_xa'             => 'P1',
            'quan_huyen'            => 'Q1',
            'tinh_thanh'            => 'HCM',
            'phuong_thuc_thanhtoan' => 'chuyen_khoan',
            'trang_thai_thanhtoan'  => 'chua_thanh_toan',
            'phi_ship'              => 0,
            'tong_tien_hang'        => $sanpham->gia,
            'so_tien_giam'          => 0,
            'tong_thanh_toan'       => $sanpham->gia,
            'trang_thai'            => Donhang::TRANG_THAI_MOI,
        ]);
    }

    // -------------------------------------------------------
    //  Trang public
    // -------------------------------------------------------

    public function test_trang_chu_tra_ve_200(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_trang_gio_hang_tra_ve_200(): void
    {
        $this->get('/gio-hang')->assertStatus(200);
    }

    public function test_trang_san_pham_tra_ve_200(): void
    {
        $this->get('/san-pham')->assertStatus(200);
    }

    // -------------------------------------------------------
    //  Giỏ hàng
    // -------------------------------------------------------

    public function test_them_san_pham_vao_gio_hang(): void
    {
        $user    = $this->taoUser();
        $sanpham = $this->taoSanpham();

        $this->actingAs($user)
             ->postJson('/gio-hang/them', [
                 'san_pham_id' => $sanpham->id,
                 'so_luong'    => 2,
             ])
             ->assertJson(['success' => true, 'tong_so_luong' => 2]);

        $this->assertDatabaseHas('chitiet_giohang', [
            'sanpham_id' => $sanpham->id,
            'so_luong'   => 2,
        ]);
    }

    public function test_them_san_pham_da_co_trong_gio_cong_don_so_luong(): void
    {
        $user    = $this->taoUser();
        $sanpham = $this->taoSanpham();

        $this->actingAs($user)->postJson('/gio-hang/them', ['san_pham_id' => $sanpham->id, 'so_luong' => 2]);
        $this->actingAs($user)->postJson('/gio-hang/them', ['san_pham_id' => $sanpham->id, 'so_luong' => 3]);

        $this->assertDatabaseHas('chitiet_giohang', [
            'sanpham_id' => $sanpham->id,
            'so_luong'   => 5,
        ]);
    }

    public function test_them_san_pham_khong_ton_tai_tra_ve_loi_422(): void
    {
        $user = $this->taoUser();

        $this->actingAs($user)
             ->postJson('/gio-hang/them', ['san_pham_id' => 9999, 'so_luong' => 1])
             ->assertStatus(422);
    }

    // -------------------------------------------------------
    //  Đặt hàng — thành công
    // -------------------------------------------------------

    public function test_dat_hang_thanh_cong_tao_don_hang_va_tru_ton_kho(): void
    {
        $user    = $this->taoUser();
        $sanpham = $this->taoSanpham(soLuong: 10);

        $this->themVaoGioHangUser($user, $sanpham, soLuong: 3);

        $this->actingAs($user)
             ->post('/thanh-toan', $this->duLieuDatHang())
             ->assertRedirect();

        // Đơn hàng được tạo đúng thông tin
        $this->assertDatabaseHas('donhang', [
            'user_id'               => $user->id,
            'ten_nguoi_nhan'        => 'Nguyễn Văn A',
            'phuong_thuc_thanhtoan' => 'cod',
            'trang_thai'            => Donhang::TRANG_THAI_MOI,
            'trang_thai_thanhtoan'  => 'chua_thanh_toan',
        ]);

        // Tồn kho bị trừ đúng 3
        $this->assertDatabaseHas('sanpham', [
            'id'       => $sanpham->id,
            'so_luong' => 7,
        ]);

        // Giỏ hàng được xóa sau khi đặt
        $this->assertDatabaseEmpty('chitiet_giohang');
    }

    public function test_dat_hang_voi_ma_giam_gia_tinh_dung_so_tien_giam(): void
    {
        $user    = $this->taoUser();
        $sanpham = $this->taoSanpham(soLuong: 10);

        $ma = Magiamgia::create([
            'ma_code'            => 'GIAM50K',
            'kieu_giam'          => 'co_dinh',
            'gia_tri'            => 50000,
            'don_hang_toi_thieu' => 0,
            'giam_toi_da'        => null,
            'so_luong'           => null,
            'da_su_dung'         => 0,
            'kich_hoat'          => true,
            'bat_dau'            => null,
            'ket_thuc'           => null,
        ]);

        $this->themVaoGioHangUser($user, $sanpham, soLuong: 1); // 150k

        $this->actingAs($user)
             ->post('/thanh-toan', $this->duLieuDatHang(['magiamgia_id' => $ma->id]))
             ->assertRedirect();

        $this->assertDatabaseHas('donhang', [
            'user_id'        => $user->id,
            'so_tien_giam'   => 50000,
            'tong_thanh_toan' => 100000,
        ]);

        // Lượt dùng mã phải tăng lên 1
        $this->assertDatabaseHas('magiamgia', [
            'id'         => $ma->id,
            'da_su_dung' => 1,
        ]);
    }

    // -------------------------------------------------------
    //  Đặt hàng — thất bại
    // -------------------------------------------------------

    public function test_dat_hang_that_bai_khi_gio_hang_trong(): void
    {
        $user = $this->taoUser();

        $this->actingAs($user)
             ->post('/thanh-toan', $this->duLieuDatHang())
             ->assertRedirect(route('gio-hang'));
    }

    public function test_dat_hang_that_bai_khi_vuot_ton_kho(): void
    {
        $user    = $this->taoUser();
        $sanpham = $this->taoSanpham(soLuong: 2); // chỉ còn 2

        $this->themVaoGioHangUser($user, $sanpham, soLuong: 5); // đặt 5

        $this->actingAs($user)
             ->post('/thanh-toan', $this->duLieuDatHang())
             ->assertRedirect(route('gio-hang'))
             ->assertSessionHas('error');
    }

    public function test_dat_hang_that_bai_khi_thieu_truong_bat_buoc(): void
    {
        $user    = $this->taoUser();
        $sanpham = $this->taoSanpham();
        $this->themVaoGioHangUser($user, $sanpham);

        $this->actingAs($user)
             ->post('/thanh-toan', $this->duLieuDatHang(['ten_nguoi_nhan' => '']))
             ->assertSessionHasErrors('ten_nguoi_nhan');
    }

    public function test_dat_hang_that_bai_khi_chua_dang_nhap(): void
    {
        // Guest không có giỏ hàng hợp lệ → redirect về giỏ hàng
        $this->post('/thanh-toan', $this->duLieuDatHang())
             ->assertRedirect(route('gio-hang'));
    }

    // -------------------------------------------------------
    //  Phân quyền admin
    // -------------------------------------------------------

    public function test_khach_khong_the_vao_trang_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_user_thuong_khong_the_vao_trang_admin(): void
    {
        $user = $this->taoUser(); // quyen_han = USER (0)

        $this->actingAs($user)
             ->get('/admin')
             ->assertStatus(403);
    }

    public function test_admin_vao_duoc_trang_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
             ->get('/admin')
             ->assertStatus(200);
    }

    public function test_tai_khoan_bi_khoa_khong_vao_duoc_admin(): void
    {
        $admin = User::factory()->admin()->locked()->create();

        $this->actingAs($admin)
             ->get('/admin')
             ->assertStatus(403);
    }

    public function test_staff_vao_duoc_admin_nhung_khong_vao_duoc_quan_ly_nguoi_dung(): void
    {
        $staff = User::factory()->staff()->create();

        $this->actingAs($staff)->get('/admin')->assertStatus(200);
        $this->actingAs($staff)->get('/admin/nguoidung')->assertStatus(403);
    }

    public function test_ketoan_vao_duoc_bao_cao_nhung_khong_vao_duoc_quan_ly_nguoi_dung(): void
    {
        $ketoan = User::factory()->ketoan()->create();

        $this->actingAs($ketoan)->get('/admin')->assertStatus(200);
        $this->actingAs($ketoan)->get('/admin/baocao')->assertStatus(200);
        $this->actingAs($ketoan)->get('/admin/nguoidung')->assertStatus(403);
    }

    // -------------------------------------------------------
    //  Bảo mật: payosSuccess KHÔNG được cập nhật DB
    //
    //  Sau khi sửa ThanhToanController.payosSuccess() chỉ redirect,
    //  test này đảm bảo hành vi đúng: trạng thái thanh toán chỉ
    //  thay đổi qua webhook (có xác thực chữ ký), không phải URL.
    // -------------------------------------------------------

    public function test_payos_success_url_khong_cap_nhat_trang_thai_thanh_toan(): void
    {
        $user    = $this->taoUser();
        $sanpham = $this->taoSanpham();
        $donhang = $this->taoDonHang($user, $sanpham);

        // Kẻ tấn công gọi return URL với donhang_id bất kỳ
        $this->actingAs($user)
             ->get("/payos/success?donhang_id={$donhang->id}")
             ->assertRedirect();

        // Trạng thái thanh toán KHÔNG được thay đổi
        $this->assertDatabaseHas('donhang', [
            'id'                   => $donhang->id,
            'trang_thai_thanhtoan' => 'chua_thanh_toan',
        ]);
    }

    public function test_payos_success_url_redirect_ve_trang_xac_nhan(): void
    {
        $user    = $this->taoUser();
        $sanpham = $this->taoSanpham();
        $donhang = $this->taoDonHang($user, $sanpham);

        $this->actingAs($user)
             ->get("/payos/success?donhang_id={$donhang->id}")
             ->assertRedirect(route('xac-nhan-don-hang', $donhang->id));
    }
}
