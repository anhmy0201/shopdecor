<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chitiet_donhang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donhang_id')->constrained('donhang')->onDelete('cascade');
            $table->foreignId('sanpham_id')->nullable()->constrained('sanpham')->nullOnDelete();
            $table->foreignId('bienthe_id')->nullable()->constrained('sanpham_bienthe')->nullOnDelete();

            $table->string('ten_san_pham');
            $table->string('ten_bienthe')->nullable();
            $table->string('ma_sku', 50)->nullable();
            $table->string('hinh_anh')->nullable();
            $table->unsignedInteger('so_luong');
            $table->decimal('gia', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chitiet_donhang');
    }
};