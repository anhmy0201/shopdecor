<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: Arial, sans-serif; background:#f0f4f8; padding:24px 12px; }
.wrap { max-width:560px; margin:0 auto; background:#fff; border-radius:8px; overflow:hidden; border:1px solid #dde3ea; }
.header { background:linear-gradient(135deg,#667eea,#764ba2); padding:32px 28px; text-align:center; }
.header h1 { color:#fff; font-size:1.3rem; font-weight:700; }
.header p { color:rgba(255,255,255,.8); font-size:.85rem; margin-top:6px; }
.body { padding:32px 28px; }
.body p { font-size:.9rem; color:#444; line-height:1.7; margin-bottom:16px; }
.btn-wrap { text-align:center; margin:28px 0; }
.btn { display:inline-block; background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; font-weight:700; font-size:.95rem; padding:13px 36px; border-radius:8px; text-decoration:none; }
.note { background:#fff8e1; border-left:3px solid #f0a500; padding:12px 16px; border-radius:4px; font-size:.82rem; color:#555; line-height:1.6; margin-top:8px; }
.footer { background:#f8f9fa; padding:16px 28px; text-align:center; }
.footer p { font-size:.75rem; color:#999; line-height:1.7; }
.url-box { background:#f4f7fb; border:1px solid #dde; border-radius:4px; padding:10px 14px; font-size:.78rem; color:#667eea; word-break:break-all; margin-top:12px; }
</style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <h1>🔑 Đặt Lại Mật Khẩu</h1>
        <p>ShopDecor - Yêu cầu đặt lại mật khẩu</p>
    </div>
    <div class="body">
        <p>Xin chào <strong>{{ $user->ho_ten ?? $user->ten_dang_nhap }}</strong>,</p>
        <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Nhấn vào nút bên dưới để tiến hành:</p>

        <div class="btn-wrap">
            <a href="{{ $url }}" class="btn">Đặt Lại Mật Khẩu</a>
        </div>

        <div class="note">
            <strong>⏰ Lưu ý:</strong> Link này chỉ có hiệu lực trong <strong>60 phút</strong>.
            Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này — tài khoản của bạn vẫn an toàn.
        </div>

        <p style="margin-top:20px;">Nếu nút không hoạt động, copy link sau vào trình duyệt:</p>
        <div class="url-box">{{ $url }}</div>
    </div>
    <div class="footer">
        <p>Email này được gửi tự động từ ShopDecor.<br>Vui lòng không reply trực tiếp.</p>
    </div>
</div>
</body>
</html>
