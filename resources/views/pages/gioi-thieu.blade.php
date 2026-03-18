@extends('layouts.app')
@section('title', 'Giới Thiệu — ShopDecor')

@section('extra-css')
<style>
    .hero-about {
        background: linear-gradient(135deg, #1a5276, #0e2f4e);
        color: #fff;
        padding: 60px 0;
    }

    .stat-box {
        text-align: center;
        padding: 24px 16px;
        border: 1px solid #eee;
        background: #fff;
    }
    .stat-box:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        transform: translateY(-2px);
        transition: all 0.2s;
    }
    .stat-number {
        font-size: 2.2rem;
        font-weight: 700;
        color: #1a5276;
        line-height: 1;
    }

    .value-card {
        padding: 24px;
        border-left: 4px solid #1a5276;
        background: #f8fbfe;
        height: 100%;
    }
    .value-icon {
        width: 48px;
        height: 48px;
        background: #1a5276;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 14px;
    }

    .divider {
        width: 60px;
        height: 3px;
        background: #e74c3c;
        margin: 10px auto 20px;
    }
</style>
@endsection

@section('content')

{{-- Hero --}}
<div class="hero-about">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="small text-uppercase mb-2" style="color:#afd6f5;letter-spacing:2px">
                    <i class="fas fa-store me-2"></i>Về chúng tôi
                </div>
                <h2 class="fw-bold mb-3" style="font-size:2rem">
                    ShopDecor — Nơi Nâng Tầm<br>Không Gian Làm Việc
                </h2>
                <p class="mb-4" style="color:#cde4f5;line-height:1.8">
                    Chúng tôi tin rằng một không gian làm việc đẹp không chỉ là nơi để làm việc —
                    đó còn là nguồn cảm hứng, là nơi phản ánh cá tính và là động lực để bạn
                    cống hiến tốt hơn mỗi ngày.
                </p>
                <a href="{{ url('/san-pham') }}" class="btn fw-bold px-4 py-2"
                   style="background:#e74c3c;color:#fff;border:none">
                    <i class="fas fa-shopping-bag me-2"></i>Khám phá sản phẩm
                </a>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:rgba(255,255,255,0.1)">
                            <div style="font-size:1.8rem;font-weight:700">5+</div>
                            <div style="font-size:0.8rem;color:#afd6f5">Năm hoạt động</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:rgba(255,255,255,0.1)">
                            <div style="font-size:1.8rem;font-weight:700">2K+</div>
                            <div style="font-size:0.8rem;color:#afd6f5">Khách hàng</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:rgba(255,255,255,0.1)">
                            <div style="font-size:1.8rem;font-weight:700">500+</div>
                            <div style="font-size:0.8rem;color:#afd6f5">Sản phẩm</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:rgba(255,255,255,0.1)">
                            <div style="font-size:1.8rem;font-weight:700">4.8★</div>
                            <div style="font-size:0.8rem;color:#afd6f5">Đánh giá TB</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Breadcrumb --}}
<div style="background:#eaf4fb;border-bottom:1px solid #d0e8f5;padding:8px 0;font-size:0.82rem;">
    <div class="container">
        <a href="{{ url('/') }}" class="text-decoration-none" style="color:#1a5276">
            <i class="fas fa-home me-1"></i>Trang chủ
        </a>
        <span class="mx-2 text-muted">›</span>
        <span class="text-muted">Giới thiệu</span>
    </div>
</div>

