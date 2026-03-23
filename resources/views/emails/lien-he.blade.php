<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:Arial,sans-serif;background:#f5f5f5;padding:20px}
.wrap{max-width:580px;margin:0 auto;background:#fff;border:1px solid #ddd}
.header{background:#1a5276;color:#fff;padding:16px 20px}
.header h2{font-size:1rem;font-weight:700;margin:0}
.header p{font-size:.8rem;color:#b3cde0;margin:4px 0 0}
.body{padding:20px}
.row{display:flex;border-bottom:1px solid #eee;padding:10px 0}
.row:last-child{border-bottom:none}
.label{
width:160px;
font-size:.78rem;
font-weight:700;
color:#888;
text-transform:uppercase;
flex-shrink:0;
padding-top:2px
}
.value{
font-size:.88rem;
color:#222;
line-height:1.5
}
.message-box{
background:#f9f9f9;
border-left:3px solid #e74c3c;
padding:10px 14px;
font-size:.88rem;
line-height:1.7;
white-space:pre-wrap
}
.footer{
background:#f5f5f5;
border-top:1px solid #ddd;
padding:12px 20px;
font-size:.75rem;
color:#999;
text-align:center
}
</style>
</head>
<body>
<div class="wrap">

    <div class="header">
        <h2>📩 Yêu cầu tư vấn mới từ website</h2>
        <p>Nhận lúc {{ now()->format('H:i — d/m/Y') }}</p>
    </div>

    <div class="body">
        <div class="row">
            <div class="label">Họ và tên</div>
            <div class="value">{{ $data['ho_ten'] }}</div>
        </div>
        <div class="row">
            <div class="label">Điện thoại</div>
            <div class="value">{{ $data['dien_thoai'] }}</div>
        </div>
        <div class="row">
            <div class="label">Email</div>
            <div class="value">{{ $data['email'] ?: '—' }}</div>
        </div>
        <div class="row">
            <div class="label">Chủ đề</div>
            <div class="value">{{ $data['chu_de'] ?: '—' }}</div>
        </div>
        <div class="row" style="flex-direction:column;gap:8px">
            <div class="label">Nội dung</div>
            <div class="message-box">{{ $data['noi_dung'] }}</div>
        </div>
    </div>

    <div class="footer">
        Email tự động từ website — Vui lòng không reply trực tiếp email này.
    </div>

</div>
</body>
</html>
