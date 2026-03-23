@extends('layouts.app')

@section('title', 'Liên Hệ')

@section('extra-css')
<style>
/* ===== CHỈ GIỮ NHỮNG GÌ BOOTSTRAP KHÔNG CÓ ===== */

/* Icon tròn đỏ */
.info-icon{
width:38px;
height:38px;
border-radius:50%;
background:#e74c3c;
color:#fff;
display:flex;
align-items:center;
justify-content:center;
flex-shrink:0
}

/* Focus input theo màu brand */
.form-control:focus,
.form-select:focus{
border-color:#1a5276;
box-shadow:none
}

/* Màu header section */
.section-header{
background:#1a5276;
color:#fff;
padding:10px 14px;
font-weight:700;
font-size:.92rem
}
.section-header-red{background:#e74c3c}

/* Map */
.map-frame{
width:100%;
height:300px;
border:none;
display:block
}

/* Nút social */
.social-fb{background:#1877f2;color:#fff}
.social-yt{background:#ff0000;color:#fff}
.social-zl{background:#0068ff;color:#fff}
.social-fb:hover,.social-yt:hover,.social-zl:hover{opacity:.85;color:#fff}
</style>
@endsection

@section('content')

{{-- BREADCRUMB --}}
<div class="bg-light border-bottom py-2">
    <div class="container">
        <a href="{{ url('/') }}" class="text-decoration-none small" style="color:#1a5276">
            <i class="fas fa-home me-1"></i>Trang chủ
        </a>
        <span class="text-muted small mx-2">/</span>
        <span class="text-muted small">Liên hệ</span>
    </div>
</div>

{{-- PAGE TITLE --}}
<div class="text-white text-center py-4" style="background:#1a5276">
    <h1 class="fs-4 fw-bold mb-1">
        <i class="fas fa-envelope-open-text me-2"></i>LIÊN HỆ VỚI CHÚNG TÔI
    </h1>
    <p class="mb-0 small" style="color:#b3cde0">Chúng tôi luôn sẵn sàng hỗ trợ & tư vấn cho bạn 24/7</p>
</div>

{{-- MAIN --}}
<div class="py-4">
    <div class="container">
        <div class="row g-4">

            {{-- CỘT TRÁI --}}
            <div class="col-lg-5">

                {{-- Thông tin liên hệ --}}
                <div class="border mb-3">
                    <div class="section-header">
                        <i class="fas fa-info-circle me-2"></i>THÔNG TIN LIÊN HỆ
                    </div>
                    <div class="p-3">

                        @foreach([
                            ['fa-map-marker-alt', 'SHOWROOM HÀ NỘI',      'Số 24, ngõ 129 đường Nguyễn Xiển,<br>Hạ Đình, Thanh Xuân, Hà Nội'],
                            ['fa-map-marker-alt', 'SHOWROOM HỒ CHÍ MINH', 'Số 268/12 đường Nguyễn Thái Bình,<br>Phường 12, Quận Tân Bình, TP.HCM'],
                            ['fa-clock',          'GIỜ LÀM VIỆC',         'Thứ 2 – Chủ Nhật: 8:00 – 21:00'],
                            ['fa-file-invoice',   'MÃ SỐ THUẾ',           '0108193879'],
                            ['fa-university',     'TÀI KHOẢN NGÂN HÀNG',  '148913568 – VPBank <small class="text-muted">(Chi nhánh Hà Nội)</small>'],
                        ] as [$icon, $label, $value])
                        <div class="d-flex gap-3 align-items-start py-2 border-bottom">
                            <div class="info-icon"><i class="fas {{ $icon }}"></i></div>
                            <div>
                                <div class="text-muted" style="font-size:.72rem">{{ $label }}</div>
                                <div class="fw-semibold small">{!! $value !!}</div>
                            </div>
                        </div>
                        @endforeach

                        {{-- Hotline --}}
                        <div class="d-flex gap-3 align-items-start py-2 border-bottom">
                            <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                            <div>
                                <div class="text-muted" style="font-size:.72rem">HOTLINE 24/7</div>
                                <div class="fw-semibold small">
                                    <a href="tel:0969534568" class="text-decoration-none" style="color:#1a5276">0969 534 568</a>
                                    &nbsp;—&nbsp;
                                    <a href="tel:0898434568" class="text-decoration-none" style="color:#1a5276">0898 434 568</a>
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="d-flex gap-3 align-items-start py-2 border-bottom">
                            <div class="info-icon"><i class="fas fa-envelope"></i></div>
                            <div>
                                <div class="text-muted" style="font-size:.72rem">EMAIL</div>
                                <div class="fw-semibold small">
                                    <a href="mailto:Decopro.vn@gmail.com" class="text-decoration-none" style="color:#1a5276">
                                        Decopro.vn@gmail.com
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Mạng xã hội --}}
                        <div class="pt-3">
                            <div class="text-muted mb-2" style="font-size:.72rem">THEO DÕI CHÚNG TÔI</div>
                            <a href="https://www.facebook.com/ShopDecopro/" target="_blank"
                               class="btn btn-sm social-fb me-1 mb-1">
                                <i class="fab fa-facebook-f me-1"></i>Facebook
                            </a>
                            <a href="https://www.youtube.com/channel/UC9HvicbYtXr2JcKlIJ-NOEQ" target="_blank"
                               class="btn btn-sm social-yt me-1 mb-1">
                                <i class="fab fa-youtube me-1"></i>YouTube
                            </a>
                            <a href="https://zalo.me/0969534568" target="_blank"
                               class="btn btn-sm social-zl mb-1">
                                <i class="fas fa-comment-dots me-1"></i>Zalo
                            </a>
                        </div>

                    </div>
                </div>

                {{-- Cam kết --}}
                <div class="p-3 border" style="background:#fff8e1;border-color:#f0c040!important">
                    <div class="fw-bold mb-2 small" style="color:#1a5276">
                        <i class="fas fa-shield-alt me-1"></i>CAM KẾT CỦA CHÚNG TÔI
                    </div>
                    @foreach([
                        ['fa-check-circle',  'Kiểm tra hàng trước khi thanh toán'],
                        ['fa-exchange-alt',  'Đổi trả miễn phí trong vòng 30 ngày'],
                        ['fa-truck',         'Giao hàng toàn quốc, miễn phí vận chuyển'],
                        ['fa-medal',         'Bảo hành sản phẩm trọn đời'],
                        ['fa-gift',          'Hỗ trợ in khắc & đóng hộp quà miễn phí'],
                    ] as [$icon, $text])
                    <div class="d-flex align-items-center gap-2 small text-secondary mb-1">
                        <i class="fas {{ $icon }} text-danger" style="width:14px"></i> {{ $text }}
                    </div>
                    @endforeach
                </div>

            </div>

            {{-- CỘT PHẢI --}}
            <div class="col-lg-7">

                {{-- Form --}}
                <div class="border mb-4">
                    <div class="section-header section-header-red">
                        <i class="fas fa-paper-plane me-2"></i>GỬI YÊU CẦU TƯ VẤN
                    </div>
                    <div class="p-3">

                        @if(session('success'))
                        <div class="alert alert-success py-2 small">
                            <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
                        </div>
                        @endif
                        @if(session('error'))
                        <div class="alert alert-danger py-2 small">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ session('error') }}
                        </div>
                        @endif

                        <form action="{{ url('/lien-he/gui') }}" method="POST">
                            @csrf
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="ho_ten" class="form-control form-control-sm"
                                           placeholder="Nhập họ và tên..." value="{{ old('ho_ten') }}" required>
                                    @error('ho_ten')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="tel" name="dien_thoai" class="form-control form-control-sm"
                                           placeholder="0xxx xxx xxx" value="{{ old('dien_thoai') }}" required>
                                    @error('dien_thoai')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control form-control-sm"
                                       placeholder="email@example.com" value="{{ old('email') }}">
                                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Chủ đề</label>
                                <select name="chu_de" class="form-select form-select-sm">
                                    <option value="">-- Chọn chủ đề --</option>
                                    <option value="tu_van_san_pham" {{ old('chu_de')=='tu_van_san_pham'?'selected':'' }}>Tư vấn sản phẩm</option>
                                    <option value="dat_hang"        {{ old('chu_de')=='dat_hang'?'selected':'' }}>Đặt hàng / Báo giá</option>
                                    <option value="doi_tra"         {{ old('chu_de')=='doi_tra'?'selected':'' }}>Đổi trả & Bảo hành</option>
                                    <option value="hop_tac"         {{ old('chu_de')=='hop_tac'?'selected':'' }}>Hợp tác kinh doanh</option>
                                    <option value="khac"            {{ old('chu_de')=='khac'?'selected':'' }}>Khác</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Nội dung <span class="text-danger">*</span></label>
                                <textarea name="noi_dung" class="form-control form-control-sm"
                                          rows="5" placeholder="Mô tả yêu cầu hoặc câu hỏi của bạn..."
                                          required>{{ old('noi_dung') }}</textarea>
                                @error('noi_dung')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <button type="submit" id="btnSubmit"
                                    class="btn btn-danger fw-bold w-100">
                                <i class="fas fa-paper-plane me-2"></i>GỬI YÊU CẦU NGAY
                            </button>
                        </form>

                    </div>
                </div>

                {{-- Bản đồ --}}
                <div class="border">
                    <div class="section-header">
                        <i class="fas fa-map-marked-alt me-2"></i>BẢN ĐỒ SHOWROOM HÀ NỘI
                    </div>
                    <iframe class="map-frame"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.9643898454!2d105.8264!3d20.9908!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ac9e9b8d0001%3A0x1!2zU-G7kSAyNCBuZ-C3oSAxMjkgxJHGsOG7nW5nIE5ndXnhu4VuIFhp4buDbg!5e0!3m2!1svi!2svn!4v1700000000000"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- HOTLINE BANNER --}}
<div class="py-3 mt-2" style="background:#1a5276">
    <div class="container">
        <div class="row align-items-center text-center text-md-start">
            <div class="col-md-8 text-white">
                <h5 class="fw-bold mb-1">
                    <i class="fas fa-headset me-2 text-warning"></i>Cần tư vấn nhanh? Gọi ngay hotline 24/7!
                </h5>
                <p class="mb-0 small" style="color:#b3cde0">
                    Đội ngũ chuyên viên luôn sẵn sàng hỗ trợ bạn chọn đúng sản phẩm — hoàn toàn miễn phí.
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="tel:0969534568" class="btn btn-danger fw-bold px-4 py-2">
                    <i class="fas fa-phone-alt me-2"></i>0969 534 568
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script>
document.querySelector('form').addEventListener('submit', function () {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang gửi...';
});
</script>
@endsection