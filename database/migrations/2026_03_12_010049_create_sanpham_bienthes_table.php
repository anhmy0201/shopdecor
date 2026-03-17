<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sanpham_bienthe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sanpham_id')->constrained('sanpham')->onDelete('cascade');
            $table->string('ma_sku', 50)->unique();
            $table->string('ten_bienthe', 100);
            $table->string('hinh_anh')->nullable();
            $table->decimal('gia', 15, 2);
            $table->unsignedInteger('so_luong')->default(0);
            $table->unsignedTinyInteger('thu_tu')->default(0);
            $table->boolean('kich_hoat')->default(true);
            $table->timestamps();

            $table->index('sanpham_id');
            $table->index(['sanpham_id', 'kich_hoat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanpham_bienthe');
    }
};