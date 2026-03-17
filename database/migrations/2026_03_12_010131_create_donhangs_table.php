<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donhang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('magiamgia_id')->nullable()->constrained('magiamgia')->nullOnDelete();

            $table->string('ten_nguoi_nhan');
            $table->string('so_dien_thoai', 15);
            $table->string('email')->nullable();
            $table->string('dia_chi_chi_tiet');
            $table->string('phuong_xa');
            $table->string('quan_huyen');
            $table->string('tinh_thanh');

            $table->enum('phuong_thuc_thanhtoan', ['cod', 'chuyen_khoan', 'momo'])->default('cod');
            $table->enum('trang_thai_thanhtoan', ['chua_thanh_toan', 'da_thanh_toan', 'hoan_tien'])->default('chua_thanh_toan');
            $table->string('ma_giao_dich')->nullable();

            $table->string('ma_van_don')->nullable();
            $table->decimal('phi_ship', 10, 2)->default(0);
            $table->enum('trang_thai_van_chuyen', ['cho_lay_hang', 'dang_van_chuyen', 'da_giao', 'that_bai', 'hoan_hang'])->default('cho_lay_hang');

            $table->decimal('tong_tien_hang', 15, 2);
            $table->decimal('so_tien_giam', 15, 2)->default(0);
            $table->decimal('tong_thanh_toan', 15, 2);

            $table->tinyInteger('trang_thai')->default(0)->comment('0=mới, 1=xử lý, 2=hoàn tất, 3=hủy');
            $table->timestamp('ngay_dat')->useCurrent();
            $table->timestamp('ngay_duyet')->nullable();
            $table->timestamp('ngay_giao')->nullable();
            $table->text('ghi_chu_khach')->nullable();
            $table->text('ghi_chu_admin')->nullable();
            $table->timestamps();

            $table->index('trang_thai');
            $table->index('trang_thai_thanhtoan');
            $table->index('trang_thai_van_chuyen');
            $table->index(['user_id', 'trang_thai']);
            $table->index('ngay_dat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donhang');
    }
};