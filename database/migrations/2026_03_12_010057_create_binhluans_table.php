<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('binhluan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sanpham_id')->constrained('sanpham')->onDelete('cascade');
            $table->text('noi_dung');
            $table->unsignedTinyInteger('sao_danh_gia')->default(5);
            $table->timestamp('ngay_dang')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'sanpham_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('binhluan');
    }
};