<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de')->nullable();           // tiêu đề hiển thị trên slide
            $table->string('mo_ta')->nullable();             // mô tả nhỏ dưới tiêu đề
            $table->string('duong_dan_anh');                 // đường dẫn file ảnh
            $table->string('url_lien_ket')->nullable();      // link khi click vào banner
            $table->unsignedTinyInteger('thu_tu')->default(0); // thứ tự hiển thị
            $table->boolean('kich_hoat')->default(true);     // bật/tắt
            $table->date('ngay_bat_dau')->nullable();        // ngày bắt đầu hiện
            $table->date('ngay_ket_thuc')->nullable();       // ngày kết thúc hiện
            $table->timestamps();

            $table->index('kich_hoat');
            $table->index('thu_tu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
