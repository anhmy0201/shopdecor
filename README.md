# ShopDecor — Website Bán Đồ Trang Trí Nội Thất

Đồ án tốt nghiệp — Website thương mại điện tử bán đồ trang trí nội thất, xây dựng bằng **Laravel 12**.

---

## Tính năng nổi bật

### Phía khách hàng
- Xem sản phẩm theo danh mục, tìm kiếm, lọc
- Sản phẩm có **biến thể** (màu sắc, kích thước) với giá riêng
- Giỏ hàng cho cả **khách vãng lai** (session) và người đã đăng nhập — tự động merge khi login
- Đăng nhập bằng **Google OAuth**
- Thanh toán **COD** hoặc **chuyển khoản qua PayOS** (tích hợp thật)
- Áp **mã giảm giá** (cố định / phần trăm, có ngày hết hạn, giới hạn số lượng)
- Theo dõi đơn hàng, huỷ đơn, **tra cứu đơn hàng không cần đăng nhập**
- Đánh giá sản phẩm sau khi mua
- Quản lý sổ địa chỉ giao hàng

### Phía quản trị (Admin)
- **Phân quyền 4 cấp**: Khách hàng → Nhân viên → Kế toán → Giám đốc
- Dashboard thống kê: doanh thu, đơn hàng, sản phẩm bán chạy
- Quản lý sản phẩm, danh mục, biến thể, hình ảnh
- Quản lý đơn hàng, cập nhật trạng thái
- Quản lý mã giảm giá
- Báo cáo doanh thu theo tháng, top sản phẩm, top khách hàng
- **Import / Export Excel** cho sản phẩm, đơn hàng, mã giảm giá
- **Realtime notification** khi có đơn hàng mới (Laravel Reverb)

---

## Tech stack

| Thành phần | Công nghệ |
|---|---|
| Backend | Laravel 12, PHP 8.2 |
| Frontend | Blade, Bootstrap, Vite |
| Database | MySQL |
| Cache / Queue | Redis (Predis) |
| Realtime | Laravel Reverb (WebSocket) |
| Thanh toán | PayOS SDK |
| Auth bên thứ 3 | Laravel Socialite (Google) |
| Import/Export | Maatwebsite Excel |

---

## Cài đặt & chạy local

### Yêu cầu
- PHP >= 8.2, Composer
- Node.js >= 18
- MySQL, Redis

### Các bước

```bash
git clone https://github.com/anhmy0201/shopdecor.git
cd shopdecor
composer install
npm install
cp .env.example .env
php artisan key:generate
# Cấu hình .env (xem bên dưới)
php artisan migrate --seed
npm run build
composer run dev
```

### Cấu hình `.env` quan trọng

```env
DB_DATABASE=shopdecor
DB_USERNAME=root
DB_PASSWORD=

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

PAYOS_CLIENT_ID=
PAYOS_API_KEY=
PAYOS_CHECKSUM_KEY=
```

---

## Tài khoản demo (sau khi seed)

| Vai trò | Email | Mật khẩu |
|---|---|---|
| Giám đốc / Admin | admin@shopdecor.vn | password |
| Kế toán | ketoan@shopdecor.vn | password |
| Nhân viên | nhanvien@shopdecor.vn | password |
| Khách hàng | khach@shopdecor.vn | password |

---

## Chạy test

```bash
php artisan test
```

---

## Một số quyết định kỹ thuật đáng chú ý

**Race condition tồn kho:** Khi đặt hàng, dùng `DB::transaction()` kết hợp `lockForUpdate()` để đảm bảo hai người dùng đặt hàng cùng lúc không vượt quá tồn kho thực tế.

**Bảo mật thanh toán:** Trạng thái thanh toán chỉ được cập nhật qua PayOS Webhook (xác thực chữ ký HMAC), không qua return URL — tránh giả mạo URL để đổi trạng thái đơn hàng.

**Giỏ hàng guest:** Khách vãng lai có giỏ hàng lưu theo session. Khi đăng nhập, giỏ hàng được merge tự động, không mất dữ liệu.
