<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tintuc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();             // tác giả — xoá user không mất bài
            $table->string('tieu_de');
            $table->string('slug')->unique();
            $table->string('mo_ta_ngan')->nullable();   // tóm tắt hiển thị ngoài danh sách
            $table->longText('noi_dung')->nullable();   // nội dung HTML đầy đủ
            $table->string('anh_dai_dien')->nullable(); // ảnh thumbnail chính
            $table->unsignedInteger('luot_xem')->default(0);
            $table->boolean('kich_hoat')->default(true); // ẩn/hiện
            $table->timestamp('ngay_dang')->nullable();  // null = chưa publish
            $table->softDeletes();
            $table->timestamps();
            $table->index('kich_hoat');
            $table->index('ngay_dang');
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('tintuc');
    }
};