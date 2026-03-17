<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sanpham', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loai_id')->constrained('loai_sanpham')->onDelete('cascade');
            $table->string('ten_san_pham');
            $table->string('slug')->unique();
            $table->decimal('gia', 15, 2);
            $table->decimal('gia_cu', 15, 2)->nullable();
            $table->text('mo_ta')->nullable();
            $table->unsignedInteger('so_luong')->default(0);
            $table->unsignedInteger('luot_xem')->default(0);
            $table->unsignedInteger('luot_mua')->default(0);
            $table->boolean('co_bien_the')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index('loai_id');
            $table->index('luot_mua');
            $table->index('co_bien_the');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanpham');
    }
};