<div class="container py-5">

    {{-- Câu chuyện --}}
    <div class="row g-5 align-items-center mb-5">
        <div class="col-lg-6">
            <div class="small text-uppercase fw-bold mb-2" style="color:#e74c3c;letter-spacing:2px">
                Câu chuyện của chúng tôi
            </div>
            <h3 class="fw-bold mb-2" style="color:#1a5276">Từ Đam Mê Đến Thương Hiệu</h3>
            <div class="divider" style="margin:0 0 20px"></div>
            <p class="text-muted mb-3" style="line-height:1.9">
                ShopDecor ra đời từ niềm đam mê với những không gian làm việc đẹp và có ý nghĩa.
                Chúng tôi bắt đầu với một cửa hàng nhỏ, mang đến những sản phẩm trang trí tinh tế
                được tuyển chọn kỹ lưỡng từ các nghệ nhân và nhà sản xuất uy tín.
            </p>
            <p class="text-muted mb-4" style="line-height:1.9">
                Qua nhiều năm, chúng tôi đã trở thành một trong những địa chỉ tin cậy chuyên cung
                cấp phụ kiện bàn làm việc và đồ decor văn phòng tại Việt Nam. Mỗi sản phẩm đều được
                chọn lọc với tiêu chí: <strong>đẹp, chất lượng và có ý nghĩa.</strong>
            </p>
            <div class="d-flex gap-4">
                <div>
                    <div class="fw-bold" style="font-size:1.5rem;color:#1a5276">2019</div>
                    <div class="text-muted small">Năm thành lập</div>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:1.5rem;color:#e74c3c">An Giang</div>
                    <div class="text-muted small">Trụ sở chính</div>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:1.5rem;color:#1a5276">63</div>
                    <div class="text-muted small">Tỉnh thành giao hàng</div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row g-3">
                <div class="col-6">
                    <div class="rounded p-4 text-center" style="background:#eaf4fb">
                        <i class="fas fa-gem fa-2x mb-3" style="color:#1a5276"></i>
                        <div class="fw-bold small mb-1">Sản phẩm chọn lọc</div>
                        <div class="text-muted" style="font-size:0.78rem">Kiểm duyệt kỹ trước khi bán</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="rounded p-4 text-center" style="background:#fdf6e3">
                        <i class="fas fa-shipping-fast fa-2x mb-3" style="color:#e67e22"></i>
                        <div class="fw-bold small mb-1">Giao hàng toàn quốc</div>
                        <div class="text-muted" style="font-size:0.78rem">Nhanh chóng, đóng gói cẩn thận</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="rounded p-4 text-center" style="background:#e8f8f0">
                        <i class="fas fa-headset fa-2x mb-3" style="color:#27ae60"></i>
                        <div class="fw-bold small mb-1">Hỗ trợ tận tâm</div>
                        <div class="text-muted" style="font-size:0.78rem">Phản hồi trong 2 giờ làm việc</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="rounded p-4 text-center" style="background:#fdf2f2">
                        <i class="fas fa-undo fa-2x mb-3" style="color:#e74c3c"></i>
                        <div class="fw-bold small mb-1">Đổi trả dễ dàng</div>
                        <div class="text-muted" style="font-size:0.78rem">7 ngày nếu lỗi sản xuất</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Con số --}}
    <div class="p-4 mb-5 rounded" style="background:#f8f9fa">
        <div class="text-center mb-4">
            <h4 class="fw-bold" style="color:#1a5276">Con Số Nói Lên Tất Cả</h4>
            <div class="divider"></div>
        </div>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-number">5+</div>
                    <div class="text-danger fw-bold small mb-1">Năm</div>
                    <div class="text-muted small">Kinh nghiệm hoạt động</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-number">2,000+</div>
                    <div class="text-danger fw-bold small mb-1">Khách hàng</div>
                    <div class="text-muted small">Tin tưởng & hài lòng</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-number">500+</div>
                    <div class="text-danger fw-bold small mb-1">Sản phẩm</div>
                    <div class="text-muted small">Đa dạng danh mục</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-number">98%</div>
                    <div class="text-danger fw-bold small mb-1">Hài lòng</div>
                    <div class="text-muted small">Tỷ lệ đánh giá tích cực</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Giá trị cốt lõi --}}
    <div class="mb-5">
        <div class="text-center mb-4">
            <h4 class="fw-bold" style="color:#1a5276">Giá Trị Cốt Lõi</h4>
            <div class="divider"></div>
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h6 class="fw-bold mb-2">Chất Lượng Không Thỏa Hiệp</h6>
                    <p class="text-muted small mb-0" style="line-height:1.7">
                        Mỗi sản phẩm đều được kiểm tra kỹ trước khi đến tay khách hàng.
                        Chúng tôi chỉ bán những gì chúng tôi tự tin sử dụng.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card" style="border-left-color:#e74c3c">
                    <div class="value-icon" style="background:#e74c3c">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h6 class="fw-bold mb-2">Tận Tâm Với Khách Hàng</h6>
                    <p class="text-muted small mb-0" style="line-height:1.7">
                        Chúng tôi lắng nghe, tư vấn và đồng hành cùng khách hàng từ khi
                        chọn sản phẩm đến khi nhận hàng và sau đó.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card" style="border-left-color:#27ae60">
                    <div class="value-icon" style="background:#27ae60">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h6 class="fw-bold mb-2">Thiết Kế Có Ý Nghĩa</h6>
                    <p class="text-muted small mb-0" style="line-height:1.7">
                        Mỗi món đồ decor không chỉ đẹp về hình thức mà còn truyền tải
                        một câu chuyện, một cảm xúc riêng.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="text-center py-5 rounded" style="background:linear-gradient(135deg,#1a5276,#154360);color:#fff">
        <h4 class="fw-bold mb-2">Sẵn Sàng Nâng Cấp Không Gian Làm Việc?</h4>
        <p class="mb-4" style="color:#cde4f5">
            Khám phá hơn 500 sản phẩm decor được tuyển chọn kỹ lưỡng
        </p>
        <a href="{{ url('/san-pham') }}" class="btn fw-bold px-5 py-2"
           style="background:#e74c3c;color:#fff;border:none">
            <i class="fas fa-shopping-bag me-2"></i>Mua sắm ngay
        </a>
    </div>

</div>
@endsection